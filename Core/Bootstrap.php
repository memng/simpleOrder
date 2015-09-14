<?php

class Bootstrap {
    
    public static function autoload ($className) {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        require ROOT .DIRECTORY_SEPARATOR. $fileName;
    }
    
    public static function handleException($exception) {
        // disable error capturing to avoid recursive errors
        restore_error_handler();
        restore_exception_handler();
        $message = $exception->getFile() . "\t" .$exception->getLine(). "\t" . $exception->getMessage();
        \Core\Help::log($message, 'uncatch_exception');
    }
    
    public static function handleError($code,$message,$file,$line) {
         if ($code & error_reporting()) {
             // disable error capturing to avoid recursive errors
            restore_error_handler();
            restore_exception_handler();
            $message = $file . "\t" .$line. "\t" . $message;
            \Core\Help::log($message, 'php_error');
         }  
    }
    
}
spl_autoload_register(array('Bootstrap','autoload'));
//set_exception_handler(array("Bootstrap",'handleException'));
//set_error_handler(array('Bootstrap','handleError'),error_reporting());