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
        if ($path instanceof Zend_Controller_Request_Http) {
            $path = $path->getPathInfo();
            $path = trim($path, self::URI_DELIMITER);
        }
		
        $path = trim($path, self::URI_DELIMITER);
            
		// the home page isn't a match for this router
		if ($path == '')
			return false;
    
        $urlExceptionChecker = new Vfr_Controller_Router_UrlExcemptionChecker();
        if ($urlExceptionChecker->isExcempt($path))
            return false;

        
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