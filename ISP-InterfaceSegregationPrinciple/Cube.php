<?php
require_once 'ShapeInterface.php';

class Cube implements ShapeInterface {
    
    private $sideLength;

    public function __construct($sideLength)
    {
        $this->sideLength = $sideLength;
    }

    public function area()
    {
        return 6 * pow($this->sideLength, 2);
    }

    public function volume()
    {
        return pow($this->sideLength, 3);
    }
}