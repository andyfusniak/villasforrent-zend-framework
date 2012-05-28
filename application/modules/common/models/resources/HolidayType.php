<?php
class Common_Resource_HolidayType extends Vfr_Model_Resource_Db_Table_Abstract implements Common_Resource_HolidayType_Interface
{
    // holiday types
    const HOLIDAY_TYPE_NORMAL   = 1;
    const HOLIDAY_TYPE_GOLF     = 2;
    const HOLIDAY_TYPE_SKIING   = 3;
    const HOLIDAY_TYPE_INTEREST = 4;
    const HOLIDAY_TYPE_ACCESS   = 5;

    private $_holidayTypes = array(
            self::HOLIDAY_TYPE_NORMAL   => 'Normal',
            self::HOLIDAY_TYPE_GOLF     => 'Golf',
            self::HOLIDAY_TYPE_SKIING   => 'Skiing',
            self::HOLIDAY_TYPE_INTEREST => 'Special Interest',
            self::HOLIDAY_TYPE_ACCESS   => 'Access'
    );

    public function getAllHolidayTypesArray()
    {
        return $this->_holidayTypes;
    }
}
