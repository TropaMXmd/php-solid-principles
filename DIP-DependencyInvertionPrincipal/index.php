<?php

require_once 'AreaCalculator.php';
require_once 'Square.php';
require_once 'Circle.php';

$square = new Square(5);
$circle = new Circle(3);

$shape = new AreaCalculator([$square, $circle]);
echo "Total area: " . $shape->sumArea()."\n";

//Here, if we want to add a new shape, we just need to create a new class that implements ShapeInterface without modifying existing code.
?>