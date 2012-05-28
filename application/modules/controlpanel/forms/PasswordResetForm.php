<?php
class Controlpanel_Form_PasswordResetForm extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/controlpanel/password-reset');

        $this->addElement('text', 'emailAddress', array(
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array ('NotEmpty', true, array(
                    'messages' => array('isEmpty' => 'Enter the email you use when logging in')
                )),
                array (new Vfr_Validate_EmailCheck(), true, array ('messages' => array(
                    'emailAddressInvalidFormat' => 'The email address you entered is invalid')))
            )
        ));
    }
}
