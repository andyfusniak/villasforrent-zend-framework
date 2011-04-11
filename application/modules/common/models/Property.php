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
		$propertyResource 			= $this->getResource('Property');
		$propertyContentResource	= $this->getResource('PropertyContent');
		$propertyFacilityResource	= $this->getResource('PropertyFacility');
        $calendarResource 			= $this->getResource('Calendar');
		$facilitiesResource			= $this->getResource('Facility');

        $idProperty = (int) $propertyResource->createProperty($options);
		
		// create the property facility switches
		$facilityRowset	= $facilitiesResource->getAllFacilities(true);
		foreach ($facilityRowset as $facilityRow) {
			$propertyFacilityResource->createPropertyFacility($idProperty, $facilityRow->facilityCode);
		}
		
		$calendarResource->createCalendar($idProperty);
		
		// setup the main version content
		$params = $options['params'];
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_LOCATION_URL, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_META_DATA, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_SEO_DATA, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_WEBSITE, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_HEADLINE_1, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_HEADLINE_2, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_SUMMARY, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_DESCRIPTION, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_BEDROOM_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_BATHROOM_DESC, '');
		
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_KITCHEN_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_UTILITY_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_LIVING_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_OTHER_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_SERVICE_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_NOTES_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_ACCESS_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_OUTSIDE_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_GOLF_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_SKIING_DESC, '');
		
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_SPECIAL_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_BEACH_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_TRAVEL_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_BOOKING_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_TESTIMONIALS_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_CHANGEOVER_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_CONTACT_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_COUNTRY, $params['country']);
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_REGION, $params['region']);
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_LOCATION, $params['destination']);
		
		// setup the update version content
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_LOCATION_URL, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_META_DATA, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_SEO_DATA, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_WEBSITE, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_HEADLINE_1, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_HEADLINE_2, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_SUMMARY, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_DESCRIPTION, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_BEDROOM_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_BATHROOM_DESC, '');
		
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_KITCHEN_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_UTILITY_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_LIVING_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_OTHER_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_SERVICE_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_NOTES_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_ACCESS_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_OUTSIDE_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_GOLF_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_SKIING_DESC, '');
		
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_SPECIAL_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_BEACH_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_TRAVEL_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_BOOKING_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_TESTIMONIALS_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_CHANGEOVER_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_CONTACT_DESC, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_COUNTRY, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_REGION, '');
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_LOCATION, '');
        
		return $idProperty;
	}
	
	public function updateContent($idProperty, $params)
	{
		$idProperty = (int) $idProperty;
		
		//$facilityResource			= $this->getResource('Facility');
		$propertyFacilityResource	= $this->getResource('PropertyFacility');
		$propertyContentResource	= $this->getResource('PropertyContent');
		
		//$facilityRowset = $facilityResource->getAllFacilities();
		
		$propertyFacilityResource->updateFacilitiesByPropertyId($idProperty, $params['facilities']);
		
		// content
		//$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_LOCATION_URL, '');  NOT USED
		//$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_META_DATA, '');     NOT USED
		//$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_SEO_DATA, '');      NOT USED
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_WEBSITE, '');
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_HEADLINE_1, $params['headline1']);
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_HEADLINE_2, $params['headline2']);
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_SUMMARY, $params['summary']);
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_DESCRIPTION, $params['description']);
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_BEDROOM_DESC, $params['bedroomDesc']);
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_BATHROOM_DESC, $params['bathroomDesc']);
		
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_KITCHEN_DESC, $params['kitchenDesc']);
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_UTILITY_DESC, $params['utilityDesc']);
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_LIVING_DESC, $params['livingDesc']);
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_OTHER_DESC, $params['otherDesc']);
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_SERVICE_DESC, $params['serviceDesc']);
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_NOTES_DESC, $params['notesDesc']);
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_ACCESS_DESC, $params['accessDesc']);
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_OUTSIDE_DESC, $params['outsideDesc']);
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_GOLF_DESC, $params['golfDesc']);
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_SKIING_DESC, $params['skiingDesc']);
		
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_SPECIAL_DESC, $params['specialDesc']);
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_BEACH_DESC, $params['beachDesc']);
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_TRAVEL_DESC, $params['travelDesc']);
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_BOOKING_DESC, $params['bookingDesc']);
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_TESTIMONIALS_DESC, $params['testimonialsDesc']);
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_CHANGEOVER_DESC, $params['changeoverDesc']);
		$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_CONTACT_DESC, $params['contactDesc']);
		//$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_COUNTRY, '');
		//$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_REGION, '');
		//$propertyContentResource->updatePropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_MAIN, 'EN', Common_Resource_PropertyContent::FIELD_LOCATION, '');
		
		return $this;
	}

	
	public function updatePropertyStatus($idProperty, $status)
	{
		$idProperty = (int) $idProperty;
		$status 	= (int) $status;
		
		$propertyResource = $this->getResource('Property');
		$propertyResource->updatePropertyStatus($idProperty, $status);
		
		return $this;
	}

	public function getPropertyById($idProperty)
	{
		$idProperty = (int) $idProperty;

		$propertyResource = $this->getResource('Property');
		return $propertyResource->getPropertyById($idProperty);
	}

    public function getPropertiesByAdvertiserId($idAdvertiser)
    {
        $idAdvertiser = (int) $idAdvertiser;

        $propertyResource = $this->getResource('Property');
        return $propertyResource->getPropertiesByAdvertiserId($idAdvertiser);
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
	
	public function getStatusByPropertyId($idProperty)
	{
		$idProperty = (int) $idProperty;
		
		$propertyResource = $this->getResource('Property');
		return $propertyResource->getStatusByPropertyId($idProperty);
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
