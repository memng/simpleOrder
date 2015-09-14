<?php

namespace Module\External;

class Product extends \Core\Component{
    
    public function getProductBySku($sku) {
        return array(
            'testData' => 'successed',
        );
    }
}