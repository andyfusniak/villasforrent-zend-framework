<?php
class Vfr_View_Helper_AvailabilityPicker extends Zend_View_Helper_Abstract
{
    public function availabilityPicker($value, $field)
    {
        if (!$value)
            return '';
        
        $pattern = '/' . Vfr_Form_Element_AvailabilityRangePicker::DELIMITER . '/';
        list($start, $end) = preg_split($pattern, $value);
        
        switch ($field)
        {
            case 'start':
                if ($start == Vfr_Form_Element_AvailabilityRangePicker::NO_VALUE)
                    return '';
                
                return trim(strftime('%e-%b-%Y', strtotime($start)));
            break;
            
            case 'end':
                if ($end == Vfr_Form_Element_AvailabilityRangePicker::NO_VALUE)
                    return '';
                
                return trim(strftime('%e-%b-%Y', strtotime($end)));
                
            default:
                throw new Exception("Invalid field value $field passed");
        }
    }
}