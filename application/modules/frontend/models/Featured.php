<?php
class Frontend_Model_Featured
{

    /**
     * @var int
     */
    private $idFeaturedProperty = null;

    /**
     * @var int
     */
    private $idProperty;

    /**
     * @var int
     */
    private $idLocation;

    /**
     * @var string
     */
    private $startDate;

    /**
     * @var string
     */
    private $expiryDate;

    /**
     * @var int
     */
    private $position;

    /**
     * @var string
     */
    private $added;

    /**
     * @var string
     */
    private $updated;

    /**
     * @var string
     */
    private $lastModifiedBy;

    public function __construct() {}

    public function setFeaturedPropertyId($idFeaturedProperty)
    {
        $this->idFeaturedProperty = (int) $idFeaturedProperty;
        return $this;
    }

    /**
     * @return int the featured property id
     */
    public function getFeaturedPropertyId()
    {
        return $this->idFeaturedProperty;
    }

    public function setPropertyId($idProperty)
    {
        $this->idProperty = (int) $idProperty;
        return $this;
    }

    public function getPropertyId()
    {
        return $this->idProperty;
    }

    public function setLocationId($idLocation)
    {
        $this->idLocation = (int) $idLocation;
        return $this;
    }

    public function getLocationId()
    {
        return $this->idLocation;
    }

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
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

    public function setPosition($position)
    {
        $this->position = (int) $position;
        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setAdded($added)
    {
        $this->added = $added;
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
