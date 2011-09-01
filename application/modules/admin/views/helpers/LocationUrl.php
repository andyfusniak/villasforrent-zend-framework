<?php
class Admin_View_Helper_LocationUrl extends Zend_View_Helper_Abstract
{
    public function locationUrl($propertyRow)
    //$idProperty, $urlName)
    {
        $idProperty     = (int) $propertyRow->idProperty;
        $setLocationUrl = $this->view->url(array('module'    => 'admin',
                                                 'controller' => 'property',
                                                 'action'     => 'set-url-name',
                                                 'idProperty' => $idProperty,
                                                 'urlName'    => $propertyRow->urlName), null, true);
        
        if (substr($propertyRow->urlName, 0, 1) == '_') { 
            return '<a href="' . $setLocationUrl . '">[set url]</a>';
        }
        
        
        return $this->view->escape($propertyRow->urlName) . '<p><a href="' . $setLocationUrl . '">[edit]</a>';
    }
}