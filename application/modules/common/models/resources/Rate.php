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
	
    //
    // CREATE
    //
	public function addNewRate($idCalendar, $params)
	{
        // check that the new entry doesn't collide with an existing entry
		$query = $this->select()
					  ->where('idCalendar = ?', $idCalendar)
					  ->order('startDate');

		$pattern = '/' . Vfr_Form_Element_RatesRangePicker::DELIMITER  . '/';
		list($startDate, $endDate, $weeklyRate, $weekendNightlyRate, $midweekNightlyrate, $minStayDays) = preg_split($pattern, $params['rates']);
		
		$nullExpr = new Zend_Db_Expr('NULL');
		$nowExpr  = new Zend_Db_Expr('NOW()');
		$data = array (
			'idRate'				=> $nullExpr,
			'idCalendar'			=> $idCalendar,
			'startDate'				=> strftime('%Y-%m-%d', strtotime($startDate)),
			'endDate'				=> strftime('%Y-%m-%d', strtotime($endDate)),
			'name'					=> ($params['name'] == null) ? $nullExpr : $params['name'],
			'minStayDays'			=> $minStayDays,
			'weeklyRate'			=> ($weeklyRate == 'noval') ? $nullExpr : $weeklyRate,
			'weekendNightlyRate'	=> ($weekendNightlyRate == 'noval') ? $nullExpr : $weekendNightlyRate,
			'midweekNightlyRate'	=> ($midweekNightlyrate == 'noval') ? $nullExpr : $midweekNightlyrate,
			'added'					=> $nowExpr,
			'updated'				=> $nowExpr
		);
	
		try {
			$this->insert($data);
			$rowId = $this->_db->lastInsertId();
			
			return $rowId;
		} catch (Exception $e) {
			//$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			//$profiler = $dbAdapter->getProfiler();
			//$lastDbProfilerQuery = $profiler->getLastQueryProfile();
            //var_dump($lastDbProfilerQuery->getQuery());
            
			throw $e;
		}		
	}
    
    //
    // READ
    //
    
    public function getRatesByCalendarId($idCalendar)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		
		$rates = null;
		
		$query = $this->select()
					  ->where('idCalendar = ?', $idCalendar)
					  ->order('startDate');
		try {
			$rates = $this->fetchAll($query);
		} catch (Exception $e) {
			//$profilerQuery = $this->_profiler->getLastQueryProfile();
			//$lastQuery = $profilerQuery->getQuery();
			//$params = $profilerQuery->getQueryParams();
			//$this->_logger->log(__METHOD__ . ' Exception thrown  ' . $e, Zend_Log::ERR);
			//$this->_logger->log(__METHOD__ . ' lastQuery  ' . $lastQuery, Zend_Log::ERR);
			//$this->_logger->log(__METHOD__ . ' params  ' . implode(',', $params), Zend_Log::ERR);
			//$this->_logger->table($table);
			throw $e;
		}	

		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
		return $rates;
	}
	
	public function getRateByPk($idRate)
	{
		$query = $this->select()
		              ->where('idRate = ?', $idRate)
					  ->limit(1);
		try {
			$rateRow = $this->fetchRow($query);
			
			return $rateRow;
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	public function getRateByStartAndEndDate($idCalendar, $startDate, $endDate)
	{
		$query = $this->select()
					  ->where('idCalendar = ?', $idCalendar)
					  ->where('startDate = ?', $startDate)
					  ->where('endDate = ?', $endDate)
					  ->limit(1);
		try {
			$rateRow = $this->fetchRow($query);
			
			return $rateRow;
		} catch (Exception $e) {
			throw $e;
		}
	}
    
    public function getDateRangeCollisions($idCalendar, $start, $end, $idRate=null)
    {		
        $startClause = $this->_db->quoteInto('(startDate BETWEEN ?', $start)
		             . $this->_db->quoteInto(' AND ?', $end);
					 
        $endClause   = $this->_db->quoteInto('endDate BETWEEN ?', $start)
                     . $this->_db->quoteInto(' AND ?', $end);
                     
        $spanClause  = $this->_db->quoteInto('((startDate <= ?)', $start)
                     . $this->_db->quoteInto(' AND (endDate >= ?)) )', $end);
       
        try {
            $query = $this->select()
						  ->where('idCalendar = ?', $idCalendar);
			
			// exclude the row we're editing, if we're in update mode
			if ($idRate)
				$query->where('idRate != ?', $idRate);
				
            $query->where($startClause)
				  ->orWhere($endClause)
				  ->orWhere($spanClause);
			$ratesRowset = $this->fetchAll($query);
        } catch (Exception $e) {
            throw $e;
        }
		
		return $ratesRowset;
    }
    
    //
    // UPDATE
    //
    public function updateRateByPk($idRate, $params)
	{
		$pattern = '/' . Vfr_Form_Element_RatesRangePicker::DELIMITER  . '/';
		list($startDate, $endDate, $weeklyRate, $weekendNightlyRate, $midweekNightlyrate, $minStayDays) = preg_split($pattern, $params['rates']);
		
		$nullExpr = new Zend_Db_Expr('NULL');
		$nowExpr  = new Zend_Db_Expr('NOW()');
		$data = array (
			'startDate'				=> strftime('%Y-%m-%d', strtotime($startDate)),
			'endDate'				=> strftime('%Y-%m-%d', strtotime($endDate)),
			'name'					=> ($params['name'] == null) ? $nullExpr : $params['name'],
			'minStayDays'			=> $minStayDays,
			'weeklyRate'			=> ($weeklyRate == 'noval') ? $nullExpr : $weeklyRate,
			'weekendNightlyRate'	=> ($weekendNightlyRate == 'noval') ? $nullExpr : $weekendNightlyRate,
			'midweekNightlyRate'	=> ($midweekNightlyrate == 'noval') ? $nullExpr : $midweekNightlyrate,
			'updated'				=> $nowExpr
		);		
		
		$adapter = $this->getAdapter();
		$where = $adapter->quoteInto('idRate = ?', $idRate);
		try {
			$query = $this->update($data, $where);
		} catch (Exception $e) {
			throw $e;
		}
	}
    //
    // DELETE
    //
}
