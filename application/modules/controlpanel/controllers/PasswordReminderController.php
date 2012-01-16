<?php
class AdvertiserPasswordReminderController extends Zend_Controller_Action
{
    public function init() {}

    public function indexAction()
    {
		$form = new Frontend_Form_Advertiser_PasswordReminderForm();
		
		if ($this->getRequest()->isPost()) {
			if ($form->isValid($this->getRequest()->getPost())) {
				$emailAddress = $this->getRequest()->getParam('emailAddress');
				
				$advertiserModel = new Common_Model_Advertiser();
				$advertiserRow   = $advertiserModel->getAdvertiserByEmail($emailAddress);
				
				if ($advertiserRow) {
					// prepare to send a UTF-8 email (both text and html combined)
					//$mail = new Zend_Mail('utf-8');
					
					// set the receipt
					//$mail->addTo($emailAddress);
					//$mail->setSubject('Password Reminder Email');
					
					// load the templates
					$text = file_get_contents(APPLICATION_PATH . '/modules/frontend/views/emails/advertiser-password-reminder.txt');
					$html = file_get_contents(APPLICATION_PATH . '/modules/frontend/views/emails/advertiser-password-reminder.html');
					
					// fill the template placeholders
					$text = str_replace("[[emailAddress]]", $emailAddress, $text);
					$text = str_replace("[[passwd]]", $advertiserRow->passwd, $text);
					
					$html = str_replace("[[emailAddress]]", $this->view->escape($emailAddress), $html);
					$html = str_replace("[[passwd]]", $this->view->escape($advertiserRow->passwd), $html);
					
					
                    // get the the configuration
                    $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
					$options = array(
						'name'          => 'sendmail',
						'driverOptions' => array(
							'host'      => $bootstrap['resources']['db']['params']['host'],
							'port'      => '3306',
							'username'  => $bootstrap['resources']['db']['params']['username'],
							'password'  => $bootstrap['resources']['db']['params']['password'],
							'dbname'    => $bootstrap['resources']['db']['params']['dbname'],
							'type'      => 'pdo_mysql'
						)
					);
					
                    // queue the message
					$queue = new Zend_Queue('Db', $options);
					$test = array (
						'to' 		=> $emailAddress,
						'subject' 	=> 'Password Reminder Email',
						'bodyText'  => $text,
						'bodyHtml'	=> $html
					);
					
					$message = serialize($test);
					$queue->send($message);
				}
				
				$this->_helper->redirector->gotoSimple('sent-confirm', 'advertiser-password-reminder', 'frontend');
			}
		}
		
		$this->view->form = $form;
    }
	
	public function sentConfirmAction() {}
	
}

