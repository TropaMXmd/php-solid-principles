# Single Responsibility Principle (SRP)

> **A class should have one, and only one, reason to change.**

In simpler terms:

- A class should do **one job**
- Changes in one concern should **not** affect unrelated logic

---

## ❌ Violating SRP (Mixed Responsibilities)

Here, one class is doing two jobs:

- Calculating shape areas
- Formatting output (JSON / HTML)

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

**Why this violates SRP**

- Changing formatting logic → modifies calculation class
- Adding XML output → modifies calculation class
- Two reasons to change ❌

---

## ✅ SRP-Compliant Design (Separated Responsibilities)

### 1️⃣ Shape calculation → only calculation

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

### 2️⃣ `ResponseFormatter` → only formatting

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

### 3️⃣ Usage (Clean and Flexible)

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

---

## Why This Design Follows SRP

| Class               | Responsibility        | Reason to Change         |
| ------------------- | --------------------- | ------------------------ |
| `AreaCalculator`    | Business logic (math) | Calculation rules change |
| `ResponseFormatter` | Presentation logic    | Output format changes    |

Each class has exactly one reason to change ✔

---

## Benefits of SRP (Real-World Impact)

- Easier maintenance
- Better testability
- Less merge conflicts
- Cleaner architecture
- Clear separation of concerns

---

## Applying SRP in Laravel

Laravel controllers commonly end up violating SRP — they validate input, run business logic, query the database, and shape the JSON response all in one method. A common fix is pulling response formatting out into a dedicated **`ApiResponse`** class, so the controller's only job is orchestrating the request.

### ❌ Controller mixing request handling with response formatting

```php
class OrderController extends Controller
{
    public function show($id)
    {
        $order = Order::findOrFail($id);

        // ❌ Response shaping logic mixed into the controller
        return response()->json([
            'success' => true,
            'data'    => $order,
            'message' => 'Order retrieved successfully',
        ], 200);
    }
}
```

Every time the API response shape changes (e.g. adding `meta`, pagination, error codes), every controller that builds responses manually has to be touched.

### ✅ Extract a dedicated `ApiResponse` class

```php
class ApiResponse
{
    public static function success($data = null, string $message = 'Success', int $code = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    public static function error(string $message = 'Error', int $code = 400, $errors = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }
}
```

### ✅ Controller becomes thin — only orchestration

```php
class OrderController extends Controller
{
    public function show($id)
    {
        $order = Order::find($id);

        if (! $order) {
            return ApiResponse::error('Order not found', 404);
        }

        return ApiResponse::success($order, 'Order retrieved successfully');
    }
}
```

**Why this satisfies SRP**

| Class             | Responsibility        | Reason to Change               |
| ----------------- | --------------------- | ------------------------------ |
| `OrderController` | Request orchestration | Business/workflow rules change |
| `ApiResponse`     | Response formatting   | API response contract changes  |

Now, if the API response format changes (e.g. adding pagination metadata, versioning, or error codes), only `ApiResponse` is touched — every controller using it stays untouched and keeps working consistently.
