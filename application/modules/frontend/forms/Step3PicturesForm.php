<?php
class Frontend_Form_Step3PicturesForm extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
	}
	
	public function init()
	{
		$this->setMethod('post');
		$this->setAction(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-property/step3-pictures');
        $this->setName('step3');
		$this->setAttrib('id', 'step3');
		$this->setAttrib('enctype', 'multipart/form-data');
		
		$file = new Zend_Form_Element_File('filename');
		$file->setLabel('Browse for another photo:')
			 ->setRequired(true);
		
		$caption = new Zend_Form_Element_Text('caption');
		$caption->setLabel('Photo Caption Text')
			    ->setRequired(true);
				
		
		$this->addElements(array($file, $caption));
		
		$this->addElement('submit', 'submit', array('required' => false,
                                                    'ignore' => true,
                                                    'decorators' => array('ViewHelper',array('HtmlTag',
                                                        array('tag' => 'dd', 'id' => 'form-submit')))
                                                    ));
	}
}