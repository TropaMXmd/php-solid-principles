<?php 

class AreaCalculator {

    private $shapes;

    public function __construct($shapes = [])
    {
        $this->shapes = $shapes;
    }

    public function sum()
    {
        $totalArea = 0;
        foreach ($this->shapes as $shape) {
            $totalArea += $shape->area();
        }
        return $totalArea;
    }

}   
?>