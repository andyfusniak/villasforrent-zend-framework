<?php
class Frontend_Form_Advertiser_RegistrationForm extends Zend_Form
{
    public function setIdAdministrator($id)
    {
		if ($this->idAdministrator !== null)
			$this->idAdministrator->setValue($id);
    }
    
    public function init()
    {
		// add path to custom validators
        $this->addElementPrefixPath(
            'Vfr_Validate',
            'Vfr/Validate/',
            'validate'
        );

        $this->setMethod('post');
		$this->setAttrib('id', 'registration_form');
		
		$this->addDecorators(array(
			'FormElements',
			array('Fieldset', array('legend' => 'Advertiser Registration',
									'id' => 'registration_legend')),
			'Form'
		));
		
		$this->addElement('text', 'firstname', array(
			'required' => true,
			'label' => 'Firstname:',
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Please enter your firstname')))
			)
		));
		
		$this->addElement('text', 'lastname', array(
			'required' => true,
			'label' => 'Lastname:',
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Please enter your lastname')))
			)
		));
						   
		$this->addElement('text', 'emailAddress', array(
			'required' => true,
			'label' => 'Email address:',
			'filters'    => array('StringTrim'),
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Please enter your email address'))),
				array('EmailAddress', true, array(
					'messages' => array('emailAddressInvalidFormat' => 'Your email address is invalid'))),
				array('UniqueAdvertiserEmail', false, array(new Common_Model_Advertiser()))
			)
		));
		
		$this->addElement('password', 'passwd', array(
			'required' => true,
			'label' => 'Choose a password',
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Please choose a password')))
			)
		));
		
		$this->addElement('password', 'passwdVerify', array(
			'required' => true,
			'label' => 'Repeat your password:',
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Please confirm your password'))),
				array('PasswordConfirmation', false)
			)
		));
		
		$group = $this->addDisplayGroup(array('firstname', 'lastname', 'emailAddress', 'passwd', 'passwdVerify'),
							   'main',
							   array('disableLoadDefaultDecorators' => true));
		$this->getDisplayGroup('main')
			 ->addDecorators(array(
				'FormElements',
				array('HtmlTag', array('tag' => 'dl'))
			 ));
			 
		$this->addElement('hidden', 'idAdministrator', array(
			'decorators' => array(
				'ViewHelper'
			)
		));
		
		$this->addElement('submit', 'submit', array(
			'label' => 'Send',
			'ignore' => true,
			'decorators' => array(
				'ViewHelper'
			)
		));
    }
}

//$this->firstname = new Zend_Form_Element_Text('firstname');
		//$this->firstname->setRequired(true)
		//		        ->addErrorMessage('Please enter your firstname.')
		//		        ->setLabel('Firstname:');
						
        //$this->lastname = new Zend_Form_Element_Text('lastname');
		//$this->lastname->setRequired(true)
		//			   ->addErrorMessage('Please enter your lastname.')
		//			   ->setLabel('Lastname:');

		//$this->addDecorator('FormElements')
		//	 ->addDecorator('Fieldset', array('legend' => 'Advertiser Registration'))
		//	 ->addDecorator('Form');
					   
		//function singleMessageValidator($validator, $message)
		//{
		//	    foreach ($validator->getMessageVariables() as $key) {
		//			        $validator->setMessage($message, $key);
		//					    }
		//						    return $validator;
		//}

		//$validator = singleMessageValidator(new Zend_Validate_Digits, 'Snap! Wrong again!');

		//$this->emailAddress = new Zend_Form_Element_Text('emailAddress');
		//$notEmptyValidator = new Zend_Validate_NotEmpty();
		//$notEmptyValidator->setMessage('Please enter your email address.', 'isEmpty');
		//$emailAddressValidator = new Zend_Validate_EmailAddress();
		//$emailAddressValidator->setMessage('Your email address is invalid', 'emailAddressInvalidHostname');
		//$uniqueAdvertiserEmail = new Vfr_Validate_UniqueAdvertiserEmail();
	 	//$this->emailAddress->setRequired()
		//		           ->addValidator($notEmptyValidator, true)
		//		           ->addValidator($emailAddressValidator, true)
		//				   ->addValidator($uniqueAdvertiserEmail, true)
		//		           ->setLabel('Email address:');
		
		//$this->passwd = new Zend_Form_Element_Text('passwd');
		//$this->passwd->addPrefixPath('Vfr_Validate', 'Vfr/Validate/', 'validate');
		//$this->passwd->setRequired(true)
		//			 ->setLabel('Choose a password:');
		
		//$this->passwdVerify = new Zend_Form_Element_Text('passwdVerify');
		//$this->passwdVerify->setRequired(true)
		//				   ->addErrorMessage('Please confirm your password again')
		//				   ->setLabel('Repeat your password:')
		//				   ->addValidator(new Vfr_Validate_PasswordConfirmation());
		
		//$this->idAdministrator = new Zend_Form_Element_Hidden('idAdministrator');
		//$this->hidden->setValue('v1');
		//$this->idAdministrator->setDecorators(array('ViewHelper'));
		
		//$this->submit = new Zend_Form_Element_Submit('submit');
		//$this->submit->setDecorators(array('ViewHelper'));
		//$this->submit->setLabel('Send');
	  
		//->addDecorator('FormElements')
		//->addDecorator('HtmlTag', array('tag' => 'dl'));
	  
	  	//$this->addElements(array($this->idProperty, $firstname, $lastname));
        //$this->addSubForm($sub, 'sub');
