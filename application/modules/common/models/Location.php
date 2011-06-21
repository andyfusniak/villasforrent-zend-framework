<?php
class Common_Model_Location extends Vfr_Model_Abstract
{
	//
	// CREATE
	//
	
	public function fillTable()
	{
		return $this->getResource('Location')->fillTable();
	}
	
	//
	// READ
	//
	
	public function lookup()
	{
		return $this->getResource('Location')->lookup();
	}
	
    public function getFastAllLocations()
    {
        $fastLookupRowset = $this->getResource('FastLookup')->getAllLocations();

        foreach ($fastLookupRowset as $fastLookupRow) {
            
        }
    }

	public function getCountryById($idCountry)
	{
		$idCountry = (int) $idCountry;

		return $this->getResource('Country')->getCountryById($idCountry);
	}

	public function getCountries($visible=true, $orderBy='displayPriority')
	{
		return $this->getResource('Country')->getCountries($visible);
	}
	
	public function getCountriesWithTotalVisible()
	{
		return $this->getResource('Country')->getCountriesWithTotalVisible();
	}
	
	public function getFastAllCountries()
	{
		return $this->getResource('FastLookup')->getAllCountries();
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
	
	public function getRegionsWithTotalVisible($idCountry)
	{
		return $this->getResource('Region')->getRegionsWithTotalVisible($idCountry);
	}
	
	public function getFastAllRegions($idCountry)
	{
		$idCountry = (int) $idCountry;
		
		return $this->getResource('FastLookup')->getAllRegions($idCountry);
	}

	public function getFastAllDestinations($idRegion)
	{
		$idRegion = (int) $idRegion;
		
		return $this->getResource('FastLookup')->getAllDestinations($idRegion);
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
	
	//
	// DELETE
	//
	
	public function purgeLocationTable()
	{
		return $this->getResource('Location')->purgeLocationTable();
	}
}
