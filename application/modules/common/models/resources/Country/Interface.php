<?php
interface Common_Resource_Country_Interface
{
	public function getCountryById($idCountry);
	public function getCountries($visible=true, $orderBy='displayPriority');
	public function getCountriesCount($visible=true);
}
