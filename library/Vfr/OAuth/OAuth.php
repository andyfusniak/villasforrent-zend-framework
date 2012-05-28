<?php
/**
 * @author Andrew Fusniak <andy@greycatmedia.co.uk>
 * @copyright Copyright (c) 2012, Andrew Fusniak
 * @version 1.0.0
 * @package Vfr_OAuth_OAuth
 */
class Vfr_OAuth_OAuth
{
    /**
     * @var array list of valid response types
     */
    protected $_validResponseTypes = array (
        'token',
        'code'
    );

    /**
     * @var array list of valid grant types
     */
    protected $_validGrantTypes = array (
        'authorization_code',
        'password',
        'refresh_token',
        'client_credentials'
    );

    /**
     * Determine if the given response_type string is supported
     *
     * @param string responseType to given response type to be checked
     *
     * @return bool returns true if the response type is supported, otherwise false
     */
    public function isValidResponseType($responseType)
    {
        if (in_array($responseType, $this->_validResponseTypes))
            return true;

        return false;
    }

    /**
     * Determine if a given grant_type string is supported
     *
     * @param string grantType the given grant type to be checked
     *
     * @return bool returns true if the grant type is supported, otherwise false
     */
    public function isValidGrantType($grantType)
    {
        if (in_array($grantType, $this->_validGrantTypes))
            return true;

        return false;
    }

    /**
     * Generates a random 15 digit client id
     *
     * 3rd party clients require a unique client id for use with
     * the OAuth 2.0 protocol
     *
     * @return int A random 15-digit numeric client id
     */
    public function generateClientId()
    {

        $clientId = '';

        for ($i=1; $i<=15; $i++) {
            $clientId .= mt_rand(0, 9);
        }

        return $clientId;
    }

    /**
     * Generate a cryptographically strong unique client secret key
     *
     * This starts with 2048 bits of entropy from OpenSSL library psuedo
     * random bytes stream, and then uses a cryptographic hash SHA-256
     * to reduce down to a fixed length of 64 bytes.
     *
     *
     * @return string a 64-byte unique client secret
     */
    public function generateClientSecret()
    {
        $strong = null;

        // start with 2048 bits of entropy
        $entropy = openssl_random_pseudo_bytes(256, $strong);

        $key = hash("sha256", $entropy);

        return $key;
    }


    /**
     * Generate a cryptographically strong unique auth code
     *
     * Start with 2048 bits of entropy using psuedo random bytes
     * from the OpenSSL library, and then generate a 64-byte hash (SHA-256)
     * to reduce down to a fixed length
     *
     * @return string a 64-byte unique authorization code
     */
    public function generateAuthorizationCode()
    {
        $strong = null;

        $entropy = openssl_random_pseudo_bytes(256, $strong);

        $key = hash("sha256", $entropy);

        return $key;
    }
}
