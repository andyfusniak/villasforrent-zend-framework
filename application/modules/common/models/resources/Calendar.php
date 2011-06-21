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

	//
	// CREATE
	//
	
    public function createCalendar($idProperty)
    {
		$nowExpr = new Zend_Db_Expr('NOW()');
        $data = array(
            'idCalendar'    		=> null,
            'idProperty'   			=> $idProperty,
            'calendarName'  		=> '',
            'visible'       		=> 1,
            'vLanguage'     		=> 'en_EN',
            'vDuration'     		=> 18,
            'vStartYear'    		=> 99,
            'vStartMonth'   		=> 99,
            'vColumns'      		=> 4,
            'vSlope'        		=> 1,
            'vBoundary'     		=> 0,
            'vBookedColour' 		=> 'FF6666',
            'vProvBookedColour'     => 'FF6600',
            'vAvailableColour'      => '99CC99',
            'vUnavailableColour'    => 'CCCCCC',
            'vTitleHeightPixels'    => 15,
            'vDayCellWidthPixels'   => 14,
            'vDayCellHeightPixels'  => 13,
            'vDayNameColour'        => 'CC0000',
            'vTableBorderColour'    => '336699',
            'vKeyOn'                => 1,
            'vNumKeys'              => 2,
            'vHorizontalGapPixels'  => 4,
            'vVerticalGapPixels'    => 2,
            'vWeekStartDay'         => 0, // default saturday
            'rentalBasis'           => 'PR',
            'currencyCode'          => 'GBP',
            'added'   				=> $nowExpr,
            'updated' 				=> $nowExpr
        );
        
        try {
            $query = $this->insert($data);
        } catch (Exception $e) {
            $dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$profiler = $dbAdapter->getProfiler();
			$lastDbProfilerQuery = $profiler->getLastQueryProfile();

			var_dump($lastDbProfilerQuery->getQuery());

			throw $e;
        }
    }

	//
	// READ
	//
	
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
	
	public function getRentalBasis($idCalendar)
	{
		$query = $this->select('rentalBasis')
					  ->where('idCalendar = ?', $idCalendar);
		try {
			$calendarRow = $this->fetchRow($query);
			return $calendarRow->rentalBasis;
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	public function getBaseCurrency($idCalendar)
	{
		$query = $this->select('currencyCode')
					  ->where('idCalendar = ?', $idCalendar);
		try {
			$calendarRow = $this->fetchRow($query);
			return $calendarRow->currencyCode;
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	
	//
	// UPDATE
	//
	
	public function updateRentalBasis($idCalendar, $rentalBasis)
	{
		try {
			$data = array (
				'rentalBasis' => $rentalBasis
			);
			$where = $this->getAdapter()->quoteInto('idCalendar = ?', $idCalendar);
			$query = $this->update($data, $where);
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	public function updateBaseCurrency($idCalendar, $currencyCode)
	{
		try {
			$data = array (
				'currencyCode' => $currencyCode
			);
			$where = $this->getAdapter()->quoteInto('idCalendar = ?', $idCalendar);
			$query = $this->update($data, $where);
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	//
	// DELETE
	//
}
