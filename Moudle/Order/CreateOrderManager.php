<?php
namespace Module\Order;

class CreateOrderManager extends \Core\Component {
    
    public $paramData;
    
    public static $requireKey = array(
        'uid'        => null,
        'cartDetail' => null,
    );

    // ----event start
    // the following is event infomation in this class.
    public function event() {
        return array(
            "onBeforeReadyData",
            'onAfterReadyData',
            'onAfterCheckData',
            'onAfterGenerateOrders',
        );
    }
    
    public function onBeforeReadyData() {
        $this->raiseEvent('onBeforeReadyData', new \Core\Event($this));
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
    
    public function onAfterGenerateOrders() {
        $this->raiseEvent('onAfterGenerateOrders', new \Core\Event($this));
        return $this;
    }
    //----event end.

    public function __construct($orderDatas) {
        $this->paramData = $orderDatas;
    }
    
    public function readyData() {
        return $this;
    }
    
    public function checkData() {
        return $this->checkOrderBaseData()->checkItemData()->checkOrderOtherData();
    } 
    
    public function generateOrders() {
        foreach ($this->paramData['orderDetail'] as $key => $value) {
            $types = $this->parseType($key);
            $createOrder = new \Order\Module\Order\CreateOrder($value);
            foreach ($types as $type) {
                $namespaceType = '\\Order\\Module\Order\\' . $type;
                $object = new $namespaceType;
                $createOrder->attachBehavior($type, $object);
            }
            $createOrder->process();
        }
        return $this;
    }
    
    public function process() {
        try {
             $this->onBeforeReadyData()
             ->readyData()
             ->onAfterReadyData()
             ->checkData()
             ->onAfterCheckData()
             ->generateOrders()
             ->onAfterGenerateOrders();
        } catch (\Core\Lib\RpcBusinessException $ex) {
            return $this->failed($ex->getMessage());
        } catch (Exception $ex) {
            \Moudle\Log::instance()->log($ex); 
        }
    }
    
    public function parseType($key) {
        $key = '/Pop/Warehouse';
        return explode('/', trim($key,' /'));
    }
    
    //the following is sub method    
    // checkDataOrderLevel start.
    public function checkOrderBaseData(){
        return $this->checkUserInfo()->checkUserBlance()->checkOrderTotalPrice();
    }
    
    public function checkUserInfo(){
        $user = \Moudle\User::instance()->getUserByUid($uid);
        \Moudle\User::instance()->checkUserAllowed($user);
        return $this;
    }
    
    public function checkUserBlance(){
        return $this;
    }
    
    public function checkOrderTotalPrice(){
        return $this;
    }
    // checkDataOrderLevel end.
    
    
    // checkItemData start.
    public function checkItemData(){
        return $this->checkUserBuyMethod()->dealFreeGiftItem();
    }
    
    public function checkUserBuyMethod(){
        return $this;
    }
    
    public function dealFreeGiftItem() {
        return $this;
    }
    // checkItemData end. 
    
    
    // checkOrderOtherData start.
    public function checkOrderOtherData() {
        return $this->checkBuyPolicy()->updateUserLastConfirmInfo()->checkLimitSale();
    }
    
    public function checkBuyPolicy(){
        return $this;
    }
    
    public function updateUserLastConfirmInfo(){
        return $this;
    }
    
    public function checkLImitSale(){
        return $this;
    }
    // checkOrderOtherData end.
    
    
}

