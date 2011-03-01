<?php
interface Common_Resource_Region_Interface
{
	public function getRegionById($idRegion);
	public function getRegionsByCountryId($idCountry, $visible=true);
	public function getRegionsCountByCountryId($idCountry, $visible=true);
	public function getRegions($visible=true);
	public function getRegionsCount($visible=true);
}
