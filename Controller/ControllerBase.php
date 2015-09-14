<?php

namespace Controller;

class ControllerBase{
    public static $instance;
    
    public static function instance() {
        if (!self::$instance) {
            self::$instance = new static;
            return self::$instance;
        } else {
            return self::$instance;
        }
    }
}

