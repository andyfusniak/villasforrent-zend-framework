<?php
class Common_Resource_PropertyContentField extends Vfr_Model_Resource_Db_Table_Abstract implements Common_Resource_PropertyContentField_Interface
{
	protected $_name = 'PropertiesContentFields';
	protected $_primary = 'idPropertyContentField';
	protected $_rowClass = 'Common_Resource_PropertyContentField_Row';
	protected $_rowsetClass = 'Common_Resource_PropertyContentField_Rowset';
	protected $_dependentTables = array('PropertiesContent');
	
	public function getAllPropertyContentFields()
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

		$query = $this->select();
		$result = $this->fetchAll($query);

		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
		return $result;
	}
}
