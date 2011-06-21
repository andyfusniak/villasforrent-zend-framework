<?php
class Admin_Form_LocationSelectForm extends Zend_Form
{
    protected $idProperty = null;
    protected $idFastLookup = null;
	
    protected $idCountry = null;
	protected $idRegion = null;
	protected $idDestination = null;
	
    public function setIdProperty($idProperty)
    {
        $this->idProperty = $idProperty;
    }
    
    public function setIdFastLookup($idFastLookup)
    {
		$this->idFastLookup = $idFastLookup;
    }
    
	public function setIdCountry($idCountry)
	{
		$this->idCountry = $idCountry;
	}
	
	public function setIdRegion($idRegion)
	{
		$this->idRegion = $idRegion;
	}
	
	public function setIdDestination($idDestination)
	{
		$this->idDestination = $idDestination;
	}
	
    public function __construct($options = null)
    {
        parent::__construct($options);
	}
	
	public function init()
	{
		$this->setMethod('post');
        $this->setAction('/admin/property/set-location');
        
        $this->addElement('hidden', 'idProperty', array (
            'required'      => true,
            'value'         => $this->idProperty,
            'validators'	=> array (
				array('NotEmpty', true, array('messages' => array ('isEmpty' => 'The hidden idProperty is missing')))
			)
        ));
        
        $this->addElement('hidden', 'idFastLookup', array (
            'required'		=> true,
			'value'	        => $this->idFastLookup,
            'validators'	=> array (
				array('NotEmpty', true, array('messages' => array ('isEmpty' => 'Select a location for this property')))
			)
		));
		
		$this->addElement('hidden', 'idCountry', array (
            'required'      => false,
            'value'         => $this->idCountry
		));
		
		$this->addElement('hidden', 'idRegion', array (
            'required'      => false,
            'value'         => $this->idRegion
		));
		
		$this->addElement('hidden', 'idDestination', array (
            'required'      => false,
            'value'         => $this->idDestination
		));
    }
}