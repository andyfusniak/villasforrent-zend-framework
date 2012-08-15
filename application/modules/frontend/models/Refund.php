<?php
class Frontend_Model_Refund
{
    private $_idRefund = null;
    private $dateSent;
    private $amount;
    private $currency;
    private $method;
    private $notes;
    private $added;
    private $updated;

    public function __construct() {}

    public function setRefundId($idRefund)
    {
        $this->_idRefund = (int) $idRefund;
        return $this;
    }

    public function getRefundId()
    {
        return $this->_idRefund;
    }

    public function dateSent($dateSent)
    {
        $this->dateSent = $dateSent;
    }

    public function getDateSent()
    {
        return $this->dateSent;
    }

    public function setAmount($amount)
    {
        $this->amount = (float) $amount;
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

    public function setAdded()
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
