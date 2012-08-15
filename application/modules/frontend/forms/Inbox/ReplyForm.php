<?php
class Frontend_Form_Inbox_ReplyForm extends Zend_Form
{
    protected $idMessageThread;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function setIdMessageThread($idMessageThread)
    {
        $this->idMessageThread = $idMessageThread;
    }

    public function init()
    {
        $this->setMethod('post');

        $this->addElement('hidden', 'idMessageThread', array(
            'value' => $this->idMessageThread
        ));

        $this->addElement('textarea', 'body', array(
            'required' => true,
            'validators' => array(
                array('NotEmpty', true,
                    array('messages' => array('isEmpty' => 'Please enter your email address'))
                )
            )
        ));
    }
}
