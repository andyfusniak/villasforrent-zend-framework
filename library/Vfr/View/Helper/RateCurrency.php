<?php
class Vfr_View_Helper_RateCurrency extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';

    public function rateCurrency($rate, $baseCurrency)
    {
        if ($rate !== null)
            return (string) $rate . ' ' . $baseCurrency;
            
        return '';
    }
}
