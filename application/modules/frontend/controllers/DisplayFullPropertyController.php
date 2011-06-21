<?php
class DisplayFullPropertyController extends Zend_Controller_Action
{
    public function init() {}

    public function indexAction()
    {
		$fastLookupModel = new Common_Model_FastLookup();
		$propertyModel 	 = new Common_Model_Property();
		$calendarModel   = new Common_Model_Calendar();
	
		$url = $this->getRequest()->getParam('country') . '/' .
			   $this->getRequest()->getParam('region') . '/' .
			   $this->getRequest()->getParam('destination') . '/' .
			   $this->getRequest()->getParam('propertyurl');
		//var_dump($url);
		$fastLookupRow = $fastLookupModel->lookup($url);
		if (null === $fastLookupRow) {
			var_dump('not found');
			exit;
		}
		
		// get the main property details
		$propertyRow = $propertyModel->getPropertyById($fastLookupRow->idProperty);

		// get the content for this property
		$propertyContent = $propertyModel->getPropertyContentArrayById($fastLookupRow->idProperty);
		
		
		
		// fetch the rates and availability
		$idCalendar = $propertyModel->getCalendarIdByPropertyId($fastLookupRow->idProperty);
		//$availabilityRowset = $calendarModel->getAvailabilityByCalendarId($idCalendar);
		$rateRowset		= $calendarModel->getRatesByCalendarId($idCalendar);
		//var_dump($availabilityRowset);
		//var_dump($rateRowset);
		
		
		// fetch the photos for this property
		$photoRowset = $propertyModel->getAllPhotosByPropertyId($fastLookupRow->idProperty);
		//var_dump($photoRowset);
		
		$allFacilities = $propertyModel->getAllFacilities();
		$facilityRowset = $propertyModel->getAllFacilities($fastLookupRow->idProperty);
		//var_dump($facilityRowset);
		
		$this->view->assign( array (
			'fastLookupRow'		=> $fastLookupRow,
			'propertyRow'		=> $propertyRow,
			'propertyContent'	=> $propertyContent,
			'photoRowset'		=> $photoRowset,
			'rateRowset'		=> $rateRowset
		));
	}
}

