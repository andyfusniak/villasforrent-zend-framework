<?php
class Admin_View_Helper_InvoiceMoney extends Zend_View_Helper_Abstract
{
    public function invoiceMoney($total, $currency = null)
    {
        $totalString = $this->view->escape(
            number_format($total, 2, ".", ",")
        );

        if ($currency) {
            $totalString .= ' ' . $this->view->escape(
                strtoupper($currency)
            );
        }

        return $totalString;
    }
}
