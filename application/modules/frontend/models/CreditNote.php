<?php
class Frontend_Model_CreditNote
{
    private $_idCreditNote = null;
    private $creditNoteDate;
    private $total;
    private $currency;
    private $creditAddressObj;
    private $itemLastAdded;
    private $refunded;
    private $applied = null; // by default assumed to be not applied to an invoice
    private $added;
    private $updated;

    private $creditNoteItems = array();
    private $appliedRefunds = array();

    public function __construct() {}

    public function setCreditNoteId($idCreditNote)
    {
        $this->_idCreditNote = (int) $idCreditNote;
        return $this;
    }

    public function getCreditNoteId()
    {
        return $this->_idCreditNote;
    }

    public function setCreditNoteDate($creditNoteDate)
    {
        $this->creditNoteDate = $creditNoteDate;
        return $this;
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

    /**
     * @param Frontend_Model_Address $creditAddressObj
     * @return Frontend_Model_CreditNote
     */
    public function setCreditAddress(Frontend_Model_Address $creditAddressObj)
    {
        $this->creditAddressObj = $creditAddressObj;
        return $this;
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

    public function setRefunded($refunded)
    {
        $this->refunded = (int) $refunded;
        return $this;
    }

    public function getRefunded()
    {
        return $this->refunded;
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

    /**
     * Add a credit note item to this credit note
     *
     * @paramm Frontend_Model_CreditNoteItem $creditNoteItemObj the credit note object
     * @return Frontend_Model_CreditNote
     */
    public function addCreditNoteItem(Frontend_Model_CreditNoteItem $creditNoteItemObj)
    {
        array_push($this->creditNoteItems, $creditNoteItemObj);
        return $this;
    }

    /**
     * @return array list of credit note item objects
     */
    public function getCreditNoteItems()
    {
        return $this->creditNoteItems;
    }

    /**
     * Add an applied refund to this credit note
     *
     * @param Frontend_Model_Refund $appliedRefundObj the refund object
     * @param Frontend_Model_CreditNote
     */
    public function addAppliedRefund(Frontend_Model_Refund $appliedRefundObj)
    {
        array_push($this->appliedRefunds, $appliedRefundObj);
        return $this;
    }

    /**
     * @return array list of applied refund objects
     */
    public function getAppliedRefunds()
    {
        return $this->appliedRefunds;
    }
}
