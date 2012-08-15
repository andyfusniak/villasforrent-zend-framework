<?php
class Admin_Form_XmlUploadForm extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->addElement('file', 'filename', array(
            'required'      => true,
            'validators'    => array(
                //array ('Count', true, array   ('min' => 1, 'max' => 1)),
                //array ('IsImage', true, array('image/jpeg', 'image/png'))
                array('Extension', false, 'xml')
            )
        ));
    }
}