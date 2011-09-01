<?php
class DisplayFullPropertyController extends Zend_Controller_Action
{
    public function init() {}

    public function indexAction()
    {
		$locationModel	= new Common_Model_Location();
		$propertyModel 	= new Common_Model_Property();
		$calendarModel  = new Common_Model_Calendar();
	
		$uri = $this->getRequest()->getParam('uri');
		
		//var_dump($uri);
		$locationRow = $locationModel->lookup($uri);
		if (null === $locationRow) {
			var_dump('not found');
			exit;
		}
		
		//var_dump($locationRow);
		
		// get the main property details
		$propertyRow = $propertyModel->getPropertyById($locationRow->idProperty);

		// get the content for this property
		$propertyContent = $propertyModel->getPropertyContentArrayById($locationRow->idProperty);
		
		// fetch the rates and availability
		$idCalendar = $propertyModel->getCalendarIdByPropertyId($locationRow->idProperty);
		//$availabilityRowset = $calendarModel->getAvailabilityByCalendarId($idCalendar);
		$rateRowset		= $calendarModel->getRatesByCalendarId($idCalendar);
		//var_dump($availabilityRowset);
		//var_dump($rateRowset);
		
		
		
		// fetch the photos for this property
		$photoRowset = $propertyModel->getAllPhotosByPropertyId($locationRow->idProperty);
		var_dump($photoRowset);
		
		$allFacilities  = $propertyModel->getAllFacilities();
		$facilityRowset = $propertyModel->getAllFacilities($locationRow->idProperty);
		//var_dump($facilityRowset);
		
		$this->view->assign(
			array (
				'locationRow'		=> $locationRow,
				'propertyRow'		=> $propertyRow,
				'propertyContent'	=> $propertyContent,
				'photoRowset'		=> $photoRowset,
				'rateRowset'		=> $rateRowset
			)
		);
	}
	
}

