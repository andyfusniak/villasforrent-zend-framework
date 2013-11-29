<?php
class Vfr_Application_Resource_Cron extends Zend_Application_Resource_ResourceAbstract
{
    function init()
    {
        $options = $this->getOptions();

        if (array_key_exists('pluginPaths', $options)) {
            $cron = new Vfr_Application_Service_Cron($options['pluginPaths']);
        } else {
            throw new Exception('Plugin path not set for Cron resource');
        }

        if (array_key_exists('actions', $options)) {
            foreach ($options['actions'] as $name => $args) {
                print_r($args);
                $cron->addAction($name, $args);
            }
        }

        return $cron;
    }
}
