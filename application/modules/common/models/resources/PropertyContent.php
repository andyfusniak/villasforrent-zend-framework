<?php
class Common_Resource_PropertyContent extends Vfr_Model_Resource_Db_Table_Abstract implements Common_Resource_PropertyContent_Interface
{
	protected $_name = 'PropertiesContent';
	protected $_primary = 'idPropertyContent';
	protected $_rowClass = 'Common_Resource_PropertyContent_Row';
	protected $_rowsetClass = 'Common_Resource_PropertyContent_Rowset';
	protected $_dependentTables = array('PropertiesContentFields');
	protected $_referenceMap = array (
		'Property' => array (
			'columns' => array('idProperty'),
			'refTableClass' => 'Common_Resource_Property'
		)
	);
	
	// Content IDs
	const FIELD_TOTAL_ROWS	= 30;
	
	const FIELD_LOCATION_URL 		= 1;
	const FIELD_META_DATA 			= 2;
	const FIELD_SEO_DATA 			= 3;
	const FIELD_WEBSITE 			= 4;
	const FIELD_HEADLINE_1 			= 5;
	const FIELD_HEADLINE_2 			= 6;
	const FIELD_SUMMARY 			= 7;
	const FIELD_DESCRIPTION 		= 8;
	const FIELD_BEDROOM_DESC		= 9;
	const FIELD_BATHROOM_DESC 		= 10;
	
	const FIELD_KITCHEN_DESC 		= 11;
	const FIELD_UTILITY_DESC 		= 12;
	const FIELD_LIVING_DESC 		= 13;
	const FIELD_OTHER_DESC 			= 14;
	const FIELD_SERVICE_DESC 		= 15;
	const FIELD_NOTES_DESC 			= 16;
	const FIELD_ACCESS_DESC 		= 17;
	const FIELD_OUTSIDE_DESC 		= 18;
	const FIELD_GOLF_DESC 			= 19;
	const FIELD_SKIING_DESC 		= 20;
	const FIELD_SPECIAL_DESC 		= 21;
	const FIELD_BEACH_DESC 			= 22;
	const FIELD_TRAVEL_DESC 		= 23;
	const FIELD_BOOKING_DESC 		= 24;
	const FIELD_TESTIMONIALS_DESC 	= 25;
	const FIELD_CHANGEOVER_DESC  	= 26;
	const FIELD_CONTACT_DESC 		= 27;
	const FIELD_COUNTRY 			= 28;
	const FIELD_REGION 				= 29;
	const FIELD_LOCATION 			= 30;

	const VERSION_MAIN 		= 1;
	const VERSION_UPDATE 	= 2;
	
	public function createPropertyContent($idProperty, $version, $lang, $idPropertyContentField, $content)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		
		$nowExpr = new Zend_Db_Expr('NOW()');
		$data = array (
			'idPropertyContent' 		=> new Zend_Db_Expr('NULL'),
			'idProperty'				=> $idProperty,
			'version'					=> $version,
			'idPropertyContentField'	=> $idPropertyContentField,
			'iso2char'					=> $lang,
			'content'					=> $content,
			'added'						=> $nowExpr,
			'updated'					=> $nowExpr
		);
		
		try {
			$this->insert($data);
		} catch (Exception $e) {
			//$dbAdapter = Zend_Db_Table::getDefaultAdapter();
			//$profiler = $dbAdapter->getProfiler();
			//$lastDbProfilerQuery = $profiler->getLastQueryProfile();

			//var_dump($lastDbProfilerQuery->getQuery());
			throw $e;
		}
		
		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
	}
	
	public function getPropertyContentById($idProperty, $lang='EN')
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

		$query = $this->select()
					  ->where('iso2char = ?', $lang)
					  ->where('version = ?', (int) 1)
		              ->where('idProperty = ?', $idProperty);
		$result = $this->fetchAll($query);

		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
		return $result;
	}
	
	public function updatePropertyContent($idProperty, $version, $lang, $idPropertyContentField, $content)
	{
		$params = array (
			'content'	=> $content
		);
		
		$adapter = $this->getAdapter();
		$where = $adapter->quoteInto('idProperty = ?', $idProperty)
			   . $adapter->quoteInto(' AND version = ?', $version)
			   . $adapter->quoteInto(' AND iso2char = ?', $lang)
			   . $adapter->quoteInto(' AND idPropertyContentField = ?', $idPropertyContentField);
		try {
			$query = $this->update($params, $where);
		} catch (Exception $e) {
			throw $e;
		}
	}
}
	