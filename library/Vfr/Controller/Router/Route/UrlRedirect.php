<?php
class Vfr_Controller_Router_Route_UrlRedirect extends Zend_Controller_Router_Route_Abstract
{
    protected $_urlRedirectModel = null;
    protected $_path = null;

    const URI_DELIMITER = '/';

    public static function getInstance(Zend_Config $config)
    {
        return;
    }

    public function __construct($route, $default = array(), $map = array(), $reverse = null)
    {
    }

    public function match($path)
    {
        if ($path instanceof Zend_Controller_Request_Http) {
            $request = $path;

            $path = $path->getPathInfo();
        }

        $path = trim($path, self::URI_DELIMITER);

        // keep a copy of path for assembling the router later
        $this->_path = $path;

        // the home path isn't a match for this router
        if ("" === $path)
            return false;

        $urlExceptionChecker = new Vfr_Controller_Router_UrlExcemptionChecker();
        if ($urlExceptionChecker->isExcempt($path))
            return false;

        if (null === $this->_urlRedirectModel) {
            $this->_urlRedirectModel = new Common_Model_UrlRedirect();
        }

        $urlRedirectRow = $this->_urlRedirectModel->lookupIncomingUrl($path);

        if (null === $urlRedirectRow)
            return false;

        switch ($urlRedirectRow->responseCode) {
            case 301:
                header("HTTP/1.1 301 Moved Permanently");
                break;
            case 302:
                // no need for a header
                break;
            case 404:
                header('HTTP/1.1 404 Not Found');
                break;
            case 503:
                header('HTTP/1.1 503 Service Temporarily Unavailable');
                header('Status: 503 Service Temporarily Unavailable');
                exit;
                break;
        }
        header("Location: " . $request->getScheme()
               . '://' . $request->getHttpHost() . self::URI_DELIMITER
               . $urlRedirectRow->redirectUrl);
        exit();
    }

    public function assemble($data = array(), $reset = false, $encode = false)
    {
    }
}