<?php
class Command extends Zend_Application_Bootstrap_Bootstrap
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
        // Style        Description
        // LOGi         Displays a plain log message
        // INFO         Displays an info log message
        // WARN         Displays a warning log message
        // ERROR        Displays an error log message that increments Firebug's error count
        // TRACE        Displays a log message with an expandable stack trace
        // EXCEPTION    Displays an error long message with an expandable stack trace
        // TABLE        Displays a log message with an expandable table
        $loggerResource = $this->getPluginResource('log');
        $this->_logger = $loggerResource->getLog();



        $this->_logger->log(__METHOD__ . ' Putting logger into Registry', Zend_Log::DEBUG);
        Zend_Registry::set('logger', $this->_logger);
    }
}
