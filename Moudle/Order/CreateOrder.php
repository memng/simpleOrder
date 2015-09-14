<?php
namespace Module\Order;

class CreateOrder extends \Core\Component {
    public $createData;
    
    public $extData;
    
    public $requireKeys = array(
        'uid',
        'items',
        'group',
        'shipping_system_id',
        'total_price',
        'delivery_fee',
        'site',
        'gateway',
        'phase',
        'address_id',
        'logistic_preference',
        'prefer_delivery_day',
        'ip',
        'referer_site',
        'cps',
        'platform'
    );
    
    public $itemRequireKeys;
    
    // ----event start
    // the following is event infomation in this class.
    public function allowEvents(){
        return array(
            'onBeforeValidateParam',
            'onAfterValidateParam',
            'onAfterReadyData',
            'onAfterCheckData',
            'onAfterConstructOrderData',
            'onAfterGenerateOrders',
        );
    }
    
    public function onBeforeValidateParam() {
        $this->raiseEvent('onBeforeValidateParam', new \Core\Event($this));
        return $this;
    }
    
    public function onAfterValidateParam() {
        $this->raiseEvent('onAfterValidateParam', new \Core\Event($this));
        return $this;
    }
    
    public function onAfterReadyData() {
       $this->raiseEvent('onAfterReadyData', new \Core\Event($this));
       return $this;
    }
    
    public function onAfterCheckData() {
        $this->raiseEvent('onAfterCheckData', new \Core\Event($this));
        return $this;
    }
    
    public function onAfterConstructOrderData() {
        $this->raiseEvent('onAfterCheckData', new \Core\Event($this));
        return $this;
    }
    
    public function onAfterGenerateOrder() {
        $this->raiseEvent('onAfterGenerateOrder', new \Core\Event($this));
        return $this;
    }
    //----event end.
    
    public function process()
    {
        $this->onBeforeValidateParam()
             ->validateParam()
             ->onAfterValidateParam()
             ->readyData()
             ->onAfterReadyData()
             ->checkData()
             ->onAfterCheckData()
             ->constructOrderData()
             ->onAfterConstructOrderData()
             ->generateOrder()
             ->onAfterGenerateOrder();
    }
    
    public function __construct(&$param) {
        $this->createData = array_intersect_key($param, array_flip($this->requireKeys));
    }

    // the following is sub method
    
    public function validateParam() {
        if (($missedKey = array_diff_key(array_flip($this->requireKeys), $this->createData))) {
            throw new \Core\BusinessException(implode(',', array_keys($missedKey)) . ' are required');
        }
        if (($itemMissedKey = array_diff_key(array_flip($this->itemRequireKeys), $this->createData['item']))) {
            throw new \Core\BusinessException(implode(',', array_keys($itemMissedKey)) . ' are required');
        }
        return $this;
    }
    
    public function readyData() {
        return $this;
    }
    
    public function checkData() {
        $user = \Module\External\User::instance()->getUserByAddressId($this->paramData['addressId']);
        $this->ext['user'] = $user;
        if (empty($user['uid'])) {
            throw  new \Core\BusinessException('invalid user');
        }
        if ($user['uid'] !== $this->createData['uid']) {
            throw  new \Core\BusinessException('addressId and userId is not matched');
        }
        if(!filter_var($this->createData['ip'],FILTER_VALIDATE_IP)) {
            throw  new \Core\BusinessException('addressId and userId is not matched');
        }
        array_map(array($this,'checkItemSingle'), $this->createData['item']);
        return $this;
    }
    
    public function checkItemSingle($v) {
        $skuInfo = \Module\External\Product::instance()->getProductBySku($v['sku']);
        if (empty($skuInfo)) {
            throw  new \Core\BusinessException('invalid skuinfo');
        }
    }

    public function constructOrderData() {
        return $this;
    }
    
    public function generateOrder() {
        return $this;
    }
    
}