<?php
class Common_Model_Property extends Vfr_Model_Abstract
{
	private $_facilityResource = null;
	
	public function doSearch($options=null)
	{
		$propertyResource = $this->getResource('Property');
		return $propertyResource->doSearch($options);
	}
	
	//
	// CREATE
	//
	
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
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_COUNTRY, $params['country']);
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_REGION, $params['region']);
		$propertyContentResource->createPropertyContent($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE, 'EN', Common_Resource_PropertyContent::FIELD_LOCATION, $params['destination']);
        
		$checksum = Common_Resource_PropertyContent::generateChecksum($params);
		
		$propertyResource->setPropertyContentChecksum($idProperty, $checksum, Common_Resource_PropertyContent::VERSION_BOTH);
		
		
		return $idProperty;
	}

	//
	// READ
	//

	public function getProperties($page, $interval=30, $order=null, $direction='ASC')
	{
		$page 		= (int) $page;
		$interval 	= (int) $interval;
		
		$propertyResource = $this->getResource('Property');
		
		return $propertyResource->getProperties($page, $interval, $order, $direction);
	}
	
	public function getPropertiesByCountryRegionDestination($idCountry=null, $idRegion=null, $idDestination=null, $page=null, $itemCountPerPage=10, $order=null, $direction='ASC')
	{
		if ($idCountry)
			$idCountry = (int) $idCountry;
			
		if ($idRegion)
			$idRegion = (int) $idRegion;
		
		if ($idDestination)
			$idDestination = (int) $idDestination;
			
		$page = (int) $page;
		$itemCountPerPage	= (int) $itemCountPerPage;
		
		$propertyResource = $this->getResource('Property');
		
		return $propertyResource->getPropertiesByCountryRegionDestination($idCountry, $idRegion, $idDestination, $page, $itemCountPerPage, $order, $direction);
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
	
	public function getAllPropertiesAwaitingInitialApproval()
	{
		$propertyResource = $this->getResource('Property');
		
		return $propertyResource->getAllPropertiesAwaitingInitialApproval();
	}
	
	public function getAllPropertiesAwaitingUpdateApproval()
	{
		$propertyResource = $this->getResource('Property');
		
		return $propertyResource->getAllPropertiesAwaitingUpdateApproval();
	}
	
	public function getAllHolidayTypesArray()
	{
		$holidayTypeResource = $this->getResource('HolidayType');
		
		return $holidayTypeResource->getAllHolidayTypesArray();
	}
	
	public function getPropertyContentByPropertyId($idProperty, $version=Common_Resource_PropertyContent::VERSION_MAIN, $lang='EN', $idPropertyContentFieldList=null)
	{
		$idProperty = (int) $idProperty;
		
		$propertyContentResource = $this->getResource('PropertyContent');
		return $propertyContentResource->getPropertyContentByPropertyId($idProperty, $version, $lang, $idPropertyContentFieldList);
	}
	
	public function getPropertyContentByPropertyList($propertyRowset, $version=Common_Resource_PropertyContent::VERSION_MAIN, $lang='EN', $idPropertyContentFieldList=null)
	{
		if (!$propertyRowset instanceof Common_Resource_Property_Rowset) {
			throw new Exception('Invalid propertyRowset type passed must be of type Common_Resource_Property_Rowset, instead got ' . gettype($propertyRowset));
		}
		
		$propertyContentResource = $this->getResource('PropertyContent');
		return $propertyContentResource->getPropertyContentByPropertyList($propertyRowset, $version, $lang, $idPropertyContentFieldList);
	}
	
	public function getPropertyContentArrayByPropertyList($propertyRowset, $version=Common_Resource_PropertyContent::VERSION_MAIN, $lang='EN', $idPropertyContentFieldList=null)
	{
		if (!$propertyRowset instanceof Common_Resource_Property_Rowset) {
			throw new Exception('Invalid propertyRowset type passed must be of type Common_Resource_Property_Rowset, instead got ' . gettype($propertyRowset));
		}
		
		$propertyContentResource = $this->getResource('PropertyContent');
		$propertyContentRowset = $propertyContentResource->getPropertyContentByPropertyList($propertyRowset, $version, $lang, $idPropertyContentFieldList);
		
		$fieldsArray = array();
		foreach ($propertyContentRowset as $propertyContentRow) {
			$idProperty		 		= $propertyContentRow->idProperty;
			$idPropertyContentField	= $propertyContentRow->idPropertyContentField;
			
			$fieldsArray[$idProperty][$idPropertyContentField] = $propertyContentRow->content;
		}
		
		return $fieldsArray;
	}

	public function getPropertyContentArrayById($idProperty, $version=Common_Resource_PropertyContent::VERSION_MAIN, $lang='EN', $idPropertyContentFieldList=null)
	{
		$idProperty = (int) $idProperty;
		
		$propertyContentResource = $this->getResource('PropertyContent');
		$propertyContentFieldResource = $this->getResource('PropertyContentField');
		$fieldLookupArray = $propertyContentFieldResource->getAllPropertyContentFields();
		
		$rowset = $propertyContentResource->getPropertyContentByPropertyId($idProperty, $version, $lang, $idPropertyContentFieldList);
		
		// build the mapping array
		$fieldsArray = array ();
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
	
	public function getAllPropertyTypesArray($inUse=true)
	{
		$propertyTypeRowset = $this->getAllPropertyTypes($inUse);
		
		$hashTable = array ();
		foreach ($propertyTypeRowset as $propertyTypeRow) {
			$hashTable[$propertyTypeRow->idPropertyType] = $propertyTypeRow->name;
		}
		
		return $hashTable;
	}
	
	public function getPropertyTypeById($idPropertyType)
	{
		$idPropertyType = (int) $idPropertyType;
		$propertyTypeResource = $this->getResource('PropertyType');
		
		return $propertyTypeResource->getPropertyTypeById($idPropertyType);
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
	
	public function getPrimaryPhotoByPropertyId($idProperty)
	{
		$idProperty = (int) $idProperty;
		
		$photoResource = $this->getResource('Photo');
		return $photoResource->getPrimaryPhotoByPropertyId($idProperty);
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
		$idProperty = (int) $idProperty;
		
		if (null === $this->_facilityResource) {
			$this->_facilityResource = $this->getResource('Facility');
		}
		return $this->_facilityResource->getAllFacilitiesByPropertyId($idProperty, $inUse);
	}
	
	public function getHolidayTypeByPropertyId($idProperty)
	{
		$idProperty = (int) $idProperty;
		
		$propertyResource = $this->getResource('Property');
		return $propertyResource->getHolidayTypeByPropertyId($idProperty);
	}
	
	public function isUrlNameTaken($idProperty, $urlName)
	{
		$idProperty = (int) $idProperty;
		
		$propertyResource = $this->getResource('Property');
		return $propertyResource->isUrlNameTaken($idProperty, $urlName);
	}
	
	public function getFeaturedProperties($mask=Common_Resource_Property::FEATURE_MASK_HOMEPAGE, $limit=3, $idCountry=null, $idRegion=null, $idDestination=null)
	{
		$propertyResource = $this->getResource('Property');
		
		return $propertyResource->getFeaturedProperties($mask, $limit, $idCountry, $idRegion, $idDestination);
	}
	
	//
	// UPDATE
	//
	
	public function updateContent($idProperty, $mode='both', $data)
	{
		$idProperty = (int) $idProperty;
		
		// we need to filter the values as sometimes we won't be passed golf, access, skiing etc
		$params = array (
			'website'			=> $data['website'],
			'headline1'			=> $data['headline1'],
			'headline2'			=> $data['headline2'],
			'summary'			=> $data['summary'],
			'description'		=> $data['description'],
			'bedroomDesc'		=> $data['bedroomDesc'],
			'bathroomDesc'		=> $data['bathroomDesc'],
			'kitchenDesc'		=> $data['kitchenDesc'],
			'utilityDesc'		=> $data['utilityDesc'],
			'livingDesc'		=> $data['livingDesc'],
			'otherDesc'			=> $data['otherDesc'],
			'serviceDesc'		=> $data['serviceDesc'],
			'notesDesc'			=> $data['notesDesc'],
			'accessDesc'		=> (isset($data['accessDesc']) && (mb_strlen($data['accessDesc']) > 0)) ? $data['accessDesc'] : '',	// optional field
			'outsideDesc'		=> $data['outsideDesc'],
			'golfDesc'			=> (isset($data['golfDesc']) && (mb_strlen($data['golfDesc']) > 0)) ? $data['golfDesc'] : '', 		// optional field
			'skiingDesc'		=> (isset($data['skiingDesc']) && (mb_strlen($data['skiingDesc']) > 0)) ? $data['skiingDesc'] : '',
			'specialDesc'		=> (isset($data['specialDesc']) && (mb_strlen($data['specialDesc']) > 0)) ? $data['specialDesc'] : '',	// optional field
			'beachDesc'			=> $data['beachDesc'],
			'travelDesc'		=> $data['travelDesc'],
			'bookingDesc'		=> $data['bookingDesc'],
			'testimonialsDesc'	=> $data['testimonialsDesc'],
			'changeoverDesc'	=> $data['changeoverDesc'],
			'contactDesc'		=> $data['contactDesc']
		);
		
		//$facilityResource			= $this->getResource('Facility');
		$propertyResource			= $this->getResource('Property');
		$propertyFacilityResource	= $this->getResource('PropertyFacility');
		$propertyContentResource	= $this->getResource('PropertyContent');
		
		//$facilityRowset = $facilityResource->getAllFacilities();
		
		$propertyFacilityResource->updateFacilitiesByPropertyId($idProperty, $data['facilities']);
		
		switch ($mode) {
			case 'both':
				$updateSet = array (
					Common_Resource_PropertyContent::VERSION_MAIN,
					Common_Resource_PropertyContent::VERSION_UPDATE
				);
				
				$checksum = Common_Resource_PropertyContent::generateChecksum($params);
				$propertyResource->setPropertyContentChecksum($idProperty, $checksum, Common_Resource_PropertyContent::VERSION_BOTH);
			break;
		
			case 'main':
				$updateSet = array (
					Common_Resource_PropertyContent::VERSION_MAIN
				);
				
				$checksum = Common_Resource_PropertyContent::generateChecksum($params);
				$propertyResource->setPropertyContentChecksum($idProperty, $checksum, Common_Resource_PropertyContent::VERSION_MAIN);
			break;
		
			case 'update':
				$updateSet = array (
					Common_Resource_PropertyContent::VERSION_UPDATE
				);
				
				$checksum = Common_Resource_PropertyContent::generateChecksum($params);
				$propertyResource->setPropertyContentChecksum($idProperty, $checksum, Common_Resource_PropertyContent::VERSION_UPDATE);
			break;
		
			default:
				throw new Exception('Invalid mode called');
		}
		
		foreach ($updateSet as $version) {
			//$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_LOCATION_URL, '');  NOT USED
			//$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_META_DATA, '');     NOT USED
			//$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_SEO_DATA, '');      NOT USED
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_WEBSITE, $params['website']);	
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_HEADLINE_1, $params['headline1']);
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_HEADLINE_2, $params['headline2']);
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_SUMMARY, $params['summary']);
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_DESCRIPTION, $params['description']);
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_BEDROOM_DESC, $params['bedroomDesc']);
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_BATHROOM_DESC, $params['bathroomDesc']);
			
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_KITCHEN_DESC, $params['kitchenDesc']);
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_UTILITY_DESC, $params['utilityDesc']);
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_LIVING_DESC, $params['livingDesc']);
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_OTHER_DESC, $params['otherDesc']);
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_SERVICE_DESC, $params['serviceDesc']);
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_NOTES_DESC, $params['notesDesc']);
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_ACCESS_DESC, $params['accessDesc']);
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_OUTSIDE_DESC, $params['outsideDesc']);
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_GOLF_DESC, $params['golfDesc']);
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_SKIING_DESC, $params['skiingDesc']);
			
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_SPECIAL_DESC, $params['specialDesc']);
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_BEACH_DESC, $params['beachDesc']);
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_TRAVEL_DESC, $params['travelDesc']);
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_BOOKING_DESC, $params['bookingDesc']);
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_TESTIMONIALS_DESC, $params['testimonialsDesc']);
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_CHANGEOVER_DESC, $params['changeoverDesc']);
			$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_CONTACT_DESC, $params['contactDesc']);
			//$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_COUNTRY, '');
			//$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_REGION, '');
			//$propertyContentResource->updatePropertyContent($idProperty, $version, 'EN', Common_Resource_PropertyContent::FIELD_LOCATION, '');
		}
		
		return $this;
	}
	
	public function copyUpdateToMaster($idProperty)
	{
		$idProperty = (int) $idProperty;
		
		$propertyContentResource	= $this->getResource('PropertyContent');
		$propertyResource			= $this->getResource('Property');
		
		$checksum = $propertyContentResource->copyUpdateToMaster($idProperty);
		$propertyResource->setPropertyContentChecksum($idProperty, $checksum, Common_Resource_PropertyContent::VERSION_MAIN);
		$propertyResource->updateApproveProperty($idProperty);
	}
	
	public function updatePropertyStatus($idProperty, $status)
	{
		$idProperty = (int) $idProperty;
		$status 	= (int) $status;
		
		$propertyResource = $this->getResource('Property');
		$propertyResource->updatePropertyStatus($idProperty, $status);
		
		return $this;
	}
	
	public function updateProperty($idProperty, $params)
	{
		$idProperty = (int) $idProperty;
		
		$propertyResource = $this->getResource('Property');
		$propertyResource->updateProperty($idProperty, $params);
		
		return $this;
	}
	
	public function setPropertyLocationByFastLookupId($idProperty, $idFastLookup)
	{
		$idProperty		= (int) $idProperty;
		$idFastLookup	= (int) $idFastLookup;
		
		$fastLookupResource = $this->getResource('FastLookup');
		$fastLookupRow = $fastLookupResource->getFastLookupById($idFastLookup);
		
		if ($fastLookupRow) {
			$propertyResource = $this->getResource('Property');
			$propertyResource->updatePropertyLocation($idProperty,
													  $fastLookupRow->idCountry,
													  $fastLookupRow->idRegion,
													  $fastLookupRow->idDestination,
													  $fastLookupRow->url);
		}
	}
	
	public function setPropertyUrlName($idProperty, $urlName)
	{
		$idProperty = (int) $idProperty;
		$propertyResource = $this->getResource('Property');

		return $propertyResource->setPropertyUrlName($idProperty, $urlName);
	}
	
	public function setPropertyExpiryDate($idProperty, $expiry)
	{
		$idProperty = (int) $idProperty;
		$expiry = strftime('%Y-%m-%d', strtotime($expiry));
		
		$propertyResource = $this->getResource('Property');
		return $propertyResource->setPropertyExpiryDate($idProperty, $expiry);
	}
	
	public function setAwaitingApproval($idProperty)
	{
		$idProperty = (int) $idProperty;
		
		$propertyResource = $this->getResource('Property');
		$propertyResource->setAwaitingApproval($idProperty);
		
		return $this;
	}
	
	public function initialApproveProperty($idProperty,
										   $idCountry, $countryName,
										   $idRegion, $regionName,
										   $idDestination, $destinationName,
										   $locationUrl, $urlName)
	{
		$idProperty = (int) $idProperty;
		$idCountry  = (int) $idCountry;
		$idRegion   = (int) $idRegion;
		$idDestination = (int) $idDestination;
		
		// add a new entry to the fast lookup table
		$fastLookupResource = $this->getResource('FastLookup');
		$fastLookupResource->addNewLookup($idCountry, $countryName,
										  $idRegion, $regionName,
										  $idDestination, $destinationName,
										  null, // total visible
										  null, // total
										  $locationUrl . '/' .$urlName,
										  $idProperty);
		
		// increase the number of properties in this country, region and destination
		$fastLookupResource->increaseNumPropertiesCountryRegionDestination($idCountry, $idRegion, $idDestination);
		
		$propertyResource = $this->getResource('Property');
		$propertyResource->initialApproveProperty($idProperty);
	}
	
	public function advertiserSendForUpdateApproval($idProperty)
	{
		$idProperty = (int) $idProperty;
		
		$propertyResource = $this->getResource('Property');
		$propertyResource->setAwaitingApproval($idProperty);
	}
	
	//
	// DELETE
	//
}
