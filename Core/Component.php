<?php
/**
 * component base.support event and behavior feature.
 * 
 * @author cai mimeng <mimengc@163.com>
 */
namespace Core;

class Component {
    
    /**
     * event list.
     * array('eventName' => array(handler,handler..).  
     *
     * @var array
     */
    private $_e = array();
    
    /**
     * behavior list.
     * array('behaviorName' => $objcet).
     * 
     * @var array 
     */
    private $_m = array();
    
    /**
     * a object of this class.
     * 
     * @var \Core\Component 
     */
    public static $instance;


    /**
     * get the single instance.
     * 
     * @return \Core\Component
     */ 
    public static function instance($param = array()) {
        if (!self::$instance) {
            if (!empty($param)) {
                self::$instance = new static($param);
            } else {
                self::$instance = new static;
            }
            return self::$instance;
        } else {
            return self::instance;
        }
    }
    
    /**
     * the component allowed method,subclass should have the method if it want to define events,and it should merge with parent.
     * 
     * @return array.
     */
    public function allowEvents()
    {
        return array();
    }
    
    /**
     * raise up the event.
     * 
     * @param string $eventName  event name.
     * @param CEvent $event pass to the handle.
     * @return void.
     * 
     * @throws \Exception
     */
    public function raiseEvent($eventName, $event)
    {
        if(isset($this->_e[$eventName]))
        {
                foreach($this->_e[$eventName] as $handler)
                {
                        if(is_string($handler)) {
                            call_user_func($handler,$event);
                        }
                        elseif(is_callable($handler,true)) {
                            if(is_array($handler)) {
                                    // an array: 0 - object, 1 - method name
                                    list($object,$method)=$handler;
                                    // static method call
                                    if(is_string($object)) {
                                        call_user_func($handler,$event);
                                    }         
                                    elseif(method_exists($object,$method)) {
                                         $object->$method($event);
                                    }   
                                    else {
                                        throw new \Exception('Event "{class}.{event}" is attached with an invalid handler "{handler}".');

                                    }

                            }
                            // PHP 5.3: anonymous function
                            else {
                                call_user_func($handler,$event);
                            }
                        }
                        else {
                            throw new \Exception('Event "{class}.{event}" is attached with an invalid handler "{handler}".'); 
                        }
                        // stop further handling if param.handled is set true
                        if(($event instanceof \Core\Event) && $event->handled) {
                            return;
                        }
                }
        }
    }
    
    /**
     * attach event handler to the event.
     * 
     * @param string   $eventName event name.
     * @param callback $callback  callback function attached to the event.
     * 
     * @return boolean
     * @throws \Exception
     */
    public function attachEventHandler($eventName,$callback)
    {
        if (in_array($eventName,$this->allowEvents())) {
            if (isset($this->_e[$eventName])) {
                $this->_e[$eventName][] = $callback;
            } else {
                $this->_e[$eventName] = array($callback);
            }
            return true;
        }
        throw new \Exception('the event is not allowed by this object');
    }
    
    /**
     * detach event handler from the event.
     * 
     * @param string   $eventName event name. 
     * @param callback $callback  callback function.
     * 
     * @return boolean
     * @throws \Exception
     */
    public function detachEventHandler($eventName, $callback)
    {
        if (isset($this->_e[$eventName])) {
            if (($index=array_search($callback, $this->_e[$eventName], true)) !== false) {
                unset($this->_e[$eventName][$index]);
                return true;
            }
        }
        throw new \Exception('the event is not exist at all');
    }
    
    /**
     * get behavior by name.
     * 
     * @param string $name behavior name.
     * 
     * @return array.
     * @throws \Exception
     */
    public function getBehavior($name)
    {
        if (isset($this->_m[$name])) {
            return $this->_m[$name];
        }
        throw new \Exception('the behavior not defined, plase check you attach it previously');
    }
    
    /**
     * attach a behavior by name.
     * 
     * @param sting    $name
     * @param Behavior $behavior
     * @return boolean
     * 
     * @throws \Exception
     */
    public function attachBehavior($name, $behavior)
    {
        if(!isset($this->_m[$name]) && ($behavior instanceof Behavior)) {
            $this->_m[$name] = $behavior;
            $behavior->setEnabled(true);
            $behavior->attach($this);
            return true;
        }
        throw new \Exception('the behavior you try to attaching is already exist or the behavior is invalid');
    }
    
    /**
     * detach a behavior by name.
     * 
     * @param string $name
     * @return boolean
     * 
     * @throws \Exception
     */
    public function detachBehavior($name)
    {
        if (isset($this->_m[$name])) {
            $this->_m[$name]->detach($this);
            unset($this->_m[$name]);
            return true;
        }
        throw new \Exception('the behavior is not exists');
    }
    
    /**
     * get all behaviors the component attached.
     * 
     * @return array
     */
    public function getAttachedBehaviors()
    {
        return $this->_m;
    }
}