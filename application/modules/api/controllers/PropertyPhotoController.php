<?php
class Api_PropertyPhotoController extends Zend_Controller_Action
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

        $idProperty = $request->getParam('idProperty');
        $digestKey  = $request->getParam('digestKey');

        $propertyModel = new Common_Model_Property();
        $photosRowset  = $propertyModel->getAllPhotosByPropertyId($idProperty);

        if ('json' == $this->_contextSwitch->getCurrentContext()) {
            $this->view->assign(
                array(
                    'photosRowset' => $photosRowset
                )
            );

            $this->getResponse()->setHttpResponseCode(200);
        }
    }
}
