<?php
class Common_Resource_Calendar extends Vfr_Model_Resource_Db_Table_Abstract
{
	protected $_name = 'Calendars';
	protected $_primary = 'idCalendar';
	protected $_rowClass = 'Common_Resource_Calendar_Row';
	protected $_rowsetClass = 'Common_Resource_Calendar_Rowset';
	protected $_dependentTables = array('Availability', 'Rates');
	protected $_referenceMap = array (
		'Property' => array (
			'columns' => array('idProperty'),
			'refTableClass' => 'Common_Resource_Property'
		)
	);

	public function getCalendarIdByPropertyId($idProperty)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		$query = $this->select()
					  ->where('idProperty = ?', $idProperty);
		try {
			$idCalendar = $this->_db->fetchOne($query);
		} catch (Exception $e) {
			throw $e;
		}
		
		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
		return $idCalendar;
	}

	public function getCalendarById($idCalendar)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

		$query = $this->select()
			          ->where('idCalendar = ?', $idCalendar);
		$result = $this->fetchRow($query);

		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
		return $result;
	}
}
