<?php
class Frontend_Form_Step5AvailabilityEditForm extends Zend_Form
{
    protected $_idProperty;
    protected $_idAvailability;
    protected $_availability;
	
	protected $_digestKey = null;
    
    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    
    public function setIdProperty($idProperty)
    {
        $this->_idProperty = $idProperty;
    }
    
    public function setIdAvailability($idAvailability)
    {
        $this->_idAvailability = $idAvailability;
    }
    
    public function setAvailability($availability)
    {
        $this->_availability = $availability;
    }
	
	public function setDigestKey($digestKey)
	{
		$this->_digestKey = $digestKey;
	}
    
    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/advertiser-availability/edit');
        
        $this->addPrefixPath('Vfr_Form', 'Vfr/Form');
        $this->addElementPrefixPath('Vfr_Validate', 'Vfr/Validate', 'validate');
        
        $this->addElement('hidden', 'idProperty', array (
			'value'	=> $this->_idProperty	
		));
        
		$this->addElement('hidden', 'idAvailability', array (
			'value'	=> $this->_idAvailability
		));
		
		$this->addElement('hidden', 'digestKey', array (
			'value'	=> $this->_digestKey	
		));
        
        $this->addElement('availabilityRangePicker', 'availability', array (
            'validators' => array (
				array('AvailabilityRange', true, array('mode' 			=> 'update',
													   'idAvailability' => $this->_idAvailability) )
            ),
            'value' => $this->_availability
        ));
    }
}