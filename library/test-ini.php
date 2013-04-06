<?php
require 'Zend/Config/Ini.php';

define('MYTEST', 'yahoo');
$config = new Zend_Config_Ini('./config.ini', 'live');

var_dump($config->map);
