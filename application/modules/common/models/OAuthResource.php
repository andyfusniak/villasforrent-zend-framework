<?php
class Common_Model_OAuthResource extends Vfr_Model_Abstract
{
    /**
     * Check to see that the redirect uri matches for a given client
     *
     * @param string clientId the client identifier
     * @param string redirectUri the uri to check for match
     * @return bool true|false is the uri matches the uri stored for this client id
     */
    public function checkClientIdMatchesRedirectUri($clientId, $redirectUri)
    {
        $oAuthApps = $this->getResource('OAuthApps');

        $oAuthAppsRow = $oAuthApps->getClientByClientId($clientId);

        if (($oAuthAppsRow) && ($oAuthAppsRow->redirectUri == $redirectUri))
            return true;

        return false;
    }

    /**
     * Check to if the client secret matches for a given client
     *
     * @param string clientId the client identifier
     * @param string clientSecret the 64-byte secret key used to check for match
     * @return bool true|false if the secret key matches the stored key
     */
    public function checkClientSecretKey($clientId, $clientSecret)
    {
        $oAuthApps = $this->getResource('OAuthApps');

        $oAuthAppsRow = $oAuthApps->getClientByClientId($clientId);

        if (($oAuthAppsRow) && ($oAuthAppsRow->clientSecret == $clientSecret))
            return true;

        return false;
    }
}
