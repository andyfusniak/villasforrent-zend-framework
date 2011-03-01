<?php
class Common_Resource_PropertyType extends Vfr_Model_Resource_Db_Table_Abstract implements Common_Resource_PropertyType_Interface
{
	protected $_name = 'PropertyTypes';
	protected $_primary = 'idPropertyType';
	protected $_rowClass = 'Common_Resource_PropertyType_Row';
	protected $_rowsetClass = 'Common_Resource_PropertyType_Rowset';
	protected $_dependantTables = array('Properties');

	public function getAllPropertyTypes($inUse=true)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		$query = $this->select()
		              ->where('inUse = ?', ((true === $inUse) ? '1' : '0'))
					  ->from('PropertyTypes');
		try {
			$rowSet = $this->fetchAll($query);
		} catch (Exception $e) {
			$profilerQuery = $this->_profiler->getLastQueryProfile();
			$lastQuery = $profilerQuery->getQuery();
			$params = $profilerQuery->getQueryParams();
			$this->_logger->log(__METHOD__ . ' Exception thrown  ' . $e, Zend_Log::ERR);
			$this->_logger->log(__METHOD__ . ' lastQuery  ' . $lastQuery, Zend_Log::ERR);
			$this->_logger->log(__METHOD__ . ' params  ' . implode(',', $params), Zend_Log::ERR);
			throw $e;
		}

		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);

		return $rowSet;
	}
}

