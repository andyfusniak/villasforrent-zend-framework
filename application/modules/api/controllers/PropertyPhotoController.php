<?php
class Api_PropertyPhotoController extends Zend_Rest_Controller
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
        $this->_forward(strtolower($this->getRequest()->getMethod()));
    }

    public function getAction()
    {
        $request = $this->getRequest();

        $idProperty = $request->getParam('idProperty');
        $digestKey  = $request->getParam('digestKey');

        $propertyModel = new Common_Model_Property();
        $photosRowset  = $propertyModel->getAllPhotosByPropertyId($idProperty);

        // 200 OK
        $this->getResponse()->setHttpResponseCode(200);

        $json = Zend_Json::encode($photosRowset->toArray());

        $this->_helper->jsonRestfulApi->respond($json);
    }

    public function postAction() {}
    public function putAction() {}
    public function deleteAction() {}
}
