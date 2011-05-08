<?php
class Frontend_Form_Step4RatesForm extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
	}
	
	public function init()
	{ 
		$this->setMethod('post');
		$this->setAction(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-property/step4-rates');
        $this->setName('step4');
		$this->setAttrib('id', 'step4');
        
        $this->datePicker = new ZendX_JQuery_Form_Element_DatePicker(
                        'from', array('jQueryParams' => array('defaultDate' => '2007/10/10'))
                      );
		
		//$this->addElements(array($datePicker));
		
		$this->addElement('submit', 'submit', array('required' => false,
                                                    'ignore' => true,
                                                    'decorators' => array('ViewHelper',array('HtmlTag',
                                                        array('tag' => 'dd', 'id' => 'form-submit')))
                                                    ));
	}
}
