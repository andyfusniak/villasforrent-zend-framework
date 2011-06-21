<?php
class Frontend_Form_Step4BaseCurrency extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
	}
    
    public function init()
    {
        $this->setMethod('post');
		$this->setAction('/advertiser-rates/base-currency');
        
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