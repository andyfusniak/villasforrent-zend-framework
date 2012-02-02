<?php
class Vfr_Validate_RatesRange extends Zend_Validate_Abstract
{
    const version = '1.0.0';

    // dates
    const NOT_DATE_RANGE     = 'notDateRange';
    const DATE_REVERSE       = 'dateReverse';
    const DATE_OVERLAP       = 'dateOverlap';
    const BOTH_MISSING       = 'bothMissing';
    const START_DATE_MISSING = 'startDateMissing';
    const END_DATE_MISSING   = 'endDateMissing';
    const SAME_DAY           = 'sameDay';
    const DATE_COLLISION     = 'dateCollision';
    
    // rates
    const WEEKLY_RATE_NOT_NUMBER = 'weeklyRateNotNumber';
    const WEEKEND_NIGHTLY_RATE_NOT_NUMBER = 'weekendNightlyRateNotNumber';
    const MIDWEEK_NIGHTLY_RATE_NOT_NUMBER = 'midweekNightlyRateNotNumber';
    
    const AT_LEAST_ONE_RATE = 'atLeastOneRate';
    
    protected $_messageTemplates = array (
        self::NOT_DATE_RANGE     => "'%value%' is not a valid date range",
        self::DATE_REVERSE       => "The start date must come before the end date",
        self::DATE_OVERLAP       => "This date range overlaps an existing entry",
        self::BOTH_MISSING       => "Enter both start and end date ranges",
        self::START_DATE_MISSING => "You must choose a start date",
        self::END_DATE_MISSING   => "You must choose an end date",
        self::SAME_DAY           => "Ranges must be at least 1 day apart",
        // rates
        self::WEEKLY_RATE_NOT_NUMBER => "'%value%' is not a value weekly rate. Use numbers in format of 800 or 800.2 or 800.25 no commas",
        self::WEEKEND_NIGHTLY_RATE_NOT_NUMBER => "'%value%' is not a valid weekend nightly rate. Use numbers in format of 800 or 800.2 or 800.25 no commas",
        self::MIDWEEK_NIGHTLY_RATE_NOT_NUMBER => "'%value%' is not a valid midweek nightly rate. Use numbers in format of 800 or 800.2 or 800.25 no commas",
        self::AT_LEAST_ONE_RATE => "You must enter at least one type of rate for your entry",
        self::DATE_COLLISION    => "The date range given overlaps an existing entry"
    );
    
    /**
     * @var bool
     */
    protected $_mode;
    
    
    protected $_idRate;
    
    /**
     * Sets validator options
     *
     * @params array|Zend_Config
     * @return void
     */
    public function __construct($options = array())
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }
        
        // default to add mode
        if (!array_key_exists('mode', $options)) {
            $options['mode'] = 'add';
        }
        
        $this->setMode($options['mode']);
        
        
        if (array_key_exists('idRate', $options)) {
            $this->_idRate = (int) $options['idRate'];
        } else {
            $this->_idRate = null;    
        }
    }
    
    /**
     * Returns the mode
     *
     * @return string
     */
    public function getMode()
    {
        return $this->_mode;
    }
    
    /**
     * Sets the mode
     *
     * @param string $mode
     * @return Vfr_Validate_RatesRange Provides a fluent interface
     */
    public function setMode($mode)
    {
        $this->_mode = $mode;
        return $this;
    }
    
    public function isValid($value)
    {
        //var_dump("v=". $value);
        //die();
        
        $delimiter = Vfr_Form_Element_RatesRangePicker::DELIMITER;
        $pattern = '/' . $delimiter  . '/';
        list($startDate, $endDate, $weeklyRate, $weekendNightlyRate, $midweekNightlyrate, $minStayDays) = preg_split($pattern, $value);
    
        //var_dump($startDate, $endDate, $weeklyRate, $weekendNightlyRate, $midweekNightlyrate, $minStayDays);
        
        
        
    
        // Date checking
        if (($startDate == Vfr_Form_Element_RatesRangePicker::NO_VALUE_GIVEN)
            && ($endDate == Vfr_Form_Element_RatesRangePicker::NO_VALUE_GIVEN)) {
            $this->_error(self::BOTH_MISSING);
            return false;
        }
        
        if ($startDate == Vfr_Form_Element_RatesRangePicker::NO_VALUE_GIVEN) {
            $this->_error(self::START_DATE_MISSING);
            return false;
        }
        
        if ($endDate == Vfr_Form_Element_RatesRangePicker::NO_VALUE_GIVEN) {
            $this->_error(self::END_DATE_MISSING);
            return false;
        }
    
        $unixTimeStart  = strtotime($startDate);
        $unixTimeEnd    = strtotime($endDate);
        
        if ($unixTimeStart == $unixTimeEnd) {
            $this->_error(self::SAME_DAY);
            return false;
        }
        
        if ($unixTimeStart > $unixTimeEnd) {
            $this->_error(self::DATE_REVERSE);
            return false;
        }
        
        // rates checking
        $numNoValueGivenEntries = 0;
        //$validNumberPattern = '/^[0-9]+$/'; // integer only
        $validNumberPattern = '/^[0-9]+(\.[0-9]{1,2})?$/';
        
        if ($weeklyRate !== Vfr_Form_Element_RatesRangePicker::NO_VALUE_GIVEN) {
            //var_dump($weeklyRate);
            
            if (!preg_match($validNumberPattern, $weeklyRate)) {
                $this->_error(self::WEEKLY_RATE_NOT_NUMBER, $weeklyRate);
            }
        } else {
            $numNoValueGivenEntries++;
        }
        
        if ($weekendNightlyRate !== Vfr_Form_Element_RatesRangePicker::NO_VALUE_GIVEN) {
            if (!preg_match($validNumberPattern, $weekendNightlyRate)) {
                $this->_error(self::WEEKEND_NIGHTLY_RATE_NOT_NUMBER, $weekendNightlyRate);
            }
        } else {
            $numNoValueGivenEntries++;
        }
        
        if ($midweekNightlyrate !== Vfr_Form_Element_RatesRangePicker::NO_VALUE_GIVEN) {
            if (!preg_match($validNumberPattern, $midweekNightlyrate)) {
                $this->_error(self::MIDWEEK_NIGHTLY_RATE_NOT_NUMBER, $midweekNightlyrate);
            }
        } else {
            $numNoValueGivenEntries++;
        }
        //var_dump($numNoValueGivenEntries);
        //die();
        
        if ($numNoValueGivenEntries == 3) {
            $this->_error(self::AT_LEAST_ONE_RATE);
        }

        //var_dump($startDate, $endDate, $this->getMode(), $this->_idRate);
		$frontController = Zend_Controller_Front::getInstance();
		$post = $frontController->getRequest()->getPost();
		
		$propertyModel = new Common_Model_Property();
        $calendarModel = new Common_Model_Calendar();
		$idCalendar  = $propertyModel->getCalendarIdByPropertyId($post['idProperty']);
        $ratesRowset = $calendarModel->getRateDateRangeCollisions($idCalendar, $startDate, $endDate, $this->getMode(), $this->_idRate);
		
		//var_dump("query done");
		if (sizeof($ratesRowset) > 0) {
            $this->_error(self::DATE_COLLISION);
        }
	    
        if (sizeof($this->_messages) > 0)
            return false;
        
        return true;
    }
}
