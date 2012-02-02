<?php
class Controlpanel_AuthenticationController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_logger = Zend_Registry::get('logger');
		//$this->_logger->log(__METHOD__ . ' started method function init()', Zend_Log::INFO);
    }
	
	public function preDispatch()
	{
		$this->_helper->ensureSecure();
	}
    
    public function loginAction()
    {
		// check to see if the advertiser is already logged in
		if (Zend_Auth::getInstance()->hasIdentity()) {
		    $this->_helper->redirector->gotoSimple(
                'home',
                'account',
                'controlpanel'
            );
		}
		
		$request = $this->getRequest();
		$form = new Controlpanel_Form_LoginForm(
			 array (
                'emailAddress' => $request->getParam('emailAddress', null)
            )
		);
		
		if ($request->isPost()) {	
			if ($form->isValid($this->getRequest()->getPost())) {
				
				$emailAddress = $form->getValue('emailAddress');
				$passwd 	  = $form->getValue('passwd');
				
				try {
                    $advertiserModel = new Common_Model_Advertiser();
					$advertiserRow = $advertiserModel->login($emailAddress, $passwd);
					
                    $auth = Zend_Auth::getInstance();
					$auth->getStorage()->write($advertiserRow);
					
                    $this->_helper->redirector->gotoSimple(
                        'home',
                        'account',
                        'controlpanel'
                    );    
                //} catch (Vfr_Exception_AdvertiserEmailNotConfirmed $e) {
                //    redit to /controlpanel/email-confirmation/activation-required
				} catch (Vfr_Exception_AdvertiserNotFound $e) {
					$this->view->errorMessage = "Email address and password combination incorrect";
				} catch (Vfr_Exception_AdvertiserPasswordFail $e) {
					//var_dump('password incorrect');
					$this->view->errorMessage = "Email address and password combination incorrect";
				} catch (Vfr_Exception_BlowfishInvalidHash $e) {
					$this->view->errorMessage = "You need to reset your password";
				}
			}
		}
        
		$this->view->assign(
            array (
                'form' => $form
            )
        );
	}
    
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
    }
}