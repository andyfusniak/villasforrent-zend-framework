<?php
class Frontend_Form_Step3PicturesForm extends Zend_Form
{
	public function setFormIdProperty($idProperty)
    {
		if ($this->idProperty !== null)
			$this->idProperty->setValue($idProperty);
    }
	
    public function __construct($options = null)
    {
        parent::__construct($options);
	}
	
	public function init()
	{
		$this->setMethod('post');
        $this->setName('step3');
		$this->setAttrib('id', 'step3');
		$this->setAttrib('enctype', 'multipart/form-data');
		//$this->setAttrib('onsubmit', 'alert(1)');
		
		$this->addDecorators(array(
			'FormElements',
			array('Fieldset', array('legend' => 'Pictures',
									'id' 	 => 'step3pictures_legend')),
			'Form'
		));
		
		$this->addElement('file', 'filename', array (
			'required'		=> true,
			'label' 		=> 'Browse for a photo',
			'validators'	=> array (
				//array ('Count', true, array	('min' => 1, 'max' => 1)),
				array ('IsImage', true, array('image/jpeg', 'image/png'))
			)
		));
		
		$this->addElement('text', 'caption', array (
			'label'		=> 'Photo Caption Text',
			
		));
		
		$group = $this->addDisplayGroup(array('filename', 'caption'),
							   'main',
							   array('disableLoadDefaultDecorators' => true));
		
		$this->getDisplayGroup('main')
			 ->addDecorators(array(
				'FormElements',
				array('HtmlTag', array('tag' => 'dl'))
			 ));
		
		$this->addElement('hidden', 'idProperty', array(
			'decorators' => array(
				'ViewHelper'
			)
		));
			 
		$this->addElement('submit', 'submit', array(
			'label' => 'Send',
			'ignore' => true,
			'decorators' => array(
				'ViewHelper'
			)
		));	
	}
}