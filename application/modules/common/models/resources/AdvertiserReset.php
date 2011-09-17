<?php
class Common_Resource_AdvertiserReset extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'AdvertisersReset';
    protected $_primary = 'idAdvertiserReset';
    protected $_rowClass = 'Common_Resource_AdvertiserReset_Row';
    protected $_rowsetClass = 'Common_Resource_Advertiser_Rowset';
    protected $_dependantTables = array ('Advertisers');
    protected $_referenceMap = array (
		'idAdvertiser' => array (
			'columns' => array('idAdvertiser'),
			'refTableClass' => 'Common_Resource_Advertiser'
		)
    );
    
    //
    // CREATE
    //
    
    public function addPasswordReset($idAdvertiser, $token)
    {
        $data = array (
            'idAdvertiserReset' => new Zend_Db_Expr('null'),
            'idAdvertiser'      => $idAdvertiser,
            'token'             => $token,
            'expires'           => new Zend_Db_Expr('NOW() + INTERVAL 2 DAY'),
            'added'             => new Zend_Db_Expr('NOW()')
        );
        
        try {
            $this->insert($data);
            
            //$idAdvertiserReset = $this->_db->lastInsertId();
            
            //return $idAdvertiserReset;
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    public function voidOldTokens($idAdvertiser, $keepToken)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $whereClause = $db->quoteInto('idAdvertiser=?', $idAdvertiser);
        $whereClause .= $db->quoteInto(' AND token != ?', $keepToken);
        
        $db->delete($this->_name, $whereClause);
    }
    
    //
    // READ
    //
    
    public function getAdvertiserResetDetailsByToken($token)
    {
        $query = $this->select()
                      ->where('token=?', $token);
        try {
            $advertiserResetRow = $this->fetchRow($query);
            
            return $advertiserResetRow;
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
