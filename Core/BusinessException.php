<?php
/**
 * class businessException
 * @author cai mimeng<mimengc@163.com> 
 * 
 */

namespace Core;

class BusinessException extends \Exception
{
    
    public function __construct($message=null,$code=0)
    {
        parent::__construct($message,$code);
    }
    
}

