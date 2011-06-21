<?php
class LevelController extends Zend_Controller_Action
{
    protected $_fastLookupModel;
    protected $_locationModel;
    
    protected $_featuredConfig;
    
    public function init()
    {
        $this->_fastLookupModel = new Common_Model_FastLookup();
        $this->_locationModel   = new Common_Model_Location();
        
        // get the destination from the configuration
		$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
		$this->_featuredConfig = $bootstrap['vfr']['featured'];
    }
    
    public function countryAction()
    {
        $lookupUrl = $this->getRequest()->getParam('country');
        $fastLookupRow  = $this->_fastLookupModel->lookup($lookupUrl);
        
        if (!$fastLookupRow) {
            $this->getResponse()->setHttpResponseCode(404);
            return;
        }
        
        // get the list of regions within this country
        $fastLookupRowset = $this->_locationModel->getFastAllRegions($fastLookupRow->idCountry);
        
        
        // get the summary for this level
        $this->_helper->levelSummary($fastLookupRow->idCountry, null, null);
        
        // get the country level featured properties
        $this->_helper->featuredProperty(Common_Resource_Property::FEATURE_MASK_COUNTRY, $this->_featuredConfig['limit_per_page'],
                                         $fastLookupRow->idCountry);
        
        //var_dump($fastLookupRow);
        
        // pass results to the view
        $this->view->assign( array (
            'countryName'      => $fastLookupRow->countryName,
            'fastLookupRow'    => $fastLookupRow,
            'fastLookupRowset' => $fastLookupRowset
        ));
    }
    
    public function regionAction()
    {
        $lookupUrl = $this->getRequest()->getParam('country') . '/' . $this->getRequest()->getParam('region');
        $fastLookupRow  = $this->_fastLookupModel->lookup($lookupUrl);
        
        if (!$fastLookupRow) {
            $this->getResponse()->setHttpResponseCode(404);
            return;
        }

        // get the list of destinations within this region
        $fastLookupRowset = $this->_locationModel->getFastAllDestinations($fastLookupRow->idRegion);

        // get the level summary
        $this->_helper->levelSummary($fastLookupRow->idCountry, $fastLookupRow->idRegion, null);

        // get the region level featured properties
        $this->_helper->featuredProperty(Common_Resource_Property::FEATURE_MASK_REGION, $this->_featuredConfig['limit_per_page'],
                                         $fastLookupRow->idCountry,
                                         $fastLookupRow->idRegion);

        // pass results to the view
        $this->view->assign( array (
            'fastLookupRow'     => $fastLookupRow,
            'fastLookupRowset'  => $fastLookupRowset
        ));
    }

    public function destinationAction()
    {
        $lookupUrl = $this->getRequest()->getParam('country') . '/' . $this->getRequest()->getParam('region') . '/' . $this->getRequest()->getParam('destination');
        $fastLookupRow  = $this->_fastLookupModel->lookup($lookupUrl);

        if (!$fastLookupRow) {
            $this->getResponse()->setHttpResponseCode(404);
            return;
        }

        $this->_helper->levelSummary($fastLookupRow->idCountry, $fastLookupRow->idRegion, $fastLookupRow->idDestination);
         
        //var_dump($propertyRowset);
        //$fastLookupRowset = $this->_locationModel->getFastAllDestinations($fastLookupRow->idRegion);

         // get the region level featured properties
        $this->_helper->featuredProperty(Common_Resource_Property::FEATURE_MASK_DESTINATION, $this->_featuredConfig['limit_per_page'],
                                         $fastLookupRow->idCountry,
                                         $fastLookupRow->idRegion,
                                         $fastLookupRow->idDestination);
        
        // pass results to the view
        $this->view->assign( array (
            'fastLookupRow'     => $fastLookupRow,
            'destinationName'   => $fastLookupRow->destinationName
        )); 
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
