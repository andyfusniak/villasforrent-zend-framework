<?php
class Admin_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $moduleName = $request->getModuleName();
        if ($moduleName !== 'admin')
            return;

        // force SSL connection
        $request = $this->getRequest();
        $server   = $request->getServer();
        $hostname = $server['HTTP_HOST'];

        if (!$request->isSecure()) {
            //url scheme is not secure so we rebuild url with secureScheme
            $url = Zend_Controller_Request_Http::SCHEME_HTTPS . "://" . $hostname . $request->getPathInfo();

            $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
            $redirector->setGoToUrl($url);
            $redirector->redirectAndExit();
        }

        $namespace = new Zend_Session_Namespace();

        if (!isset($namespace->adminUsername)) {
            $request->setModuleName('admin');
            $request->setControllerName('authentication');
            $request->setActionName('login');
        }

        //$config = array (
        //    'accept_schemes' => 'digest',
        //    'realm'          => 'Admin',
        //    'digest_domains' => '/admin',
        //    'nonce_timeout'  => 3600
        //);
        //
        //$adapter = new Zend_Auth_Adapter_Http($config);
        //$digestResolver = new Zend_Auth_Adapter_Http_Resolver_File(APPLICATION_PATH .'/configs/passwd.txt');
        //
        //$adapter->setDigestResolver($digestResolver);
        //
        //$request  = $this->getRequest();
        //$response = $this->getResponse();
        //
        //$adapter->setRequest($request);
        //$adapter->setResponse($response);
        //
        //$adapter = $adapter->authenticate();
        //$identity = $adapter->getIdentity();
        //
        //if (!isset($identity['username'])) {
        //    $request->setModuleName('admin');
        //    $request->setControllerName('auth-fail');
        //    $request->setActionName('auth-fail');
        //}
    }
}
