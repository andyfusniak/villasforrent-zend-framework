<?php
class Admin_View_Helper_InvoiceDate extends Zend_View_Helper_Abstract
{
    public function invoiceDate($dt)
    {
        return $this->view->escape(
            strtoupper(
                strftime("%e-%b-%y", strtotime($dt))
            )
        );
    }
}
