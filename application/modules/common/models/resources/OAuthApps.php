<?php
/**
 * Table data gateway implementation of OAuthApps
 *
 * @author Andrew Fusniak <andy@greycatmedia.co.uk>
 * @version 1.0.0
 * @copyright Copyright (c) 2012, Andrew Fusniak
 * @package Common_Resource
 */
class Common_Resource_OAuthApps extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'OAuthApps';
    protected $_primary = 'idOAuthApps';
    protected $_rowClass = 'Common_Resource_OAuthApps_Row';
    protected $_rowsetClass = 'Common_Resource_OAuthApps_Rowset';
    protected $_dependentTables = array('OAuthGrants', 'OAuthAccessTokens');


    /**
     * Add a new 3rdparty application to the system
     *
     * @param string $clientId Application client id for oauth app
     * @param string $clientSecret 64-byte cryptographically strong application secret
     * @param string $refererUri URI used to call back
     * @return Common_Resource_OAuthApps
     */
    public function addClient($clientId, $clientSecret, $refererUri)
    {
        // to be implemented

    }

    /**
     * Get a single OAuthApp row by primary key
     *
     * @param int $idOAuthApps primary key identifier for the table row
     *
     * @return Common_Resource_OAuthApps_Row|null row entry matching pk
     */
    public function getClientByPk($idOAuthApps)
    {
        try {
            $query = $this->select()
                          ->where('idOAuthApps=?', $idOAuthApps)
                          ->limit(1);

            $oAuthAppRow = $this->fetchRow($query);

            return $oAuthAppRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     *    Fetch a single row by client id
     *    @param string $clientId identifier for the client app
     *
     *    @return Common_Resource_OAuthApps_Row|null matching row
     */
    public function getClientByClientId($clientId)
    {
        try {
            $query = $this->select()
                          ->where('clientId=?', (int) $clientId)
                          ->limit(1);

            $oAuthAppRow = $this->fetchRow($query);

            return $oAuthAppRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the client secret for a give client id app
     *
     * @param string $clientId the client id application identifier
     * @param string $clientSecret the new 64-byte cryptographic secret
     */
    public function updateClientSecretByClientId($clientId, $clientSecret)
    {
        try {
            $params = array(
                'clientSecret' => $clientSecret,
                'update'       => new Zend_Db_Expr('NOW()')
            );

            $where = $this->getAdapter()->quoteInto('clientId=?', (int) $clientId);

            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete a client application by client id
     *
     * @param string $clientId the client id application identifier
     */
    public function deleteClientByClientId($clientId)
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
