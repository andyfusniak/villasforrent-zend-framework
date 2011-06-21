<?php
class Admin_View_Helper_ApprovalLocation extends Zend_View_Helper_Abstract
{
    public function approvalLocation($idProperty, $idCountry, $idRegion, $idDestination)
    {
        $idProperty     = (int) $idProperty;
        $idCountry      = (int) $idCountry;
        $idRegion       = (int) $idRegion;
        $idDestination  = (int) $idDestination;
        
        
        if (($idCountry != 1) && ($idRegion != 1) && ($idDestination != 1)) {
            $fastLookupModel = new Common_Model_FastLookup();
            $fastLookupRow = $fastLookupModel->getFastLookupByCountryRegionDestinationId($idCountry, $idRegion, $idDestination);
            
            $changeLocationUrl = $this->view->url(array('module'     => 'admin',
                                                        'controller' => 'property',
                                                        'action'     => 'set-location',
                                                        'idProperty' => $idProperty,
                                                        'idFastLookup' => $fastLookupRow->idFastLookup,
                                                        'idCountry'  => $idCountry,
                                                        'idRegion'   => $idRegion,
                                                        'idDestination' => $idDestination), null, true);
            
            return $this->view->locationBreadcrumb($fastLookupRow, array ('whitespace' => false)) . ' <a href="' . $changeLocationUrl . '">[change]</a>';
        } else {
            $setLocationUrl = $this->view->url(array('module'     => 'admin',
                                                     'controller' => 'property',
                                                     'action'     => 'set-location',
                                                     'idProperty' => $idProperty), null, true);
            
            return '<a href="' . $setLocationUrl . '">[set location]</a>';
        }
    }
}