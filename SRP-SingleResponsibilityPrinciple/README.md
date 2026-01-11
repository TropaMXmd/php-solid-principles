Single Responsibility Principle (SRP)

SRP states:

A class should have one and only one reason to change.

In simpler terms:

A class should do one job

Changes in one concern should not affect unrelated logic

❌ Violating SRP (Mixed Responsibilities)

Here, one class is doing two jobs:

Calculating shape areas

Formatting output (JSON / HTML)

```php
class AreaCalculator
{
    protected array $shapes;

    public function __construct(array $shapes)
    {
        $this->shapes = $shapes;
    }

    public function sum(): float
    {
        $area = 0;
        foreach ($this->shapes as $shape) {
            $area += $shape->area();
        }
        return $area;
    }

    // ❌ Formatting responsibility mixed in
    public function outputAsJson(): string
    {
        return json_encode(['sum' => $this->sum()]);
    }

    public function outputAsHtml(): string
    {
        return "<h1>Sum: {$this->sum()}</h1>";
    }

}
```

Why this violates SRP

Changing formatting logic → modifies calculation class

Adding XML output → modifies calculation class

Two reasons to change ❌

✅ SRP-compliant Design (Separated Responsibilities)
1️⃣ Shape calculation → only calculation

```php
class AreaCalculator
{
    public function __construct(
        private array $shapes
    ) {}

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

✔ One responsibility: calculate area

2️⃣ ResponseFormatter → only formatting

```php
class ResponseFormatter
{
    public function json(float $value): string
    {
        return json_encode(['sum' => $value]);
    }

    public function html(float $value): string
    {
        return "<h1>Sum: {$value}</h1>";
    }

    public function xml(float $value): string
    {
        return "<sum>{$value}</sum>";
    }
}
```

✔ One responsibility: format response

3️⃣ Usage (Clean and Flexible)

```php
$calculator = new AreaCalculator([
    new Square(4),
    new Circle(3),
]);

$result = $calculator->sum();

$formatter = new ResponseFormatter();

echo $formatter->json($result);
echo $formatter->html($result);
```

Why this design follows SRP
Class Responsibility Reason to change
AreaCalculator Business logic (math) Calculation rules change
ResponseFormatter Presentation logic Output format changes

Each class has exactly one reason to change ✔

Benefits of SRP (Real-world impact)

- Easier maintenance
- Better testability
- Less merge conflicts
- Cleaner architecture
- Clear separation of concerns
