<?php
class Cron_Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initAutoload()
	{	
		$autoLoader = new Zend_Application_Module_Autoloader(
			array(
			'namespace' => 'Admin',
			'basePath' => APPLICATION_PATH . '/modules/admin'));
						
		$autoLoader = new Zend_Application_Module_Autoloader(
			array(
			'namespace' => 'Frontend',
			'basePath' => APPLICATION_PATH . '/modules/frontend'));

		Zend_Loader_Autoloader::getInstance()->registerNamespace('Vfr_');
		return $autoLoader;
	}

    public function run()
    {
        try {
            if ($this->hasPluginResource('cron')) {
                //$this->bootstrap('cron');
                //die('bootstrapped');
                $server = $this->getResource('cron');
                echo $server->run();
            } else {
                echo 'The cron plugin resource needs to be configured in application.ini.' . PHP_EOL;
            }
        } catch (Exception $e) {
            echo 'An error has occured.' . PHP_EOL;
        }
    }
}
