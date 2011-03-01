<?php
class Common_Resource_Country extends Vfr_Model_Resource_Db_Table_Abstract implements Common_Resource_Country_Interface
{
	protected $_name = 'Countries';
	protected $_primary = 'idCountry';
	protected $_rowClass = 'Common_Resource_Country_Row';
	protected $_rowsetClass = 'Common_Resource_Country_Rowset';
	protected $_dependantTables = array('Properties');

	const DEFAULT_COUNTRY_ID = 1;

	public function getCountryById($idCountry)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

		$query = $this->select()
		              ->where('idCountry = ?', $idCountry)
					  ->limit(1);
		$result = $this->fetchRow($query);

		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
		return $result;
	}
	
	public function getCountries($visible=true)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		$query = $this->select()
		              ->where('visible = ?', (($visible) ? '1' : '0'))
					  ->order('idCountry');
		
		$result = $this->fetchAll($query);
		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
		return $result;
	}

	public function getCountriesCount($visible=true)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

		$query = $this->select('COUNT(*)')
		              ->where('visible = ?', (($visible) ? '1' : '0'));
		$result = $this->fetchOne($query);
		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);

		return $result;
	}

	public function addCountry($name,$priority,$prefix,$postfix,$visible)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

		$data = array(
			'idCountry' => new Zend_Db_Expr(500),
			'name' => $name
		);
		try {
			$query = $this->insert($data);
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
	}
}

