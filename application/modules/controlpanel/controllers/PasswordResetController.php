<?php
class AdvertiserPasswordResetController extends Zend_Controller_Action
{
    public function preDispatch()
	{
		$this->_helper->ensureSecure();
	}
    
    public function resetAction()
    {        
        $request = $this->getRequest();
        $token = $request->getParam('token');
        
        $form = new Frontend_Form_Advertiser_ChooseNewPasswordForm(
            array (
                'token' => $token
            )
        );        
        
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $advertiserModel = new Common_Model_Advertiser();
                
                $tokenRow = $advertiserModel->getAdvertiserResetDetailsByToken($token);
                $advertiserRow = $advertiserModel->getAdvertiserById($tokenRow->idAdvertiser);
                
                if ($tokenRow) {
                    $advertiserModel->updatePassword(
						$tokenRow->idAdvertiser,
                        $request->getParam('passwd')
                    );
                    
                    // send the template
                    $vfrMail = new Vfr_Mail(
                        '/modules/frontend/views/emails',
                        'advertiser-password-reset-confirmation'
                    );
                    
                    $vfrMail->send(
                        $advertiserRow->emailAddress,
                        "HolidayPropertyWorldwide.com Password Changed",
                        null,
                        Vfr_Mail::MODE_ONLY_TXT
                    );
                      
                    // delete this advertiser reset token entry
                    $tokenRow->delete();
                    
                    $this->_redirect(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-password-reset/successfully-updated');
                } else {
                    $this->_redirect(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-password-reset/expired');
                }
            }
        } else {
            // check to see if the token exists
            $advertiserModel = new Common_Model_Advertiser();
            $tokenRow = $advertiserModel->getAdvertiserResetDetailsByToken($token);
            
            if (!$tokenRow) {
                $this->_redirect(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-password-reset/expired');
            }
        }
        
        $this->view->assign(array (
           'form' => $form 
        ));
    }
    
    public function indexAction()
    {
        $form = new Frontend_Form_Advertiser_PasswordResetForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $emailAddress = $request->getParam('emailAddress');
                
                $advertiserModel = new Common_Model_Advertiser();
                $advertiserRow = $advertiserModel->getAdvertiserByEmail($emailAddress);
                
                //var_dump($advertiserRow);
                
                if ($advertiserRow) {
                    // generate a new random token
                    $tokenObj = new Vfr_Token();
        			$token = $tokenObj->generateUniqueToken();
                    
                    // add this token to the reset table
                    $advertiserModel->addPasswordReset($advertiserRow->idAdvertiser, $token);
                                        
                    // send the template
                    $vfrMail = new Vfr_Mail('/modules/frontend/views/emails', 'advertiser-password-reset');
                    $vfrMail->send(
                        $advertiserRow->emailAddress,
                        "HolidayPropertyWorldwide.com Reset Your Password",
                        array (
                            'token' => $token  
                        ),
                        Vfr_Mail::MODE_ONLY_TXT
                    );
                }
                //die('sending reset email');
                
                $this->_redirect(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-password-reset/sent');
            } else {
                $form->populate($request->getParams());
            }
        }

        $this->view->assign( array (
            'form' => $form
        ));
    }
    
    public function sentAction() {}
    public function expiredAction() {}
    public function successfullyUpdatedAction() {}
}
