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
    
    public function redisTest() {
        $set = \Core\RedisConnection::instance()->getConnection('local')->set('data', 50);
        var_dump($set);
        
        $get = \Core\RedisConnection::instance()->getConnection('local')->get('data');
        var_dump($get);
        $other = \Core\RedisConnection::instance()->getConnection('local')->get('data54');
        var_dump($other);
    }
}