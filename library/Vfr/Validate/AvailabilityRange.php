<?php
class Vfr_Validate_AvailabilityRange extends Zend_Validate_Abstract
{
    const version = '1.0.0';

    const REVERSE           = 'dateReverse';
    const BOTH_MISSING      = 'bothMissing';
    const START_MISSING     = 'startMissing';
    const END_MISSING       = 'endMissing';
    const BOTH_INVALID      = 'bothInvalid';
    const START_INVALID     = 'startInvalid';
    const END_INVALID       = 'endInvalid';
    const SAME_DAY          = 'sameDay';
    const DATE_COLLISION    = 'dateCollision';

    protected $_messageTemplates = array (
        self::REVERSE        => "The start date must come before the end date",
        self::START_MISSING  => "Select a start date for this booking",
        self::BOTH_MISSING   => "Select a start and end date for this booking",
        self::END_MISSING    => "Select an end date for this booking",
        self::BOTH_INVALID   => "Both start and end dates are invalid",
        self::START_INVALID  => "The start date is invalid",
        self::END_INVALID    => "The end date is invalid",
        self::SAME_DAY       => "Bookings must be at least 1 day apart",
        self::DATE_COLLISION => "The date range given overlaps an existing booking"
    );

    protected $_mode;

    protected $_idAvailability;

    public function __construct($options = array() )
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }

        // default to add mode
        if (!array_key_exists('mode', $options)) {
            $options['mode'] = 'add';
        }

        $this->setMode($options['mode']);

        if (array_key_exists('idAvailability', $options)) {
            $this->_idAvailability = (int) $options['idAvailability'];
        } else {
            $this->_idAvailability = null;
        }
    }

    public function getMode()
    {
        return $this->_mode;
    }

    public function setMode($mode)
    {
        $this->_mode = $mode;
        return $this;
    }

    public function isValid($value) {
        $delimiter = Vfr_Form_Element_AvailabilityRangePicker::DELIMITER;
        $pattern = '/' . $delimiter . '/';
        list($start, $end) = preg_split($pattern, $value);

        $startNoVal = (bool) ($start == Vfr_Form_Element_AvailabilityRangePicker::NO_VALUE);
        $endNoVal   = (bool) ($end == Vfr_Form_Element_AvailabilityRangePicker::NO_VALUE);

        if ($startNoVal && $endNoVal) {
            $this->_error(self::BOTH_MISSING);
            return false;
        }

        if ($startNoVal) {
            $this->_error(self::START_MISSING);
            return false;
        }

        if ($endNoVal) {
            $this->_error(self::END_MISSING);
            return false;
        }

        $unixTimeStart  = strtotime($start);
        $unixTimeEnd    = strtotime($end);

        if (($unixTimeStart <= 0) && ($unixTimeEnd <= 0)) {
            $this->_error(self::BOTH_INVALID);
            return false;
        }

        if ($unixTimeStart <= 0) {
            $this->_error(self::START_INVALID);
            return false;
        }

        if ($unixTimeEnd <= 0) {
            $this->_error(self::END_INVALID);
            return false;
        }

        if ($unixTimeStart == $unixTimeEnd) {
            $this->_error(self::SAME_DAY);
            return false;
        }

        $frontController = Zend_Controller_Front::getInstance();
        $post = $frontController->getRequest()->getPost();

        $propertyModel      = new Common_Model_Property();
        $calendarModel      = new Common_Model_Calendar();
        $idCalendar         = $propertyModel->getCalendarIdByPropertyId($post['idProperty']);

        $availabilityRowset = $calendarModel->getBookingCollisions($idCalendar, $start, $end, $this->getMode(), $this->_idAvailability);

        //var_dump($availabilityRowset);
        //return false;

        if (sizeof($availabilityRowset) > 0) {
            $this->_error(self::DATE_COLLISION);
            return false;
        }

        return true;
    }
}
