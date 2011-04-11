<?php
class Common_Resource_Advertiser extends Vfr_Model_Resource_Db_Table_Abstract
{
	protected $_name = 'Advertisers';
	protected $_primary = 'idAdvertiser';
	protected $_rowClass = 'Common_Resource_Advertiser_Row';
	protected $_rowsetClass = 'Common_Resource_Advertiser_Rowset';
	protected $_dependentTables = array('Properties');

	/**
	 * Get all advertisers
	 *
	 * @param boolean $page	Use Zend_Paginator?
	 * @return Common_Resource_Advertiser_Rowset|Zend_Paginator
	 */
	public function getAll($page=null)
	{
		$query = $this->select();
		$rowSet = $this->fetchAll($query);
		
		
		if (null !== $page) {
			$adapter = new Zend_Paginator_Adapter_DbTableSelect($query);
			$paginator = new Zend_Paginator($adapter);
			$paginator->setItemCountPerPage(3)
					  ->setCurrentPageNumber((int) $page);
			return $paginator;
		}
		
		//var_dump($rowSet);
		return $rowSet;
		
				 
		//var_dump($rowSet);
		//exit;
		//try {
		//	
		//} catch (Exception $e) {
		//	die('Caught exception: ' . $e->getMessage() . "\n");
		//}
	}
	
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