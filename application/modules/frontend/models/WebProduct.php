<?php
class Frontend_Model_WebProduct
{
    private $_idWebProduct = null;
    private $productCode;
    private $name;
    private $unitPrice;
    private $repeats;
    private $description;
    private $added;
    private $updated;

    public function __construct() {}

    public function setWebProductId($idWebProduct)
    {
        $this->_idWebProduct = (int) $idWebProduct;
        return $this;
    }

    public function getWebProduct()
    {
        return $this->_idWebProduct;
    }

    public function setProductCode($productCode)
    {
        $this->productCode = $productCode;
        return $this;
    }

    public function getProductCode()
    {
        return $this->productCode;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = (float) $unitPrice;
        return $this;
    }

    public function getUnitPrice()
    {
        return $this->unitPrice;
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

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
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
