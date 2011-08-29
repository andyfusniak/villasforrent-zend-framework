<?php
class Api_PropertyCalendarController extends Zend_Controller_Action
{
    /**
     * ContextSwitch object
     * @var Zend_Controller_Action_Helper_ContextSwitch
     */
    public $_contextSwitch;
    
    public function init()
    {
       $this->_helper->restfulApi();
    }
        
	public function indexAction()
	{
		$this->_forward(strtolower($this->getRequest()->getMethod()));
	}
	
    public function getAction()
    {
		$request = $this->getRequest();
		$idProperty = $this->getRequest()->getParam('idProperty');
        $idCalendar = $this->getRequest()->getParam('idCalendar');
        $digestKey  = $this->getRequest()->getParam('digestKey');
		
		$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
		$this->_vfrConfig = $bootstrap['vfr'];
		
		$s = $request->getParam('apiKey', '')
			 . $request->getParam('idProperty', '')
			 . $request->getParam('idCalendar')
	         . $this->_vfrConfig['api']['digestpasswd'];
		$checkKey = sha1($s);
		
		if ($digestKey != $checkKey) {
			$this->_helper->viewRenderer->setNoRender(true);
			$this->getResponse()
                 ->setHttpResponseCode(403);
			$err = array (
				'code' => 403,
				'message' => 'digest keys do not match'
			);
			
			$this->getResponse()->appendBody(Zend_Json::encode($err));
			return;
		}
		
        $calendarModel = new Common_Model_Calendar();
        $ratesRowset        = $calendarModel->getRatesByCalendarId($idCalendar);
        $availabilityRowset = $calendarModel->getAvailabilityByCalendarId($idCalendar);
        
        if ($this->_contextSwitch->getCurrentContext() == 'json') {
            $this->view->ratesRowset        = $ratesRowset;
            $this->view->availabilityRowset = $availabilityRowset;
            $this->getResponse()->setHttpResponseCode(200);
        }
		
		//	"availability": {<?php echo Zend_Json_Encoder::encode($this->availabilityRowset->toArray()) }

    }
    
    public function postAction()
    {
    }
    
    public function putAction() {}
    public function deleteAction() {}
}