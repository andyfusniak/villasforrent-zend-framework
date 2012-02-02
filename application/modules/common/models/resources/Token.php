<?php
class Common_Resource_Token extends Vfr_Model_Resource_Db_Table_Abstract
{
    const TOKEN_TYPE_ADVERTISER_RESET_PASSWORD = 'A-RESET';
    const TOKEN_TYPE_ADVERTISER_EMAIL_CONFIRM  = 'A-ECONFIRM';
    const TOKEN_TYPE_ADVERTISER_EMAIL_CHANGE   = 'A-CHANGE';

    protected $_name = 'Tokens';
    protected $_primary = 'idToken';
    protected $_rowClass = 'Common_Resource_Token_Row';
    protected $_rowsetClass = 'Common_Resource_Token_Rowset';
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
            'idToken'      => new Zend_Db_Expr('null'),
            'idAdvertiser' => $idAdvertiser,
            'type'         => self::TOKEN_TYPE_ADVERTISER_RESET_PASSWORD,
            'token'        => $token,
            'expires'      => new Zend_Db_Expr('NOW() + INTERVAL 2 DAY'),
            'added'        => new Zend_Db_Expr('NOW()')
        );
        
        try {
            $this->insert($data);
            
            //$idToken= $this->_db->lastInsertId();
            
            //return $idToken;
        } catch (Exception $e) {
            throw $e;
        }
    }
	
	public function addEmailConfirmation($idAdvertiser, $token)
	{
		$data = array (
            'idToken'           => new Zend_Db_Expr('null'),
            'idAdvertiser'      => $idAdvertiser,
            'type'              => self::TOKEN_TYPE_ADVERTISER_EMAIL_CONFIRM,
            'token'             => $token,
            'expires'           => new Zend_Db_Expr('NOW() + INTERVAL 2 DAY'),
            'added'             => new Zend_Db_Expr('NOW()')
        );
        
        try {
            $this->insert($data);
        } catch (Exception $e) {
            throw $e;
        }
	}
    
    /**
     * @param idAdvertiser int primary key
     */
    public function addEmailChange($idAdvertiser, $token)
    {
        $data = array (
            'idToken'      => new Zend_Db_Expr('null'),
            'idAdvertiser' => $idAdvertiser,
            'type'         => self::TOKEN_TYPE_ADVERTISER_EMAIL_CHANGE,
            'token'        => $token,
            'expires'      => new Zend_Db_Expr('NOW() + INTERVAL 2 DAY'),
            'added'        => new Zend_Db_Expr('NOW()')
        );
        
        try {
            $this->insert($data);
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    public function voidOldToken($idAdvertiser, $type, $keepToken=null)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $whereClause = $db->quoteInto('idAdvertiser=?', $idAdvertiser);
        $whereClause .= $db->quoteInto(' AND type=?', $type);
        
        if ($keepToken)
            $whereClause .= $db->quoteInto(' AND token != ?', $keepToken);
        
        $db->delete($this->_name, $whereClause);
    }
    
    //
    // READ
    //
    
    public function getChangeEmailTokenByAdvertiserId($idAdvertiser)
    {
        // note there 'should' be only one token and we assume this
        
        $query = $this->select()
                      ->where('idAdvertiser=?', $idAdvertiser)
                      ->limit(1);
        try {
            $tokenRow = $this->fetchRow($query);
            
            return $tokenRow;
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    public function getAdvertiserResetDetailsByToken($token)
    {
        $query = $this->select()
                      ->where('type=?', self::TOKEN_TYPE_ADVERTISER_RESET_PASSWORD)
                      ->where('token=?', $token);
        try {
            $tokenRow = $this->fetchRow($query);
            
            return $tokenRow;
        } catch (Exception $e) {
            throw $e;
        }
    }
	
	public function getAdvertiserEmailConfirmationDetailsByToken($token)
	{
		$query = $this->select()
				      ->where('type=?', self::TOKEN_TYPE_ADVERTISER_EMAIL_CONFIRM)
                      ->where('token=?', $token);
        try {
            $tokenRow = $this->fetchRow($query);
            
            return $tokenRow;
        } catch (Exception $e) {
            throw $e;
        }
	}
    
    public function getAdvertiserChangeEmailAddressConfirmationDetailsByToken($token)
    {
        $query = $this->select()
                      ->where('type=?', self::TOKEN_TYPE_ADVERTISER_EMAIL_CHANGE)
                      ->where('token=?', $token);
        try {
            $tokenRow = $this->fetchRow($query);
            
            return $tokenRow;
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    public function getEmailConfirmatinTokenByIdAdvertiser($idAdvertiser)
    {
        $query = $this->select()
                      ->where('type=?', self::TOKEN_TYPE_ADVERTISER_EMAIL_CONFIRM)
                      ->where('idAdvertiser=?', $idAdvertiser);
        try {
            $tokenRow = $this->fetchRow($query);
            
            return $tokenRow;
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
