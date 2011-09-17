<?php
class Frontend_Form_Advertiser_RegistrationForm extends Zend_Form
{
	protected $idAdministrator;
	
    public function setIdAdministrator($idAdministrator)
    {
		$this->idAdministrator = $idAdministrator;
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
		$this->setAction('/advertiser-registration/register/');
		
		$this->addElement('hidden', 'idAdministrator', array (
			'value'	=> $this->idAdministrator
		));
		
		$this->addElement('text', 'firstname', array(
			'required' => true,
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Please enter your firstname')))
			)
		));
		
		$this->addElement('text', 'lastname', array(
			'required' => true,
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Please enter your lastname')))
			)
		));
						   
		$this->addElement('text', 'emailAddress', array(
			'required' => true,
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
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Please choose a password'))),
				array('StringLength', false, array(6,24))
			)
		));
		
		$this->addElement('password', 'passwdVerify', array(
			'required' => true,
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Please confirm your password'))),
				array('StringLength', false, array(6,24)),
				array('PasswordConfirmation', false)
			)
		));
    }
}
