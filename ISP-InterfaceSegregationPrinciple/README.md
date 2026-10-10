# Interface Segregation Principle (ISP)

> **A class should never be forced to implement an interface that it doesn't use — class shouldn't be forced to depend on methods they do not use.**

This principle emphasizes that large, general-purpose interfaces should be broken down into smaller, more specific ones. This way, client classes only need to know about the methods that are relevant to them.

---

## ❌ Violating ISP (One Monolithic Interface)

If we use a single `ShapeInterface` for both 2D and 3D shapes, and it defines both `area()` and `volume()`, then every class that implements it — including `Square` — is forced to implement `volume()`, even though squares are flat shapes and don't have a volume.

```php
interface ShapeInterface
{
    public function area(): float;
    public function volume(): float;
}
```

```php
class Square implements ShapeInterface
{
    public function __construct(private float $side) {}

    public function area(): float
    {
        return $this->side ** 2;
    }

    // ❌ Forced to implement something that doesn't apply
    public function volume(): float
    {
        throw new \LogicException('A square has no volume.');
    }
}
```

**Why this violates ISP**

- `Square` is forced to depend on a method (`volume()`) it doesn't use
- Implementing it means either throwing an exception or returning a meaningless value
- Any client relying on `ShapeInterface` now has to know which shapes "really" support `volume()`

---

## ✅ ISP-Compliant Design (Segregated Interfaces)

Instead of one large interface, split it into smaller, capability-specific interfaces — one for 2D shapes and one for 3D shapes.

```php
interface Shape2DInterface
{
    public function area(): float;
}
```

```php
interface Shape3DInterface
{
    public function volume(): float;
}
```

### Shapes implement only what applies to them

```php
class Square implements Shape2DInterface
{
    public function __construct(private float $side) {}

    public function area(): float
    {
        return $this->side ** 2;
    }
}
```

```php
class Cube implements Shape3DInterface
{
    public function __construct(private float $side) {}

    public function volume(): float
    {
        return $this->side ** 3;
    }
}
```

A solid that genuinely has both properties can implement both interfaces without issue:

```php
class Cylinder implements Shape2DInterface, Shape3DInterface
{
    public function __construct(
        private float $radius,
        private float $height
    ) {}

    public function area(): float
    {
        return 2 * pi() * $this->radius * ($this->radius + $this->height);
    }

    public function volume(): float
    {
        return pi() * $this->radius ** 2 * $this->height;
    }
}
```

✔ `Square` only depends on `area()`
✔ `Cube` only depends on `volume()`
✔ No forced, meaningless implementations
✔ ISP satisfied

---

## Applying ISP in Laravel

In Laravel, this principle can be applied by creating smaller, more focused interfaces that define specific tasks. Rather than having a single interface that defines every method a user repository might need, it's better to have separate interfaces for authentication, authorization, and user management — so a class only depends on the piece of behavior it actually uses.

### ❌ One bloated interface

```php
interface UserRepositoryInterface
{
    public function findByCredentials(array $credentials);
    public function attemptLogin(array $credentials): bool;

    public function hasPermission(User $user, string $permission): bool;
    public function assignRole(User $user, string $role): void;

    public function create(array $data): User;
    public function update(int $id, array $data): User;
    public function delete(int $id): bool;
}
```

A class that only needs to check permissions (like a middleware) would still depend on login and CRUD methods it never calls.

### ✅ Segregated into focused interfaces

```php
interface AuthenticatesUsers
{
    public function findByCredentials(array $credentials);
    public function attemptLogin(array $credentials): bool;
}
```

```php
interface AuthorizesUsers
{
    public function hasPermission(User $user, string $permission): bool;
    public function assignRole(User $user, string $role): void;
}
```

```php
interface ManagesUsers
{
    public function create(array $data): User;
    public function update(int $id, array $data): User;
    public function delete(int $id): bool;
}
```

### Classes depend only on what they need

```php
class PermissionMiddleware
{
    public function __construct(private AuthorizesUsers $authService) {}

    public function handle($request, \Closure $next, string $permission)
    {
        if (! $this->authService->hasPermission($request->user(), $permission)) {
            abort(403);
        }

        return $next($request);
    }
}
```

```php
class LoginController extends Controller
{
    public function __construct(private AuthenticatesUsers $authService) {}

    public function login(Request $request)
    {
        if ($this->authService->attemptLogin($request->only('email', 'password'))) {
            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }
}
```

A concrete `UserRepository` can still implement all three interfaces together if it genuinely handles all three concerns — but every _consumer_ only depends on the slice it actually uses.

**Why this satisfies ISP**

| Interface            | Responsibility            | Who Depends On It      |
| -------------------- | ------------------------- | ---------------------- |
| `AuthenticatesUsers` | Login / credential checks | `LoginController`      |
| `AuthorizesUsers`    | Permissions / roles       | `PermissionMiddleware` |
| `ManagesUsers`       | CRUD on user records      | `UserController`       |

No class is forced to depend on methods it doesn't use, and changes to one concern (e.g. adding two-factor auth to `AuthenticatesUsers`) don't ripple into unrelated consumers like `PermissionMiddleware`.
