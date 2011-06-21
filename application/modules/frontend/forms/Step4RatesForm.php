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
		$this->setAction('/advertiser-property/step4-rates');
        
        $this->addPrefixPath('Vfr_Form', 'Vfr/Form');
        $this->addElementPrefixPath('Vfr_Validate', 'Vfr/Validate', 'validate');
		
		$this->addElement('text', 'name', array (
			'required'	=> false
		));
        
        $this->addElement('ratesRangePicker', 'rates', array (
            'validators' => array (
				array('RatesRange', true, array() )
            )
        ));
		
		$items = array (
			'0' => 'n/a',
            '1' => '1 night',
            '2' => '2 nights',
            '3' => '3 nights',
            '4' => '4 nights',
            '5' => '5 nights',
            '7' => '1 week',
            '14' => '2 weeks',
            '21' => '3 weeks',
            '30' => '1 month',
            '60' => '2 months',
			'90' => '3 months'
		);
		$this->addElement('select', 'minStayDays', array (
			//'required'		=> true,
			'multiOptions'	=> $items,
			//'validators'	=> array (
			//	array('NotEmpty', true, array ('messages' => array ('isEmpty' => 'Required')))
			//)
		));
	}
}
