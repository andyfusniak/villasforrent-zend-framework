<?php
class Frontend_Form_Member_LoginForm extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/authentication/login');

        $this->addElement('text', 'emailAddress', array(
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', true,
                    array('messages' => array('isEmpty' => 'Please enter your email address'))
                ),
                array('EmailAddress', true,
                    array('messages' => array('emailAddressInvalidFormat' => 'Your email address is invalid'))
                )
            )
        ));

        $this->addElement('password', 'passwd', array(
            'required'   => true,
            'validators' => array(
                array('NotEmpty', true,
                    array('messages' => array('isEmpty' => 'Please choose a password'))
                ),
                array('StringLength', false, array(6,24))
            )
        ));
    }
}
