<?php
class Admin_View_Helper_ApproveProperty extends Zend_View_Helper_Abstract
{
    public function approveProperty($idProperty, $idCountry, $idRegion, $idDestination, $urlName, $expiry)
    {
        $idProperty     = (int) $idProperty;
        $idCountry      = (int) $idCountry;
        $idRegion       = (int) $idRegion;
        $idDestination  = (int) $idDestination;
      
        // check the location is set  
        if (($idCountry == 1) || ($idRegion == 1) || ($idDestination == 1)) {
            return 'Set Location';
        }
        
        // check the url is set
        if (substr($urlName, 0, 1) == '_') {
            return 'Set Url';
        }
        
        // check the expiry is set in the future
        if (($expiry == null) || ($expiry == '0000-00-00')) {
            return 'Set Expiry';
        }
        
        $approveLink = $this->view->url(array(
            'module'        => 'admin',
            'controller'    => 'property',
            'action'        => 'approve-property',
            'idProperty'    => $idProperty
        ));
        
        return '<a href="' . $approveLink . '">[approve]</a>';
    }
}