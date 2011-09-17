<?php
class AdvertiserPasswordResetController extends Zend_Controller_Action
{
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
                
                $advertiserResetRow = $advertiserModel->getAdvertiserResetDetailsByToken($token);
                $advertiserRow = $advertiserModel->getAdvertiserById($advertiserResetRow->idAdvertiser);
                
                if ($advertiserResetRow) {
                    $advertiserModel->updatePassword($advertiserResetRow->idAdvertiser, $request->getParam('passwd'));
                    
                    // load the templates
					$text = file_get_contents(APPLICATION_PATH . '/modules/frontend/views/emails/advertiser-password-reset-confirmation.txt');
                                        
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
						'to' 		=> $advertiserRow->emailAddress,
						'subject' 	=> 'HolidayPropertyWorldwide.com Password Changed',
						'bodyText'  => $text,
						'bodyHtml'	=> null
					);
					
					$message = serialize($test);
					$queue->send($message);
                    
                    // delete this advertiser reset token entry
                    $advertiserResetRow->delete();
                    
                    $this->_redirect(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-password-reset/successfully-updated');
                } else {
                    $this->_redirect(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-password-reset/expired');
                }
              
                //var_dump($advertiserResetRow, $token);
                //die();
            }
        } else {
            // check to see if the token exists
            $advertiserModel = new Common_Model_Advertiser();
            $advertiserResetRow = $advertiserModel->getAdvertiserResetDetailsByToken($token);
            
            if (!$advertiserResetRow) {
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
                    // load the templates
					$text = file_get_contents(APPLICATION_PATH . '/modules/frontend/views/emails/advertiser-password-reset.txt');
					$html = file_get_contents(APPLICATION_PATH . '/modules/frontend/views/emails/advertiser-password-reset.html');
		
                    // generate a new random token
                    $tokenObj = new Vfr_Token();
        			$token = $tokenObj->generateUniqueToken();
                    
                    // add this token to the reset table
                    $advertiserModel->addPasswordReset($advertiserRow->idAdvertiser, $token);
                    
					// fill the template placeholders
					$text = str_replace("[[token]]", $token, $text);
					$html = str_replace("[[token]]", $this->view->escape($token), $html);
                    
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
						'to' 		=> $advertiserRow->emailAddress,
						'subject' 	=> 'HolidayPropertyWorldwide.com Reset Your Password',
						'bodyText'  => $text,
						'bodyHtml'	=> null
					);
					
					$message = serialize($test);
					$queue->send($message);
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
    
    public function sentAction()
    {   
    }
    
    public function expiredAction()
    {   
    }
    
    public function successfullyUpdatedAction() {}
}
