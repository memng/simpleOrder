<?php

namespace Model\Order;

class Order extends \Model\ModelBase {

    public $flipFields;

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

    public function __construct() {
        parent::__construct();
        $this->flipFields = array_flip(self::$fields);
    }
    
    public function getTableName(){
        return 'orders';
    }
}
