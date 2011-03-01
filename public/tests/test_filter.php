<?php

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath('/../library'),
	    get_include_path(),
		)));

require_once 'Zend/Filter/Boolean.php';
