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
    protected $_name = 'OAuthTokens';
    protected $_primary = 'idOAuthGrants';
    protected $_rowClass = 'Common_Resource_OAuthTokens_Row';
    protected $_rowsetClass = 'Common_Resource_OAuthTokens_Rowset';
    protected $_referenceMap = array (
        'clientId' => array (
            'columns'       => array ('clientId'),
            'refTableClass' => 'Common_Resource_OAuthApps'
        )
    );

    /**
     * Issues a new oauth access token for a given client id
     *
     * @param string clientId         the client id identifier for the application
     * @param string oAuthAccessToken 64-byte bearer token
     * @param int the last insert id of the database row added
     */
    public function addOAuthAccessToken($clientId, $oAuthAccessToken)
    {
        $nullExpr = new Zend_Db_Expr('NULL');
        $nowExpr  = new Zend_Db_Expr('NOW()');

        $data = array (
            'idOAuthAccessToken' => $nullExpr,
            'clientId'           => $clientId,
            'oAuthAccessToken'   => $oAuthAccessToken,
            'added'              => $nowExpr,
            'updated'            => $nowExpr
        );

        try {
            $this->insert($data);

            return $this->_db->lastInsertId();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Retrieves the token details for a given oauth access token
     *
     * @param string $oAuthAcessToken the oauth access bearer token
     * @return Common_Resource_OAuthAccessToken_Row|null the access token data row object
     */
    public function getAccessToken($oAuthAccessToken)
    {
        try {
            $query = $this->select()
                          ->where('oAuthAccessToken=?', $oAuthAccessToken)
                          ->limit(1);

            $oAuthAccessTokenRow = $this->fetchRow($query);

            return $oAuthAccessTokenRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Retrieve an oauth access token for a given client id app
     *
     * @param string clientId the client identifier for the app
     * @return Common_Resource_OAuthAccessToken_Row the database row
     */
    public function getOAuthAccessTokenByClientId($clientId)
    {
        try {
            $query = $this->select()
                          ->where('clientId=?', $clientId)
                          ->limit(1);

            $oAuthAcessTokenRow = $this->fetchRow($query);

            return $oAuthAcessTokenRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete an oauth access token for a given client id
     *
     * @param string clientId the client identifier
     */
    public function deleteOAuthAccessTokenByClientId($clientId)
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
