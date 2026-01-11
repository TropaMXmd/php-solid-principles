<?php

require_once 'Shape3DInterface.php';

class Cube implements Shape3DInterface {
    private $side;

    public function __construct($side) {
        $this->side = $side;
    }

    public function volume() {
        return pow($this->side, 3);
    }
}

?>