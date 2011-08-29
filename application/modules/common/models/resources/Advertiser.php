<?php
class Common_Resource_Advertiser extends Vfr_Model_Resource_Db_Table_Abstract
{
	protected $_name = 'Advertisers';
	protected $_primary = 'idAdvertiser';
	protected $_rowClass = 'Common_Resource_Advertiser_Row';
	protected $_rowsetClass = 'Common_Resource_Advertiser_Rowset';
	protected $_dependentTables = array('Properties');

	//
	// CREATE
	//
	public function addNewAdvertiser($params)
	{
		$nullExpr = new Zend_Db_Expr('NULL');
		$nowExpr  = new Zend_Db_Expr('NOW()');
		
		$data = array (
			'idAdvertiser'    => $nullExpr,
			'iso2char'	   	  => $nullExpr,
			'idAdministrator' => 1,
			'username'		  => $nullExpr,
			'passwd'		  => $params['passwd'],
			'emailAddress'	  => $params['emailAddress'],
			'firstname'		  => $params['firstname'],
			'lastname'		  => $params['lastname'],
			'address'		  => $nullExpr,
			'postcode'		  => $nullExpr,
			'telephone'		  => $nullExpr,
			'fax'			  => $nullExpr,
			'mobile'		  => $nullExpr,
			'added'			  => $nowExpr,
			'updated'		  => $nowExpr,
			'lastModifiedBy'  => 'system'
		);
		
		try {
			$this->insert($data);
			$rowId = $this->_db->lastInsertId();
			
			return $rowId;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Get all advertisers
	 *
	 * @param boolean $page	Use Zend_Paginator?
	 * @return Common_Resource_Advertiser_Rowset|Zend_Paginator
	 */
	public function getAll($page=null)
	{
		$query = $this->select();
		
		if (null !== $page) {
			$adapter = new Zend_Paginator_Adapter_DbTableSelect($query);
			$paginator = new Zend_Paginator($adapter);
			$paginator->setItemCountPerPage(3)
					  ->setCurrentPageNumber((int) $page);
		
			return $paginator;
		}
		
		try {
			$rowSet = $this->fetchAll($query);
			
			return $rowSet;			
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	public function getAdvertiserById($id)
	{
		return $this->find($id)->current();
	}
	
	public function getAdvertiserByEmail($emailAddress)
	{
		//$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		
		try {
			$query = $this->select()
						  ->where('emailAddress=?', $emailAddress)
						  ->limit(1);
			$result = $this->fetchRow($query);
		} catch (Exception $e) {
			throw $e;
		}
		
		//$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
		return $result;
	}
}