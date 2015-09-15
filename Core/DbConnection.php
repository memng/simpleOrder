<?php
/**
 * 
 */

namespace Core;

class DbConnection {
    
    public static $connections;
    public static $instance;
    public $dbConfig;
    public $connection;
    
    /**
     * construct function.
     */
    public function __construct() {
        $this->dbConfig = new \Config\Db();
    }
    
    /**
     * get single instance.
     * 
     * @return \Core\DbConnection
     */
    public static function instance() {
        if (!self::$instance) {
            self::$instance = new static;
            return self::$instance;
        } else {
            return self::instance;
        }
    }
    
    /**
     * get a connection by db config name. 
     * 
     * @param string $name
     * @return \Core\DbConnection
     */
    public function getConnection($name) {
        if (isset(self::$connections[$name])) {
            $this->connection = self::$connections[$name];
            return $this;
        } else {
            if (property_exists($this->dbConfig, $name)) {
                $thisDbConfig = $this->dbConfig->$name;
                $this->connection = self::$connections[$name] = new \PDO($thisDbConfig['dsn'],$thisDbConfig['username'],$thisDbConfig['password']);
                return $this;
            }
        }
    }
    
    /**
     * call the pdo prepare and execute.
     * 
     * @param string $sql    sql whit placeholder.
     * @param array  $param  'key' is placeholder and 'value' is the value.
     * 
     * @return \PdoStatement
     * @throws \Exception
     */
    public function executeInternel($sql,$param) {
        $statement = $this->connection->prepare($sql);
        if (!$statement) {
            throw new \Exception('pdo prepare failed');
        }
        if($statement->execute($param)) {
            return $statement;
        }
        throw new \Exception('pdo execute after prepare failed');
    }
    
    /**
     * fetch execute data for query.
     * 
     * @param string $sql
     * @param array $param
     * 
     * @return array
     */
    public function queryRaw($sql,$param) {
        $statement = $this->executeInternel($sql, $param);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * fetch execute data for execute.
     * 
     * @param string $sql
     * @param array $param
     * 
     * @return int | false
     */
    public function executeRaw($sql,$param) {
        $statement = $this->executeInternel($sql, $param);
        return $statement->rowCount();
    }
    
    /**
     * begin transaction.
     * 
     * @return boolean
     */
    public function beginTransaction(){
        return $this->connection->beginTransaction();
    }
    
    /**
     * transaction commit.
     *  
     * @return boolean
     */
    public function commit() {
        return $this->connection->commit();
    }
    
    /**
     * transaction rollback.
     * 
     * @return boolean
     */
    public function rollBack() {
        return $this->connection->rollBack();
    }
    
    /**
     * weather the connection in transaction.
     * 
     * @return boolean
     */
    public function inTransaction() {
        return $this->connection->inTransaction();
    }
}