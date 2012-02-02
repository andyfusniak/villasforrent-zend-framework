<?php
class Controlpanel_Form_Property_Step4BaseCurrency extends Zend_Form
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
		$this->setAction('/controlpanel/rates/base-currency');
        
		$this->addElement('hidden', 'idProperty', array(
			'value' => $this->_idProperty,
		));
		
		$this->addElement('hidden', 'digestKey', array(
			'value' => $this->_digestKey,
		));
		
        $calendarModel = new Common_Model_Calendar();
        $currencyRowset = $calendarModel->getAllCurrencies();
        $currencyList = array('' => '--select--');
		foreach ($currencyRowset as $currencyRow) {
			$currencyList[$currencyRow->iso3char] = $currencyRow->name;
		}
        
        $this->addElement('select', 'currencyCode', array(
			'required' => true,
			'multiOptions' => $currencyList
		));
    }
}