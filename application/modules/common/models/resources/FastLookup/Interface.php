<?php
interface Common_Resource_FastLookup_Interface
{
    public function getFastAllLocations();
	public function addNewLookup($idCountry, $countryName,
								 $idRegion, $regionName,
								 $idDestination, $destinationName,
								 $totalVisible=0, $total=0, $url='', $idProperty=null);
	public function lookup($url);
	public function getAllCountries();
}
