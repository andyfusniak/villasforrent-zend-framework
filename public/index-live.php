<?php
// Define path to application directory
define('APPLICATION_PATH', '/var/www/www.holidaypropertyworldwide.com/application');

// Define application environment
define('APPLICATION_ENV', 'live');

// Ensure library/ is on include_path
set_include_path(
    implode(PATH_SEPARATOR, array(
        '/var/www/www.holidaypropertyworldwide.com/library',
        '.'
    ))
);

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();
