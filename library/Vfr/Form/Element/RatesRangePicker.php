<?php
class Vfr_Form_Element_RatesRangePicker extends Zend_Form_Element_Xhtml
{
    const NO_VALUE_GIVEN    = 'noval';
    const DELIMITER = '#';
    const FORMAT = '%start%#%end%#%weeklyRate%#%weekendNightlyRate%#%midweekNightlyRate%#%minStayDays%';

    public $helper = 'ratesRangePicker';

    protected $_format = null;
    protected $_start = null;
    protected $_end   = null;
    protected $_weeklyRate = null;
    protected $_weekendNightlyRate = null;
    protected $_midweekNightlyRate = null;
    protected $_minStayDays = null;

    public function init()
    {
        $this->_format = '%start%' . self::DELIMITER . '%end%' . self::DELIMITER . '%weeklyRate%' . self::DELIMITER . '%weekendNightlyRate%' . self::DELIMITER . '%midweekNightlyRate%' . self::DELIMITER . '%minStayDays%';
    }

    public function getValue()
    {
        //if ((! $this->_start) && (! $this->_end))
        //    return false;

        //var_dump($this->value);
        return $this->_value;
    }

    public function setStart($dt)
    {
        if ($dt == self::NO_VALUE_GIVEN) {
            $this->_start = self::NO_VALUE_GIVEN;
            return $this;
        }

        $unixTime    = strtotime($dt);
        $this->_start = strftime("%Y-%m-%d", $unixTime);

        return $this;
    }

    public function setEnd($dt)
    {
        if ($dt == self::NO_VALUE_GIVEN) {
            $this->_end = self::NO_VALUE_GIVEN;
            return $this;
        }

        $unixTime   = strtotime($dt);
        $this->_end  = strftime("%Y-%m-%d", $unixTime);

        return $this;
    }

    public function setWeeklyRate($num)
    {
        if ($num == '') {
            $this->_weeklyRate = self::NO_VALUE_GIVEN;
            return $this;
        }

        $this->_weeklyRate = (string) $num;
        return $this;
    }

    public function setMidweekNightlyRate($num)
    {
        if ($num == '') {
            $this->_midweekNightlyRate = self::NO_VALUE_GIVEN;
            return $this;
        }

        $this->_midweekNightlyRate = (string) $num;
        return $this;
    }

    public function setWeekendNightlyRate($num)
    {
        if ($num == '') {
            $this->_weekendNightlyRate = self::NO_VALUE_GIVEN;
            return $this;
        }

        $this->_weekendNightlyRate = (string) $num;
        return $this;
    }

    public function setMinStayDays($num)
    {
        if ($num == '') {
            $this->_minStayDays = self::NO_VALUE_GIVEN;
            return $this;
        }

        $this->_minStayDays = (string) $num;
        return $this;
    }

    public function setValue($value)
    {
        $this->init();

        if (is_string($value)) {
            $this->_value = $value;
            return parent::setValue($this->_value);
        }

        if (!is_array($value))
            return false;

        if (isset($value['start']) && ($value['start'] !== '')) {
            $this->setStart($value['start']);
        } else {
            $this->_start = self::NO_VALUE_GIVEN;
        }

        if (isset($value['end']) && ($value['end'] !== '')) {
            $this->setEnd($value['end']);
        } else {
            $this->_end = self::NO_VALUE_GIVEN;
        }

        if (isset($value['weeklyRate']) && ($value['weeklyRate'] !== '')) {
            $this->setWeeklyRate($value['weeklyRate']);
        } else {
            $this->_weeklyRate = self::NO_VALUE_GIVEN;
        }

        if (isset($value['weekendNightlyRate']) && ($value['weekendNightlyRate'] !== '')) {
            $this->setWeekendNightlyRate($value['weekendNightlyRate']);
        } else {
            $this->_weekendNightlyRate = self::NO_VALUE_GIVEN;
        }

        if (isset($value['midweekNightlyRate']) && ($value['midweekNightlyRate'] !=='')) {
            $this->setMidweekNightlyRate($value['midweekNightlyRate']);
        } else {
            $this->_midweekNightlyRate = self::NO_VALUE_GIVEN;
        }

        if (isset($value['minStayDays']) && ($value['minStayDays'] !== '')) {
            $this->setMinStayDays($value['minStayDays']);
        } else {
            $this->_minStayDays = self::NO_VALUE_GIVEN;
        }

        $this->_value = str_replace(
            array('%start%', '%end%', '%weeklyRate%', '%weekendNightlyRate%', '%midweekNightlyRate%', '%minStayDays%'),
            array($this->_start, $this->_end, $this->_weeklyRate, $this->_weekendNightlyRate, $this->_midweekNightlyRate, $this->_minStayDays),
            $this->_format
        );

        return parent::setValue($this->_value);
    }
}
