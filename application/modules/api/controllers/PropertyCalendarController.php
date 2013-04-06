<?php
class Api_PropertyCalendarController extends Zend_Rest_Controller
{
    /**
     * ContextSwitch object
     * @var Zend_Controller_Action_Helper_ContextSwitch
     */
    protected $_contextSwitch;
    protected $_request;

    public function preDispatch()
    {
        $this->_contextSwitch = $this->_helper->jsonRestfulApi();
    }

    public function init()
    {
        $this->_request = $this->getRequest();
    }

    public function indexAction()
    {
        $this->_forward(strtolower($this->_request->getMethod()));
    }

    public function getAction()
    {
        $response = $this->getResponse();

        $idProperty = $this->_request->getParam('idProperty');
        $idCalendar = $this->_request->getParam('idCalendar');
        $digestKey  = $this->_request->getParam('digestKey');

        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
        $this->_vfrConfig = $bootstrap['vfr'];

        $s = $this->_request->getParam('apiKey', '')
           . $this->_request->getParam('idProperty', '')
           . $this->_request->getParam('idCalendar')
           . $this->_vfrConfig['api']['digestpasswd'];
        $checkKey = sha1($s);

        if ($digestKey != $checkKey) {
            // 403 Forbidden
            $response->setHttpResponseCode(403);

            // encode and output the JSON
            $json = Zend_Json::encode(
                array(
                    'code'    => 403,
                    'message' => 'digest keys do not match'
                )
            );

            $response->appendBody(Zend_Json::prettyPrint($json,
                array('indent' => '    ')
            ));
            return;
        }

        $calendarModel = new Common_Model_Calendar();
        $ratesRowset = $calendarModel->getRatesByCalendarId($idCalendar);
        $availabilityRowset = $calendarModel->getAvailabilityByCalendarId($idCalendar);

        // 200 OK
        $response->setHttpResponseCode(200);

        // encode and output the JSON
        $json = Zend_Json::encode(array(
            'rates'       => $ratesRowset->toArray(),
            'availability' => $availabilityRowset->toArray()
        ));

        $this->_helper->jsonRestfulApi->respond($json);
    }

    public function postAction()
    {

    }

    public function putAction() {}
    public function deleteAction() {}
}
