<?php
class Admin_Form_LoginForm extends Zend_Form
{
    protected $username;
    protected $passwd;

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPasswd($passwd)
    {
        $this->passwd = $passwd;
    }

    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/admin/authentication/login');

        $this->addElement('text', 'username', array(
            'required' => true,
            'filters'  => array('StringTrim'),
            'value'    => $this->username,
            'validators' => array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'Enter admin username'))),
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
