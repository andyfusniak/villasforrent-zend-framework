<?php
class Admin_AutoLoginController extends Zend_Controller_Action
{
    public function loginAction()
    {
        // log this advertiser out
        Vfr_Auth_Advertiser::getInstance()->clearIdentity();

        $advertiserModel = new Common_Model_Advertiser();

        $advertiserRow = $advertiserModel->getAdvertiserById(
            $this->getRequest()->getParam('idAdvertiser')
        );

        $advertiserAuth = Vfr_Auth_Advertiser::getInstance();
        $advertiserAuth->getStorage()->write($advertiserRow);

        $this->_helper->redirector->gotoSimple(
            'home',
            'account',
            'controlpanel'
        );
    }
}
