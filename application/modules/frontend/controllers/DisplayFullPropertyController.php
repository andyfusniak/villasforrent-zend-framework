<?php
class DisplayFullPropertyController extends Zend_Controller_Action
{
    public function init() {}

    public function indexAction()
    {
		$locationModel  = new Common_Model_Location();
		$propertyModel 	= new Common_Model_Property();
		$calendarModel  = new Common_Model_Calendar();
	
		$uri 		= $this->getRequest()->getParam('uri');
		$idProperty = $this->getRequest()->getParam('idProperty');

		// get the main property details
		$propertyRow = $propertyModel->getPropertyById($idProperty);
		
		if (null === $propertyRow) {
			var_dump('not found');
			exit;
		}
		
		// get the content for this property
		$propertyContent = $propertyModel->getPropertyContentArrayById($propertyRow->idProperty);
		
		// fetch the rates and availability
		$idCalendar = $propertyModel->getCalendarIdByPropertyId($propertyRow->idProperty);
		$calendarRow = $propertyModel->getCalendarById($idCalendar);
		
		//$availabilityRowset = $calendarModel->getAvailabilityByCalendarId($idCalendar);
		$rateRowset	= $calendarModel->getRatesByCalendarId($idCalendar);
		
		
		// fetch the photos for this property
		$photoRowset = $propertyModel->getAllPhotosByPropertyId($propertyRow->idProperty);
		//var_dump($photoRowset);
		
		$allFacilities  = $propertyModel->getAllFacilities();
		$facilityRowset = $propertyModel->getAllFacilities($propertyRow->idProperty);
		//var_dump($facilityRowset);
		
		$locationRow = $locationModel->lookup($propertyRow->locationUrl);
		
		//var_dump($propertyRow);
		//var_dump($locationRow);
		//die();
		
        // enable jQuery Core Library
        ZendX_JQuery::enableView($this->view);
        $jquery = $this->view->jQuery();
        $jquery->enable();
        
		$this->view->assign(
			array (
				'propertyRow'		=> $propertyRow,
				'calendarRow'		=> $calendarRow,
				'locationRow'		=> $locationRow,
				'propertyContent'	=> $propertyContent,
				'photoRowset'		=> $photoRowset,
				'rateRowset'		=> $rateRowset
			)
		);
	}
}

