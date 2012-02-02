<?php
class Controlpanel_Form_Property_Step4RatesForm extends Zend_Form
{
	protected $_idProperty;
	protected $_digestKey = null;
	
    public function __construct($options = null)
    {
        parent::__construct($options);
	}
	
	public function setIdProperty($idProperty)
	{
		$this->_idProperty = $idProperty;
	}
	
	public function setDigestKey($digestKey)
	{
		$this->_digestKey = $digestKey;
	}
	
	public function init()
	{ 
		$this->setMethod('post');
		$this->setAction('/controlpanel/property/step4-rates');
        
        $this->addPrefixPath('Vfr_Form', 'Vfr/Form');
        $this->addElementPrefixPath('Vfr_Validate', 'Vfr/Validate', 'validate');
		
		$this->addElement('hidden', 'idProperty', array (
			'value'	=> $this->_idProperty	
		));
		
		$this->addElement('hidden', 'digestKey', array (
			'value'	=> $this->_digestKey	
		));
		
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