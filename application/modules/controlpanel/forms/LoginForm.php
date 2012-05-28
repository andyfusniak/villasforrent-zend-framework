<?php
class Controlpanel_Form_LoginForm extends Zend_Form
{
    protected $emailAddress;
    protected $disableLayout;
    protected $redirectUri;

    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    public function setDisableLayout($disableLayout)
    {
        $this->disableLayout = $disableLayout;
    }

    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/controlpanel/authentication/login');

        $this->addElement('hidden', 'disable_layout',
            array(
                'value' => $this->disableLayout
            )
        );

        $this->addElement('hidden', 'redirect_uri',
            array(
                'value' => $this->redirectUri
            )
        );

        $this->addElement('text', 'emailAddress', array(
            'required'   => true,
            'filters'    => array('StringTrim'),
            'value'      => $this->emailAddress,
            'validators' => array(
                array('NotEmpty', true,
                    array('messages' => array('isEmpty' => 'Please enter your email address'))),
                array(new Vfr_Validate_EmailCheck(), true,
                    array(
                        'messages' => array('emailAddressInvalidFormat' => 'Your email address is invalid')
                    )
                )
            )
        ));

        $this->addElement('password', 'passwd', array(
            'required'   => true,
            'filters'    => array('StringTrim'),
            'value'      => $this->passwd,
            'validators' => array(
                array('NotEmpty', true,
                    array('messages' => array('isEmpty' => 'Please enter your password'))),
                array('StringLength', false, array(6,24))
            )
        ));
    }
}
