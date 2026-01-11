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


$volumes = new VolumeCalculator([
    new Cube(3),              // volume = 27
    new Cylinder(2, 5),       // volume ≈ 62.83
]);
$result = new ResponseFormatter($volumes);

// Output the total volume in different formats
echo "JSON: Total volume: " . $result->JSON();
echo "\nXML: Total volume: " . $result->XML();
echo "\nHTML: Total volume: " . $result->HTML()."\n";


?>