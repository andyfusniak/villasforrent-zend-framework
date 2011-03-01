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
		
		$rentalBasis = new Zend_Form_Element_Text('rentalBasis');
		$rentalBasis->setLabel('Rental Basis:')
					->setOptions(array('readOnly' => true));
					
		$baseCurrency = new Zend_Form_Element_Text('baseCurrency');
		$baseCurrency->setLabel('Base Currency:')
					 ->setOptions(array('readOnly' => true));
		
		$this->addElements(array($rentalBasis, $baseCurrency));
		
		$this->addElement('submit', 'submit', array('required' => false,
                                                    'ignore' => true,
                                                    'decorators' => array('ViewHelper',array('HtmlTag',
                                                        array('tag' => 'dd', 'id' => 'form-submit')))
                                                    ));
	}
}
