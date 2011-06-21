<?php
class Vfr_View_Helper_BaseCurrency extends Zend_View_Helper_Abstract
{
    public function baseCurrency($currencyCode)
    {
        $calendarModel = new Common_Model_Calendar();
        $currencyRowset = $calendarModel->getAllCurrencies();

        foreach ($currencyRowset as $row) {
            if ($row->iso3char == $currencyCode)
                break;
        }

        return $row->name;
    }
}
