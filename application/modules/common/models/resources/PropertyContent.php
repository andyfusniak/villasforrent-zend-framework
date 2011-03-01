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
}
