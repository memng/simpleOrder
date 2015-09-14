<?php

namespace Core;

class RedisConnection {
    
    public static $connections;
    public static $instance;
    public $redisConfig;
    public $connection;
    
    public function __construct() {
        $this->redisConfig = new \Config\Redis();
    }
    
   public static function instance() {
        if (!self::$instance) {
            self::$instance = new static;
            return self::$instance;
        } else {
            return self::instance;
        }
    }
    
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
    
    public function set($key, $value) {
        return $this->connection->set($key,$value);
    }
    
    public function get($key) {
        return $this->connection->get($key);
    }
}