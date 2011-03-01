<?php
class LevelController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
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
