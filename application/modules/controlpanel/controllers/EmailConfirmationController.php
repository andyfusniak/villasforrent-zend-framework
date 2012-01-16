<?php
class AdvertiserEmailConfirmationController extends Zend_Controller_Action
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
            $this->_redirect(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-email-confirmation/token-expired-or-invalid');
            return;
        }
        
        $idAdvertiser = $tokenRow->idAdvertiser;
        
        // update the email last confirmed date field
        // and remove the old token
        $advertiserModel->activate($idAdvertiser, $token);
     
        $this->_redirect(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-email-confirmation/activated');   
    }
    
    public function activatedAction() {}
    public function tokenExpiredOrInvalidAction() {}
}