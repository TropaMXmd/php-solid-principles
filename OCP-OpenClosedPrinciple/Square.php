<?php
require_once 'Shape2DInterface.php';

class Square implements Shape2DInterface {
    
    private $length;

    public function __construct($length)
    {
        $this->length = $length;
    }

    public function area()
    {
        return $this->length * $this->length;
    }
}
?>