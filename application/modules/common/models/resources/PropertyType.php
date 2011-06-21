<?php
class Common_Resource_PropertyType extends Vfr_Model_Resource_Db_Table_Abstract implements Common_Resource_PropertyType_Interface
{
	protected $_name = 'PropertyTypes';
	protected $_primary = 'idPropertyType';
	protected $_rowClass = 'Common_Resource_PropertyType_Row';
	protected $_rowsetClass = 'Common_Resource_PropertyType_Rowset';
	protected $_dependantTables = array('Properties');


	//
	// CREATE
	//
	
	//
	// READ
	//
	
	public function getAllPropertyTypes($inUse=true)
	{
		//$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		$query = $this->select();
		if ($inUse == true)
		    $query->where('inUse = ?', (int) 1);
			
		try {
			$propertyTypeRowset = $this->fetchAll($query);
			
			return $propertyTypeRowset;
		} catch (Exception $e) {
			$profilerQuery = $this->_profiler->getLastQueryProfile();
			$lastQuery = $profilerQuery->getQuery();
			$params = $profilerQuery->getQueryParams();
			$this->_logger->log(__METHOD__ . ' Exception thrown  ' . $e, Zend_Log::ERR);
			$this->_logger->log(__METHOD__ . ' lastQuery  ' . $lastQuery, Zend_Log::ERR);
			$this->_logger->log(__METHOD__ . ' params  ' . implode(',', $params), Zend_Log::ERR);
			throw $e;
		}

		//$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
	}
	
	public function getPropertyTypeById($idPropertyType)
	{
		$query = $this->select('name')
					  ->where('idPropertyType = ?', $idPropertyType)
					  ->limit(1);
		try {
			$row = $this->fetchRow($query);
			
			return $row;
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	//
	// UPDATE
	//
	
	//
	// DELETE
	//
}

