<?php
class Common_Resource_Location
	extends Vfr_Model_Resource_Db_Table_Abstract
	implements Common_Resource_Location_Interface
{
    protected $_name = 'Locations';
    protected $_primary = 'idLocation';
    protected $_rowClass = 'Common_Resource_Location_Row';
    protected $_rowsetClass = 'Common_Resource_Location_Rowset';
    
	const ROOT_NODE_ID = 321;
    
    //
    // CREATE
    //
    
    public function addLocation($idParent=null, $url, $totalVisible=null, $total=null)
    {
		//
		// needs modifying for nested set DB structure
		//
        $nowExpr  = new Zend_Db_Expr('NOW()');
		$nullExpr = new Zend_Db_Expr('NULL');
        
        $data = array (
            'idLocation'    => $nullExpr,
            'idParent'      => $idParent,
            'url'           => $url,
            'added'   		=>  new Zend_Db_Expr('NOW()'),
			'updated' 		=>  new Zend_Db_Expr('NOW()')
        );
        
        try {
            $this->insert($data);
        } catch (Exception $e) {
            throw $e;
        }
    }
	
	public function rebuildTree($idParent, $lt)
	{
		// the right value of this node is the left value + 1
		$rt = $lt + 1;
		
		// get all children of this node
		$childrenRowset = $this->getAllLocationsIn($idParent);
		
		foreach ($childrenRowset as $row) {
			// recursively execute this function for each child of this node
			// $rt is the current right value, which is
			// incremented by the rebuildTree function
			$rt = $this->rebuildTree($row->idLocation, $rt);
		}
		
		// we've got the left value, and now that we've processed
		// the children of this node we also know the right value
		$this->updateLeftRightOnNode($idParent, $lt, $rt);
	
		// return the right value of this node + 1
		return $rt + 1;
	}
    
    //
    // READ
    //
    
    public function lookup($url)
    {
        $query = $this->select()
                      ->where('url = ?', $url)
                      ->limit(1);
        try {
            $locationRow = $this->fetchRow($query);
            
            return $locationRow;
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    public function getLocationByPk($idLocation)
    {
        $query = $this->select()
					  ->where('idLocation=?', $idLocation)
					  ->limit(1);
		try {
			$locationRow = $this->fetchRow($query);
			
			return $locationRow;
		} catch (Exception $e) {
			throw $e;
		}
    }
    
    public function getAllLocations($depth=null)
    {
        $query = $this->select()
                      ->order('lt');
					  
		if ($depth)
			$query->where('depth = ?', $depth);
			
        try {
            $locationRowset = $this->fetchAll($query);
            
            return $locationRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    public function getAllLocationsIn($idLocation=null)
    {
        $query = $this->select();
		if ($idLocation == null)
			$query->where('idParent IS NULL');
        else
			$query->where('idParent = ?', $idLocation);
        
		$query->order('idLocation');
        try {
            $locationRowset = $this->fetchAll($query);
            
            return $locationRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }
	
	public function getPathFromRootNode($idLocation)
	{        
        $query = $this->select($this::SELECT_WITHOUT_FROM_PART)
                      ->reset(Zend_Db_Select::COLUMNS)
                      ->from(
                          array ('ancestor' => $this->_name),
                          array (
                              'ancestor.idLocation',
                              'ancestor.rowname',
                              'ancestor.url',
                              'ancestor.totalVisible'
                          )
                      )
                      ->from(array ('child' => $this->_name), array())
                      ->where('child.lt BETWEEN ancestor.lt AND ancestor.rt')
                      ->where('child.idLocation = ?', $idLocation)
                      ->where('ancestor.url != ?', '')
                      ->group('ancestor.idLocation');
        try {
            $locationRowset = $this->fetchAll($query);
            
            return $locationRowset;
        } catch (Exception $e) {
            throw $e;
        }
	}
    
    
    //
    // UPDATE
    //
	public function updateLeftRightOnNode($idLocation, $lt, $rt)
	{
		$params = array (
			'lt' => (int) $lt,
			'rt' => (int) $rt
		);
		
		$where = $this->getAdapter()->quoteInto('idLocation = ?', $idLocation);
		
		try {
            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
	}
	
	private function _makeGapForNewEntry($idParent)
	{
		$exprRt = new Zend_Db_Expr('rt + 2');
		$dataRt = array (
			'rt' => $exprRt
		);
		
		$exprLt = new Zend_Db_Expr('lt + 2');
		$dataLt = array (
			'lt' => $exprLt
		);
		
		try {
			$whereClauseRt = $this->getAdapter()->quoteInto('rt >= ?', $idParent);
            $queryRt = $this->update($dataRt, $whereClauseRt);
			
			$whereClauseLt = $this->getAdapter()->quoteInto('lt > ?', $idParent);
			$queryLt = $this->update($dataLt, $whereClauseLt);
        } catch (Exception $e) {
            throw $e;
		}
	}

	
    //
    // DELETE
    //
}