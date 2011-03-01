<?php
class Common_Resource_FastLookup extends Vfr_Model_Resource_Db_Table_Abstract implements Common_Resource_FastLookup_Interface
{
	protected $_name = 'FastLookups';
	protected $_primary = 'idFastLookup';
	protected $_rowClass = 'Common_Resource_FastLookup_Row';
	protected $_rowsetClass = 'Common_Resource_FastLookup_Rowset';
	
	public function purgeFastLookupTable()
	{
		$where = $this->getAdapter()->quoteInto('?', '1');
		$rowsAffected = $this->delete($where);

		return $rowsAffected;
	}

	public function addNewLookup($cItem, $rItem, $dItem, $totalVisible=0, $total=0, $url='', $idProperty=null)
	{
		if (null === $idProperty) {
			$idProperty = (int) Common_Resource_Property::DEFAULT_PROPERTY_ID;
		}

		if (null !== $cItem) {
			$cname = (string) $cItem->name;
		} else {
			$cname = null;
		}

		if (null !== $rItem) {
			$rname = (string) $rItem->name;
		} else {
			$rname = null;
		}

		if (null !== $dItem) {
			$dname = (string) $dItem->name;
		} else {
			$dname = null;
		}

		$data = array(
			'idFastLookup' => new Zend_Db_Expr('NULL'),
			'url' => (string) $url,
			'idProperty' => (int) $idProperty,
			'idCountry' => (isset($cItem->idCountry) ? (int) $cItem->idCountry : (int) 1),
			'idRegion' => (isset($rItem->idRegion) ? (int) $rItem->idRegion : (int) 1),
			'idDestination' => (isset($dItem->idDestination) ? (int) $dItem->idDestination : (int) 1),
			'totalVisible' => $totalVisible,
			'total' => $total,
			'countryName' => (string) $cname,
			'regionName' => (string) $rname,
			'destinationName' => (string) $dname,
			'added'   =>  new Zend_Db_Expr('NOW()'),
			'updated' =>  new Zend_Db_Expr('NOW()') 
		);
		
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

	public function lookup($url)
	{
		$url = (string) $url;

		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

		$query= $this->select()
		     		 ->where('url = ?', $url);

		try {
			$result = $this->fetchRow($query);
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
	
		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
		return $result;
	}
}
