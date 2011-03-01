<?php
class Frontend_Form_Advertiser_LoginForm extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
	}
	
	public function init()
	{
        $this->setName('advertiser_login');   
		$this->setAttrib('id', 'advertiser_id');

		$this->addDecorators(array(
			'FormElements',
			array('Fieldset', array('legend' => 'Advertiser Login',
									'id' => 'login_legend')),
			'Form'
		));
		
		$this->addElement('text', 'emailAddress', array(
			'required' => true,
			'label' => 'Email address:',
			'filters' => array('StringTrim'),
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Please enter your email address'))),
				array('EmailAddress', true, array(
					'messages' => array('emailAddressInvalidFormat' => 'Your email address is invalid')))
			)
		));
		  
		/*
        'StringLength', false, array(6,20,'messages' => array(
                    Zend_Validate_StringLength::TOO_SHORT => 'Your password is too short.',
                    Zend_Validate_StringLength::TOO_LONG => 'Your password is too long.'
                    )),
        */
        
		$this->addElement('password', 'passwd', array(
			'required' => true,
			'label' => 'Password:',
			'filters' => array('StringTrim'),
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Please enter your password')))
			)
		));
		
		$group = $this->addDisplayGroup(array('emailAddress', 'passwd'),
										'main',
										array('disableLoadDefaultDecorators' => true));
		$this->getDisplayGroup('main')
		     ->addDecorators(array('FormElements',
								   array('HtmlTag', array('tag' => 'dl'))
							));
        
        $this->addElement('submit', 'submit', array(
				'label' => 'Login',
				'ignore' => true,
                'decorators' => array('ViewHelper')
		));
	}
}