<?php
class Controlpanel_Form_ChooseNewPasswordForm extends Zend_Form
{
    protected $token;
    
    public function setToken($token)
    {
        $this->token = $token;
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
        $this->setAction('/controlpanel/password-reset/reset');
    
        $this->addElement('hidden', 'token', array (
			'value'	=> $this->token
		));
    
        $this->addElement('password', 'passwd', array(
            'required' => true,
            'validators' => array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'Please choose a password'))),
                array('StringLength', false, array(6,24)),
        )));
            
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