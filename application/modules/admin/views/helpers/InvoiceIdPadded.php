<?php
class Admin_View_Helper_InvoiceIdPadded extends Zend_View_Helper_Abstract
{
    public function invoiceIdPadded($id, $padding = 8)
    {
        if (null === $id)
            return '-';
        
        return str_pad($id, $padding, "0", STR_PAD_LEFT);
    }
}
