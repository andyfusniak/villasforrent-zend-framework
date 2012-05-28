<?php
class Frontend_Form_PasswordReminderForm extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/controlpanel/password-reminder');

        $this->addElement('text', 'emailAddress', array(
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', true,
                    array('messages' => array('isEmpty' => 'Please enter your email address'))),
                array('EmailAddress', true,
                    array(
                        'messages' => array('emailAddressInvalidFormat' => 'Your email address is invalid')
                    )
                )
            )
        ));
    }
}
