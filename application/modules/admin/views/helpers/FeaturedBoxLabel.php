<?php
class Admin_View_Helper_FeaturedBoxLabel extends Zend_View_Helper_Abstract
{
    public function featuredBoxLabel($id)
    {
        switch ($id) {
            case 1:
                return 'Box A';
            break;
        
            case 2:
                return 'Box B';
            break;
        
            case 3:
                return 'Box C';
            break;
        
            default:
                return $id;
        }
    }
}