<?php
class Common_Model_Location extends Vfr_Model_Abstract
{
	public function getCountryById($idCountry)
	{
		$idCountry = (int) $idCountry;

		return $this->getResource('Country')->getCountryById($idCountry);
	}

	public function getCountries($visible=true)
	{
		return $this->getResource('Country')->getCountries($visible);
	}

	public function addCountry($name, $priority=1, $prefix='', $postfix='', $visible=true)
	{
		return $this->getResource('Country')->addCountry($name,$priority,$prefix,$postfix,$visible);
	}

	public function getCountriesCount($visible=true)
	{
		return $this->getResource('Country')->getCountriesCount($visible);
	}

	public function getRegionById($idRegion)
	{
		$idRegion = (int) $idRegion;

		return $this->getResource('Region')->getRegionById($idRegion);
	}

	public function getRegions($visible=true)
	{
		return $this->getResource('Region')->getRegions($visible);
	}

	public function getRegionsByCountryId($idCountry, $visible=true)
	{
		return $this->getResource('Region')->getRegionsByCountryId($idCountry, $visible);
	}

	public function getDestinationById($idDestination)
	{
		$idDestination = (int) $idDestination;
		return $this->getResource('Destination')->getDestinationById($idDestination);
	}

	public function getDestinations($visible=true)
	{
		return $this->getResource('Destination')->getDestinations($visible);
	}

	public function getDestinationsByRegionId($idRegion, $visible)
	{
		$idRegion = (int) $idRegion;
		return $this->getResource('Destination')->getDestinationsByRegionId($idRegion, $visible);
	}
}
