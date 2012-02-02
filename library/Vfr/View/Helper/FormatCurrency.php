<?php
class Vfr_View_Helper_FormatCurrency extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';

    public function formatCurrency($amount)
    {
        if ($amount === null)
            return '';
        
        return sprintf("%d", $amount);
    }
}
