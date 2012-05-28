<?php
class Api_PropertyRatesController extends Vfr_Controller_Resource
{
    //public function init()
    //{
    //    $this->_helper->restfulApi();
    //}

    public function init()
    {
        $request = $this->getRequest();

        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
        $this->_vfrConfig = $bootstrap['vfr'];

        //$this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();


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

        $request->setParam('apikey', $request->getHeader('x-apikey'), null);
        $request->setParam('digestkey', $request->getHeader('x-digestkey'), null);

        $this->_contextSwitch = $this->_helper->getHelper('contextSwitch');
        $this->_contextSwitch->addActionContext('get', array('xml','json'))
                             ->initContext();
        $this->_contextSwitch->setAutoJsonSerialization(false);


        // Set a Vary response header based on the Accept header
        $this->getResponse()->setHeader('Vary', 'Accept');
        var_dump($request);
        die();
    }

    public function getAction()
    {
        $ratesModel = Common_Model_Rates();

        die('get');
    }

    public function postAction() {}
    public function putAction() {}
    public function deleteAction() {}
}
