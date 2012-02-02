<?php
class Admin_View_Helper_PropertyStatus extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';
    
    private $_statusList = array (
          Common_Resource_Property::STEP_1_LOCATION => 'S1 Location',
          Common_Resource_Property::STEP_2_CONTENT  => 'S2 Content',
          Common_Resource_Property::STEP_3_PICTURES => 'S3 Pictures',
          Common_Resource_Property::STEP_4_RATES    => 'S4 Rates',
          Common_Resource_Property::STEP_5_AVAILABILITY => 'S5 Availability',
          Common_Resource_Property::COMPLETE => 'Complete'
    );
    
    private function wrapWithSpan($className, $name)
    {
        return '<span class="' . $className . '">' . $name . '</span>';
    }
    
    public function propertyStatus($status, $approved)
    {
        switch ($status) {
            case Common_Resource_Property::STEP_1_LOCATION:
            case Common_Resource_Property::STEP_2_CONTENT:
            case Common_Resource_Property::STEP_3_PICTURES:
            case Common_Resource_Property::STEP_4_RATES:
            case Common_Resource_Property::STEP_5_AVAILABILITY:
                if ($approved)
                    return $this->wrapWithSpan(
                        'status-err',
                        $this->_statusList[$status]
                    );
                else
                    return $this->_statusList[$status];
            break;
        
            case Common_Resource_Property::COMPLETE:
                if ($approved)
                    return $this->wrapWithSpan(
                        'status-complete',
                        $this->_statusList[Common_Resource_Property::COMPLETE]
                    );
                else
                    return $this->_statusList[$status];;
            break;
        
            default:
                return 'Unknown';
        }
    }
}