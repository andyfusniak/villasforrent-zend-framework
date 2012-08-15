<?php
class Frontend_Model_InvoiceItem
{
    private $_idInvoiceitem = null;
    private $idInvoice;
    private $webProductObj = null;
    private $webServiceObj = null;
    private $qty;
    private $unitAmount;
    private $description;
    private $startDate;
    private $expiryDate;
    private $repeats;
    private $lineTotal;
    private $added;
    private $updated;

    public function __construct() {}

    public function setInvoiceItemId($invoiceItemId)
    {
        $this->_idInvoiceitem = $invoiceItemId;
        return $this;
    }

    /**
     * @return int the invoice item id
     */
    public function getInvoiceItemId()
    {
        return $this->_idInvoiceitem;
    }

    public function setInvoiceId($idInvoice)
    {
        $this->idInvoice = $idInvoice;
        return $this;
    }

    /**
     * @return int the invoice id
     */
    public function getInvoiceId()
    {
        return $this->idInvoice;
    }

    /**
     * Set the web product for this invoice-item
     * @param Frontend_Model_WebProduct $webProductObj the web-product object
     * @return Frontend_Model_InvoiceItem fluent interface
     */
    public function setWebProduct(Frontend_Model_WebProduct $webProductObj)
    {
        $this->webProductObj = $webProductObj;
        return $this;
    }

    /**
     * @return Frontend_Model_WebService
     */
    public function getWebProduct()
    {
        return $this->webProductObj;
    }

    /**
     * Set the web service for this invoice-item
     * @param Frontend_Model_WebService $webServiceObj the web-service object
     * @return Frontend_Model_InvoiceItem fluent interface
     */
    public function setWebService(Frontend_Model_WebService $webServiceObj)
    {
        $this->webServiceObj = $webServiceObj;
        return $this;
    }

    /**
     * @return Frontend_Model_WebService
     */
    public function getWebService()
    {
        return $this->webServiceObj;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
        return $this;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setUnitAmount($unitAmount)
    {
        $this->unitAmount = $unitAmount;
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

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;
        return $this;
    }

    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    public function setRepeats($repeats)
    {
        $this->repeats = $repeats;
        return $this;
    }

    public function getRepeats()
    {
        return $this->repeats;
    }

    public function setLineTotal($lineTotal)
    {
        $this->lineTotal = $lineTotal;
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
