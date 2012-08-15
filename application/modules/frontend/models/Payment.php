<?php
class Frontend_Model_Payment
{
    private $_idPayment = null;
    private $_idInvoice = null;
    private $dateReceived;
    private $amount;
    private $currency;
    private $method;
    private $notes;
    private $applied = null; // by default assumed to be non-applied to invoice
    private $added = null;
    private $updated = null;

    public function __construct() {}

    public function setPaymentId($idPayment)
    {
        $this->_idPayment = $idPayment;
        return $this;
    }

    public function getPaymentId()
    {
        return $this->_idPayment;
    }

    public function setInvoiceId($idInvoice)
    {
        $this->_idInvoice = $idInvoice;
        return $this;
    }

    public function getInvoiceId()
    {
        return $this->_idInvoice;
    }

    public function setDateReceived($dateReceived)
    {
        $this->dateReceived = $dateReceived;
        return $this;
    }

    public function getDateReceived()
    {
        return $this->dateReceived;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
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

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function setApplied($applied)
    {
        $this->applied = $applied;
        return $this;
    }

    public function getApplied()
    {
        return $this->applied;
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
}
