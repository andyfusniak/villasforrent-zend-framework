<?php
class Api_Helper_RestfulApi extends Zend_Controller_Action_Helper_Abstract
{
    public function init()
    {
		$request = $this->getRequest();
        Zend_Controller_Action_HelperBroker::getStaticHelper('layout')->disableLayout();
        //Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(true)
        
        $acceptHeader = $request->getHeader('Accept');
        if ($acceptHeader) {
            switch (true) {
                case strstr($acceptHeader, "+xml"):
                    $request->setParam('format', 'xml');
                break;
                
                case strstr($acceptHeader, "+json"):
                    $request->setParam('format', 'json');
                break;
            }
        }
        
        $request->setParam('apiKey', $request->getHeader('x-apikey'), null);
        $request->setParam('digestKey', $request->getHeader('x-digestkey'), null);
		
		$contextSwitch = Zend_Controller_Action_HelperBroker::getStaticHelper('contextSwitch');
        $contextSwitch->addActionContext('index', array('xml', 'json')); // main arrival point for std routing
		$contextSwitch->addActionContext('get', array('xml','json')); // RESTful CRUD ops
		$contextSwitch->addActionContext('post', array('xml','json'));
		$contextSwitch->addActionContext('put', array('xml','json'));
		$contextSwitch->addActionContext('delete', array('xml','json')); 
		$contextSwitch->initContext();
		$contextSwitch->setAutoJsonSerialization(false);
		$this->getActionController()->_contextSwitch = $contextSwitch;
		
        // Set a Vary response header based on the Accept header
        $this->getResponse()->setHeader('Vary', 'Accept');
    }
    
    public function direct()
    {
        //var_dump('Api_Helper_RestfulApi called');
    }
}
