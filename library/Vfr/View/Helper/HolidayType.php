<?php
class Vfr_View_Helper_HolidayType extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';

    private $_holidayTypes = null;
    
    public function holidayType($idHolidayType)
    {
        if ($idHolidayType == 0)
            return '';
        
        if ($this->_holidayTypes == null) {
            $propertyModel = new Common_Model_Property();
            $this->_holidayTypes = $propertyModel->getAllHolidayTypesArray();
        }
        
        return $this->view->escape($this->_holidayTypes[$idHolidayType]);
    }
}
