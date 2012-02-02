<?php
class Common_Model_Property extends Vfr_Model_Abstract
{
	const SESSION_NS_ADMIN_PROPERTY = 'AdminPropertyNS';
	const SESSION_NS_ADMIN_PROPERTY_LIST_BY_PROPERTY = 'AdminPropertyNSPropertyListByProperty';
	
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
		//$propertyFacilityResource	= $this->getResource('PropertyFacility');
        $calendarResource 			= $this->getResource('Calendar');
		//$facilitiesResource			= $this->getResource('Facility');

        $idProperty = (int) $propertyResource->createProperty($options);
		
		// create the property facility switches
		//$facilityRowset	= $facilitiesResource->getAllFacilities(true);
		//foreach ($facilityRowset as $facilityRow) {
		//	$propertyFacilityResource->createPropertyFacility($idProperty, $facilityRow->facilityCode);
		//}
		
		$calendarResource->createCalendar($idProperty);
		
        $params = $options['params'];

        $versionList = array (
            Common_Resource_PropertyContent::VERSION_MAIN,
            Common_Resource_PropertyContent::VERSION_UPDATE
        );
        
        $updateList = Common_Resource_PropertyContentField::$propertiesContentFieldsMap;

        // unset these as they will be added manually outside the inner loop
        unset($updateList[Common_Resource_PropertyContent::FIELD_COUNTRY]);
        unset($updateList[Common_Resource_PropertyContent::FIELD_REGION]);
        unset($updateList[Common_Resource_PropertyContent::FIELD_LOCATION]);

        //var_dump($updateList);
        //die();

        foreach ($versionList as $version) {
            foreach ($updateList as $key=>$value) {
                $propertyContentResource->createPropertyContent(
                    $idProperty,
                    $version,
                    'EN',
					$key,
                    ''
                );
            }

            $propertyContentResource->createPropertyContent(
                $idProperty,
                $version,
				'EN',
				Common_Resource_PropertyContent::FIELD_COUNTRY,
                $params['country']
            );

            $propertyContentResource->createPropertyContent(
                $idProperty,
                $version,
				'EN',
				Common_Resource_PropertyContent::FIELD_REGION,
                $params['region']
            );

            $propertyContentResource->createPropertyContent(
                $idProperty,
                $version,
				'EN',
				Common_Resource_PropertyContent::FIELD_LOCATION,
                $params['destination']
            );
        }
     
		return $idProperty;
	}

	//
	// READ
	//

    public function getAllProperties()
    {
        $propertyResource = $this->getResource('Property');
        
        return $propertyResource->getAllProperties();
    }

    public function getAllPropertyIds()
    {
        $propertyResource = $this->getResource('Property');
        
        return $propertyResource->getAllPropertyIds();
    }
	
	public function getAllPaginator($page=1, $interval=30, $order='idProperty', $direction = 'ASC')
	{
		$session = new Zend_Session_Namespace(self::SESSION_NS_ADMIN_PROPERTY);
		
		// defaults
		if ($page !== null)
			$session->page = $page;
		
		if ($interval !== null)
			$session->interval = $interval;
		
		if ($order !== null)
			$session->order = $order;
			
		if ($direction !== null)
			$session->direction = $direction;
		
		return $this->getResource('Property')->getAllPaginator(
			isset($session->page)      ? $session->page : 1,
			isset($session->interval)  ? $session->interval : 30,
			isset($session->order)     ? $session->order : 'idProperty',
			isset($session->direction) ? $session->direction : 'ASC'
		);
	}
	
	public function getAllFeaturedPropertiesPaginator($page=1, $interval=30, $order='F.idProperty, L.name', $direction='ASC')
	{
		$session = new Zend_Session_Namespace(Common_Model_Property::SESSION_NS_ADMIN_PROPERTY_LIST_BY_PROPERTY);
		
		// defaults
		if ($page !== null)
			$session->page = $page;
			
		if ($interval !== null)
			$session->interval = $interval;
		
		if ($order !== null)
			$session->order = $order;
			
		if ($direction !== null)
			$session->direction = $direction;
		
		return $this->getResource('FeaturedProperty')->getAllFeaturedPropertiesPaginator(
			isset($session->page)      ? $session->page : 1,
			isset($session->interval)  ? $session->interval : 30,
			isset($session->order)     ? $session->order : 'F.idProperty, L.name',
			isset($session->direction) ? $session->direction : 'ASC'
		);
	}

	public function getProperties($page, $interval=30, $order=null, $direction='ASC')
	{
		$page 		= (int) $page;
		$interval 	= (int) $interval;
		
		$propertyResource = $this->getResource('Property');
		
		return $propertyResource->getProperties($page, $interval, $order, $direction);
	}
	
	public function getPropertiesByGeoUri($uri, $page=null, $itemCountPerPage=10, $order=null, $direction='ASC')
	{	
		$page = (int) $page;
		$itemCountPerPage	= (int) $itemCountPerPage;
		
		$propertyResource = $this->getResource('Property');
		
		return $propertyResource->getPropertiesByGeoUri($uri, $page, $itemCountPerPage, $order, $direction);
	}

	public function getPropertyByUrl($url)
	{
		$propertyResource = $this->getResource('Property');
		return $propertyResource->getPropertyByUrl($url);
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
	
	public function getPropertyContentByPropertyId($idProperty,
												   $version=Common_Resource_PropertyContent::VERSION_MAIN,
												   $lang='EN', $idPropertyContentFieldList=null)
	{
		$idProperty = (int) $idProperty;
		
		$propertyContentResource = $this->getResource('PropertyContent');
		return $propertyContentResource->getPropertyContentByPropertyId($idProperty, $version, $lang, $idPropertyContentFieldList);
	}
	
	public function getPropertyContentByPropertyList($properties,
													 $version=Common_Resource_PropertyContent::VERSION_MAIN,
													 $lang='EN', $idPropertyContentFieldList=null)
	{
		if ((!$properties instanceof Common_Resource_Property_Rowset)
			&& (!$properties instanceof Common_Resource_FeaturedProperty_Rowset)
			&& (! is_array($properties))) {
				throw new Exception('Invalid propertyRowset type passed must be of type Common_Resource_Property_Rowset, Common_Resource_FeaturedProperty_Rowset or array, instead got ' . gettype($properties));
		}
		
		$propertyContentResource = $this->getResource('PropertyContent');
		return $propertyContentResource->getPropertyContentByPropertyList($properties, $version, $lang, $idPropertyContentFieldList);
	}
	
	public function getPropertyContentArrayByPropertyList($properties,
														  $version=Common_Resource_PropertyContent::VERSION_MAIN,
														  $lang='EN', $idPropertyContentFieldList=null)
	{
		if ((!$properties instanceof Common_Resource_Property_Rowset)
			&& (!$properties instanceof Common_Resource_FeaturedProperty_Rowset)
			&& (! is_array($properties))) {
				throw new Exception('Invalid propertyRowset type passed must be of type Common_Resource_Property_Rowset, Common_Resource_FeaturedProperty_Rowset or array, instead got ' . gettype($properties));
		}
		
		$propertyContentResource = $this->getResource('PropertyContent');
		$propertyContentRowset = $propertyContentResource->getPropertyContentByPropertyList(
			$properties,
			$version,
			$lang,
			$idPropertyContentFieldList
		);
		
		// convert to an associative array
		$fieldsArray = array();
		foreach ($propertyContentRowset as $propertyContentRow) {
			$idProperty		 		= $propertyContentRow->idProperty;
			$idPropertyContentField	= $propertyContentRow->idPropertyContentField;
			
			$fieldsArray[$idProperty][$idPropertyContentField] = $propertyContentRow->content;
		}
		
		return $fieldsArray;
	}

	public function getPropertyContentArrayById($idProperty,
												$version=Common_Resource_PropertyContent::VERSION_MAIN, $lang='EN', $idPropertyContentFieldList=null)
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
	
	public function getPrimaryPhotosByPropertyList($idPropertyList)
	{
		$idPropertyList = (array) $idPropertyList;
		
		if (empty($idPropertyList))
			return null;
		
		$photoResource = $this->getResource('Photo');
		return $photoResource->getPrimaryPhotosByPropertyList($idPropertyList);
	}
	
	public function getPrimaryPhotosByPropertyLookup($idPropertyList)
	{
		$photoRowset = $this->getPrimaryPhotosByPropertyList($idPropertyList);
		
		$lookup = array ();
		if ($photoRowset) {
			foreach ($photoRowset as $photoRow) {
				$idProperty = $photoRow->idProperty;
				$lookup[$idProperty] = $photoRow;
			}	
		}
		
		return $lookup;
	}
	
	public function getPrimaryPhotoByPropertyId($idProperty)
	{
		$idProperty = (int) $idProperty;
		
		$photoResource = $this->getResource('Photo');
		return $photoResource->getPrimaryPhotoByPropertyId($idProperty);
	}
	
	public function getPrimaryPhotosByPropertyIds($idPropertyList)
	{
		$idPropertyList = (array) $idPropertyList;
		
		$photoResource = $this->getResource('Photo');
		return $photoResource->getPrimaryPhotosByPropertyIds($idPropertyList);
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
	
	public function getFeaturedProperties($idLocation, $limit=null)
	{
		if ($limit)
			$limit = (int) $limit;
			
		$propertyResource = $this->getResource('FeaturedProperty');
		
		return $propertyResource->getFeaturedProperties($idLocation, $limit);
	}
	
	//
	// UPDATE
	//
	
	public function updateContent($idProperty, $updateSet, $data)
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
			'accessDesc'		=> (isset($data['accessDesc']) && (mb_strlen($data['accessDesc']) > 0))
                                        ? $data['accessDesc'] : '',	// optional field
			'outsideDesc'		=> $data['outsideDesc'],
			'golfDesc'			=> (isset($data['golfDesc']) && (mb_strlen($data['golfDesc']) > 0))
                                        ? $data['golfDesc'] : '', 		// optional field
			'skiingDesc'		=> (isset($data['skiingDesc']) && (mb_strlen($data['skiingDesc']) > 0))
                                        ? $data['skiingDesc'] : '',
			'specialDesc'		=> (isset($data['specialDesc']) && (mb_strlen($data['specialDesc']) > 0))
                                        ? $data['specialDesc'] : '',	// optional field
			'beachDesc'			=> $data['beachDesc'],
			'travelDesc'		=> $data['travelDesc'],
			'bookingDesc'		=> $data['bookingDesc'],
			'testimonialsDesc'	=> $data['testimonialsDesc'],
			'changeoverDesc'	=> $data['changeoverDesc'],
			'contactDesc'		=> $data['contactDesc']
		);
		
		$propertyResource			= $this->getResource('Property');
		$propertyContentResource	= $this->getResource('PropertyContent');
		
		// we need all the updates to happen or none
		$db = Zend_Db_Table::getDefaultAdapter();
		$db->beginTransaction();
		
		try {
			foreach ($updateSet as $version) {
				$version = (int) $version;
				$propertyContentResource->updatingPropertyContentDataset($idProperty, $version, $params);
				$propertyResource->_updateChecksum($idProperty, $version);
			}
			
			$db->commit();
		} catch (Exception $e) {
			$db->rollBack();
			
			throw $e;
		}
		
		// fluent interface
		return $this;
	}
	
	public function copyUpdateToMaster($idProperty)
	{
		$idProperty = (int) $idProperty;
		
		$propertyContentResource	= $this->getResource('PropertyContent');
		$propertyResource			= $this->getResource('Property');
		
		$propertyContentResource->copyUpdateToMaster($idProperty);
		$propertyResource->updateMasterChecksum($idProperty);
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
	
	public function updatePropertyLocationIdAndUrl($idProperty, $idLocation)
	{
		$idProperty = (int) $idProperty;
		$idLocation	= (int) $idLocation;
		
		// check to see that this location exists
		$locationResource = $this->getResource('Location');
		$locationRow = $locationResource->getLocationByPk($idLocation);
		
		if ($locationRow) {
			$propertyResource = $this->getResource('Property');
			$propertyResource->updatePropertyLocationIdAndUrl(
				$idProperty,
				$locationRow->idLocation,
				$locationRow->url
			);
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
	
	public function initialApproveProperty($propertyRow)
	{
		$idProperty = (int) $propertyRow->idProperty;
		
		$propertyResource = $this->getResource('Property');
		$propertyResource->initialApproveProperty($idProperty);
	}
	
	public function advertiserSendForUpdateApproval($idProperty)
	{
		$idProperty = (int) $idProperty;
		
		$propertyResource = $this->getResource('Property');
		$propertyResource->setAwaitingApproval($idProperty);
	}

    public function repairPropertyBatchChecksums()
    {
        $propertyContentResource = $this->getResource('PropertyContent');
        $propertyContentResource->repairPropertyBatchChecksums();
    }
	
    public function updateMasterCheckSum($idProperty)
    {
        $idProperty = (int) $idProperty;
		
        $propertyResource = $this->getResource('Property');
        $propertyResource->updateMasterChecksum($idProperty);
    }

    public function updateUpdateCheckSum($idProperty)
    {
        $idProperty = (int) $idProperty;
        
		$propertyResource = $this->getResource('Property');
        $propertyResource->updateUpdateChecksum($idProperty);
    }

	//
	// DELETE
	//
}
