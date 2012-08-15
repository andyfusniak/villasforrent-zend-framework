<?php
class Common_Resource_Token extends Vfr_Model_Resource_Db_Table_Abstract
{
    const TOKEN_TYPE_ADVERTISER_RESET_PASSWORD = 'A-RESET';
    const TOKEN_TYPE_ADVERTISER_EMAIL_CONFIRM  = 'A-ECONFIRM';
    const TOKEN_TYPE_ADVERTISER_EMAIL_CHANGE   = 'A-CHANGE';

    const TOKEN_TYPE_MEMBER_RESET_PASSWORD = 'M-RESET';
    const TOKEN_TYPE_MEMBER_EMAIL_CONFIRM  = 'M-ECONFIRM';
    const TOKEN_TYPE_MEMBER_EMAIL_CHANGE   = 'M-CHANGE';

    const TYPE_ADVERTISER = 1;
    const TYPE_MEMBER = 2;

    protected $_name = 'Tokens';
    protected $_primary = 'idToken';
    protected $_rowClass = 'Common_Resource_Token_Row';
    protected $_rowsetClass = 'Common_Resource_Token_Rowset';
    protected $_dependantTables = array('Advertisers', 'Members');
    protected $_referenceMap = array(
        'idAdvertiser' => array(
            'columns' => array('idAdvertiser'),
            'refTableClass' => 'Common_Resource_Advertiser'
        ),
        'idMember' => array(
            'columns' => array('idMember'),
            'refTableClass' => 'Common_Resource_Member'
        )
    );

    /**
     * add a password reset token for an advertiser or member
     *
     * @param int $id the advertiser or member id
     * @param string $token the token value
     * @param int $type of token either advertiser or member
     */
    public function addPasswordReset($id, $token, $type=self::TYPE_ADVERTISER)
    {
        $nullExpr = new Zend_Db_Expr('null');

        switch ($type) {
            case self::TYPE_ADVERTISER:
                $data = array(
                    'idToken'      => $nullExpr,
                    'idAdvertiser' => $id,
                    'idMember'     => $nullExpr,
                    'type'         => self::TOKEN_TYPE_ADVERTISER_RESET_PASSWORD,
                    'token'        => $token,
                    'expires'      => new Zend_Db_Expr('NOW() + INTERVAL 2 DAY'),
                    'added'        => new Zend_Db_Expr('NOW()')
                );
                break;
            case self::TYPE_MEMBER:
                $data = array(
                    'idToken'      => $nullExpr,
                    'idAdvertiser' => $nullExpr,
                    'idMember'     => $id,
                    'type'         => self::TOKEN_TYPE_MEMBER_RESET_PASSWORD,
                    'token'        => $token,
                    'expires'      => new Zend_Db_Expr('NOW() + INTERVAL 2 DAY'),
                    'added'        => new Zend_Db_Expr('NOW()')
                );
                break;
        }

        try {
            $this->insert($data);

            $idToken= $this->_db->lastInsertId();
            return $idToken;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Add an advertiser email confirmation token to the DB
     *
     * @param int $id the id of the advertiser
     * @param string $token the token to be used to confirm
     * @param int $type of token either advertiser or member
     */
    public function addEmailConfirmation($id, $token, $type=self::TYPE_ADVERTISER)
    {
        $nullExpr = new Zend_Db_Expr('null');

        switch ($type) {
            case self::TYPE_ADVERTISER:
                $data = array(
                    'idToken'           => $nullExpr,
                    'idAdvertiser'      => $id,
                    'idMember'          => $nullExpr,
                    'type'              => self::TOKEN_TYPE_ADVERTISER_EMAIL_CONFIRM,
                    'token'             => $token,
                    'expires'           => new Zend_Db_Expr('NOW() + INTERVAL 2 DAY'),
                    'added'             => new Zend_Db_Expr('NOW()')
                );
                break;
            case self::TYPE_MEMBER:
                $data = array(
                    'idToken'           => $nullExpr,
                    'idAdvertiser'      => $nullExpr,
                    'idMember'          => $id,
                    'type'              => self::TOKEN_TYPE_MEMBER_EMAIL_CONFIRM,
                    'token'             => $token,
                    'expires'           => new Zend_Db_Expr('NOW() + INTERVAL 2 DAY'),
                    'added'             => new Zend_Db_Expr('NOW()')
                );
                break;
        }

        try {
            $this->insert($data);

            $idToken= $this->_db->lastInsertId();
            return $idToken;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param int $id the id of the advertiser
     * @param string $token the token to be used to confirm
     * @param int $type of token either advertiser or member
     */
    public function addEmailChange($id, $token, $type = self::TYPE_ADVERTISER)
    {
        $nullExpr = new Zend_Db_Expr('null');

        switch ($type) {
            case self::TYPE_ADVERTISER:
                $data = array(
                    'idToken'      => new Zend_Db_Expr('null'),
                    'idAdvertiser' => $id,
                    'idMember'     => $nullExpr,
                    'type'         => self::TOKEN_TYPE_ADVERTISER_EMAIL_CHANGE,
                    'token'        => $token,
                    'expires'      => new Zend_Db_Expr('NOW() + INTERVAL 2 DAY'),
                    'added'        => new Zend_Db_Expr('NOW()')
                );
                break;
            case self::TYPE_MEMBER:
                $data = array(
                    'idToken'      => new Zend_Db_Expr('null'),
                    'idAdvertiser' => $nullExpr,
                    'idMember'     => $id,
                    'type'         => self::TOKEN_TYPE_MEMBER_EMAIL_CHANGE,
                    'token'        => $token,
                    'expires'      => new Zend_Db_Expr('NOW() + INTERVAL 2 DAY'),
                    'added'        => new Zend_Db_Expr('NOW()')
                );
                break;
        }

        try {
            $this->insert($data);

            $idToken= $this->_db->lastInsertId();
            return $idToken;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function voidOldToken($id, $type, $keepToken = null, $mode = self::TYPE_ADVERTISER)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        switch ($mode) {
            case self::TYPE_ADVERTISER:
                $whereClause = $db->quoteInto('idAdvertiser = ?', $id);
                $whereClause .= $db->quoteInto('idMember = ?', null);
                break;
            case self::TYPE_MEMBER:
                $whereClause = $db->quoteInto('idMember = ?', $id);
                $whereClause .= $db->quoteInto('idAdvertiser = ?', null);
                break;
        }

        $whereClause .= $db->quoteInto(' AND type = ?', $type);

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
                      ->where('idAdvertiser = ?', $idAdvertiser)
                      ->limit(1);
        try {
            $tokenRow = $this->fetchRow($query);

            return $tokenRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getChangeEmailTokenByMemberId($idMember)
    {
        $query = $this->select()
                      ->where('idMember = ?', $idMember)
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

    public function getMemberResetDetailsByToken($token)
    {
        $query = $this->select()
                      ->where('type = ?', self::TOKEN_TYPE_MEMBER_RESET_PASSWORD)
                      ->where('token = ?', $token);
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
    public function getMemberEmailConfirmationDetailsByToken($token)
    {
        $query = $this->select()
                      ->where('type = ?', self::TOKEN_TYPE_MEMBER_EMAIL_CONFIRM)
                      ->where('token = ?', $token);
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
                      ->where('type = ?', self::TOKEN_TYPE_ADVERTISER_EMAIL_CHANGE)
                      ->where('token = ?', $token);
        try {
            $tokenRow = $this->fetchRow($query);

            return $tokenRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getMemberChangeEmailAddressConfirmationDetailsByToken($token)
    {
        $query = $this->select()
                      ->where('type = ?', self::TOKEN_TYPE_MEMBER_EMAIL_CHANGE)
                      ->where('token = ?', $token);
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
                      ->where('type = ?', self::TOKEN_TYPE_ADVERTISER_EMAIL_CONFIRM)
                      ->where('idAdvertiser = ?', $idAdvertiser);
        try {
            $tokenRow = $this->fetchRow($query);

            return $tokenRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getEmailConfirmationTokenByMemberId($idMember)
    {
        $query = $this->select()
                      ->where('type = ?', self::TOKEN_TYPE_MEMBER_EMAIL_CONFIRM)
                      ->where('idMember = ?', $idMember);
        try {
            $tokenRow = $this->fetchRow($query);

            return $tokenRow;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
