<?php
class Vfr_Validate_DateInput extends Zend_Validate_Abstract
{
    const version = '1.00';

    const INVALID_DATEFORMAT = 'invalidDateFormat';
    const INVALID_DATE = 'invalidDate';

    protected $_messageTemplates = array(
        self::INVALID_DATEFORMAT => "The date format needs to be DD/MM/YYYY format",
        self::INVALID_DATE => "The date is invalid"
    );

    public function isValid($value)
    {
        $validDatePattern = '#[0-3][0-9]/[0-1][0-9]/[0-9]{4}#';

        if (!preg_match($validDatePattern, $value)) {
            $this->_error(self::INVALID_DATEFORMAT);
            return false;
        }

        list($dd, $mm, $yyyy) = explode('/', $value);
        //var_dump($dd, $mm, $yyyy);

        if (!checkdate($mm, $dd, $yyyy)) {
            $this->_error(self::INVALID_DATE);
            return false;
        }

        return true;
    }
}
