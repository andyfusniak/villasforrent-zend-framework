<?php
class Frontend_Model_Invoice
{
    private $_idInvoice = null;
    private $invoiceDate;
    private $total;
    private $currency;
    private $itemLastAdded;
    private $status;
    private $added;
    private $updated;

    private $invoiceItems = array();
    private $fromAddressObj = null;
    private $billingAddressObj = null;
    private $appliedPayments = array();
    private $appliedCreditNotes = array();

    public function __construct() {}

    public function setInvoiceId($idInvoice)
    {
        $this->_idInvoice = $idInvoice;
        return $this;
    }

    public function getInvoiceId()
    {
        return $this->_idInvoice;
    }

    public function setInvoiceDate($invoiceDate)
    {
        $this->invoiceDate = $invoiceDate;
        return $this;
    }

    public function getInvoiceDate()
    {
        return $this->invoiceDate;
    }

    public function setTotal($total)
    {
        $this->total = (float) $total;
        return $this;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setItemLastAdded($itemLastAdded)
    {
        $this->itemLastAdded = $itemLastAdded;
        return $this;
    }

    public function getItemLastAdded()
    {
        return $this->itemLastAdded;
    }

    /**
     * @param string $status of the invoice 'OPEN' or 'CLOSED'
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setAdded($added)
    {
        $this->added = $added;
        return $this;
    }

    public function getAdded()
    {
        return $this->added;
    }

    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    public function getUpdated()
    {
        return $this->updated;
    }


    /**
     * Attach the from address object
     *
     * @param Frontend_Model_Address $fromAddressObj the from address object
     * @return Frontend_Model_Invoice fluent inferface
     */
    public function setFromAddress(Frontend_Model_Address $fromAddressObj)
    {
        $this->fromAddressObj = $fromAddressObj;
        return $this;
    }

    /**
     * @return Frontend_Model_Address
     */
    public function getFromAddress()
    {
        return $this->fromAddressObj;
    }

    /**
     * Attach the billing address object
     *
     * @param Frontend_Model_Address $billingAddressObj the billing object
     * @return Frontend_Model_Invoice fluent interface
     */
    public function setBillingAddress(Frontend_Model_Address $billingAddressObj)
    {
        $this->billingAddressObj = $billingAddressObj;
        return $this;
    }

    /**
     * @return Frontend_Model_Address
     */
    public function getBillingAddress()
    {
        return $this->billingAddressObj;
    }

    /**
     * Add an applied payment to this invoice object
     *
     * @param Frontend_Model_Payment $paymentObj the payment to be added
     * @return Frontend_Model_Invoice
     */
    public function addAppliedPayment(Frontend_Model_Payment $paymentObj)
    {
        array_push($this->appliedPayments, $paymentObj);
        return $this;
    }

    /**
     * Get the list of payment objects
     * @return array list of payment objects associated to this invoice
     */
    public function getAppliedPayments()
    {
        return $this->appliedPayments;
    }

    /**
     * Check to see if payments have been applied to this invoice
     *
     * @return bool true or false
     */
    public function hasAppliedPayments()
    {
        return (sizeof($this->appliedPayments) > 0);
    }

    /**
     * Add an applied credit note to this invoice object
     *
     * @param Frontend_Model_CreditNote $creditNote the credit note to be added
     * @return Frontend_Model_Invoice
     */
    public function addAppliedCreditNote(Frontend_Model_CreditNote $creditNoteObj)
    {
        array_push($this->appliedCreditNotes, $creditNoteObj);
        return $this;
    }

    /**
     * Get a list of credit notes applied to this invoice
     *
     * @return array list of applied credit notes
     */
    public function getAppliedCreditNotes()
    {
        return $this->appliedCreditNotes;
    }

    public function addInvoiceItem(Frontend_Model_InvoiceItem $invoiceItemObj)
    {
        array_push($this->invoiceItems, $invoiceItemObj);
        return $this;
    }

    /**
     * @return array of invoice items
     */
    public function getInvoiceItems()
    {
        return $this->invoiceItems;
    }

    /**
     * Checks to see if the invoice has invoice items
     *
     * @return bool true or false
     */
    public function hasInvoiceItems()
    {
        return (sizeof($this->invoiceItems) > 0);
    }
}
