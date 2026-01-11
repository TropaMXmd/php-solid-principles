<?php

require_once 'AreaCalculator.php';
require_once 'Square.php';
require_once 'VolumeCalculator.php';
require_once 'Cube.php';

$square = new Square(5);

$area = new AreaCalculator([$square]);
echo "Totlal Area: " . $area->sum()."\n";

//Here Square and Cube both implement ShapeInterface but square has no volume method so it violates ISP as ISP states that no client should be forced to depend on methods it does not use.

$volumes = new VolumeCalculator([
    new Cube(3),
]);
echo "Totlal Volume: " . $volumes->sum()."\n";

?>