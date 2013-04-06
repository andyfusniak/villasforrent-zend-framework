<?php
class Api_Helper_JsonRestfulApi extends Zend_Controller_Action_Helper_Abstract
{
    protected $_contextSwitch;

    public function preDispatch()
    {
        Zend_Controller_Action_HelperBroker::getStaticHelper('layout')->disableLayout();
        Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(true);
    }

    public function init()
    {
        $request = $this->getRequest();

        // Handle only JSON requests
        $acceptHeader = $request->getHeader('Accept');

        if (strstr($acceptHeader, 'json')) {
            $request->setParam('format', 'json');
        } else {
            // 415 Unsupported Media Type
            $this->getResponse()->setHttpResponseCode(415);

            // encode and output the JSON
            $json = Zend_Json::encode(
                array(
                    'code'    => 415,
                    'message' => 'This API Support only JSON Requests'
                )
            );

            $this->getResponse()->appendBody(Zend_Json::prettyPrint($json,
                array('indent' => '    ')
            ));
            return;
        }

        $request->setParam('apiKey', $request->getHeader('x-apikey'), null);
        $request->setParam('digestKey', $request->getHeader('x-digestkey'), null);

        $contextSwitch = Zend_Controller_Action_HelperBroker::getStaticHelper('contextSwitch');

        $contextSwitch->addActionContext('index', array('json')); // main arrival point for std routing
        $contextSwitch->addActionContext('get', array('json'));   // RESTful CRUD ops
        $contextSwitch->addActionContext('post', array('json'));
        $contextSwitch->addActionContext('put', array('json'));
        $contextSwitch->addActionContext('delete', array('json'));
        $contextSwitch->setAutoJsonSerialization(false);
        $contextSwitch->initContext();

        //$this->getActionController()->_contextSwitch = $contextSwitch;

        // Set a Vary response header based on the Accept header
        $this->getResponse()->setHeader('Vary', 'Accept');

        $this->_contextSwitch = $contextSwitch;
    }

    public function respond($json, $pretty = false)
    {
        if ($pretty) {
            $this->getResponse()->appendBody(Zend_Json::prettyPrint($json,
                array('indent' => '    ')
            ));
        } else {
            $this->getResponse()->appendBody($json);
        }
    }

    public function direct()
    {
        return $this->_contextSwitch;
    }
}
