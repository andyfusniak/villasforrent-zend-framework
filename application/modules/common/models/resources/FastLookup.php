<?php
class Common_Resource_FastLookup extends Vfr_Model_Resource_Db_Table_Abstract implements Common_Resource_FastLookup_Interface
{
	protected $_name = 'FastLookups';
	protected $_primary = 'idFastLookup';
	protected $_rowClass = 'Common_Resource_FastLookup_Row';
	protected $_rowsetClass = 'Common_Resource_FastLookup_Rowset';
	protected $_referenceMap = array (
		'Countries' => array (
			'columns' => array('idCountry'),
			'refTableClass' => 'Common_Resource_Country'
		),
		'Regions' => array (
			'columns' => array('idRegion'),
			'refTableClass' => 'Common_Resource_Region'
		),
		'Destinations' => array (
			'columns' => array('idDestination'),
			'refTableClass' => 'Common_Resource_Destination'
		)
	);

	const DUMMY_PROPERTY_ID = 10000;
	
	const DUMMY_COUNTRY_ID     = 1;
	const DUMMY_REGION_ID	   = 1;
	const DUMMY_DESTINATION_ID = 1;

	//
	// CREATE
	//

	public function addNewLookup($idCountry, $countryName,
								 $idRegion, $regionName,
								 $idDestination, $destinationName,
								 $totalVisible=0, $total=0, $url='', $idProperty=null)
	{
		//var_dump($idCountry, $countryName, $idRegion, $regionName, $idDestination, $destinationName, $totalVisible, $total, $url, $idProperty);
		//die();
		
		if (null === $idProperty) {
			$idProperty = (int) Common_Resource_Property::DEFAULT_PROPERTY_ID;
		}

		$nowExpr  = new Zend_Db_Expr('NOW()');
		$nullExpr = new Zend_Db_Expr('NULL');
		$data = array(
			'idFastLookup'		=> $nullExpr,
			'url'				=> (string) $url,
			'idProperty'		=> (int) $idProperty,
			'idCountry'			=> ($idCountry != null) ? (int) $idCountry : (int) self::DUMMY_COUNTRY_ID,
			'idRegion'			=> (isset($idRegion) ? (int) $idRegion : (int) self::DUMMY_REGION_ID),
			'idDestination' 	=> (isset($idDestination) ? (int) $idDestination : (int) self::DUMMY_DESTINATION_ID),
			'totalVisible'		=> ($totalVisible === null) ? $nullExpr : $totalVisible,
			'total'				=> ($total === null) ? $nullExpr : $total,
			'countryName'		=> ($countryName == null) ? $nullExpr : (string) $countryName,
			'regionName'		=> ($regionName == null) ? $nullExpr : (string) $regionName,
			'destinationName'	=> ($destinationName == '') ? $nullExpr : (string) $destinationName,
			'added'   			=>  new Zend_Db_Expr('NOW()'),
			'updated' 			=>  new Zend_Db_Expr('NOW()') 
		);
	
		//var_dump($data);
		
		try {
			$this->insert($data);
		} catch (Exception $e) {
			$profiler = Zend_Db_Table::getDefaultAdapter()->getProfiler();
			var_dump($profiler);
			if ($profiler->getEnabled()) {
				$lastDbProfilerQuery = $profiler->getLastQueryProfile();
				Zend_Debug::dump($lastDbProfilerQuery->getQuery(), "Profiler: ", true);
			}
			throw $e;
		}
	}
	
	//
	// READ
	//
	
	/*
	public function lookup($url)
	{
		$url = (string) $url;

		//$count = sizeof(explode('/', $url));
		
		$query = $this->select()
					  ->where('url = ?', $url);
			
		try {
			$fastLookupRow = $this->fetchRow($query);
		
			return $fastLookupRow;
		} catch (Exception $e) {
			$profilerQuery = $this->_profiler->getLastQueryProfile();
			$lastQuery = $profilerQuery->getQuery();
			$params = $profilerQuery->getQueryParams();
			$this->_logger->log(__METHOD__ . ' Exception thrown  ' . $e, Zend_Log::ERR);
			$this->_logger->log(__METHOD__ . ' lastQuery  ' . $lastQuery, Zend_Log::ERR);
			$this->_logger->log(__METHOD__ . ' params  ' . implode(',', $params), Zend_Log::ERR);
			$this->_logger->table($table);
			throw $e;
		}
	}
	
	public function getFastLookupById($idFastLookup)
	{
		$query = $this->select()
					  ->where('idFastLookup = ?', $idFastLookup)
					  ->limit(1);
		try {
			$fastLookupRow = $this->fetchRow($query);
			
			return $fastLookupRow;
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	public function getFastLookupByCountryRegionDestinationId($idCountry, $idRegion, $idDestination)
	{
		$query = $this->select()
					  ->where('idCountry = ?', $idCountry)
					  ->where('idRegion = ?', $idRegion)
					  ->where('idDestination = ?', $idDestination)
					  ->limit(1);
		try {
			$fastLookupRow = $this->fetchRow($query);
			
			return $fastLookupRow;
		} catch (Exception $e) {
			throw $e;
		}
	}

    public function getFastAllLocations()
    {
        $query = $this->select()
                      ->order('idFastLookup');
        try {
            $fastLookupRowset = $this->fetchAll($query);
        } catch (Exception $e) {
            throw $e;
        }

        return $fastLookupRowset;
    }

	
	public function getAllCountries()
	{
		$query = $this->select()
					  ->where('idRegion = ?', self::DUMMY_REGION_ID)
					  ->where('idDestination = ?', self::DUMMY_DESTINATION_ID)
					  ->order('idFastLookup');
		try {			  
			$fastLookupRowset = $this->fetchAll($query);
			
			return $fastLookupRowset;
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	public function getAllRegions($idCountry)
	{
		$query = $this->select()
					  ->where('idCountry= ?', $idCountry)
					  ->where('idRegion != ?', self::DUMMY_REGION_ID)
					  ->where('idDestination = ?', self::DUMMY_DESTINATION_ID)
					  ->order('idFastLookup');
		try {			  
			$fastLookupRowset = $this->fetchAll($query);
		} catch (Exception $e) {
			throw $e;
		}
		
		return $fastLookupRowset;
	}
	
	public function getAllDestinations($idRegion)
	{
		$query = $this->select()
					  ->where('idRegion = ?', $idRegion)
					  ->where('idDestination != ?', self::DUMMY_DESTINATION_ID)
					  ->where('idProperty = ?', self::DUMMY_PROPERTY_ID);
		try {			  
			$fastLookupRowset = $this->fetchAll($query);
			
			return $fastLookupRowset;
		} catch (Exception $e) {
			throw $e;
		}
	}
	*/
	
	public function getEntirePropertyHierarchy()
	{
		$query = $this->select()
				      ->where('idProperty = ?', self::DUMMY_PROPERTY_ID)
					  ->order('url');
		try {
			$fastLokoupRowset = $this->fetchAll($query);
			
			return $fastLokoupRowset;
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	//
	// UPDATE
	//
	
	public function increaseNumPropertiesCountryRegionDestination($idCountry, $idRegion, $idDestination)
	{
		$params = array (
            'totalVisible' => new Zend_Db_Expr('totalVisible+1'),
			'total'		   => new Zend_Db_Expr('total+1')
        );
        
		// country
        $where = $this->getAdapter()->quoteInto('idProperty = ?', Common_Resource_Property::DEFAULT_PROPERTY_ID)
		       . $this->getAdapter()->quoteInto(' AND idCountry = ?', $idCountry)
			   . $this->getAdapter()->quoteInto(' AND idRegion = ?', $idRegion)
			   . $this->getAdapter()->quoteInto(' AND idDestination = ?', $idDestination);
        try {
            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
		
		// region
		$where = $this->getAdapter()->quoteInto('idProperty = ?', Common_Resource_Property::DEFAULT_PROPERTY_ID)
		       . $this->getAdapter()->quoteInto(' AND idCountry = ?', $idCountry)
			   . $this->getAdapter()->quoteInto(' AND idRegion = ?', $idRegion)
			   . $this->getAdapter()->quoteInto(' AND idDestination = ?', Common_Resource_Destination::DEFAULT_DESTINATION_ID);
		try {
            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
		
		// destination
		$where = $this->getAdapter()->quoteInto('idProperty = ?', Common_Resource_Property::DEFAULT_PROPERTY_ID)
		       . $this->getAdapter()->quoteInto(' AND idCountry = ?', $idCountry)
			   . $this->getAdapter()->quoteInto(' AND idRegion = ?', Common_Resource_Region::DEFAULT_REGION_ID)
			   . $this->getAdapter()->quoteInto(' AND idDestination = ?', Common_Resource_Destination::DEFAULT_DESTINATION_ID);
		try {
            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
	}

	//
	// DELETE
	//
	
	public function purgeFastLookupTable()
	{
		$where = $this->getAdapter()->quoteInto('?', '1');
		$rowsAffected = $this->delete($where);

		return $rowsAffected;
	}
}
