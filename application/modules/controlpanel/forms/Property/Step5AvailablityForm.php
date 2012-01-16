<?php
class Frontend_Form_Step5AvailablityForm extends Zend_Form
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
		$this->setAction('/advertiser-property/step5-availability');
		
		$this->addElement('hidden', 'idProperty', array (
			'value'	=> $this->_idProperty	
		));
		
		$this->addElement('hidden', 'digestKey', array (
			'value'	=> $this->_digestKey	
		));
		
		$this->addPrefixPath('Vfr_Form', 'Vfr/Form');
        $this->addElementPrefixPath('Vfr_Validate', 'Vfr/Validate', 'validate');
		
		$this->addElement('availabilityRangePicker', 'availability', array (
            'validators' => array (
                array('AvailabilityRange', true, array() )
            )
        ));
	}
}
