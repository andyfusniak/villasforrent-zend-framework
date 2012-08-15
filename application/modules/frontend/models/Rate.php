<?php
class Frontend_Model_Rate
{
    private $idRate = null;
    private $startDate;
    private $endDate;
    private $name;
    private $minStayDays;
    private $weeklyRate;
    private $weekendNightlyRate;
    private $midweekNightlyRate;
    private $added;
    private $updated;

    public function __construct($idRate, $startDate, $endDate, $name, $weeklyRate, $weekendNightlyRate, $midweekNightlyRate, $added, $updated)
    {
        $this->idRate = (int) $idRate;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->name = $name;
        $this->weeklyRate = $weeklyRate;
        $this->weekendNightlyRate = $weekendNightlyRate;
        $this->midweekNightlyRate = $midweekNightlyRate;
        $this->added = $added;
        $this->updated = $updated;
    }

    public function setIdRate($idRate)
    {
        $this->idRate = $rate;
    }

    public function getIdRate()
    {
        return $this->idRate;
    }

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setMinStayDays($minStayDays)
    {
        $this->minStayDays = $minStayDays;
    }

    public function getMinStayDays()
    {
        return $this->minStayDays;
    }

    public function setWeeklyRate($weeklyRate)
    {
        $this->weeklyRate = (float) $weeklyRate;
    }

    public function getWeeklyRate()
    {
        return $this->weeklyRate;
    }

    public function setWeekendNightlyRate($weekendNightlyRate)
    {
        $this->weekendNightlyRate = (float) $weekendNightlyRate;
    }

    public function getWeekendNightlyRate()
    {
        return $this->weekendNightlyRate;
    }

    public function setMidweekNightlyRate($midweekNightlyRate)
    {
        $this->midweekNightlyRate = (float) $midweekNightlyRate;
    }

    public function getMidweekNightlyRate()
    {
        return $this->midweekNightlyRate;
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
    }

    public function getUpdated()
    {
        return $this->updated;
    }
}
