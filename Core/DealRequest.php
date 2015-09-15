<?php
/**
 * class DealRequest
 */

namespace Core;

class DealRequest {
    private static $_instance;

    /**
     * get a object of this class
     * 
     * @return \Core\DealRequest
     */
    public static function instance() {
        if (!self::$_instance) {
            self::$_instance = new self;
            return self::$_instance;
        } else {
            return self::$_instance;
        }
    }
    
    /**
     * start function.
     */
    public function run() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $route = trim($_GET['_rp_'],'/ ');
            // default
            if (empty($route)) {
                $route = 'site/index';
            }
            $route = ucfirst($route);
            $this->runController($route, $_GET);
        }
    }
    
    /**
     * runController
     * 
     * @param string $route
     * @param array  $params
     * @return boolean
     * @throws \Exception
     */
    public function runController($route,$params) {
        if(preg_match('%^(?P<controller>\w+)\/(?P<action>\w+)$%', $route, $match)) {
            $controllerClass = '\\Controller\\' . $match['controller'];
            if (class_exists($controllerClass,true)) {
                $controller = $controllerClass::instance();
                $action = $match['action'];
                if (method_exists($controller, $action)) {
                    $method = new \ReflectionMethod($controller, $action);
                    if($method->getNumberOfParameters()>0) {
                        return $this->runActionWithParams($controller, $method, $params);
                    } else {
                        $controller->$action();
                        return true;
                    }
                }
                throw  new \Exception("method $action of $controllerClass not exists");
            }
            throw  new \Exception("class $controllerClass not exists");
        }
        throw new \Exception('route is invalid');
    }
    
    /**
     * run action with get params.
     * 
     * @param  \Core\ControllerBase | subclass $object
     * @param \ReflectionMethod      $method
     * @param array $params
     * 
     * @return boolean
     */
    public function runActionWithParams($object, $method, $params) {
        $ps=array();
        foreach($method->getParameters() as $i=>$param)
        {
                $name=$param->getName();
                if(isset($params[$name]))
                {
                        if($param->isArray())
                                $ps[]=is_array($params[$name]) ? $params[$name] : array($params[$name]);
                        elseif(!is_array($params[$name]))
                                $ps[]=$params[$name];
                        else
                                return false;
                }
                elseif($param->isDefaultValueAvailable())
                        $ps[]=$param->getDefaultValue();
                else
                        return false;
        }
        $method->invokeArgs($object,$ps);
        return true;
    }
}