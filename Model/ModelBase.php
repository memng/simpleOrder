<?php
/**
 * class ModelBase.
 * 
 * @author cai mimeng<mimengc@163.com>
 */

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
     * @param string $condition
     * @param string $other
     * @param string $params
     * 
     * @return array
     */
    public function queryAll($columns, $condition, $other, $params) {
        $tableName = $this->getTableName();
        $sql = "select $columns from $tableName where $condition $other";
        return $this->db->queryRaw($sql, $params);
    }

    /**
     * construct insert data struct and execute.
     * 
     * @param array $insertField       array('order_id','uid'...).
     * @param array $placeHolderValue  array(454,589). 
     * 
     * @return int｜false.
     */
    public function insert($insertField,$placeHolderValue) {
        if (count($insertField) === count($placeHolderValue)) {
            $tableName = $this->getTableName();
            $values = array_fill_keys($insertField, self::PLACEHOLDER);
            $columns = implode(',', $insertField);
            $valueString = implode(',', $values);
            $sql = "insert into $tableName ($columns) values ($valueString)";
            return $this->db->executeRaw($sql, $placeHolderValue);
        }
        throw new \Exception('sql error: placeHolderValue count do not match value');
    }

    /**
     * update.
     * 
     * @param string $condition        'id>? and status = ?'.
     * @param array $updateField       array('order_id','uid','status').
     * @param array $placeHolderValue  array(787979,25456,2,8,7).
     * 
     * @return int | false.
     */
    public function update($condition, $updateField, $placeHolderValue) {
        if (substr_count($condition, self::PLACEHOLDER) + count($updateField) === count($placeHolderValue)) {
            $tableName = $this->getTableName();
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
     * @param string $condition  'id>? and status = ?'.
     * @param array $params      array(47,2).
     * 
     * @return int | false.
     */
    public function delete($condition, $params) {
        $tableName = $this->getTableName();
        $sql = "delete from $tableName where $condition";
        return $this->db->executeRaw($sql, $params);
    }
}