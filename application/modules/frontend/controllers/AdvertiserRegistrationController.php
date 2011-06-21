<?php
class AdvertiserRegistrationController extends Zend_Controller_Action
{
	protected $_advertiserModel;
	
    public function init()
    {
        $this->_logger = Zend_Registry::get('logger');
		$this->_logger->log(__METHOD__ . ' started method function init()', Zend_Log::DEBUG);
		
		$this->_advertiserModel = new Common_Model_Advertiser();
    }

    public function registerAction()
    {
		$form = new Frontend_Form_Advertiser_RegistrationForm(array('idAdministrator' => 1));
        
		if ($this->getRequest()->isPost()) {
			if ($form->isValid($this->getRequest()->getPost())) {
				$this->_advertiserModel->registerAdvertiser($form->getValues());
				
				// automatically login the advertiser
				$model = new Common_Model_Advertiser();
                $identity = $model->getAdvertiserByEmail($form->getValue('emailAddress')); // type Common_Resource_Advertiser_Row
				
				$auth = Zend_Auth::getInstance();
				$auth->getStorage()->write($identity);
				
				$this->_redirect(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-account/home');
			} else {
				$form->populate($this->getRequest()->getPost());
			}
		}
		
        $this->view->form = $form;
    }
	
	public function completeAction()
	{
		
	}
}
