<?php

namespace Config;

class Db {
    public $order = array(
        'dsn'      => 'mysql:dbname=orders;host=127.0.0.1:3306',
        'username' => 'root',
        'password' => '123456',
        'options' => array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'latin1\'',
                \PDO::ATTR_TIMEOUT => 3600
            )
    );
}