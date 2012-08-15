<?php
class EmailConfirmationController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $this->_helper->ensureSecure();
    }

    public function confirmAction()
    {
        $request = $this->getRequest();
        $token = $request->getParam('token');

        $memberModel = new Common_Model_Member();
        //$tokenRow = $userModel->getMember();
    }
}