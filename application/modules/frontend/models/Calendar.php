<?php
class Frontend_Model_Calendar
{
    private $idCalendar = null;
    private $rentalBasis = null;
    private $currencyCode = null;
    private $added = null;
    private $updated = null;

    private $rates = array();
    private $availability = array();

    public function __construct($idCalendar, $rentalBasis, $currencyCode, $added, $updated)
    {
        $this->idCalendar = (int) $idCalendar;
        $this->rentalBasis = $rentalBasis;
        $this->currencyCode = $currencyCode;
        $this->added = $added;
        $this->updated = $updated;
    }

    public function setIdCalendar($idCalendar)
    {
        $this->idCalendar = (int) $idCalendar;
    }

    public function getIdCalendar()
    {
        return $this->idCalendar;
    }

    public function setRentalBasis($rentalBasis)
    {
        $this->rentalBasis = $rentalBasis;
    }

    public function getRentalBasis()
    {
        return $this->rentalBasis;
    }

    public function setCurrencyCode($currenyCode)
    {
        $this->currencyCode = $currencyCode;
    }

    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    public function setAdded($added)
    {
        $this->added = $added;
    }

    public function getAdded()
    {
        return $this->added;
    }

    public function getUpdated($updated)
    {
        return $this->updated;
    }

    public function addRate(Frontend_Model_Rate $rate)
    {
        array_push($this->rates, $rate);
    }

    public function getRate($idx)
    {
        if (isset($this->rates[$idx])) {
            return $this->rates[$idx];
        } else {
            throw new Exception("Rate with index $idx does not exist");
        }
    }

    public function getRatesList()
    {
        return $this->rates;
    }

    public function addAvailability(Frontend_Model_Availability $availability)
    {
        array_push($this->availability, $availability);

        return $this;
    }

    /**
     * @param int $idx index into the list of availability items
     * @return Frontend_Model_Availability the availability object
     */
    public function getAvailability(int $idx)
    {
        if (isset($this->availability[$idx])) {
            return $this->availability[$idx];
        } else {
            throw new Exception("Availability of index $idx does not exist");
        }
    }

    public function getAvailabilityList()
    {
        return $this->availability;
    }
}
