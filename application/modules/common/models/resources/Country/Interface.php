<?php
interface Common_Resource_Country_Interface
{
	public function getCountryById($idCountry);
	public function getCountries($visible=true);
	public function getCountriesCount($visible=true);
}
