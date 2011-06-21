<?php
class Common_Resource_Photo extends Vfr_Model_Resource_Db_Table_Abstract implements Common_Resource_Photo_Interface
{
	protected $_name = 'Photos';
	protected $_primary = 'idPhoto';
	protected $_rowClass = 'Common_Resource_Photo_Row';
	protected $_rowsetClass = 'Common_Resource_Photo_Rowset';
	protected $_referenceMap = array (
		'idProperty' => array (
			'columns' => array('idProperty'),
			'refTableClass' => 'Common_Resource_Property'
		)
    );
	
	
	//
	// CREATE
	//
	
	public function addPhotoByPropertyId($idProperty, $params)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		
		$query = $this->select()
					  ->from($this, array(new Zend_Db_Expr('MAX(displayPriority) + 1 AS newpri')))
					  ->where('idProperty = ?', $idProperty);
						  		
		$row = $this->fetchRow($query);
		$nextDisplayPriority = $row->newpri;
		
		// if this is the first photo added for this property
		// then there won't be a result from the query
		if ($nextDisplayPriority == null)
			$nextDisplayPriority = 1;
	
		$nowExpr = new Zend_Db_Expr('NOW()');
		$data = array (
			'idPhoto'			=> new Zend_Db_Expr('NULL'),
			'approved'			=> $params['approved'],
			'idProperty'		=> $idProperty,
			'displayPriority'	=> $nextDisplayPriority,
			'originalFilename'	=> $params['originalFilename'],
			'fileType'			=> $params['fileType'],
			'widthPixels'		=> $params['widthPixels'],
			'heightPixels'		=> $params['heightPixels'],
			'sizeK'				=> $params['sizeK'],
			'caption'			=> $params['caption'],
			'visible'			=> $params['visible'],
			'added'				=> $nowExpr,
			'updated'			=> $nowExpr,
			'lastModifiedBy'	=> $params['lastModifiedBy']
		);
		
		try {
			$this->insert($data);
			$idPhoto = $this->_db->lastInsertId();
		} catch (Exception $e) {
			throw $e;
		}
		
		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
		return $idPhoto;
	}
	
	//
	// READ
	//
	
	public function getAllPhotos()
	{
		$query = $this->select();
		
		try {
			$photoRowset = $this->fetchAll($query);
			
			return $photoRowset;
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	public function getCountries($visible=true)
	{
		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);

		$query = $this->select()
		              ->where('visible = ?', (($visible) ? '1' : '0'))
					  ->order('idCountry');

		try {
			$countryRowset = $this->fetchAll($query);
			
			return $countryRowset;
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function getAllPhotosByPropertyId($idProperty, $visible=true)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

		$query = $this->select()
					  ->where('idProperty = ?', $idProperty)
					  ->where('visible = ?', $visible)
					  ->order('displayPriority');
		try {
			$resultSet = $this->fetchAll($query);
		} catch (Exception $e) {
			$profilerQuery = $this->_profiler->getLastQueryProfile();
			$lastQuery = $profilerQuery->getQuery();
			$params = $profilerQuery->getQueryParams();
			$this->_logger->log(__METHOD__ . ' Exception thrown  ' . $e, Zend_Log::ERR);
			$this->_logger->log(__METHOD__ . ' lastQuery  ' . $lastQuery, Zend_Log::ERR);
			$this->_logger->log(__METHOD__ . ' params  ' . implode(',', $params), Zend_Log::ERR);
			$this->_logger->table($table);
			throw $e;
		}

		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
	
		return $resultSet;
	}
	
	public function getPrimaryPhotoByPropertyId($idProperty)
	{
		$query = $this->select()
				      ->where('idProperty = ?', $idProperty)
					  ->order('displayPriority ASC')
					  ->limit(1);
		try {
			$photoRow = $this->fetchRow($query);
			
			if ($photoRow) {
				return $photoRow;
			} else {
				return null;
			}
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	
	public function getPhotoByPhotoId($idPhoto)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		
		$query = $this->select()
					  ->where('idPhoto = ?', $idPhoto)
					  ->limit(1); 
		try {
			$row = $this->fetchRow($query);
		} catch (Exception $e) {
			throw $e;
		}
				
		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
		return $row;  
	}
	
	public function getPhotoByPhotoIdAndPropertyID($idProperty, $idPhoto)
	{
		$query = $this->select()
					  ->where('idProperty = ?', $idProperty)
					  ->where('idPhoto = ?', $idPhoto)
					  ->limit(1);
		try {
			$row = $this->fetchRow($query);
		} catch (Exception $e) {
			throw $e;
		}
		
		return $row;
	}
	
	//
	// UPDATE
	//
	
	public function fixBrokenDisplayPriorities($idProperty)
	{
		$query = $this->select()
					  ->where('displayPriority = ?', 0)
					  ->order('idPhoto ASC');
		try {
			$photoRowset = $this->fetchAll($query);
			
			foreach ($photoRowset as $row) {
				$query = $this->select()
							  ->from($this, array('idPhoto', new Zend_Db_Expr('MAX(displayPriority) + 1 AS nextpri')))
					          ->where('idProperty = ?', $idProperty);
						  		
				$maxrow = $this->fetchRow($query);
				$nextDisplayPriority = $maxrow->nextpri;
				
				$data = array (
					'displayPriority' => $nextDisplayPriority
				);
				$adapter = $this->getAdapter();
				$where =  $this->getAdapter()->quoteInto('idPhoto = ?', $row->idPhoto)
				       . ' ' . $this->getAdapter()->quoteInto('AND displayPriority = ?', 0);
				$query = $this->update($data, $where);
			}
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	public function updateMovePosition($idProperty, $idPhoto, $moveDirection)
	{
		$query = $this->select('displayPriority')
					  ->where('idProperty = ?', $idProperty)
					  ->where('idPhoto = ?', $idPhoto);
					  
		try {
			$row = $this->fetchRow($query);
		} catch (Exception $e) {
			throw $e;
		}
		$currentDisplayPriority = $row->displayPriority;
		
		if ($moveDirection == 'up') {
			$compare 	= '<';
			$direction	= 'DESC';
		} else {
			$compare 	= '>';
			$direction	= 'ASC'; 
		}
		
		$query = $this->select(array('idPhoto', 'displayPriority'))
					  ->where('displayPriority ' . $compare . ' ?', $currentDisplayPriority)
					  ->where('idProperty = ?', $idProperty)
					  ->order('displayPriority ' . $direction)
					  ->limit(1);
		try {
			$row = $this->fetchRow($query);
		} catch (Exception $e) {
			throw $e;
		}
		
		$switchDisplayPriority 	= $row->displayPriority;
		$switchIdPhoto			= $row->idPhoto;
		
		
		$this->_db->beginTransaction();
		
		try {
			$data = array (
				'displayPriority' => 0
			);
			$where = $this->getAdapter()->quoteInto('idPhoto = ?', $idPhoto);
			$query = $this->update($data, $where);
	
			$data = array (
				'displayPriority' => $currentDisplayPriority
			);
			$where = $this->getAdapter()->quoteInto('idPhoto = ?', $switchIdPhoto);
			$query = $this->update($data, $where);
			
			$data = array (
				'displayPriority' => $switchDisplayPriority
			);
			$where = $this->getAdapter()->quoteInto('idPhoto = ?', $idPhoto);
			$query = $this->update($data, $where);
			
			$this->_db->commit();
		} catch (Exception $e) {
			$this->_db->rollBack();
			throw $e;
		}
	}
	
	//
	// DELETE
	//
}
