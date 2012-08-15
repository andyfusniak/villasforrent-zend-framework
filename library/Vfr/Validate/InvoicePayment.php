<?php
class Vfr_Validate_InvoicePayment extends Zend_Validate_Abstract
{
    const version = '1.00';

    const INVALID_INVOICE_FORMAT = 'invalidInvoiceFormat';
    const INVOICE_NOT_FOUND = 'invoiceNotFound';

    protected $_messageTemplates = array(
        self::INVALID_INVOICE_FORMAT => "Invoices should be numbers only between 1 and 8 characters",
        self::INVOICE_NOT_FOUND => "Invoice not found",
    );

    public function isValid($value)
    {
        $validNumberPattern = '/^[0-9]{1,8}$/';

        if (!preg_match($validNumberPattern, $value)) {
            $this->_error(self::INVALID_INVOICE_FORMAT, $value);
            return false;
        }

        $idInvoice = (int) $value;
        $invoiceMapper = new Frontend_Model_InvoiceMapper();
        if (!$invoiceMapper->invoiceExists($idInvoice)) {
            $this->_error(self::INVOICE_NOT_FOUND);
            return false;
        }

        return true;
    }
}
