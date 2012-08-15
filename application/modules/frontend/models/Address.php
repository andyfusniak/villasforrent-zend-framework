<?php
class Frontend_Model_Address
{
    private $_idAddress = null;
    private $name;
    private $line1;
    private $line2;
    private $line3;
    private $townCity;
    private $county;
    private $postcode;
    private $country;
    private $added;
    private $updated;

    public function __construct() {}

    public function setAddressId($idAddress)
    {
        $this->_idAddress = $idAddress;
        return $this;
    }

    public function getAddressId()
    {
        return $this->idAddress;
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

    public function setLine1($line1)
    {
        $this->line1 = $line1;
        return $this;
    }

    public function getLine1()
    {
        return $this->line1;
    }

    public function setLine2($line2)
    {
        $this->line2 = $line2;
        return $this;
    }

    public function getLine2()
    {
        return $this->line2;
    }

    public function setLine3($line3)
    {
        $this->line3 = $line3;
        return $this;
    }

    public function getLine3()
    {
        return $this->line3;
    }

    public function setTownCity($townCity)
    {
        $this->townCity = $townCity;
        return $this;
    }

    public function getTownCity()
    {
        return $this->townCity;
    }

    public function setCounty($county)
    {
        $this->county = $county;
        return $this;
    }

    public function getCounty()
    {
        return $this->county;
    }

    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
        return $this;
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    public function getCountry()
    {
        return $this->country;
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
