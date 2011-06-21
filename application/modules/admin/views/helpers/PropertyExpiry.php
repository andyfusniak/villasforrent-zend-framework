<?php
class Admin_View_Helper_PropertyExpiry extends Zend_View_Helper_Abstract
{
    public function propertyExpiry($idProperty, $expiry)
    {
        $idProperty = (int) $idProperty;
        
        $setPropertyExpiry = $this->view->url(array ('module'       => 'admin',
                                                     'controller'   => 'property',
                                                     'action'       => 'set-expiry',
                                                     'idProperty'   => $idProperty,
                                                     'expiry'       => $expiry), null, true);
        //var_dump($expiry);
        
        if (($expiry == null) || ($expiry == '0000-00-00')) {
            
            return '<a href="' . $setPropertyExpiry . '">[set expiry]</a>';
        }
        
        
        return $this->view->prettyDate($expiry, Vfr_View_Helper_PrettyDate::STYLE_DD_MMM_YY) . '<p><a href="' . $setPropertyExpiry . '">[edit]</a></p>';
    }
}