<?php
class Vfr_Fastlookups
{
	private $countriesTbl;
	private $regionsTbl;
	private $destinationsTbl;

	private $fastlooksupTbl;
	private $fastlookuptable;
	private $fastlookupgeography;
	
	public function __construct()
    {
		$countriesTbl = null;
		$regionsTbl   = null;
		$destinationsTbl = null;
		$this->fastlookupTbl = new Common_Model_DbTable_Fastlookups();
		
        $this->fastlookuptable = array();
		$this->fastlookupgeography = array();
	}
	
	public function __destruct()
    {
    }
	
	private function buildGeographicName($countryName, $regionName, $destinationName)
    {
		$result_string = "";
		if(mb_strlen($countryName) > 0) {
			$resultString = $countryName;
		}
		
		if(mb_strlen($regionName) > 0) {
			$resultString .= ' &gt; ' . $regionName; 
		}
		
		if(mb_strlen($destinationName) > 0) {
			$resultString .= ' &gt; ' . $destinationName;
		}
		
		return $resultString;
	}
	
	
	public function loadFastTable()
    {
        $rowset = $this->fastlookupTbl->fetchAll();
        var_dump($rowset);
        die('stop point');
        exit;
	    foreach ($rowset as $row) {
            die('die here');
			$id_countries 	= $vo->getIdCountries();
			$id_regions   	= $vo->getIdRegions();
			$id_destinations 	= $vo->getIdDestinations(); 
			$country_name 	= $vo->getCountryName();
			$region_name  	= $vo->getRegionName();
			$destination_name = $vo->getDestinationName();
			
			// create a url index for fast url conversions
			$idx = $vo->getUrl();
			$this->fastlookuptable[$idx] = array($id_countries, $id_regions, $id_destinations, $vo->getTotalVisible(), $vo->getTotal(), $countryName, $regionName, $destinationName);
			$this->fastlookupgeography[$id_countries][$id_regions][$id_destinations] = array($country_name, $region_name, $destination_name);
		}
	}
	
	public function lookup($url) {
		//var_dump("Request to find " . $url);
		//var_dump($this->fastlookuptable);

		
		if(isset($this->fastlookuptable[$url])) {
			//var_dump($this->fastlookuptable[$url]);
			return $this->fastlookuptable[$url];
		} else {
			return null;
		}
	}
	
	public function countryRegionDestinationAsString($id_country, $id_region, $id_destination) {
		list($country_string, $region_string, $destination_string) = $this->fastlookupgeography[$id_country][$id_region][$id_destination];
		
		$result_string = $country_string . " " . $region_string . " " . $destination_string;
		
	}
	
	private function nameToUrlString($name) {
		$name = ereg_replace(" ", "-", strtolower($name));
		$name = ereg_replace("!", "-", strtolower($name));
		$name = ereg_replace("%", "-", strtolower($name));
		$name = ereg_replace("---", "-", $name);
		$name = ereg_replace("--", "-", $name);
		return $name;
	}
	
	public function purgeFastTable()
    {
        $this->fastlooksupTbl->
		$fastlookups_dao   = new FastLookupsDAO();
		return $fastlookups_dao->purgeFastTable();
	}
	
	public function createFastTableDB() {
		if($this->countries_dao == null) {
			$this->countries_dao = new CountriesDAO();
		}
		if($this->regions_dao == null) {
			$this->regions_dao = new RegionsDAO();
		}
		if($this->destinations_dao == null) {
			$this->destinations_dao = new DestinationsDAO();
		}
		
		$fastlookups_dao   = new FastLookupsDAO();
		
		$countries_vo_list = $this->countries_dao->findWhere(null, 'idCountries');
		for($c=0; $c < sizeof($countries_vo_list); $c++) {
			// get the next country in the list
			$country_vo = $countries_vo_list[$c];
			$id_countries = $country_vo->getIdCountries();
			$countries_url_part = $this->nameToUrlString($country_vo->getName());
			
			$fastlookups_vo = new FastLookupsVO(null, $countries_url_part, PropertiesDAO::DEFAULT_PROPERTY_ID, $id_countries, RegionsDAO::DEFAULT_REGION_ID, DestinationsDAO::DEFAULT_DESTINATION_ID, 0, 0, $country_vo->getName(), null, null, "now()", "now()");
			$fastlookups_dao->insertVO($fastlookups_vo);
			
			//var_dump($countries_url_part);
			
			$regions_vo_list = $this->regions_dao->findWhere('idCountries=' . $id_countries, 'idCountries');
			for($r=0; $r < sizeof($regions_vo_list); $r++) {
				// get the next region within this country
				$regions_vo = $regions_vo_list[$r];
				$id_regions = $regions_vo->getIdRegions();
				$regions_url_part = $this->nameToUrlString($regions_vo->getName());
				
				$fastlookups_vo = new FastLookupsVO(null, $countries_url_part . "/" . $regions_url_part, PropertiesDAO::DEFAULT_PROPERTY_ID, $id_countries, $id_regions, DestinationsDAO::DEFAULT_DESTINATION_ID, 0, 0, $country_vo->getName(), $regions_vo->getName(), null, "now()", "now()");
				$fastlookups_dao->insertVO($fastlookups_vo);
				//var_dump($countries_url_part . "/" . $regions_url_part);
				
				$destinations_vo_list = $this->destinations_dao->findWhere('idRegions=' . $id_regions, 'idRegions');
				for($d=0; $d < sizeof($destinations_vo_list); $d++) {
					$destinations_vo = $destinations_vo_list[$d];
					$id_destinations = $destinations_vo->getIdDestinations();
					$destinations_url_part = $this->nameToUrlString($destinations_vo->getName());
					
					$fastlookups_vo = new FastLookupsVO(null, $countries_url_part . "/" . $regions_url_part . "/" . $destinations_url_part, PropertiesDAO::DEFAULT_PROPERTY_ID, $id_countries, $id_regions, $id_destinations, 0, 0, $country_vo->getName(), $regions_vo->getName(), $destinations_vo->getName(), "now()", "now()");
					$fastlookups_dao->insertVO($fastlookups_vo);
				}
			}
		}
	}
}
