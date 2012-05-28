<?php
class Admin_View_Helper_ApproveProperty extends Zend_View_Helper_Abstract
{
    public function approveProperty($propertyRow)
    {
        $idProperty = (int)    $propertyRow->idProperty;
        $idLocation = (int)    $propertyRow->idLocation;
        $expiry     = (string) $propertyRow->expiry;

        // these are done in a specic order
        // 1. property unique url
        // 2. idLocation   node in the nested-set
        // 3. expiry date is set in the future

        // 1. check the url is set
        if (substr($propertyRow->urlName, 0, 1) == '_') {
            return 'Set Url';
        }

        // 2. check the location is set
        if ($idLocation == null)
            return 'Set Location';

        // 3. check the expiry is set in the future
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
