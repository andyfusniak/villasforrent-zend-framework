<?php
class Vfr_View_Helper_BaseCurrency extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';

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
