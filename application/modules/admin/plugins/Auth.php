<?php
class Admin_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $moduleName = $request->getModuleName();
        if ($moduleName != 'admin')
            return;

        $config = array (
            'accept_schemes' => 'digest',
            'realm'          => 'Admin',
            'digest_domains' => '/admin',
            'nonce_timeout'  => 3600
        );

        $adapter = new Zend_Auth_Adapter_Http($config);
        $digestResolver = new Zend_Auth_Adapter_Http_Resolver_File(APPLICATION_PATH .'/configs/passwd.txt');

        $adapter->setDigestResolver($digestResolver);   
                
        $request  = $this->getRequest();
        $response = $this->getResponse();

        $adapter->setRequest($request);
        $adapter->setResponse($response);

        $adapter = $adapter->authenticate();
        $identity = $adapter->getIdentity();

        if (!isset($identity['username'])) {
            $request->setModuleName('admin');
            $request->setControllerName('auth-fail');
            $request->setActionName('auth-fail');
        }
    }
}

