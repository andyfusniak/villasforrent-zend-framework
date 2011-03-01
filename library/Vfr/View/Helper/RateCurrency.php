<?php
class Vfr_View_Helper_RateCurrency extends Zend_View_Helper_Abstract
{
    public function rateCurrency($rate, $baseCurrency)
    {
        return (string) $rate . ' ' . $baseCurrency;
    }
}