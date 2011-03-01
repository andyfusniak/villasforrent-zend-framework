<?php

$application = new Zend_Application(
    APPLICATION_ENV,
    array(
                'bootstrap' => array(
                'class' => 'Bootstrap_Cron',
                'path' => APPLICATION_PATH . '/Bootstrap/Cron.php',
	     ),
                'config' => APPLICATION_PATH . '/configs/application.ini',
	)
);
$application->bootstrap()
            ->run();

$fastlookengine = new Vfr_FastLookupEngine();
//$fastlookengine->purgeFastTable();
//$fastlookengine->createFastTableDB();
