<?php
class Admin_View_Helper_LocationUrl extends Zend_View_Helper_Abstract
{
    public function locationUrl($idProperty, $urlName)
    {
        $idProperty     = (int) $idProperty;
        
        $setLocationUrl = $this->view->url(array('module'    => 'admin',
                                                 'controller' => 'property',
                                                 'action'     => 'set-url-name',
                                                 'idProperty' => $idProperty,
                                                 'urlName'    => $urlName), null, true);
        
        if (substr($urlName, 0, 1) == '_') { 
            return '<a href="' . $setLocationUrl . '">[set url]</a>';
        }
        
        
        return $this->view->escape($urlName) . '<p><a href="' . $setLocationUrl . '">[edit]</a>';
    }
}