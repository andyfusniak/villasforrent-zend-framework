<?php
class Controlpanel_Form_Property_Step5AvailabilityDeleteConfirmForm extends Zend_Form
{
	protected $_idProperty;
	protected $_idAvailability;
	
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
	
	public function setDigestKey($digestKey)
	{
		$this->_digestKey = $digestKey;
	}
	
	public function init()
	{ 
		$this->setMethod('post');
		$this->setAction('/controlpanel/availability/delete-confirm');
		
		$this->addElement('hidden', 'idProperty', array (
			'value'	=> $this->_idProperty
		));
		
		$this->addElement('hidden', 'idAvailability', array (
			'value'	=> $this->_idAvailability
		));
		
		$this->addElement('hidden', 'digestKey', array (
			'value'	=> $this->_digestKey
		));
	}
}
