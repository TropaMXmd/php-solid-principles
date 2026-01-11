Dependency Inversion Principle (DIP)

Dependency Inversion Principle states:

High-level modules should not depend on low-level modules.
Both should depend on abstractions.
Abstractions should not depend on details; details should depend on abstractions.

In short:

Don’t depend on concrete classes

Depend on interfaces (abstractions)

This leads to loose coupling, easy extension, and better testability.

Suppose we want to calculate the total area of shapes.

Low-level concrete classes

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

High-level module (❌ depends on concretes)

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

❌ Why this violates DIP

AreaCalculator (high-level) depends directly on Square and Circle

Adding a new shape (Triangle) forces modification of AreaCalculator

Violates DIP and Open–Closed Principle

✅ Applying Dependency Inversion Principle

Instead of depending on concrete shapes, we introduce an abstraction (interface) Shape2DInterface

This interface defines what a shape can do, not how it does it.

High-level module(AreaCalculator) depends on abstraction only

As a result,

- AreaCalculator no longer cares what concrete shape it receives
- It only knows that each shape has an area() method
- High-level and low-level modules both depend on the interface

Real life example:

Here is an example of a PasswordReminder that connects to a MySQL database:

```php
class MySQLConnection
{
    public function connect()
    {
        // handle the database connection
        return 'Database connection';
    }
}

class PasswordReminder
{
    private $dbConnection;

    public function __construct(MySQLConnection $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }
}
```

First, the MySQLConnection is the low-level module while the PasswordReminder is high level, but according to the definition of D in SOLID, which states to Depend on abstraction, not on concretions. This snippet above violates this principle as the PasswordReminder class is being forced to depend on the MySQLConnection class.

Later, if you were to change the database engine (e.g., from MySQL to PostgreSQL or an API service), you would also have to edit the PasswordReminder class, which would violate the open-close principle, as the class would need modification for extension.

The PasswordReminder class should not care what database your application uses. To address these issues, you can code to an interface since high-level and low-level modules should depend on abstraction.

First, define an interface for database connections. This interface (DBConnectionInterface) serves as the abstraction:

```php
interface DBConnectionInterface
{
    public function connect();
}
```

The interface has a connect method, and the MySQLConnection class implements this interface. Also, instead of directly type-hinting the MySQLConnection class in the constructor of the PasswordReminder, you type-hint the DBConnectionInterface. No matter the type of database your application uses, the PasswordReminder class can connect to the database without any problems, and the open-close principle is not violated.

This interface simply declares what a database connection object should be able to do (connect()), without specifying how it does it.

Next, your concrete MySQLConnection class will implement this interface, providing the specific details of how to connect to a MySQL database:

```php
class MySQLConnection implements DBConnectionInterface
{
    public function connect()
    {
        // handle the database connection
        return 'Database connection established';
    }
}
```

Now, in the constructor of the PasswordReminder class, instead of directly type-hinting the concrete MySQLConnection class, you type-hint the DBConnectionInterface.

```php
class PasswordReminder{
    private $dbConnection;

    public function __construct(DBConnectionInterface $dbConnection) // Type-hinting the interface
    {
        $this->dbConnection = $dbConnection;
    }

    public function remind() {
        $connectionStatus = $this->dbConnection->connect();
        return "Password reminder process initiated. Connection status: " . $connectionStatus;
    }
}
```

When you use PasswordReminder, you provide it with an instance of a class that implements DBConnectionInterface. The PasswordReminder doesn’t need to know the specific type of connection (MySQL, PostgreSQL, etc.); it just knows it has an object that guarantees it can call a connect() method.

Here’s how you would use it in your application:

```php
// Create a concrete MySQL connection object
$mysqlConnector = new MySQLConnection();

// Inject the concrete MySQL connection object into the PasswordReminder
// The PasswordReminder only sees it as a DBConnectionInterface
$passwordReminder = new PasswordReminder($mysqlConnector);

echo $passwordReminder->remind(); // Output: Password reminder process initiated. Connection status: MySQL Database connection established.
```

If you later decide to switch to a PostgreSQL database, you would simply create a PostgreSQLConnection class that also implements DBConnectionInterface and, in your application’s setup, simply change which concrete class you instantiate and inject:

```php
$pgConnector = new PostgreSQLConnection();
$passwordReminder = new PasswordReminder($pgConnector);

echo $passwordReminder->remind();
```

This demonstrates how easily you can swap implementations thanks to dependency inversion.

Notice that the PasswordReminder class itself did not need to be changed or modified when the underlying database technology changed. Both the high-level PasswordReminder module and the low-level MySQLConnection (or PostgreSQLConnection) module now depend on the DBConnectionInterface abstraction, not on each other’s concretions. This makes the system more flexible, easier to test (you can inject mock database connections), and adheres to the Open-Closed Principle.
