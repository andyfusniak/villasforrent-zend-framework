<?php
abstract class Frontend_Model_MapperAbstract
{
    /**
     * @var array
     */
    protected $_resources = array();

    /**
     * @var array
     */
    protected $_services = array();

    /**
     * @param string $resource a resource to load
     */
    protected function getResource($resourceName)
    {
        if (!isset($this->_resources[$resourceName])) {
            $className = 'Common_Resource_' . $resourceName;
            $this->_resources[$resourceName] = new $className();
        }

        return $this->_resources[$resourceName];
    }

    /**
     * @param string|array $service a service to load
     */
    protected function getService($serviceName)
    {
        if (!isset($this->_services[$serviceName])) {
            $className = 'Common_Service_' . $serviceName;
            $this->_services[$serviceName] = new $className();
        }

        return $this->_services[$serviceName];
    }
}
