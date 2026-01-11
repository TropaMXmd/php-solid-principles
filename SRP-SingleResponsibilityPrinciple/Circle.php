<?php

class Circle {
    
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