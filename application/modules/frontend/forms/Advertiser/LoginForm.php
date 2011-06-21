<?php
class Frontend_Form_Advertiser_LoginForm extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
	}
	
	public function init()
	{
		$this->setMethod('post');
		$this->setAction('/advertiser-authentication/login');
		
		$this->addElement('text', 'emailAddress', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Please enter your email address'))),
				array('EmailAddress', true, array(
					'messages' => array('emailAddressInvalidFormat' => 'Your email address is invalid')))
			)
		));
		
		$this->addElement('password', 'passwd', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Please enter your password')))
			)
		));
	}
}