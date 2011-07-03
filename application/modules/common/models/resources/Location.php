<?php
class Common_Resource_Location extends Vfr_Model_Resource_Db_Table_Abstract implements Common_Resource_Location_Interface
{
    protected $_name = 'Locations';
    protected $_primary = 'idLocation';
    protected $_rowClass = 'Common_Resource_Location_Row';
    protected $_rowsetClass = 'Common_Resource_Location_Rowset';
    
    
    //
    // CREATE
    //
    
    public function addLocation($idParent=null, $url, $totalVisible=null, $total=null, $idProperty=null)
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
            'added'   			=>  new Zend_Db_Expr('NOW()'),
			'updated' 			=>  new Zend_Db_Expr('NOW()')
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
					  ->where('idLocation = ?', $idLocation)
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
					  ->where('idProperty is null')
                      ->order('idLocation');
					  
		if ($depth)
			$query->where('depth = ?', $depth);
			
        try {
            $locationRowset = $this->fetchAll($query);
            
            return $locationRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    public function getAllLocationsIn($idParent=null)
    {
        $query = $this->select();
		if ($idParent == null)
			$query->where('idParent IS NULL');
        else
			$query->where('idParent = ?', $idParent);
        
		$query->order('idLocation');
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
	
    //
    // DELETE
    //
}