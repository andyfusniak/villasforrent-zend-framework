<?php
class Admin_AutoLoginController extends Zend_Controller_Action
{
    public function loginAction()
    {
        $request = $this->getRequest();

        $idAdvertiser = $request->getParam('idAdvertiser');

        Zend_Auth::getInstance()->clearIdentity();

        $advertiserModel = new Common_Model_Advertiser();
        $advertiserRow = $advertiserModel->getAdvertiserById($idAdvertiser);

        $auth = Zend_Auth::getInstance();
        $auth->getStorage()->write($advertiserRow);

        $this->_redirect(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-account/home');
    }
}

