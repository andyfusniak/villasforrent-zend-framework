<?php
class Common_Resource_Token extends Vfr_Model_Resource_Db_Table_Abstract
{
    const TOKEN_TYPE_ADVERTISER_RESET_PASSWORD = 'A-RESET';
    const TOKEN_TYPE_ADVERTISER_EMAIL_CONFIRM  = 'A-ECONFIRM';
    const TOKEN_TYPE_ADVERTISER_EMAIL_CHANGE   = 'A-CHANGE';

    const TOKEN_TYPE_USER_RESET_PASSWORD = 'U-RESET';
    const TOKEN_TYPE_USER_EMAIL_CONFIRM  = 'U-ECONFIRM';
    const TOKEN_TYPE_USER_EMAIL_CHANGE   = 'U-CHANGE';

    const TYPE_ADVERTISER = 1;
    const TYPE_USER = 2;

    protected $_name = 'Tokens';
    protected $_primary = 'idToken';
    protected $_rowClass = 'Common_Resource_Token_Row';
    protected $_rowsetClass = 'Common_Resource_Token_Rowset';
    protected $_dependantTables = array('Advertisers', 'Users');
    protected $_referenceMap = array(
        'idAdvertiser' => array(
            'columns' => array('idAdvertiser'),
            'refTableClass' => 'Common_Resource_Advertiser'
        ),
        'idUser' => array(
            'columns' => array('idUser'),
            'refTableClass' => 'Common_Resource_User'
        )
    );

    /**
     * add a password reset token for an advertiser or user
     *
     * @param int $id the advertiser or user id
     * @param string $token the token value
     * @param int $type of token either advertiser or user
     */
    public function addPasswordReset($id, $token, $type=self::TYPE_ADVERTISER)
    {
        $nullExpr = new Zend_Db_Expr('null');

        switch ($type) {
            case self::TYPE_ADVERTISER:
                $data = array(
                    'idToken'      => $nullExpr,
                    'idAdvertiser' => $id,
                    'idUser'       => $nullExpr,
                    'type'         => self::TOKEN_TYPE_ADVERTISER_RESET_PASSWORD,
                    'token'        => $token,
                    'expires'      => new Zend_Db_Expr('NOW() + INTERVAL 2 DAY'),
                    'added'        => new Zend_Db_Expr('NOW()')
                );
                break;
            case self::TYPE_USER:
                $data = array(
                    'idToken'      => $nullExpr,
                    'idAdvertiser' => $nullExpr,
                    'idUser'       => $id,
                    'type'         => self::TOKEN_TYPE_USER_RESET_PASSWORD,
                    'token'        => $token,
                    'expires'      => new Zend_Db_Expr('NOW() + INTERVAL 2 DAY'),
                    'added'        => new Zend_Db_Expr('NOW()')
                );
                break;
        }

        try {
            $this->insert($data);

            //$idToken= $this->_db->lastInsertId();
            //return $idToken;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Add an advertiser email confirmation token to the DB
     *
     * @param int $id the id of the advertiser
     * @param string $token the token to be used to confirm
     * @param int $type of token either advertiser or user
     */
    public function addEmailConfirmation($id, $token, $type=self::TYPE_ADVERTISER)
    {
        $nullExpr = new Zend_Db_Expr('null');

        switch ($type) {
            case self::TYPE_ADVERTISER:
                $data = array(
                    'idToken'           => $nullExpr,
                    'idAdvertiser'      => $id,
                    'idUser'            => $nullExpr,
                    'type'              => self::TOKEN_TYPE_ADVERTISER_EMAIL_CONFIRM,
                    'token'             => $token,
                    'expires'           => new Zend_Db_Expr('NOW() + INTERVAL 2 DAY'),
                    'added'             => new Zend_Db_Expr('NOW()')
                );
                break;
            case self::TYPE_USER:
                $data = array(
                    'idToken'           => $nullExpr,
                    'idAdvertiser'      => $nullExpr,
                    'idUser'            => $id,
                    'type'              => self::TOKEN_TYPE_USER_EMAIL_CONFIRM,
                    'token'             => $token,
                    'expires'           => new Zend_Db_Expr('NOW() + INTERVAL 2 DAY'),
                    'added'             => new Zend_Db_Expr('NOW()')
                );
                break;
        }

        try {
            $this->insert($data);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param int $id the id of the advertiser
     * @param string $token the token to be used to confirm
     * @param int $type of token either advertiser or user
     */
    public function addEmailChange($id, $token, $type=self::TYPE_ADVERTISER)
    {
        $nullExpr = new Zend_Db_Expr('null');

        switch ($type) {
            case self::TYPE_ADVERTISER:
                $data = array(
                    'idToken'      => new Zend_Db_Expr('null'),
                    'idAdvertiser' => $id,
                    'idUser'       => $nullExpr,
                    'type'         => self::TOKEN_TYPE_ADVERTISER_EMAIL_CHANGE,
                    'token'        => $token,
                    'expires'      => new Zend_Db_Expr('NOW() + INTERVAL 2 DAY'),
                    'added'        => new Zend_Db_Expr('NOW()')
                );
                break;
            case self::TYPE_USER:
                $data = array(
                    'idToken'      => new Zend_Db_Expr('null'),
                    'idAdvertiser' => $nullExpr,
                    'idUser'       => $id,
                    'type'         => self::TOKEN_TYPE_USER_EMAIL_CHANGE,
                    'token'        => $token,
                    'expires'      => new Zend_Db_Expr('NOW() + INTERVAL 2 DAY'),
                    'added'        => new Zend_Db_Expr('NOW()')
                );
                break;
        }

        try {
            $this->insert($data);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function voidOldToken($id, $type, $keepToken=null, $mode=self::TYPE_ADVERTISER)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        switch ($mode) {
            case self::TYPE_ADVERTISER:
                $whereClause = $db->quoteInto('idAdvertiser=?', $id);
                //$whereClause .= $db->quoteInto('idUser=?', null);
                break;
            case self::TYPE_USER:
                $whereClause = $db->quoteInto('idUser=?', $id);
                //$whereClause .= $db->quoteInto('idAdvertiser=?', null);
                break;
        }

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

    public function getChangeEmailTokenByUserId($idUser)
    {
        $query = $this->select()
                      ->where('idUser=?', $idUser)
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

    public function getUserResetDetailsByToken($token)
    {
        $query = $this->select()
                      ->where('type=?', self::TOKEN_TYPE_USER_RESET_PASSWORD)
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

    /**
     * @param string $token
     * @return Common_Resource_Token_Row
     */
    public function getUserEmailConfirmationDetailsByToken($token)
    {
        $query = $this->select()
                      ->where('type=?', self::TOKEN_TYPE_USER_EMAIL_CONFIRM)
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

    public function getUserChangeEmailAddressConfirmationDetailsByToken($token)
    {
        $query = $this->select()
                      ->where('type=?', self::TOKEN_TYPE_USER_EMAIL_CHANGE)
                      ->where('token=?', $token);
        try {
            $tokenRow = $this->fetchRow($query);

            return $tokenRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getEmailConfirmationTokenByAdvertiserId($idAdvertiser)
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

    public function getEmailConfirmationTokenByUserId($idUser)
    {
        $query = $this->select()
                      ->where('type=?', self::TOKEN_TYPE_USER_EMAIL_CONFIRM)
                      ->where('idUser=?', $idUser);
        try {
            $tokenRow = $this->fetchRow($query);

            return $tokenRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

}
