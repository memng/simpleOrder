<?php

/**
 * class behavior, used for extend an object. at the same time,observing the object event.
 * 
 * @author cai mimeng <mimengc@163.com>
 */
namespace Core;

class Behavior
{
    /**
     * weather the behavior is enabled.
     * 
     * @var booean 
     */
    private $_enabled=false;

    /**
     * the behavior owner.
     * 
     * @var object
     */
    private $_owner;

    /**
     * attach event handler to the owner 
     * 
     * @return array.
     */
    public function events()
    {
            return array();
    }

    /**
     * attach the owner
     * 
     * @param object $owner
     */
    public function attach($owner)
    {
            $this->_enabled=true;
            $this->_owner=$owner;
            $this->_attachEventHandlers();
    }

    /**
     * detach the owner.
     * 
     * @param object $owner
     */
    public function detach($owner)
    {
            foreach($this->events() as $event=>$handler) {
                $owner->detachEventHandler($event,array($this,$handler));
            }
            $this->_owner=null;
            $this->_enabled=false;
    }

    /**
     * get the owner.
     * 
     * @return object 
     */
    public function getOwner()
    {
            return $this->_owner;
    }

    /**
     * get weather the owner is enabled.
     * 
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->_enabled;
    }

    /**
     * set the enable value.
     * 
     * @param boolean $value
     */
    public function setEnabled($value)
    {
        $value=(bool)$value;
        if($this->_enabled!=$value && $this->_owner) {
            if($value) {
                $this->_attachEventHandlers();
            }
            else {
                foreach($this->events() as $event=>$handler) {
                    $this->_owner->detachEventHandler($event,array($this,$handler));
                }
            }
        }
        $this->_enabled=$value;
    }

    /**
     * attach the event handlers to the owner.
     */
    private function _attachEventHandlers()
    {
        $class=new \ReflectionClass($this);
        foreach($this->events() as $event=>$handler)
        {
            if($class->getMethod($handler)->isPublic()) {
                $this->_owner->attachEventHandler($event,array($this,$handler));
            }
        }
    }
}





