<?php
class Controlpanel_Form_LoginForm extends Zend_Form
{
	protected $emailAddress;
	
    public function setEmailAddress($emailAddress)
    {
		$this->emailAddress = $emailAddress;
    }
	
    public function __construct($options = null)
    {
        parent::__construct($options);
	}
	
	public function init()
	{
		$this->setMethod('post');
		$this->setAction('/controlpanel/authentication/login');
		
		$this->addElement('text', 'emailAddress', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'value' => $this->emailAddress,
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Please enter your email address'))),
				array(new Vfr_Validate_EmailCheck(), true, array(
					'messages' => array('emailAddressInvalidFormat' => 'Your email address is invalid')))
			)
		));
		
		$this->addElement('password', 'passwd', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'value' => $this->passwd,
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Please enter your password'))),
				array('StringLength', false, array(6,24))
			)
		));
	}
}