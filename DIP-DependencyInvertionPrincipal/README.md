# Dependency Inversion Principle (DIP)

> **High-level modules should not depend on low-level modules. Both should depend on abstractions.**
> **Abstractions should not depend on details; details should depend on abstractions.**

In short:

- Don't depend on concrete classes
- Depend on interfaces (abstractions)

This leads to loose coupling, easy extension, and better testability.

---

## ❌ Violating DIP

Suppose we want to calculate the total area of shapes.

**Low-level concrete classes**

```php
class Square
{
    public function area(): float
    {
        return 16;
    }
}

class Circle
{
    public function area(): float
    {
        return 28.27;
    }
}
```

**High-level module (depends directly on concretes)**

```php
class AreaCalculator
{
    private array $shapes;

    public function __construct(array $shapes)
    {
        $this->shapes = $shapes;
    }

    public function sum(): float
    {
        $area = 0;

        foreach ($this->shapes as $shape) {
            if ($shape instanceof Square) {
                $area += $shape->area();
            } elseif ($shape instanceof Circle) {
                $area += $shape->area();
            }
        }

        return $area;
    }
}
```

**Why this violates DIP**

- `AreaCalculator` (high-level) depends directly on `Square` and `Circle`
- Adding a new shape (`Triangle`) forces modification of `AreaCalculator`
- Violates DIP **and** the Open–Closed Principle

---

## ✅ Applying Dependency Inversion Principle

Instead of depending on concrete shapes, we introduce an abstraction — `Shape2DInterface`. This interface defines _what_ a shape can do, not _how_ it does it.

```php
interface Shape2DInterface
{
    public function area(): float;
}
```

```php
class Square implements Shape2DInterface
{
    public function area(): float
    {
        return 16;
    }
}

class Circle implements Shape2DInterface
{
    public function area(): float
    {
        return 28.27;
    }
}
```

```php
class AreaCalculator
{
    public function __construct(private array $shapes) {}

    public function sum(): float
    {
        $area = 0;

        foreach ($this->shapes as $shape) {
            if ($shape instanceof Shape2DInterface) {
                $area += $shape->area();
            }
        }

        return $area;
    }
}
```

As a result:

- `AreaCalculator` no longer cares what concrete shape it receives
- It only knows that each shape has an `area()` method
- High-level and low-level modules both depend on the same abstraction (`Shape2DInterface`)

---

## Applying DIP in Laravel: File Storage Example

The Dependency Inversion Principle (DIP) states that high-level modules should not depend on low-level modules, but both should depend on abstractions. In other words, instead of depending on specific implementations, modules should rely on interfaces or abstract classes.

### ❌ Code Sample Violating DIP — Using the `Storage` Facade Directly

```php
class UserController extends Controller
{
    public function store(Request $request)
    {
        $avatar = $request->file('avatar');

        // Violation: The UserController depends directly on the Storage facade.
        // This makes it tightly coupled to Laravel's file storage implementation.
        $path = Storage::disk('local')->put('avatars', $avatar);

        // ...
    }
}
```

The `UserController` class relies on the `Storage` facade and calls its `disk` and `put` methods directly. By depending on the `Storage` facade directly, `UserController` is tightly coupled to the specific file storage system implemented by Laravel, making it harder to switch to a different storage mechanism without modifying the `UserController` code.

### ✅ Code Sample Following DIP — Using a `FileStorage` Service

**Step 1: Define the abstraction**

```php
interface FileStorage
{
    public function storeFile($directory, $file);
}
```

**Step 2: Concrete implementations**

```php
class LocalFileStorage implements FileStorage
{
    public function storeFile($directory, $file)
    {
        return Storage::disk('local')->put($directory, $file);
    }
}
```

```php
class S3FileStorage implements FileStorage
{
    public function storeFile($directory, $file)
    {
        return Storage::disk('s3')->put($directory, $file);
    }
}
```

**Step 3: Depend on the abstraction, not the facade**

```php
class UserController extends Controller
{
    private $fileStorage;

    public function __construct(FileStorage $fileStorage)
    {
        $this->fileStorage = $fileStorage;
    }

    public function store(Request $request)
    {
        $avatar = $request->file('avatar');

        // The UserController depends on the FileStorage abstraction, which can be
        // implemented using different storage systems.
        $path = $this->fileStorage->storeFile('avatars', $avatar);

        // ...
    }
}
```

The `UserController` class now depends on the `FileStorage` interface instead of directly relying on the `Storage` facade or a specific implementation. This interface serves as an abstraction that defines the contract for file storage operations.

The `S3FileStorage` class implements the `FileStorage` interface and provides the concrete implementation for storing files in AWS S3. By injecting the `FileStorage` interface into the `UserController` constructor, the controller is decoupled from the specific storage mechanism and depends only on the abstraction.

This adherence to the Dependency Inversion Principle allows for easier interchangeability of storage implementations. You can introduce new implementations of the `FileStorage` interface (e.g. a `LocalFileStorage` class) without modifying the `UserController` code. The choice of storage mechanism can be determined at runtime or through configuration (`.env` file), providing flexibility and maintainability.

**Why this satisfies DIP**

| Module                                           | Depends On                  | Reason                                           |
| ------------------------------------------------ | --------------------------- | ------------------------------------------------ |
| `UserController` (high-level)                    | `FileStorage` (abstraction) | Doesn't care which storage system is used        |
| `LocalFileStorage` / `S3FileStorage` (low-level) | `FileStorage` (abstraction) | Each provides its own storage details            |
| Service provider binding                         | Both, via binding           | Wires abstraction to implementation in one place |
