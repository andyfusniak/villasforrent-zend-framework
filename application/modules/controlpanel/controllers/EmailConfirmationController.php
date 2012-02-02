<?php
class Controlpanel_EmailConfirmationController extends Zend_Controller_Action
{
    public function preDispatch()
	{
		$this->_helper->ensureSecure();
	}
    
    public function confirmAction()
    {
        $request = $this->getRequest();
        $token = $request->getParam('token');
        
        $advertiserModel = new Common_Model_Advertiser();
        
        $tokenRow = $advertiserModel->getAdvertiserEmailConfirmationDetailsByToken($token);
        
        if ($tokenRow == null) {
            $this->_helper->redirector->gotoSimple(
                'token-expired-or-invalid',
                'email-confirmation',
                'controlpanel'
            );
        }
        
        $idAdvertiser = $tokenRow->idAdvertiser;
        
        // update the email last confirmed date field
        // and remove the old token
        $advertiserModel->activate($idAdvertiser, $token);
     
        $this->_helper->redirector->gotoSimple(
            'activated',
            'email-confirmation',
            'controlpanel'
        );
    }
    
    public function activatedAction() {}
    public function tokenExpiredOrInvalidAction() {}
}