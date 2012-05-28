<?php
/**
 * Table data gateway implementation of OAuthGrants
 *
 * @author Andrew Fusniak <andy@greycatmedia.co.uk>
 * @version 1.0.0
 * @copyright Copyright (c) 2012, Andrew Fusniak
 * @package Common_Resource
 */
class Common_Resource_OAuthGrants extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'OAuthGrants';
    protected $_primary = 'idOAuthGrant';
    protected $_rowClass = 'Common_Resource_OAuthGrants_Row';
    protected $_rowsetClass = 'Common_Resource_OAuthGrants_Rowset';
    protected $_referenceMap = array(
        'clientId' => array(
            'columns'       => array('clientId'),
            'refTableClass' => 'Common_Resource_OAuthApps'
        )
    );

    /**
     * Add a new grant to the system
     *
     * @param string $clientId   the client id identifier for the app
     * @param string code        the cryptographic grant code to later be exchanged for an access token
     * @param string redirectUri the redirect URI after the grant is exchanged for an access token
     *
     * @return int the last insert id row of the table
     */
    public function addGrant($clientId, $code, $redirectUri)
    {
        $nullExpr = new Zend_Db_Expr('NULL');
        $nowExpr  = new Zend_Db_Expr('NOW()');

        $data = array(
            'idOAuthGrant' => $nullExpr,
            'clientId'     => $clientId,
            'code'         => $code,
            'redirectUri'  => $redirectUri,
            'expiry'       => new Zend_Db_Expr('NOW() + INTERVAL 10 MINUTE'),
            'added'        => $nowExpr,
            'updated'      => $nowExpr
        );

        try {
            $this->insert($data);

            return $this->_db->lastInsertId();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Gets an authorization grant by it's auth grant code
     *
     * @param string $code the auth grant code to retreive
     * @return Common_Resource_OAuthGrants_Row the auth grant row object
     */
    public function getAuthGrant($code)
    {
        try {
            $query = $this->select()
                          ->where('code=?', $code)
                          ->limit(1);

            $oAuthGrantRow = $this->fetchRow($query);

            return $oAuthGrantRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the authorization grant for a given client id
     *
     * Only retrieves the grant if it hasn't already expired
     * otherwise it will be removed by the house-keeping
     * python scripts
     *
     * @param string clientId the client id identifier
     * @return Common_Resource_OAuthGrants_Row the grant table row
     */
    public function getAuthGrantByClientId($clientId)
    {
        try {
            $query = $this->select()
                          ->where('clientId=?', $clientId)
                          ->where('expiry > ?', new Zend_Db_Expr('NOW()'))
                          ->limit(1);

            $oAuthGrantRow = $this->fetchRow($query);

            return $oAuthGrantRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Revoke all authorization grants for the given client id
     *
     * @param string $clientId the client id identifier for the app
     */
    public function revokeExistingGrants($clientId)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $whereClause = $db->quoteInto('clientId=?', $clientId);

        try {
            $db->delete($this->_name, $whereClause);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
