<?php

namespace Model\Order;

class Order extends \Core\Component {

    public $flipFields;
    public $db;

    const TABLE_NAME = 'orders';

    public static $fields = array(
        'id',
        'order_sn',
        'order_id',
        'uid',
        'quantity',
        'total_price',
        'address_id',
        'status',
        'delivery_fee',
        'creation_time',
        'payment_time',
        'delivery_time',
        'completed_time',
        'payment_method',
        'payment_amount',
        'order_ip',
        'order_site',
        'updated_at',
        'is_deleted',
    );

    public function getDb() {
        return $this->db;
    }
    
    public function __construct() {
        $this->db = \Core\DbConnection::instance()->getConnection('order');
        $this->flipFields = array_flip(self::$fields);
    }

    public function insert($insertData){
        return $this->db->insert(self::TABLE_NAME,$insertData);
    }
    
    public function select($columns,$condition,$other,$params) {
        return $this->db->select($columns, self::TABLE_NAME, $condition, $other, $params);
    }
}
