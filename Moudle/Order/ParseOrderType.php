<?php

namespace Module\Order;

class ParseOrderType extends \Core\Component{
    public $allAloneType = array(
        1 => 'Warehouse',
        2 => 'Pop',
        3 => 'Presale',
        4 => 'Global',
        5 => 'Zero',
    );
    
    public $typeCount = 0;
    
    public function __construct() {
        $this->typeCount = count($this->allAloneType);
    }
    
    public function parseToType($value) {
        $types = array();
        foreach ($this->allAloneType as $i => $v) {
          if($value & pow(2, $i - 1)) {
              $types[] = $v;
          }
        }
        return types;
    }
    
    public function parseToValue($types) {
        $positions = array_keys($this->allAloneType, $types);
        $value = 0;
        foreach ($positions as $v) {
            $value += pow(2, $v-1) & 0xFFFF;
        }
        return $value;
    }
}