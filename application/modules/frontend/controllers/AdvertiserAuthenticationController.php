<?php
class AdvertiserAuthenticationController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_logger = Zend_Registry::get('logger');
		$this->_logger->log(__METHOD__ . ' started method function init()', Zend_Log::INFO);
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
		
		$this->_logger->log("Frontend_AdvertiserAuthenticationController creating new Frontend_Form_AdvertiserLoginForm", Zend_Log::DEBUG);
		$form = new Frontend_Form_Advertiser_LoginForm();
		
		$form->setMethod('post');
		$form->setAction(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-authentication/login');
		$this->view->form = $form;
		
		//var_dump($this->_getAllParams());
		
		if ($this->getRequest()->isPost()) {
			
			if ($form->isValid($this->getRequest()->getPost())) {
				$authAdapter = $this->getAuthAdapter();
				
				$emailAddress = $form->getValue('emailAddress');
				$passwd 	  = $form->getValue('passwd');
				
				$authAdapter->setIdentity($emailAddress)
							->setCredential($passwd);
							
				$auth = Zend_Auth::getInstance();
				$result = $auth->authenticate($authAdapter);
				
				if ($result->isValid()) {
					//$this->_logger->log("Frontend_Form_AdvertiserAuthenticationController Yes \$result->isValid() is true ", Zend_Log::DEBUG);
					//$identity = $authAdapter->getResultRowObject(); // return an object(stdClass)
                    
                    $model = new Common_Model_Advertiser();
                    $identity = $model->getAdvertiserByEmail($emailAddress); // type Common_Resource_Advertiser_Row
					
					//$this->_logger->log("Frontend_Form_AdvertiserAuthenticationController storing " . $identity->idAdministrator . " on authStorage", Zend_Log::DEBUG);
					$auth->getStorage()->write($identity);
					
					// update the last logged in date to now
					$model->updateLastLogin($identity->idAdvertiser);
					
					$this->_redirect(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-account/home');
				} else {
					$this->_logger->log(__METHOD__ . ' Result is invalid', Zend_Log::DEBUG);
                    $this->view->errorMessage = "Email address and password combination incorrect";
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
