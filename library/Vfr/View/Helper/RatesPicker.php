<?php
class Vfr_View_Helper_RatesPicker extends Zend_View_Helper_Abstract
{
    public function ratesPicker($value, $field)
    {
        if (!$value) {
            return '';
        }
        
        // value is in the format yyyy-mm-dd,yyyy-mm-dd
        $pattern = '/' . Vfr_Form_Element_RatesRangePicker::DELIMITER . '/';
        list($start, $end, $weeklyRate, $weekendNightlyRate, $midweekNightlyRate, $minStayDays) = preg_split($pattern, $value);
        
        switch ($field)
        {
            case 'start':
                if ($start == Vfr_Form_Element_RatesRangePicker::NO_VALUE_GIVEN)
                    return '';
                
                return trim(strftime('%e-%b-%Y', strtotime($start)));
            case 'end':
                if ($end == Vfr_Form_Element_RatesRangePicker::NO_VALUE_GIVEN)
                    return '';
                
                return trim(strftime('%e-%b-%Y', strtotime($end)));
            break;
        
            case 'weeklyRate':
                 if ($weeklyRate == Vfr_Form_Element_RatesRangePicker::NO_VALUE_GIVEN)
                    return '';
                
                return $weeklyRate;
            break;
        
            case 'weekendNightlyRate':
                 if ($weekendNightlyRate == Vfr_Form_Element_RatesRangePicker::NO_VALUE_GIVEN)
                    return '';
                
                return $weekendNightlyRate;
            break;
        
            case 'midweekNightlyRate':
                 if ($midweekNightlyRate == Vfr_Form_Element_RatesRangePicker::NO_VALUE_GIVEN)
                    return '';
                
                return $midweekNightlyRate;
            break;
        
            case 'minStayDays':
                if ($minStayDays == Vfr_Form_Element_RatesRangePicker::NO_VALUE_GIVEN)
                    return '';
                
                return $minStayDays;
            break;
        
            default:
                throw new Exception("Invalid field value $field passed");
        }
    }
}