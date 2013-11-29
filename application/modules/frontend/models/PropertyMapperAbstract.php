<?php
abstract class Frontend_Model_PropertyMapperAbstract
{
    /**
     * @var array
     */
    protected $_resources = array();

    /**
     * @param string|array $resource a single or list of resource to load
     */
    protected function getResource($resourceName)
    {
        if (!isset($this->_resources[$resourceName])) {
            $className = 'Common_Resource_' . $resourceName;
            $this->_resources[$resourceName] = new $className();
        }

        return $this->_resources[$resourceName];
    }
}
