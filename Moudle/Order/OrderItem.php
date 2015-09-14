<?php

namespace Module\Order;

class OrderItem extends \Core\Component{
    public $paramData = array();
    
    public function __construct(&$param) {
        $this->paramData = $param;
    }
}