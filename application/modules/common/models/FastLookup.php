<?php
class Common_Model_FastLookup extends Vfr_Model_Abstract
{
	private $_fastLookupResource = null;

	protected $_filterStringTrim = null;
	protected $_filterStringToLower = null;
	protected $_filterPregReplace = null;
	
	protected $_filterChain = null;

	public function init()
	{
	}

	private function _initFilters()
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

		if (null === $this->_filterStringTrim) {
			$this->_filterStringTrim = new Zend_Filter_StringTrim();
		}

		if (null === $this->_filterStringToLower) {
			$this->_filterStringToLower = new Zend_Filter_StringToLower();
		}

		if (null === $this->_filterPregReplace) {
			$this->_filterPregReplace = new Zend_Filter_PregReplace(array('match' => array('/ /', '/---/', '/%/', '/!/', "/'/"),
			                                                              'replace' => array('-', '-', '-', '-','-')));
		}

		$this->_logger->log(__METHOD__ . ' Setting up filter chains', Zend_Log::INFO);
		if (null === $this->_filterChain) {
			$this->_filterChain = new Zend_Filter();
			
			$this->_filterChain->addFilter($this->_filterStringTrim)
					   		   ->addFilter($this->_filterStringToLower)
							   ->addFilter($this->_filterPregReplace);
		}
		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
	}

	protected function _makeIntoUrl($name)
	{
		$this->_initFilters();

		$name = (string) $name;
		$name = $this->_filterChain->filter($name);
		
		return $name;
	}

	protected function _generateUrl($cName=null, $rName=null, $dName=null, $propertyUrl=null)
	{
		if (isset($propertyUrl) && isset($cName) && isset($rName) && isset($dName)) {
			return $this->_makeIntoUrl($cName) . '/' . $this->_makeIntoUrl($rName) . '/' . $this->_makeIntoUrl($dName) . '/' . $this->_makeIntoUrl($propertyUrl);
		} elseif (isset($cName) && isset($rName) && isset($dName)) {
			return $this->_makeIntoUrl($cName) . '/' . $this->_makeIntoUrl($rName) . '/' . $this->_makeIntoUrl($dName);
		} elseif (isset($cName) && isset($rName)) {
			return $this->_makeIntoUrl($cName) . '/' . $this->_makeIntoUrl($rName);
		} elseif (isset($cName)) {
			return $this->_makeIntoUrl($cName);
		}
	}
	
	public function lookup($url)
	{
		return $this->getResource('FastLookup')->lookup($url);
	}

	public function purgeFastLookupTable()
	{
		return $this->getResource('FastLookup')->purgeFastLookupTable();
	}

	//
	// CREATE
	//

	public function createFastLookupTable($visible=true)
	{
		$this->purgeFastLookupTable();

		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		
		$locationsModel = new Common_Model_Location();
		$propertyModel = new Common_Model_Property();

		//$p = $propertyModel->getResource('Property')->getFullPropertyById(10421);

		//$locationsModel->addCountry('my country');
	
		if (null === $this->_fastLookupResource) {
			$this->_fastLookupResource = $this->getResource('FastLookup');
		}
		
		// for each country
		$countries = $locationsModel->getCountries($visible);
		foreach ($countries as $cItem) {
			$idCountry = $cItem->idCountry;	
			$url = $this->_generateUrl($cItem->name);
			
			// find all properties in this location
			$options = array(
				'approved' 	=> true,
				'visible'	=> true,
				'count' 	=> true,
				'idCountry' => $idCountry,
			);
			$totalVisible = $propertyModel->doSearch($options);
			if ($totalVisible == null)
				$totalVisible = 0;

			$options['visible'] = false;
			$total = $propertyModel->doSearch($options);
			if ($total == null)
				$total = 0;
			
			// add an entry for this country
			$this->_fastLookupResource->addNewLookup($idCountry, $cItem->name,
													 null, null,
													 null, null,
													 $totalVisible, $total, $url);
			
			// for each region in the current country
			$regions = $locationsModel->getRegionsByCountryId($idCountry, $visible);
			foreach ($regions as $rItem) {
				$idRegion = $rItem->idRegion;	
				$url = $this->_generateUrl($cItem->name, $rItem->name);
					
				// find all properties in this location
				$options = array(
					'approved' 	=> true,
					'visible'	=> true,
					'count' 	=> true,
					'idCountry' => $idCountry,
					'idRegion'  => $idRegion,
				);
				$totalVisible = $propertyModel->doSearch($options);
				if ($totalVisible == null)
					$totalVisible = 0;
				
				$options['visible'] = false;
				$total = $propertyModel->doSearch($options);
				if ($total == null)
					$total = 0;
				
				$this->_fastLookupResource->addNewLookup($idCountry, $cItem->name,
														 $idRegion, $rItem->name,
														 null, null,
														 $totalVisible, $total, $url);
	
				// for each destination in the current region
				$destinations = $locationsModel->getDestinationsByRegionId($idRegion, $visible);
				foreach ($destinations as $dItem) {
					$idDestination = $dItem->idDestination;
					$url = $this->_generateUrl($cItem->name, $rItem->name, $dItem->name);

					// find all properties in this location
					$options = array(
						'approved' 	=> true,
						'visible'	=> true,
						'count' 	=> true,
						'idCountry' => $idCountry,
						'idRegion'  => $idRegion,
						'idDestination' => $idDestination
					);
					$totalVisible = $propertyModel->doSearch($options);
					if ($totalVisible == null)
						$totalVisible = 0;
						
					$options['visible'] = false;
					$total = $propertyModel->doSearch($options);
					if ($total == null)
						$total = 0;
					
					// create a new fast lookup entry
					$this->_fastLookupResource->addNewLookup($idCountry, $cItem->name,
															 $idRegion, $rItem->name,
															 $idDestination, $dItem->name, $totalVisible, $total, $url, Common_Resource_Property::DEFAULT_PROPERTY_ID);

					$options = array(
						'approved' 		=> true,
						'idCountry' 	=> $idCountry,
						'idRegion' 		=> $idRegion,
						'idDestination' => $idDestination
					);
					$properties = $propertyModel->doSearch($options);
					foreach ($properties as $p) {
						$url = $this->_generateUrl($cItem->name, $rItem->name, $dItem->name, $p->urlName);
						$this->_fastLookupResource->addNewLookup($idCountry, $cItem->name,
																 $idRegion, $rItem->name,
																 $idDestination, $dItem->name,
																 null, null, $url, $p->idProperty);
					}
				}
			}
		}

		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
	}
	
	//
	// READ
	//
	
	public function getFastLookupByCountryRegionDestinationId($idCountry, $idRegion, $idDestination)
	{
		$idCountry		= (int) $idCountry;
		$idRegion  		= (int) $idRegion;
		$idDestination	= (int) $idDestination;
		
		$fastLookupResource = $this->getResource('FastLookup');
		return $fastLookupResource->getFastLookupByCountryRegionDestinationId($idCountry, $idRegion, $idDestination);
	}
	
	public function getEntirePropertyHierarchy()
	{
		$fastLookupResource = $this->getResource('FastLookup');
		//$countryRowset 		= $fastLookupResource->getAllCountries();
		//$regionRowset		= $fastLookupResource->getAllRegions();
		//$destinationRowset = $fastLookupResource->getAllDestinations(); 
		
		$fastLookupRowset = $fastLookupResource->getEntirePropertyHierarchy();
		
		
		$hierarchy = array ();
		
		$firstCountry		= true;
		$firstRegion  		= true;
		foreach ($fastLookupRowset as $row) {
			if (($row->idRegion == Common_Resource_FastLookup::DUMMY_REGION_ID) &&
				($row->idDestination == Common_Resource_FastLookup::DUMMY_DESTINATION_ID)) {
				
				// this is a top-level country item
				if ($firstCountry) {
					$regions = array ();
					$firstCountry = false;
				}
				$hierarchy[$row->idCountry] = array ('name'			=> $row->countryName,
													 'idCountry'	=> $row->idCountry,
													 'idFastLookup' => $row->idFastLookup,
													 'children'		=> $regions);
			} else if (($row->idDestination == Common_Resource_FastLookup::DUMMY_DESTINATION_ID) &&
					   ($row->idRegion != Common_Resource_FastLookup::DUMMY_REGION_ID)) {
				// this is a region level item, so attach it to the country
				if ($firstRegion) {
					$firstRegion = false;
					$destinations = array ();
				}
				$hierarchy[$row->idCountry]['children'][$row->idRegion] = array ('name'			=> $row->regionName,
																				 'idRegion'		=> $row->idRegion,
																				 'idFastLookup' => $row->idFastLookup,
																				 'children' => $destinations);
			} else if (($row->idDestination != Common_Resource_FastLookup::DUMMY_DESTINATION_ID) &&
					   ($row->idRegion != Common_Resource_FastLookup::DUMMY_REGION_ID)) {
				// this is a destination level item, so attach it to the region
				$hierarchy[$row->idCountry]['children'][$row->idRegion]['children'][$row->idDestination] = array ('name' => $row->destinationName,
																												  'idFastLookup' => $row->idFastLookup);
			}
		}
		
		return $hierarchy;
	}
	
	//
	// UPDATE
	//
	
	//
	// DELETE
	//
}
