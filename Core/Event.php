<?php

/**
 * class event,used for pass data when raising event.
 * 
 * @author mimengc <mimengc@163.com>
 */
namespace Core;

class Event
{
    /**
     * the event sender.
     * 
     * @var object 
     */
    public $sender;

    /**
     * weather the event is handled.
     * 
     * @var boolean 
     */
    public $handled=false;

    /**
     *　the additional params.
     * 
     * @var mix 
     */
    public $params;

    /**
     * @param object $sender
     * @param mix    $params
     * 
     */
    public function __construct($sender=null,$params=null)
    {
        $this->sender=$sender;
        $this->params=$params;
    }
}