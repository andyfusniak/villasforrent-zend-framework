<?php
class Vfr_Controller_Router_Route_Location
	extends Zend_Controller_Router_Route_Abstract
{
    /**
     * URI delimiter
     */
    const URI_DELIMITER = '/';
    
    public static function getInstance(Zend_Config $config)
    {
        return;   
    }
    
    public function __construct($route, $defaults = array())
    {
        //var_dump($route);
       // var_dump($defaults);
    }
    
    public function match($path)
    {
		//var_dump("location route");
        if ($path instanceof Zend_Controller_Request_Http) {
            $path = $path->getPathInfo();
        }
        
        $path = trim($path, self::URI_DELIMITER);
        
        $locationModel = new Common_Model_Location();
        $locationRow = $locationModel->lookup($path);
		
        if (null == $locationRow)
            return false;

        
        $params = array (
			'module'		=> 'frontend',
			'controller'	=> 'level',
			'uri'			=> $path
		);
		    
        switch ($locationRow->depth) {
            case 1:
                $params['action'] = 'country';
            break;
            
			case 2:
                $params['action'] = 'region';
            break;
            
			case 3:
                $params['action'] = 'destination';
            break;
            default:
                $params['action'] = 'location';
        }
	    
		return $params;
    }

    public function assemble($data = array(), $reset = false, $encode = false)
    {
        var_dump("assemble.......");
    }
}
