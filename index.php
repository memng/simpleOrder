<?php

/**
 * enter app via this file.
 * 
 * @author cai mimeng <cai mimeng@163.com>
 */

define('ROOT', __DIR__);
define('LOGROOT', ROOT.'/logs');

include ROOT . DIRECTORY_SEPARATOR . 'Core/Bootstrap.php';

\Core\DealRequest::instance()->run();

