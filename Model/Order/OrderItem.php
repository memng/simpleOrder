<?php

namespace Model\Order;

class OrderItem extends \Core\Component {
    
    public static $fields = array(
        'id',
        'order_sn',
        'order_id',
        'uid',
        'deal_hash_id',
        'deal_price',
        'quantity',
        'product_id',
        'sku_no',
        'cost',
        'media_rebate_ratio',
        'company',
        'item_type',
        'out_of_stock',
        'updated_at',
        'is_deleted'
    );
}