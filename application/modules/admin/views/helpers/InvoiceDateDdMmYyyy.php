<?php
class Admin_View_Helper_InvoiceDateDdMmYyyy extends Zend_View_Helper_Abstract
{
    public function invoiceDateDdMmYyyy($dt)
    {
        return $this->view->escape(
            strtoupper(
                strftime("%d/%m/%Y", strtotime($dt))
            )
        );
    }
}
