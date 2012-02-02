<?php
class Controlpanel_ChangeEmailAddressController extends Zend_Controller_Action
{
    public function preDispatch()
	{
		$this->_helper->ensureSecure();
	}
    
    public function confirmAction()
    {
        $request = $this->getRequest();
        $token = $request->getParam('token', null);
        
        $advertiserModel = new Common_Model_Advertiser();
        $tokenRow = $advertiserModel->getAdvertiserChangeEmailAddressConfirmationDetailsByToken(
            $token
        );
        
        if (null == $tokenRow) {
            $this->_helper->redirector->gotoSimple(
                'token-expired-or-invalid',
                'change-email-address',
                'controlpanel'
            );
        }
        
        $idAdvertiser = $tokenRow->idAdvertiser;
        
        
        try {
            // change the email address and remove the old token
            $advertiserModel->applyNewEmail(
                $idAdvertiser,
                $token
            );
        } catch (Vfr_Exception_Advertiser_Db_DuplicateEntry $e) {
            $advertiserModel->cancelChangeEmailRequst(
                $idAdvertiser,
                $token
            );
            
            $this->_helper->redirector->gotoSimple(
                'email-exists',
                'change-email-address',
                'controlpanel'
            );
        } catch (Exception $e) {
            throw $e;
        }
        
        
        // reload the advertiser record
        $advertiserRow = $advertiserModel->getAdvertiserById($idAdvertiser);
        
        // update the current login session with the new record
        $auth = Zend_Auth::getInstance();
		$auth->getStorage()->write($advertiserRow);
     
        $this->_helper->redirector->gotoSimple(
            'changed',
            'change-email-address',
            'controlpanel'
        );
    }
    
    public function changedAction() {}
    public function emailExistsAction() {}
    public function tokenExpiredOrInvalidAction() {}
}