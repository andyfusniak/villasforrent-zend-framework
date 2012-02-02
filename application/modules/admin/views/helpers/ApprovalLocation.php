<?php
class Admin_View_Helper_ApprovalLocation
    extends Zend_View_Helper_Abstract
{
    public function approvalLocation($propertyRow)
    {
        $idProperty     = (int) $propertyRow->idProperty;
        $idLocation     = (int) $propertyRow->idLocation;
        
        if ($idLocation != null) {
            $locationModel = new Common_Model_Location();
            $locationRow = $locationModel->getLocationByPk($idLocation);
            
            $changeLocationUrl = $this->view->url(array('module'       => 'admin',
                                                        'controller'   => 'property',
                                                        'action'       => 'set-location',
                                                        'idProperty'   => $idProperty,
                                                        'idLocation'   => $locationRow->idLocation), null, true);
            
            return $this->view->locationBreadcrumb(
                $locationRow->idLocation,
                array (
                    'whitespace' => false,
                    'makeLinks'  => false
                )
            ) . ' <a href="' . $changeLocationUrl . '">[change]</a>';
        } else {
            $setLocationUrl = $this->view->url(array('module'     => 'admin',
                                                     'controller' => 'property',
                                                     'action'     => 'set-location',
                                                     'idProperty' => $idProperty), null, true);
            
            return '<a href="' . $setLocationUrl . '">[set location]</a>';
        }
    }
}