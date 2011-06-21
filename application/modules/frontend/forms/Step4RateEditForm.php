<?php
class Frontend_Form_Step4RateEditForm extends Zend_Form
{
    protected $_idProperty;
    protected $_idRate;
    protected $_name;
    protected $_rates;
    
    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    
    public function setIdProperty($idProperty)
    {
		$this->_idProperty = $idProperty;
    }
    
    public function setIdRate($idRate)
    {
        $this->_idRate = $idRate;
    }
    
    public function setName($name)
    {
        $this->_name = $name;
    }
    
    public function setRates($rates)
    {
        $this->_rates = $rates;
    }
    
    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/advertiser-rates/edit');
        
        $this->addPrefixPath('Vfr_Form', 'Vfr/Form');
        $this->addElementPrefixPath('Vfr_Validate', 'Vfr/Validate', 'validate');
        
        $this->addElement('hidden', 'idProperty', array (
			'value'	=> $this->_idProperty	
		));
        
		$this->addElement('hidden', 'idRate', array (
			'value'	=> $this->_idRate
		));

        $this->addElement('text', 'name', array (
			'required'	=> false,
            'value'     => $this->_name
		));
        
        $this->addElement('ratesRangePicker', 'rates', array (
            'validators' => array (
				array('RatesRange', true, array('mode'   => 'update',
                                                'idRate' => $this->_idRate) )
            ),
            'value' => $this->_rates
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