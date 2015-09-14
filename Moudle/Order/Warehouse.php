<?php
namespace Module\Order;

class Warehouse extends \Core\Behavior{
    
    public function events() {
        return array(
            'onBeforeInitData' => "beforeInitData",
            'onAfterInitData' => "afterInitData",
            'onAfterCheckData' => 'afterCheckData',
            'onAfterGenerateOrder' => 'afterGenerateOrder',
        );
    }
    
    public function beforeInitData($event) 
    {
        $event->sender->paramData['warehouseBeforeInitData'] = 'beforeInitData';
    }
    
    public function afterInitData($event)
    {
        $event->sender->paramData['warehouseAfterInitData'] = 'afterInitData';
    }
    
    public function afterCheckData($event)
    {
        $event->sender->paramData['warehouseAfterCheckData'] = 'afterCheckData';
    }
    
    public function afterGenerateOrder($event)
    {
        $event->sender->paramData['warehouseAfterGenerateOrder'] = 'afterGenerateOrder';
    }
}