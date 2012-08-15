<?php
class Frontend_Form_Member_RegistrationForm extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/signup/join');

        // add path to custom validators
        $this->addElementPrefixPath(
            'Vfr_Validate',
            'Vfr/Validate/',
            'validate'
        );

        $this->addElement('text', 'firstname', array(
            'required' => true,
            'validators' => array(
                array('NotEmpty', true,
                    array('messages' => array('isEmpty' => 'Please enter your firstname'))
                )
            )
        ));

        $this->addElement('text', 'emailAddress', array(
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', true,
                    array('messages' => array('isEmpty' => 'Please enter your email address'))
                ),
                array('EmailAddress', true,
                    array('messages' => array('emailAddressInvalidFormat' => 'Your email address is invalid'))
                ),
                array('UniqueMemberEmail', false, array(new Common_Model_Member()))
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
