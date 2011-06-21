<?php
class Common_Model_Calendar extends Vfr_Model_Abstract
{
	//
	// CREATE
	//
	public function addNewRate($idCalendar, $params)
	{
		$idCalendar = (int) $idCalendar;
		$rateResource = $this->getResource('Rate');
		
		return $rateResource->addNewRate($idCalendar, $params);
	}
	
	public function addNewBooking($idCalendar, $params) {
		$idCalendar = (int) $idCalendar;
		$availabilityResource = $this->getResource('Availability');
		
		return $availabilityResource->addNewBooking($idCalendar, $params);
	}
	
	//
	// READ
	//
	
	public function getRatesByCalendarId($idCalendar)
	{
		$idCalendar = (int) $idCalendar;

		$rateResource = $this->getResource('Rate');
		return $rateResource->getRatesByCalendarId($idCalendar);
	}
	
	public function getRateByPk($idRate)
	{
		$idRate = (int) $idRate;
		
		$rateResource = $this->getResource('Rate');
		return $rateResource->getRateByPk($idRate);
	}
	
	public function getRateByStartAndEndDate($idCalendar, $startDate, $endDate)
	{
		$idCalendar = (int) $idCalendar;
		
		$rateResource = $this->getResource('Rate');
		return $rateResource->getRateByStartAndEndDate($idCalendar, $startDate, $endDate);
	}
	
	public function getRateDateRangeCollisions($idCalendar, $startDate, $endDate, $mode='add', $idRate=null)
    {
        $idCalendar = (int) $idCalendar;
        
		$rateResource = $this->getResource('Rate');
		
		if (($mode == 'update') && ($idRate != null))
			return $rateResource->getDateRangeCollisions($idCalendar, $startDate, $endDate, $idRate);
		else
			return $rateResource->getDateRangeCollisions($idCalendar, $startDate, $endDate);
    }
	
	public function getBookingCollisions($idCalendar, $startDate, $endDate, $mode='add', $idAvailability=null)
	{
		$idCalendar = (int) $idCalendar;
		
		$availabilityResource = $this->getResource('Availability');
		if (($mode == 'update') && ($idAvailability != null))
			return $availabilityResource->getBookingCollisions($idCalendar, $startDate, $endDate, $idAvailability);	
		else
			return $availabilityResource->getBookingCollisions($idCalendar, $startDate, $endDate);
	}
	
	public function getAvailabilityByCalendarId($idCalendar)
	{
		$idCalendar = (int) $idCalendar;
		
		$availabilityResource = $this->getResource('Availability');
		return $availabilityResource->getAvailabilityByCalendarId($idCalendar);
	}
	
	public function getAvailabilityByPk($idAvailability)
	{
		$idAvailability = (int) $idAvailability;
		
		$availabilityResource = $this->getResource('Availability');
		return $availabilityResource->getAvailabilityByPk($idAvailability);
	}
	
	public function getAvailabilityByStartAndEndDate($idCalendar, $startDate, $endDate)
	{
		$idCalendar = (int) $idCalendar;
		
		$availabilityResource = $this->getResource('Availability');
		return $availabilityResource->getAvailabilityByStartAndEndDate($idCalendar, $startDate, $endDate);
	}
	
	public function getRentalBasis($idCalendar)
	{
		$idCalendar = (int) $idCalendar;
		
		$calendarResource = $this->getResource('Calendar');
		return $calendarResource->getRentalBasis($idCalendar);
	}
	
	public function getBaseCurrency($idCalendar)
	{
		$idCalendar = (int) $idCalendar;
		
		$calendarResource = $this->getResource('Calendar');
		return $calendarResource->getBaseCurrency($idCalendar);
	}
    
	public function getAllCurrencies($visible=true, $inUse=true)
    {
        $currencyResource = $this->getResource('Currency');
		return $currencyResource->getAllCurrencies($visible, $inUse);
    }
	
	//
	// UPDATE
	//
	
	public function updateRateByPk($idRate, $params)
	{
		$idRate = (int) $idRate;
		
		$rateResource = $this->getResource('Rate');
		return $rateResource->updateRateByPk($idRate, $params);
	}
	
	public function updateRentalBasis($idCalendar, $rentalBasis)
	{
		$idCalendar = (int) $idCalendar;
		
		$calendarResource = $this->getResource('Calendar');
		return $calendarResource->updateRentalBasis($idCalendar, $rentalBasis);
	}
	
	public function updateBaseCurrency($idCalendar, $currencyCode)
	{
		$idCalendar = (int) $idCalendar;
		
		$calendarResource = $this->getResource('Calendar');
		return $calendarResource->updateBaseCurrency($idCalendar, $currencyCode);
	}
	
	public function updateAvailabilityByPk($idAvailability, $params)
	{
		$idAvailability = (int) $idAvailability;
		
		$availabilityResource = $this->getResource('Availability');
		return $availabilityResource->updateAvailabilityByPk($idAvailability, $params);
	}
	
	//
	// DELETE
	//
    
}
