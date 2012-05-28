<?php
class Controlpanel_Form_ChangeEmailAddress extends Zend_Form
{
    public function init()
    {
        // add path to custom validators
        $this->addElementPrefixPath(
            'Vfr_Validate',
            'Vfr/Validate/',
            'validate'
        );

        $this->setMethod('post');

        $this->addElement('text', 'emailAddress', array(
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'Please enter your email address'))),
                array('EmailAddress', true, array(
                    'messages' => array('emailAddressInvalidFormat' => 'Your email address is invalid'))),
                array('UniqueAdvertiserEmail', false, array(new Common_Model_Advertiser()))
            )
        ));
    }
}
