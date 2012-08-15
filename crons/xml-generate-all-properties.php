<?php
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));
require_once "Zend/Application.php";

$application = new Zend_Application(
    APPLICATION_ENV,
    array(
           'bootstrap' => array(
           'class' => 'Cron',
           'path' => APPLICATION_PATH . '/Cron.php',
         ),
    'config' => APPLICATION_PATH . '/configs/application.ini',
    )
);
$application->bootstrap()
            ->run();

$propertyMapper = new Frontend_Model_PropertyMapper();
$propertyService = new Frontend_Service_Property();

$propertyIds = $propertyMapper->getAllPropertyIds();


$options = $application->getBootstrap()->getOptions();


$basedir = $options['vfr']['xml']['xml_files_dir'] . DIRECTORY_SEPARATOR . 'property';

foreach ($propertyIds as $idProperty) {
    var_dump($idProperty);
    $propertyXml = $propertyService->getPropertyXml($idProperty);
    $xmlFile = $basedir . DIRECTORY_SEPARATOR . $idProperty . '.xml';

    if (false === file_put_contents($xmlFile, $propertyXml, LOCK_EX)) {
        throw new Vfr_Exception("Failed to write out XML data to " . $xmlFile);
    }
}
