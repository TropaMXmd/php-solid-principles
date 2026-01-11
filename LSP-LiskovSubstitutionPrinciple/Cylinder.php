<?php
require_once 'Shape3DInterface.php';    

class Cylinder implements Shape3DInterface {
    private $radius;
    private $height;

    public function __construct($radius, $height) {
        $this->radius = $radius;
        $this->height = $height;
    }

    public function volume() {
        return pi() * pow($this->radius, 2) * $this->height;
    }
}
?>