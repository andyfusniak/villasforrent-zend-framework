<?php
class Common_Resource_ApiAccount extends Vfr_Model_Resource_Db_Table_Abstract
{
	protected $_name = 'ApiAccounts';
	protected $_primary = 'idApiAccount';
	protected $_rowClass = 'Common_Resource_ApiAccount_Row';
	protected $_rowsetClass = 'Common_Resource_ApiAccount_Rowset';
    
    public function hasAuthorisation($apiKey)
    {
        $query = $this->select()
                      ->where('apikey=?', $apiKey)
                      ->limit(1);
                      
        try {
            $result = $this->fetchRow($query);
            
            if ($result)
                return true;
            else
                return false;
        } catch (Exception $e) {
            throw $e;
        }
    }
}