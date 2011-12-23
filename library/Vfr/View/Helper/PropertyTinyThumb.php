<?php
class Vfr_View_Helper_PropertyTinyThumb extends Zend_View_Helper_Abstract
{
    public function propertyTinyThumb($idProperty, $photoRowsetLookup=null)
    {
        //var_dump($idProperty);
        //var_dump($photoRowsetLookup);
        
        if ($photoRowsetLookup) {
            $photorow = null;
            if (isset($photoRowsetLookup[$idProperty]))
                $photorow = $photoRowsetLookup[$idProperty];
        } else {
           $idProperty = (int) $idProperty;
           $propertyModel = new Common_Model_Property();
           $photorow = $propertyModel->getPrimaryPhotoByPropertyId($idProperty);    
        }
        
        if (! $photorow) {
            return 'no images';
        }
        
        return $this->view->tinyThumb($idProperty, $photorow->idPhoto, $photorow->widthPixels, $photorow->heightPixels, $photorow->caption);
    }
}
