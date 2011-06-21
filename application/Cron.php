<?php
class Cron extends Zend_Application_Bootstrap_Bootstrap
{
	
	protected function _initAutoload()
	{
        $autoLoader = new Zend_Application_Module_Autoloader(
			array(
				'namespace' => 'Common',
				'basePath' => APPLICATION_PATH . '/modules/common'));
        
		$autoLoader->addResourceTypes((array(
				'modelResource' => array(
						'path' => 'models/resources',
						'namespace' => 'Resource',
						)
				)
		));
		
		$autoLoader = new Zend_Application_Module_Autoloader(
			array(
				'namespace' => 'Admin',
				'basePath' => APPLICATION_PATH . '/modules/admin'));
		
		$autoLoader = new Zend_Application_Module_Autoloader(
			array(
				'namespace' => 'Frontend',
				'basePath' => APPLICATION_PATH . '/modules/frontend'));
		
        return $autoLoader;
	}
	
	protected function _initLogging()
	{
		// Firebug Logging Styles
		// Style 		Description
		// LOGi	 		Displays a plain log message
		// INFO 		Displays an info log message
		// WARN 		Displays a warning log message
		// ERROR 		Displays an error log message that increments Firebug's error count
		// TRACE 		Displays a log message with an expandable stack trace
		// EXCEPTION 	Displays an error long message with an expandable stack trace
		// TABLE 		Displays a log message with an expandable table
		$loggerResource = $this->getPluginResource('log');
		$this->_logger = $loggerResource->getLog();
		
		if ('development' === $this->_application->getEnvironment()) {
			$writer = new Zend_Log_Writer_Firebug();
			$writer->setPriorityStyle(8, 'TABLE');
			$this->_logger->addWriter($writer);
			$this->_logger->addPriority('TABLE', 8);

		//	//$writer->setPriorityStyle(Zend_Log::DEBUG, 'TRACE');
		//	$writer->addFilter(Zend_Log::DEBUG);
		//	//$logger->setDefaultPriorityStyle('TRACE');
		//	//$logger->setEventItem('pid', getmypid());
		//	$this->logger->addWriter($writer);
		//	
		//	//$writer = new Zend_Log_Writer_Stream('php://output');
		//
		}

		$this->_logger->log(__METHOD__ . ' Putting logger into Registry', Zend_Log::DEBUG);
		Zend_Registry::set('logger', $this->_logger);
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
