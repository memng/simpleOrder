<?php

namespace Model;

class ModelBase {
    const PLACEHOLDER = '?';
    
    public $db;
    public static $instance;
    
    /**
     * get the single instance.
     * 
     * @return \Model\ModelBase.
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
     * construct function
     */
    public function __construct() {
        $this->db = \Core\DbConnection::instance()->getConnection('order');
    }
    
    /**
     * build select string sql struct.
     * 
     * @param string $columns   
     * @param string $tableName
     * @param string $condition
     * @param string $other
     * @param string $params
     * 
     * @return array
     */
    public function queryAll($columns, $tableName, $condition, $other, $params) {
        $sql = "select $columns from $tableName where $condition $other";
        return $this->db->queryRaw($sql, $params);
    }

    /**
     * construct insert data struct and execute.
     * 
     * @param string $tableName
     * @param array  $insertData 'key' is table field.
     * 
     * @return int｜false.
     */
    public function insert($tableName,$inserField,$placeHolderValue) {
        if (count($inserField) === count($placeHolderValue)) {
            $values = array_fill_keys($inserField, self::PLACEHOLDER);
            $columns = implode(',', $inserField);
            $valueString = implode(',', $values);
            $sql = "insert into $tableName ($columns) values ($valueString)";
            return $this->db->executeRaw($sql, $placeHolderValue);
        }
        throw new \Exception('sql error: placeHolderValue count do not match value');
    }

    /**
     * update.
     * 
     * @param string $tableName
     * @param string $condition
     * @param array $updateField
     * @param array $placeHolderValue
     * 
     * @return int | false.
     */
    public function update($tableName, $condition, $updateField, $placeHolderValue) {
        if (substr_count($condition, self::PLACEHOLDER) + count($updateField) === count($placeHolderValue)) {
            $updateData = array_map(
                function($v) {
                    return $v . '=' . self::PLACEHOLDER;
                }, 
                $updateField
            );
            $updateColumns = implode(',', $updateData);
            $sql = "update $tableName set $updateColumns where $condition";
            return $this->db->executeRaw($sql, $placeHolderValue);
        }
        throw new \Exception('update param invalid,please check');
    }

    /**
     * delete data.
     * 
     * @param string $tableName
     * @param string $condition
     * 
     * @param array $params
     * 
     * @return int | false.
     */
    public function delete($tableName, $condition, $params) {
        $sql = "delete from $tableName where $condition";
        return $this->db->executeRaw($sql, $params);
    }
}