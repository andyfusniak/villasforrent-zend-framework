<?php
/**
 * Model for OAuthAuthorizations
 *
 * @author Andrew Fusniak <andy@greycatmedia.co.uk>
 * @version 1.0.0
 * @copyright Copyright (c) 2012, Andrew Fusniak
 * @package Common_Models_OAuthAuthorization
 */
class Common_Model_OAuthAuthorization extends Vfr_Model_Abstract
{
    /**
     * Issues a new authorization grant
     *
     * @param string clientId client identifier
     * @param string redirectUri the URI to which to forward the user after permission is granted
     * @return string  a cryptographically secure authorization code
     */
    public function addAuthorizationGrant($clientId, $redirectUri)
    {
        // generate a new authorization code
        $oauth = new Vfr_OAuth_OAuth();
        $code = $oauth->generateAuthorizationCode();

        $oAuthGrantResource = $this->getResource('OAuthGrants');

        $db = Zend_Db_Table::getDefaultAdapter();
        $db->beginTransaction();

        try {
            // Authorization codes MUST be short lived and single use.
            $oAuthGrantResource->revokeExistingGrants($clientId);
            $oAuthGrantResource->addGrant($clientId, $code, $redirectUri);

            $db->commit();

            return $code;
        } catch (Exception $e) {
            $db->rollBack();

            throw $e;
        }
    }

    /**
     * checks if a grant is valid for a given client
     *
     * @param string clientId the client identifier
     * @param string code the authorization code given by the authorization server
     *
     * @return bool true|false if the grant is valid
     */
    public function hasValidGrant($clientId, $code)
    {
        $oAuthGrantResource = $this->getResource('OAuthGrants');

        $oAuthGrantRow = $oAuthGrantResource->getAuthGrantByClientId($clientId);


        // if the grant cannot be found, then it most likely was
        // remove by the cron job cleaner having reached expiration
        // so the grant is invalid
        if (!$oAuthGrantRow)
            return false;

        // remove the code below since the above function only returns non-expired grants
        //
        // the grants are usually removed by a cron job periodically
        // by we will still make sure this grant has not expired
        // If it has expired, then we'll delete it from the DB to
        // improve security and keep the DB tidy
        //$expiryTime = strtotime($oAuthGrantRow->expiry);
        //$nowTime = time();
        //if ($nowTime > $expiryTime) {
        //    // delete it from the DB
        //    $oAuthGrantRow->delete();
        //
        //    return false;
        //}


        if (($clientId == $oAuthGrantRow->clientId) && ($code == $oAuthGrantRow->code)) {
            return true;
        }

        // assume the grant is invalid unless it was previously proved otherwise
        return false;
    }

    /**
     * Issues an access token for a given client, provided the grant is valid, secret key
     * matches and redirect uri matches
     *
     * @param string grantType the requested grant type e.g. authorization_code, password, refresh_token, client_credentials (currentyly only authorization_code is supported)
     * @param string clientId client identifier
     * @param string clientSecret secret key
     * @param string redirectUri the redirect URI
     *
     * @return Vfr_OAuth_AccessToken an access token object
     *
     * @throws Vfr_OAuth_
     */
    public function issueAccessToken($grantType, $clientId, $clientSecret, $code, $redirectUri)
    {
        $oauth = new Vfr_OAuth_OAuth();

        if (($grantType == null) || ($clientId == null) || ($clientSecret == null) || ($code == null) || ($redirectUri == null))
            throw Vfr_OAuth_InvalidRequest_Exception(

            );

        if (!$oauth->isValidGrantType($grantType)) {
            throw new DomainException("Invalid Grant Type");
        }
    }
}
