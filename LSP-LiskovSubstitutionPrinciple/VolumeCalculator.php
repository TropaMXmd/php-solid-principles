<?php
require_once 'Calculator.php';

class VolumeCalculator implements Calculator {
    
    private $volumes;

    public function __construct($volumes)
    {
        $this->volumes = $volumes;
    }

    public function sum()
    {
        $total = 0;
        foreach ($this->volumes as $volume) {
            if(is_a($volume, 'Shape3DInterface')){
                $total += $volume->volume();
            } else {
                throw new Exception("Shape must implement Shape3DInterface");
            }
        }
        return $total;
    }
}
?>