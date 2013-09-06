<?php
class Vfr_Controller_Router_Route_Location
    extends Zend_Controller_Router_Route_Abstract
{
    protected $_path = null;

    /**
     * URI delimiter
     */
    const URI_DELIMITER = '/';

    public static function getInstance(Zend_Config $config)
    {
        return;
    }

    public function __construct($route, $defaults = array(), $map = array(), $reverse = null)
    {
        $this->_regex = trim($route, self::URI_DELIMITER);
        $this->_reverse = $reverse;

        //var_dump($route);
       // var_dump($defaults);
    }

    public function match($path)
    {
        if ($path instanceof Zend_Controller_Request_Http) {
            $path = $path->getPathInfo();
        }

        $path = trim($path, self::URI_DELIMITER);

        // keep a copy to assembling the route later
        $this->_path = $path;

        // the home page isn't a match for this router
        if ($path == '')
            return false;

        $urlExceptionChecker = new Vfr_Controller_Router_UrlExcemptionChecker();
        if ($urlExceptionChecker->isExcempt($path))
            return false;

        $locationModel = new Common_Model_Location();
        $locationRow = $locationModel->lookup($path);

        if (null == $locationRow)
            return false;

        $params = array(
            'module'     => 'frontend',
            'controller' => 'level',
            'action'     => 'country',
            'uri'        => $path,
            'depth'      => $locationRow->depth
        );

        return $params;
    }

    public function assemble($data=array(), $reset = false, $encode = false)
    {

        $queryString = '';

        if (!empty($data)) {
            foreach ($data as $name => $value) {
                $queryString .= $name . '=' . urlencode($value) . '&';
            }
            $queryString = rtrim($queryString, '&');
            $queryString = '?' . $queryString;
        }

        return $this->_path . $queryString;
    }
}
