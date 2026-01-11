<?php

class Square {
    
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