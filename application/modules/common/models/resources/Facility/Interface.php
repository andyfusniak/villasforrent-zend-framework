<?php
interface Common_Resource_Facility_Interface
{
    public function getAllFacilities($inUse=true);
    public function getAllFacilitiesByPropertyId($idProperty, $inUse);
}
