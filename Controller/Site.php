<?php

namespace Controller;

class Site extends \Controller\ControllerBase {
    
    public function index() {
        echo 'this is site index';
    }
    
    public function dbTest() {
        $coon = \Core\DbConnection::instance()->getConnection('order');
        $sql = 'select * from orders where order_id = :order_id and uid = :uid';
        $result = $coon->query($sql,array(':order_id' => 4578, ':uid' => 58963));
        var_dump($result);
    }
    
    public function insertTest() {
        $data = array(
            'order_sn' => 48745214,
            'order_id' => 6242241,
            'uid' => 1254,
            'quantity' => 2,
            'total_price' => 8.56,
            'address_id' =>  1368745,
            'status' => 5,
            'delivery_fee' => 85.4,
            'creation_time' => 13685475,
            'payment_time' => 139658742154,
            'delivery_time' => 12455669887,
            'completed_time' => 1365478954,
            'payment_method' => 'alipay',
            'payment_amount' => 9.00,
            'order_ip' => '127.0.0.1',
            'order_site' => 'bj',
            'updated_at' => 1358745121,
            'is_deleted' => 0
        );
        print_r(array_values($data));
        $count = \Model\ModelBase::instance()->insert(\Model\Order\Order::TABLE_NAME,array_keys($data),$data);
        var_dump($count);
    }
    
    public function selectTest() {
        $data = \Model\ModelBase::instance()->queryAll('*', \Model\Order\Order::TABLE_NAME, 'id > ?', '', array(0));
        //$data = \Core\DbConnection::instance()->getConnection('order')->select('*', \Model\Order\Order::TABLE_NAME, 'id > ?', '', array(０));
        var_dump($data);
    }
    
    public function updateTest() {
        $data =  \Model\ModelBase::instance()->update(\Model\Order\Order::TABLE_NAME,'id=2',array('order_id'),array(123456));
        var_dump($data);
    }
    
    public function deleteTest() {
        $data = \Model\ModelBase::instance()->delete(\Model\Order\Order::TABLE_NAME,'id=?',array(2));
        var_dump($data);
    }
}