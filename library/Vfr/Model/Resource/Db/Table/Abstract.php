<?php
abstract class Vfr_Model_Resource_Db_Table_Abstract extends Zend_Db_Table_Abstract
{
	protected $_logger = null;
	protected $_profiler = null;

	public function __construct()
	{
		if (null === $this->_logger) {
			$this->_logger = Zend_Registry::get('logger');
		}
		
		$this->init();
		parent::__construct();
	}


	public function init()
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

		if (null === $this->_profiler) {
			$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			$this->_profiler = $dbAdapter->getProfiler();
			$this->_logger->log(__METHOD__ . ' Profiler stored in abstract class', Zend_Log::DEBUG);
		}

		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
	}
	
	public function saveRow($info, $row = null)
	{
		if (null === $row) {
			$row = $this->createRow();
		}
		
		//
		$columns = $this->info('cols');
		foreach ($columns as $column) {
			if (array_key_exists($column, $info)) {
				$row->$column = $info[$column];
			}
		}
		
		return $row->save();
	}

}
