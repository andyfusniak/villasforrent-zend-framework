<?php
class Frontend_Helper_EnsureSecure extends Zend_Controller_Action_Helper_Abstract
{
    public function init() {}
    
    public function redirectOnNonSecure()
    {
        $request = $this->getActionController()->getRequest();
				
		$server   = $request->getServer();
        $hostname = $server['HTTP_HOST'];

        if (!$request->isSecure()) {
			//url scheme is not secure so we rebuild url with secureScheme
            $url = Zend_Controller_Request_Http::SCHEME_HTTPS . "://" . $hostname . $request->getPathInfo();

            $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
            $redirector->setGoToUrl($url);
            $redirector->redirectAndExit();
		}
    }
    
    public function direct()
    {
        $this->redirectOnNonSecure();
    }
}