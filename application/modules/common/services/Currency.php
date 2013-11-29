<?php
class Common_Service_Currency
{
    protected $_calendarModel = null;

    public function __construct()
    {
        $this->_calendarModel = new Common_Model_Calendar();
    }

    public function getCurrencyHash($forSelectList = true)
    {
        $currencyRowset = $this->_calendarModel->getAllCurrencies();

        if ($forSelectList)
            $currencyList = array('' => '--select--');
        else
            $currencyList = array();

        foreach ($currencyRowset as $currencyRow) {
            $currencyList[$currencyRow->iso3char] = $currencyRow->name;
        }

        return $currencyList;
    }
}
