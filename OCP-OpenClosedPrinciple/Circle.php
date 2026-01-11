<?php
require_once 'Shape2DInterface.php';

class Circle implements Shape2DInterface {
    
    private $radius;

    public function __construct($radius)
    {
        $this->radius = $radius;
    }

    public function area()
    {
        return pi() * $this->radius * $this->radius;
    }
}   
?>