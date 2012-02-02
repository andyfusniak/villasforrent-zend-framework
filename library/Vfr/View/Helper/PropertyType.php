<?php
class Vfr_View_Helper_PropertyType extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';

    private $_propertyTypeHash = null;
    
    public function propertyType($idPropertyType)
    {   
        if ($this->_propertyTypeHash == null) {
            $propertyModel      = new Common_Model_Property();
            $this->_propertyTypeHash = $propertyModel->getAllPropertyTypesArray();
        }
        
        return $this->view->escape($this->_propertyTypeHash[$idPropertyType]);
    }
}
