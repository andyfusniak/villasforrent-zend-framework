<?php
class Frontend_Model_CreditNoteItem
{
    private $_idCreditNoteItem;
    private $qty;
    private $unitAmount;
    private $description;
    private $lineTotal;
    private $added;
    private $updated;

    public function __construct() {}

    public function setCreditNoteItemId($idCreditNoteItem)
    {
        $this->_idCreditNoteItem = $idCreditNoteItem;
        return $this;
    }

    public function getCreditNoteItemId()
    {
        return $this->_idCreditNoteItem;
    }

    public function setQty($qty)
    {
        $this->qty = (int) $qty;
        return $this;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setUnitAmount($unitAmount)
    {
        $this->unitAmount = (float) $unitAmount;
        return $this;
    }

    public function getUnitAmount()
    {
        return $this->unitAmount;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setLineTotal($lineTotal)
    {
        $this->lineTotal = (float) $lineTotal;
        return $this;
    }

    public function getLineTotal()
    {
        return $this->lineTotal;
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
