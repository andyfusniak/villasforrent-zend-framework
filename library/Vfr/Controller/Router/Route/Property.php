<?php
class Vfr_Controller_Router_Route_Property
    extends Zend_Controller_Router_Route_Abstract
{
    const URI_DELIMITER = '/';
    
    public static function getInstance(Zend_Config $config)
    {
        return;
    }
    
    public function __construct($route, $defaults = array()) {}
    
    public function match($path)
    {
        //var_dump('property route');
        
        if ($path instanceof Zend_Controller_Request_Http) {
            $path = $path->getPathInfo();
            $path = trim($path, self::URI_DELIMITER);
        }
        
        $propertyModel = new Common_Model_Property();
        $propertyRow = $propertyModel->getPropertyByUrl($path);
    
        if (null == $propertyRow)
            return false;
        
        return array (
            'module'        => 'frontend',
            'controller'    => 'display-full-property',
            'action'        => 'index',
            'uri'           => $path,
            'idProperty'    => $propertyRow->idProperty
        );
    }
    
    public function assemble($data = array(), $reset = false, $encode = false)
    {
        var_dump("assemble.......");
    }
}