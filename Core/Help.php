<?php

/**
 * Log class.
 * 
 * @author cai mimeng <mimengc@163.com>
 */
namespace Core;

class Help {
    
    /**
     * log function.
     * 
     * @param string $message
     * @param string $category
     * @return boolean
     */
    public static function log($message, $category) {
        $logConfig = new \Config\Log();
        $allowCategory = $logConfig->allLogCategory;
        if (isset($allowCategory[$category])) {
            $logDir = $logConfig->logDir . DIRECTORY_SEPARATOR;
            $fileName = $category . '.log';
            if (!is_dir($logDir) && !@mkdir($logDir, 0777, true) && !is_writable ($logDir)) {
                return false;
            }
            $message = str_replace(array("\n","\r\n"), '\n',$message);
            file_put_contents ( $logDir . $fileName, $message, FILE_APPEND );
            return true;
        }
    }
}