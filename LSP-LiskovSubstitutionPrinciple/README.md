# Liskov Substitution Principle (LSP)

> **If a class `S` is a subtype of class `T`, then objects of type `T` should be replaceable with objects of type `S` without breaking the program.**

In practice:

- A child class must **honor the contract** of its parent
- Method signatures, return types, and behavior expectations must remain **compatible**

---

## ✅ Example: Interchangeable Calculators

Here, both `AreaCalculator` and `VolumeCalculator` can be used interchangeably with `ResponseFormatter` without any issues, adhering to the Liskov Substitution Principle. The `sum()` method in both calculators works exactly as expected wherever a `Calculator` is required.

### Step 1: Define the stable contract

```php
interface Calculator
{
    public function sum(): float;
}
```

### Step 2: Implementations honor the contract

```php
class AreaCalculator implements Calculator
{
    public function __construct(private array $shapes) {}

    public function sum(): float
    {
        $total = 0;

        foreach ($this->shapes as $shape) {
            $total += $shape->area();
        }

        return $total;
    }
}
```

```php
class VolumeCalculator implements Calculator
{
    public function __construct(private array $solids) {}

    public function sum(): float
    {
        $total = 0;

        foreach ($this->solids as $solid) {
            $total += $solid->volume();
        }

        return $total;
    }
}
```

### Step 3: `ResponseFormatter` depends only on the `Calculator` contract

```php
<?php

class ResponseFormatter
{
    private $calculator;

    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function JSON()
    {
        return json_encode(['total_area' => $this->calculator->sum()]);
    }

    public function XML()
    {
        $totalArea = $this->calculator->sum();
        return "<response><total_area>{$totalArea}</total_area></response>";
    }

    public function HTML()
    {
        $totalArea = $this->calculator->sum();
        return "<html><body><h1>Total Area: {$totalArea}</h1></body></html>";
    }
}
```

### Usage — both calculators are drop-in replacements

```php
$areaFormatter = new ResponseFormatter(new AreaCalculator([
    new Square(4),
    new Circle(3),
]));

$volumeFormatter = new ResponseFormatter(new VolumeCalculator([
    new Cube(2),
    new Sphere(3),
]));

echo $areaFormatter->JSON();
echo $volumeFormatter->JSON();
```

`ResponseFormatter` has no idea whether it's holding an `AreaCalculator` or a `VolumeCalculator` — and it doesn't need to. Both honor the same `sum(): float` contract, so either one can be substituted without breaking anything.

✔ Same behavioral contract
✔ Returns `float`
✔ No inheritance abuse
✔ LSP satisfied

---

## Applying LSP in Laravel

In Laravel, this principle is commonly applied through **inheritance and polymorphism** in the repository pattern. A base class defines the common contract and shared functionality for a set of repositories, and each subclass extends it while remaining fully substitutable wherever the base type is expected — for example, a `BaseRepository` and a `UserRepository`.

### Step 1: Base repository defines the shared contract and behavior

```php
abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function all()
    {
        return $this->model->all();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->model->findOrFail($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }
}
```

### Step 2: `UserRepository` extends it without breaking the contract

```php
class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    // ✔ Adds its own behavior without changing inherited method signatures
    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }
}
```

### Step 3: Anywhere a `BaseRepository` is expected, `UserRepository` works too

```php
class UserService
{
    public function __construct(private BaseRepository $repository) {}

    public function getById($id)
    {
        return $this->repository->find($id);
    }
}

$service = new UserService(new UserRepository(new User()));
$service->getById(1); // Works exactly as if any other repository were passed in
```

**Why this satisfies LSP**

| Class            | Role                                  | Contract Honored                                      |
| ---------------- | ------------------------------------- | ----------------------------------------------------- |
| `BaseRepository` | Defines shared CRUD contract          | `find()`, `all()`, `create()`, `update()`, `delete()` |
| `UserRepository` | Extends with domain-specific behavior | Same method signatures, same return expectations      |

`UserRepository` (or any other repository extending `BaseRepository`, like `OrderRepository` or `ProductRepository`) can always be substituted wherever a `BaseRepository` is expected — without surprising side effects, changed return types, or broken assumptions. That substitutability is exactly what LSP requires.
