<?php
class Api_PropertyController extends Zend_Rest_Controller
{
    protected $_apiModel;
    
    protected $_contextSwitch = null;
    protected $_vfrConfig     = null;
    
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
    }
    
    private function digestKey()
    {
        $request = $this->getRequest();
        
        $digestkey = sha1($request->getParam('apikey', '') . 'property' . $request->getParam('id', '') . $this->_vfrConfig['api']['digestpasswd']);
        return $digestkey;
    }
    
    public function indexAction()
    {
        $this->getResponse()->setHttpResponseCode(200)
                            ->appendBody("From indexAction() returning all" . $idCalendar);
    }
    
    private function hasAuthorisation($apiKey)
    {
        // get the API Model
        $this->_apiModel = new Common_Model_Api();
        $authorised = $this->_apiModel->hasAuthorisation($apiKey);
        
        if (!$authorised) {
            $this->getResponse()->setHttpResponseCode(401)
                                ->appendBody("Authorisation failed - check your API key");
        }
        
        return $authorised;
    }
    
    public function getAction()
    {
        if (!$this->hasAuthorisation($this->getRequest()->getParam('apikey'))) {
            return;
        }
        
        $digestKey = $this->getRequest()->getParam('digestkey', '');
        
        if ($this->digestKey() != $digestKey) {
            return 'Digest keys do not match';
        }
        
        
        $propertyModel = new Common_Model_Property();
        $idCalendar = $propertyModel->getCalendarIdByPropertyId($this->getRequest()->getParam('id'));
        
        $calendarModel = new Common_Model_Calendar();
        $ratesRowset = $calendarModel->getRatesByCalendarId($idCalendar);
       
                           
        //$this->view->ratesRowset = $ratesRowset;
        if ($this->_contextSwitch->getCurrentContext() == 'json') {
            $this->getResponse()->setHttpResponseCode(200)
                                ->appendBody(Zend_Json::prettyPrint(Zend_Json_Encoder::encode($ratesRowset->toArray())));
        }
    }
    
    public function postAction()
    {
    }
    
    public function putAction()
    {
    }
    
    public function deleteAction()
    {
    }
}