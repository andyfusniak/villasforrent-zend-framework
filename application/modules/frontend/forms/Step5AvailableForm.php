<?php
class Frontend_Form_Step5AvailableForm extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
	}
	
	public function init()
	{
		$this->setMethod('post');
		$this->setAction(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-property/step5-available');
        $this->setName('step5');
		$this->setAttrib('id', 'step5');
		
		$this->addElements(array());
		
		$this->addElement('submit', 'submit', array('required' => false,
                                                    'ignore' => true,
                                                    'decorators' => array('ViewHelper',array('HtmlTag',
                                                        array('tag' => 'dd', 'id' => 'form-submit')))
                                                    ));
	}
}