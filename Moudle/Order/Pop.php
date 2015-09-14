<?php
namespace Module\Order;

class Pop extends \Core\Behavior{
    
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
        $event->sender->paramData['popbeforeInitData'] = 'beforeInitData';
    }
    
    public function afterInitData($event)
    {
        $event->sender->paramData['popafterInitData'] = 'afterInitData';
    }
    
    public function afterCheckData($event)
    {
        $event->sender->paramData['popafterCheckData'] = 'afterCheckData';
    }
    
    public function afterGenerateOrder($event)
    {
        $event->sender->paramData['popafterGenerateOrder'] = 'afterGenerateOrder';
    }
}

