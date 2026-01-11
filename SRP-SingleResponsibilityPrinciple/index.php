<?php

require_once 'AreaCalculator.php';
require_once 'Square.php';
require_once 'Circle.php';
require_once 'ResponseFormatter.php';

$square = new Square(5);
$circle = new Circle(3);

$area = new AreaCalculator([$square, $circle]);
$result = new ResponseFormatter($area);
echo "JSON: Total area: " . $result->JSON();
echo "\nXML: Total area: " . $result->XML();
echo "\nHTML: Total area: " . $result->HTML()."\n";


?>