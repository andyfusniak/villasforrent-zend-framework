<?php
class AdvertiserAuthenticationController extends Zend_Controller_Action
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
		// check to see if the administrator is already logged in
		if (Zend_Auth::getInstance()->hasIdentity()) {
			//var_dump(Zend_Auth::getInstance()->getIdentity());
			//exit;
			$this->_logger->log("Frontend_AuthenticationController already logged in, redirecting away", Zend_Log::DEBUG);
			$this->_redirect(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-account/home');
		}
		
		$this->_logger->log(
            "Frontend_AdvertiserAuthenticationController creating new Frontend_Form_AdvertiserLoginForm",
							Zend_Log::DEBUG);
		
		$request = $this->getRequest();
		$form = new Frontend_Form_Advertiser_LoginForm(
			 array (
                'emailAddress' => $request->getParam('emailAddress', null)
            )
		);
		
		$form->setMethod('post');
		$form->setAction(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-authentication/login');
		$this->view->form = $form;
		
		//var_dump($this->_getAllParams());
		
		if ($request->isPost()) {
			
			if ($form->isValid($this->getRequest()->getPost())) {
				$authAdapter = $this->getAuthAdapter();
				
				$emailAddress = $form->getValue('emailAddress');
				$passwd 	  = $form->getValue('passwd');
				
				$advertiserModel = new Common_Model_Advertiser();
				try {
					//var_dump($emailAddress, $passwd);
					$advertiserRow = $advertiserModel->login($emailAddress, $passwd);
					
					$auth = Zend_Auth::getInstance();
					$auth->getStorage()->write($advertiserRow);
					
					// update the last logged in date to now
					$advertiserModel->updateLastLogin($advertiserRow->idAdvertiser);
					
					$this->_redirect(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-account/home');
				} catch (Vfr_Exception_AdvertiserNotFound $e) {
					// advertiser failed to login
					//var_dump('advertiser not found');
					$this->view->errorMessage = "Email address and password combination incorrect";
					return;
				} catch (Vfr_Exception_AdvertiserPasswordFail $e) {
					//var_dump('password incorrect');
					$this->view->errorMessage = "Email address and password combination incorrect";
					return;
				} catch (Vfr_Exception_BlowfishInvalidHash $e) {
					$this->view->errorMessage = "You need to reset your password";
				}
			}
		}
		
		//$this->_logger->log("Frontend_Form_AdvertiserAuthenticationController loginAction() end", Zend_Log::DEBUG);
    }
    
    public function logoutAction()
    {
		$this->_logger->log("AdvertiserAuthenticationController logoutAction() start", Zend_Log::DEBUG);
		
		$this->_logger->log("AdvertiserAuthenticationController clearing identity", Zend_Log::DEBUG);
       
        Zend_Auth::getInstance()->clearIdentity();
        
		$this->_logger->log("AdvertiserAuthenticationController calling function \$this->_redirect", Zend_Log::DEBUG);
		//$this->_redirect(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-account/home/');
		
		$this->_logger->log("AdvertiserAuthenticationController logoutAction() end", Zend_Log::DEBUG);
    }

    private function getAuthAdapter()
    {
        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $authAdapter->setTableName('Advertisers')
                    ->setIdentityColumn('emailAddress')
					->setCredentialColumn('passwd');
		return $authAdapter;
    }
}
