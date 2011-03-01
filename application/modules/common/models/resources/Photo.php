<?php
class Common_Resource_Photo extends Vfr_Model_Resource_Db_Table_Abstract implements Common_Resource_Photo_Interface
{
	protected $_name = 'Photos';
	protected $_primary = 'idPhoto';
	protected $_rowClass = 'Common_Resource_Photo_Row';
	protected $_rowsetClass = 'Common_Resource_Photo_Rowset';

	public function getCountries($visible=true)
	{
		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);

		$query = $this->select()
		              ->where('visible = ?', (($visible) ? '1' : '0'))
					  ->order('idCountry');


		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);

		return $result;
	}

	public function getAllPhotosByPropertyId($idProperty, $visible=true)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

		$query = $this->select()
					  ->where('idProperty = ?', $idProperty)
					  ->where('visible = ?', $visible)
					  ->order('displayPriority');
		try {
			$resultSet = $this->fetchAll($query);
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
		return $resultSet;
	}
}

