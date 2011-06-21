<?php
class Vfr_Form_Element_AvailabilityRangePicker extends Zend_Form_Element_Xhtml
{
    const NO_VALUE  = 'noval';
    const DELIMITER = '#';
    const FORMAT = '%start%#%end%';
    
    //public $helper = 'availabilityRangePicker';
    
    protected $_start   = null;
    protected $_end     = null;
    
    public function getValue()
    {
        if ( ($this->_start == null) || ($this->_end == null))
            return false;
        
        return $this->_value;
    }
    
    public function setStart($dt)
    {
        if ($dt == self::NO_VALUE) {
            $this->_start = self::NO_VALUE;
            return $this;
        }
        
        $unixTime     = strtotime($dt);
        $this->_start = strftime('%Y-%m-%d', $unixTime);
        
        return $this;
    }
    
    public function setEnd($dt)
    {
        if ($dt == self::NO_VALUE) {
            $this->_end = self::NO_VALUE;
            return $this;
        }
        
        $unixTime   = strtotime($dt);
        $this->_end = strftime('%Y-%m-%d', $unixTime);
        
        return $this;
    }
    
    
    public function setValue($value)
    {
        if (!is_array($value))
            return false;
        
        if (isset($value['start']) && ($value['start'] !== '')) {
            $this->setStart($value['start']);
        } else {
            $this->_start = self::NO_VALUE;
        }
        
        if (isset($value['end']) && ($value['end'] !== '')) {
            $this->setEnd($value['end']);
        } else {
            $this->_end = self::NO_VALUE;
        }
        
        $this->_value = str_replace(
            array('%start%', '%end%'),
            array($this->_start, $this->_end),
            self::FORMAT
        );
        
        return parent::setValue($this->_value);
    }
}