<?php
class Controlpanel_RegistrationController extends Zend_Controller_Action
{
	protected $_advertiserModel;
	
    public function init()
    {
        $this->_logger = Zend_Registry::get('logger');
		$this->_logger->log(__METHOD__ . ' started method function init()', Zend_Log::DEBUG);
		
		$this->_advertiserModel = new Common_Model_Advertiser();
    }
	
	public function preDispatch()
	{
		$this->_helper->ensureSecure();
	}

    public function registerAction()
    {
		$form = new Controlpanel_Form_RegistrationForm(
			array('idAdministrator' => 1)
		);
        
		if ($this->getRequest()->isPost()) {
			if ($form->isValid($this->getRequest()->getPost())) {
				$token = $this->_advertiserModel->addNewAdvertiser($form->getValues());
				
				// get the new advertiser row
				$model = new Common_Model_Advertiser();
                $identity = $model->getAdvertiserByEmail(
					$form->getValue('emailAddress')
				); // type Common_Resource_Advertiser_Row
				
                //// notify the admin
                //if ($identity) {
                //    $vfrMail = new Vfr_Mail('/modules/controlpanel/views/emails', 'password-reset');
                //    $vfrMail->send(
                //        $identity->emailAddress,
                //        "HolidayPropertyWorldwide.com New Advertiser Joined",
                //        array (
                //            'idAdvertiser'  => $identity->idAdvertiser,
                //            'firstname'     => $identity->firstname,
                //            'lastname'      => $identity->lastname,
                //            'emailAddress'  => $identity->emailAddress,
                //            'added'         => $identity->added
                //        ),
                //        Vfr_Mail::MODE_ONLY_TXT
                //    );
                    
                //    $auth = Zend_Auth::getInstance();
                //    $auth->getStorage()->write($identity);
                //}
                
				$vfrMail = new Vfr_Mail(
					'/modules/controlpanel/views/emails',
					'register-confirm-email' // no extensions required
				);
				
                $vfrMail->send(
                    $identity->emailAddress,
                    "HolidayPropertyWorldwide.com Confirm Your Email Address",
                    array (
                        'idAdvertiser'  => $identity->idAdvertiser,
                        'firstname'     => $identity->firstname,
                        'lastname'      => $identity->lastname,
                        'emailAddress'  => $identity->emailAddress,
						'token'         => $token 
                    ),
                    Vfr_Mail::MODE_ONLY_TXT
                );
                
                $this->_helper->redirector->gotoSimple(
                    'continue',
                    'registration',
                    'controlpanel'
                );
			} else {
				$form->populate($this->getRequest()->getPost());
			}
		}
		
        $this->view->form = $form;
    }
	
	public function continueAction() {}
}