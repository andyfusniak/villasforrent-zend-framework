<?php
class Frontend_Helper_LevelSummary extends Zend_Controller_Action_Helper_Abstract
{
    protected $_propertyModel = null;
    protected $_fastLookupModel = null;
    
    public function init()
    {
        if (!$this->_propertyModel)
            $this->_propertyModel = new Common_Model_Property();
            
        if (!$this->_fastLookupModel)
            $this->_fastLookupModel = new Common_Model_FastLookup();
    }
    
    public function getPropertySummaries($idCountry, $idRegion, $idDestination)
    {
        $propertyRowset = $this->_propertyModel->getPropertiesByCountryRegionDestination($idCountry, $idRegion, $idDestination);
     
        if ($propertyRowset->count()) {
            
            $propertyContent = $this->_propertyModel->getPropertyContentArrayByPropertyList($propertyRowset,
                                                                                     Common_Resource_PropertyContent::VERSION_MAIN,
                                                                                     'EN',
                                                                                     array (
                                                                                        Common_Resource_PropertyContent::FIELD_SUMMARY,
                                                                                        Common_Resource_PropertyContent::FIELD_HEADLINE_1));
            foreach ($propertyRowset as $propertyRow) {
                $partials[] = $this->getActionController()->view->partial('partials/property-summary.phtml', array(
                                                            'fastLookupRow'	   => $this->_fastLookupModel->lookup($propertyRow->locationUrl . '/' . $propertyRow->urlName),
                                                            'photoRow'		   => $this->_propertyModel->getPrimaryPhotoByPropertyId($propertyRow->idProperty),
                                                            'propertyRow'      => $propertyRow,
                                                            'propertyContent'  => $propertyContent[$propertyRow->idProperty]));
            }
            
            $this->getActionController()->view->partials       = $partials;
            $this->getActionController()->view->showProperties = true;
        } else {
            $this->getActionController()->view->showProperties = false;
        }
    }
    
    public function direct($idCountry=null, $idRegion=null, $idDestination=null)
    {
        $this->getPropertySummaries($idCountry, $idRegion, $idDestination);
    }
}