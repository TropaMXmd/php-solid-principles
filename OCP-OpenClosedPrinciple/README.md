Open–Closed Principle (OCP)

OCP states:

Software entities (classes, modules, functions) should be open for extension but closed for modification.

This means:

You should be able to add new behavior

Without changing existing, tested code

❌ Wrong approach (Violates OCP)
Area calculation with conditionals

```php
class AreaCalculator
{
    public function sum(array $shapes): float
    {
            $area = 0;

            foreach ($shapes as $shape) {
                if ($shape['type'] === 'square') {
                    $area += $shape['side'] ** 2;
                } elseif ($shape['type'] === 'circle') {
                    $area += pi() * $shape['radius'] ** 2;
                }
            }

            return $area;
        }

    }
```

Problem

Every time a new shape is added, this class must be modified

High risk of bugs

Violates OCP

✅ Correct approach (OCP-compliant)
Step 1: Define a stable abstraction

```php
interface Shape2DInterface
{
    public function area(): float;
}
```

Step 2: Shapes implement their own behavior
Square

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

Circle

```php
class Circle implements Shape2DInterface
{
    public function __construct(private float $radius) {}

    public function area(): float
    {
        return pi() * $this->radius ** 2;
    }

}
```

Step 3: AreaCalculator depends only on the abstraction

```php
class AreaCalculator
{
    public function __construct(private array $shapes) {}

    public function sum(): float
    {
        $total = 0;

        foreach ($this->shapes as $shape) {
            if ($shape instanceof Shape2DInterface) {
                $total += $shape->area();
            }
        }

        return $total;
    }

}
```

✔ No if/else
✔ No shape-specific logic
✔ Closed for modification

➕ Extending the system (WITHOUT modifying existing code)
Add a new shape: Triangle

```php
class Triangle implements Shape2DInterface
{
    public function __construct(
        private float $base,
        private float $height
    ) {}

    public function area(): float
    {
        return 0.5 * $this->base * $this->height;
    }

}
```

Use it immediately

```php
$calculator = new AreaCalculator([
        new Square(4), // 16
        new Circle(3), // ≈28.27
        new Triangle(5, 6) // 15
    ]);

echo $calculator->sum(); // ≈59.27
```

✔ AreaCalculator unchanged
✔ Existing code untouched
✔ OCP respected

Why this satisfies OCP
Principle Explanation
Open for extension Add new shape classes
Closed for modification AreaCalculator never changes
Low risk No regression in tested logic
Clean design No conditional explosion
