<?php
class Admin_PropertyExportXmlController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function singlePropertyAction()
    {
        $propertyService = new Frontend_Service_Property();
        echo $propertyService->getPropertyXml(
            $this->getRequest()->getParam('idProperty')
        );
    }
}
