<?php
interface Common_Resource_Availability_Interface
{
    public function getAvailabilityRangeByCalendarId($idCalendar, $startDate = null, $endDate = null);
}
