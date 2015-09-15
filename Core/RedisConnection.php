<?php
/**
 * class RedisConnection
 * @author cai mimeng <mimengc@163.com>
 */

namespace Core;

class RedisConnection {
    
    public static $connections;
    public static $instance;
    public $redisConfig;
    public $connection;
    
    /**
     * constrct function.
     */
    public function __construct() {
        $this->redisConfig = new \Config\Redis();
    }
    
    /**
     * get the object of this class.
     * 
     * @return \Core\RedisConnection
     */
    public static function instance() {
        if (!self::$instance) {
            self::$instance = new static;
            return self::$instance;
        } else {
            return self::$instance;
        }
    }
    /**
     * get or set the connection with config name.
     * 
     * @param string $name
     * @return \Core\RedisConnection
     */
    public function getConnection($name) {
        if (isset(self::$connections[$name])) {
            $this->connection = self::$connections[$name];
            return $this;
        } else {
            if (property_exists($this->redisConfig, $name)) {
                $redis = new \Redis();
                $config = $this->redisConfig->$name;
                $redis->connect($config['host'],$config['port'], $config['timeout']);
                $redis->select($config['db']);
                $this->connection = self::$connections[$name] = $redis;
                return $this;
            }
        }
    }
    
    /**
     * set a value.
     * 
     * @param string $key
     * @param string  $value
     * 
     * @return boolean
     */
    public function set($key, $value) {
        return $this->connection->set($key,$value);
    }
    
    /**
     * get a value.
     * 
     * @param string $key
     * @return string | false
     */
    public function get($key) {
       return $this->connection->get($key);
    }
}