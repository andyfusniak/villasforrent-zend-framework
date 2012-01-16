<?php
class Frontend_Form_Step4RateDeleteConfirmForm extends Zend_Form
{
	protected $_idProperty;
	protected $_idRate;
	
	protected $_digestKey = null;
	
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
	
	public function setDigestKey($digestKey)
	{
		$this->_digestKey = $digestKey;	
	}
	
	public function init()
	{ 
		$this->setMethod('post');
		$this->setAction('/advertiser-rates/delete-confirm');
		
		$this->addElement('hidden', 'idProperty', array (
			'value'	=> $this->_idProperty
		));
		
		$this->addElement('hidden', 'idRate', array (
			'value'	=> $this->_idRate
		));
		
		$this->addElement('hidden', 'digestKey', array (
			'value'	=> $this->_digestKey
		));
	}
}