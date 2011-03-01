<?php
class Common_Resource_Advertiser extends Vfr_Model_Resource_Db_Table_Abstract
{
	protected $_name = 'Advertisers';
	protected $_primary = 'idAdvertiser';
	protected $_rowClass = 'Common_Resource_Advertiser_Row';
	protected $_rowsetClass = 'Common_Resource_Advertiser_Rowset';
	protected $_dependentTables = array('Properties');

	public function getAdvertiserById($id)
	{
		return $this->find($id)->current();
	}
	
	public function getAdvertiserByEmail($emailAddress)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		
		$query = $this->select()
		              ->where('emailAddress=?', $emailAddress)
					  ->limit(1);
		$result = $this->fetchRow($query);

		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
		return $result;
	}
}