<?php
class Frontend_Model_Availability
{
    private $idAvailability;
    private $startDate;
    private $endDate;

    public function __construct($idAvailability, $startDate, $endDate)
    {
        $this->idAvailability = (int) $idAvailability;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function setIdAvailability()
    {
        $this->idAvailability = $idAvailability;
        return $this;
    }

    public function getIdAvailability()
    {
        return $this->idAvailability;
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

    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }
}
