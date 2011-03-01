<?php
class Common_Resource_Availability extends Vfr_Model_Resource_Db_Table_Abstract implements Common_Resource_Availability_Interface
{
	protected $_name = 'Availability';
	protected $_primary = 'idAvailability';
	protected $_rowClass = 'Common_Resource_Availability_Row';
	protected $_rowsetClass = 'Common_Resource_Availability_Rowset';
	protected $_referenceMap = array (
		'Calendar' => array (
			'columns' => array('idCalendar'),
			'refTableClass' => 'Common_Resource_Calendar'
		)
	);

	public function getAvailabilityRangeByCalendarId($idCalendar,
													 $startDate=null,
													 $endDate=null)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		
		//if (null !== $startDate) {
		//	if (!($startDate instanceof Datetime))
		//		throw new Vfr_Model_Resource_Exception('Wrong data type passed for startDate');
		//}
		
		//if (null !== $endDate) {
		//	if (!($endDate instanceof Datetime))
		//		throw new Vfr_Model_Resource_Exception('Wrong data type passed for endDate');
		//}
		
		$query = $this->select()
		              ->where('idCalendar = ?', $idCalendar);
					  
		if ($startDate) {
			$query->where('endDate >= ?', $startDate);
		}
		
		if ($endDate) {
			$query->where('startDate <= ?', $endDate);
		}
		
		try {
			$result = $this->fetchAll($query);
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
	
	public function getBookedInclusive($idProperty, $startDate, $endDate)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		
		$query = $this->select()
					  ->where('endDate >= ' . $startDate)
					  ->where('startDate <= ' . $endDate)
		              ->where('idProperty = ?', $idProperty);
		try {
			$result = $this->fetchAll($query);
		} catch (Exception $e) {
			throw $e;
		}
		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
	}
}
