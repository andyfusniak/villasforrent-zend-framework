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
    
    public function getAllLocations()
    {
        $query = $this->select()
                      ->order('idLocation');
        try {
            $locationRowset = $this->fetchAll($query);
            
            return $locationRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    public function getAllLocationsIn($idParent=null)
    {
        $query = $this->select()
                      ->where('idParent = ?', $idParent)
                      ->order('idLocation');
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
    
    //
    // DELETE
    //
}