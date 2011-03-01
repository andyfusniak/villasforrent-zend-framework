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
		$form = new Frontend_Form_Advertiser_RegistrationForm(array('disableLoadDefaultDecorators' => true));
		$form->setAction('/advertiser-registration/register/');
        $form->setName('advertiser_register');
		$form->setIdAdministrator('1');
        
		if ($this->getRequest()->isPost()) {
			$this->_logger->log(__METHOD__ . ' request is of type POST', Zend_Log::DEBUG);
			
			$post = $this->getRequest()->getPost();
			
			//var_dump($post);
			//var_dump($form->isValid($post));
			//var_dump($form->getMessages());
			//foreach ($form->getErrors() as $e) {
			//	var_dump($e);
			//}
			
			if ($form->isValid($post)) {
				$this->_advertiserModel->registerAdvertiser($post);
				return $this->_helper->redirector('advertiser-registration/complete');
			} else {
				$form->populate($post);
			}
		}
		
        $this->view->form = $form;
    }	
}
