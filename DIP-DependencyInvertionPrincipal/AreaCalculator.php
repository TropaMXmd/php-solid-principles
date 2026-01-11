<?php 

class AreaCalculator {

    private $shapes;

    public function __construct($shapes = [])
    {
        $this->shapes = $shapes;
    }

    public function sumArea()
    {
        $totalArea = 0;
        foreach ($this->shapes as $shape) {
            if(is_a($shape, 'Shape2DInterface')){
                $totalArea += $shape->area();
            } else {
                throw new Exception("Shape must implement ShapeInterface");
            }
        }
        return $totalArea;
    }

}   
?>