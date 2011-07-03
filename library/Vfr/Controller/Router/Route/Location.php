<?php
class Vfr_Controller_Router_Route_Location extends Zend_Controller_Router_Route_Abstract
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
        if ($path instanceof Zend_Controller_Request_Http) {
            $path = $path->getPathInfo();
        }
        
        $path = trim($path, self::URI_DELIMITER);
        
        $locationModel = new Common_Model_Location();
        $locationRow = $locationModel->lookup($path);
        //var_dump($locationRow);
        if (null == $locationRow)
            return false;

        // check if this is a property URI or a geo-location URI
        if ($locationRow->idProperty == null) {
            // geo-location
            $params['module']       = 'frontend';
            $params['controller']   = 'level';
            
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
        } else {
            // property
            $params['module']       = 'frontend';
            $params['controller']   = 'display-full-property';
            $params['action']       = 'index';
        }
        
        $params['uri'] = $path;
        
        //$pathBits = explode(self::URI_DELIMITER, $path);

        //echo "<hr />";
        //var_dump($locationRow);
        //var_dump("match location...");
        //var_dump($pathBits);
        //if (!$partial) {
        //    $path = trim(urldecode($path), '/');
        //}
        return $params;
    }

    public function assemble($data = array(), $reset = false, $encode = false)
    {
        var_dump("assemble.......");
    }
}
