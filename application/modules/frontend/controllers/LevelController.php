<?php
class LevelController extends Zend_Controller_Action
{
    protected $_fastLookupModel;
    protected $_locationModel;
    
    protected $_featuredConfig;
    
    public function init()
    {
        $this->_locationModel = new Common_Model_Location();
        
        // get the destination from the configuration
		$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
		$this->_featuredConfig = $bootstrap['vfr']['featured'];
    }
    
	public function locationAction()
	{
		var_dump($this->getRequest()->getParams());
		$url = $this->getRequest()->getParam('1');
		$locationRow = $this->_locationModel->lookup($url);
		var_dump($locationRow);
		die();
		
		if ($locationRow === null) {
			$this->getResponse()->setHttpResponseCode(404);
			die("missing page");
			return;
		}
		
		if ($locationRow->idProperty === null) {
			
		}
		
		$parts = explode('/', $url);
		
		var_dump($parts);
		
		var_dump($locationRow);
		die();
	}
	
    public function countryAction()
    {
        $uri = $this->getRequest()->getParam('uri');
		
		//var_dump($uri);
        $locationRow = $this->_locationModel->lookup($uri);
		
        if (!$locationRow) {
            $this->getResponse()->setHttpResponseCode(404);
            return;
        }
        
        // get the list of regions within this country
        $locationRowset = $this->_locationModel->getAllLocationsIn(
			$locationRow->idLocation
		);
        
        // get the summary for this level
        $this->_helper->levelSummary($locationRow->url);
		
		// get the featured properties
        $this->_helper->featuredProperty($locationRow->idLocation);
		
        // pass results to the view
        $this->view->assign(
			array (
				'locationRow'	  => $locationRow,
				'locationRowset'  => $locationRowset
			)
		);
    }
    
    public function regionAction()
    {
        $uri = $this->getRequest()->getParam('uri');
        $locationRow = $this->_locationModel->lookup($uri);
        
        if (!$locationRow) {
            $this->getResponse()->setHttpResponseCode(404);
            return;
        }

        // get the list of destinations within this region
		$idLocation = $locationRow->idLocation;
        $locationRowset = $this->_locationModel->getAllLocationsIn($idLocation);
		
        // get the summary for this level
        $this->_helper->levelSummary($locationRow->url);

        // get the featured properties
        $this->_helper->featuredProperty($locationRow->idLocation);
		
        // pass results to the view
        $this->view->assign(
			array (
				'locationRow'	  => $locationRow,
				'locationRowset'  => $locationRowset
			)
		);
    }

    public function destinationAction()
    {
        $uri = $this->getRequest()->getParam('uri');
        $locationRow = $this->_locationModel->lookup($uri);

        if (!$locationRow) {
            $this->getResponse()->setHttpResponseCode(404);
            return;
        }

		// get the summary for this level
        $this->_helper->levelSummary($locationRow->url);
		
        // get the featured properties
        $this->_helper->featuredProperty($locationRow->idLocation);
		
        // pass results to the view
        $this->view->assign(
			array (
				'locationRow' => $locationRow
			)
		); 
    }
    
    public function listDestinationsAction()
    {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $propertyTypesTbl = new Common_Model_DbTable_PropertyTypes();
        $property_types = $propertyTypesTbl->getPropertyTypesInUseLookup();
    
        // create a fastlookup engine instance
        $lookupEngine = new Vfr_Fastlookups();
        $lookupEngine->loadFastTable();
    }
}
