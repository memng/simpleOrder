<?php

namespace Controller;

class Test extends \Controller\ControllerBase {
    
    public function index() {
        echo 'this is test index';
    }
    
    public function paramTest($id,$data) {
        var_dump($id);
        var_dump($data);
    }
}