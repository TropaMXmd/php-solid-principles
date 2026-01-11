<?php

//If only one interface is used for both 2D and 3D shapes, it violates ISP
interface ShapeInterface { 
    public function area();
    public function volume();
}   
?>