<?php

require_once 'ShapeInterface.php';

class Square implements ShapeInterface {

    private $sideLength;

    public function __construct($sideLength)
    {
        $this->sideLength = $sideLength;
    }

    public function area()
    {
        return $this->sideLength * $this->sideLength;
    }
    
    //Volume method not applicable for 2D shapes.
    public function volume(){ }
}
?>