<?php
class Common_Model_Property extends Vfr_Model_Abstract
{
	private $_facilityResource=null;
	
	public function doSearch($options=null)
	{
		$propertyResource = $this->getResource('Property');
		return $propertyResource->doSearch($options);
	}

	public function createProperty($options)
	{
		$propertyResource = $this->getResource('Property');
		return $propertyResource->createProperty($options['params']);
	}

	public function getPropertyById($idProperty)
	{
		$idProperty = (int) $idProperty;

		$propertyResource = $this->getResource('Property');
		return $propertyResource->getPropertyById($idProperty);
	}
	
	public function getPropertyContentById($idProperty)
	{
		$idProperty = (int) $idProperty;
		
		$propertyContentResource = $this->getResource('PropertyContent');
		return $propertyContentResource->getPropertyContentById($idProperty);
	}

	public function getPropertyContentArrayById($idProperty, $lang='EN')
	{
		$idProperty = (int) $idProperty;
		
		$propertyContentResource = $this->getResource('PropertyContent');
		$propertyContentFieldResource = $this->getResource('PropertyContentField');
		
		$fieldsRowset = $propertyContentFieldResource->getAllPropertyContentFields();
		
		
		// build a 1,2,3 to name1, name2, name3 lookup table
		$fieldLookupArray = array();
		foreach ($fieldsRowset as $row) {
			$idPropertyContentField = $row->idPropertyContentField;
			
			$fieldLookupArray[$idPropertyContentField] = $row->name;
		}
		
		$rowset = $propertyContentResource->getPropertyContentById($idProperty, $lang);
		
		// build the mapping array
		$fieldsArray = array();
		foreach ($rowset as $row) {
			$idPropertyContentField = $row->idPropertyContentField;
			
			$fieldName = $fieldLookupArray[$idPropertyContentField];
			$fieldsArray[$fieldName] = (string) $row->content;
		}
		
		return $fieldsArray;
	}

	public function getAllPropertyTypes($inUse=true)
	{
		$propertyTypeResource = $this->getResource('PropertyType');
		return $propertyTypeResource->getAllPropertyTypes($inUse);
	}

	public function getRatesByCalendarId($idCalendar)
	{
		$idCalendar = (int) $idCalendar;

		$rateResource = $this->getResource('Rate');
		return $rateResource->getRatesByCalendarId($idCalendar);
	}
	
	public function getCalendarById($idCalendar)
	{
		$idCalendar = (int) $idCalendar;
		
		$calendarResource = $this->getResource('Calendar');
		return $calendarResource->getCalendarById($idCalendar);
	}
	
	public function getCalendarIdByPropertyId($idProperty)
	{
		$idProperty = (int) $idProperty;
		
		$calendarResource = $this->getResource('Calendar');
		return $calendarResource->getCalendarIdByPropertyId($idProperty);
	}

	public function getAvailabilityRangeByCalendarId($idCalendar, $startDate=null, $endDate=null)
	{
		$idCalendar = (int) $idCalendar;

		$availabilityResource = $this->getResource('Availability');
		return $availabilityResource->getAvailabilityRangeByCalendarId($idCalendar,
																	   $startDate,
																	   $endDate);
	}

	public function getAllPhotosByPropertyId($idProperty)
	{
		$idProperty = (int) $idProperty;

		$photoResource = $this->getResource('Photo');
		return $photoResource->getAllPhotosByPropertyId($idProperty);
	}
	
	public function getAllFacilities($inUse=true)
	{
		if (null === $this->_facilityResource) {
			$this->_facilityResource = $this->getResource('Facility');
		}
		return $this->_facilityResource->getAllFacilities($inUse);
	}
	
	public function getAllFacilitiesByPropertyId($idProperty, $inUse=true)
	{
		if (null === $this->_facilityResource) {
			$this->_facilityResource = $this->getResource('Facility');
		}
		return $this->_facilityResource->getAllFacilitiesByPropertyId($idProperty, $inUse);
	}
}
