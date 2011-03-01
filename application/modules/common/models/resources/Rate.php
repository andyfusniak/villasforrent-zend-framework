<?php
class Common_Resource_Rate extends Vfr_Model_Resource_Db_Table_Abstract implements Common_Resource_Rate_Interface
{
	protected $_name = 'Rates';
	protected $_primary = 'idRate';
	protected $_rowClass = 'Common_Resource_Rate_Row';
	protected $_rowsetClass = 'Common_Resource_Rate_Rowset';
	protected $_referenceMap = array (
		'Calendar' => array (
			'columns' => array('idCalendar'),
			'refTableClass' => 'Common_Resource_Calendar'
		)
	);
	
	public function getRatesByCalendarId($idCalendar)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		
		$rates = null;
		
		$query = $this->select();
		$query->from(array('R' => 'Rates'))
			  ->where('R.idCalendar = ?', (int) $idCalendar)
			  ->order('startDate');

		$rates = $this->fetchAll($query);

			
//		try {
	//		
		//} catch (Exception $e) {
			//$profilerQuery = $this->_profiler->getLastQueryProfile();
			//$lastQuery = $profilerQuery->getQuery();
			//$params = $profilerQuery->getQueryParams();
			//$this->_logger->log(__METHOD__ . ' Exception thrown  ' . $e, Zend_Log::ERR);
			//$this->_logger->log(__METHOD__ . ' lastQuery  ' . $lastQuery, Zend_Log::ERR);
			//$this->_logger->log(__METHOD__ . ' params  ' . implode(',', $params), Zend_Log::ERR);
			//$this->_logger->table($table);
			//throw $e;
		//}	

		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
		return $rates;
	}
}
