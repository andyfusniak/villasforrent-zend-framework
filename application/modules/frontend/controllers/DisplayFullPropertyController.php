<?php
class DisplayFullPropertyController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		$fastLookupModel = new Common_Model_FastLookup();
		$propertyModel = new Common_Model_Property();
	
		$url = $this->_request->getParam('country') . '/' .
			   $this->_request->getParam('region') . '/' .
			   $this->_request->getParam('destination') . '/' .
			   $this->_request->getParam('propertyurl');

		$params = $fastLookupModel->lookup($url);
		if (null === $params) {
			var_dump('not found');
			exit;
		}

		$propertyTypes = $propertyModel->getPropertyTypes();
		$propertyAvailability = $propertyModel->getAvailabilityByPropertyId($params->idProperty);
		$propertyPhotos = 	
		$allFacilities = $propertyModel->getAllFacilities();
		$propertyFacilities = $propertyModel->getAllFacilities($params->idProperty);
	
		//var_dump($property);
		$this->view->idProperty = $params->idProperty;
		$this->view->p = $propertyModel->getPropertyById($params->idProperty);
		$this->view->pc = $propertyModel->getPropertyContentArrayById($params->idProperty);
		$this->view->photos = $propertyModel->getAllPhotosByPropertyId($params->idProperty);
		$this->view->rates = $propertyModel->getRatesByPropertyId($params->idProperty);
		/*	
		var_dump($allFacilities->toArray());
		exit;
	
		var_dump($allFacilities);
		echo "<hr />";
		
		var_dump($property);
		echo "<hr />";
		var_dump($propertyTypes);
		echo "<hr />";	
		var_dump($propertyRates);
		echo "<hr />";
		var_dump($propertyAvailability);
		echo "<hr />";
		var_dump($propertyPhotos);
		*/
    }
}

