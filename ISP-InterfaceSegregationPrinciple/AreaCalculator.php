<?php 
require_once 'Calculator.php';

class AreaCalculator implements Calculator {

    private $shapes;

    public function __construct($shapes = [])
    {
        $this->shapes = $shapes;
    }

    public function sum()
    {
        $totalArea = 0;
        foreach ($this->shapes as $shape) {
            if(is_a($shape, 'ShapeInterface')){   
                $totalArea += $shape->area();
            } else {
                throw new Exception("Shape must implement ShapeInterface");
            }   
        }
        return $totalArea;
    }

}   
?>