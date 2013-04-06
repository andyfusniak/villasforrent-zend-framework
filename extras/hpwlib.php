<?php
class HpwApi {
    const WEBSITE_URL = 'http://www.holidaypropertyworldwide.com';
    const SERVICE_BASE_URL  = 'http://www.holidaypropertyworldwide.com/api';
    //const SERVICE_BASE_URL  = 'http://mars.zendvfr/api';
    const ADDITIONAL_PASSWD = 'v1ll454r3nt.!';

    private $_apiKey = null;
    private $_idProperty = null;
    private $_serviceUri = null;

    public function __construct($apiKey=null, $serviceUri=self::SERVICE_BASE_URL)
    {
        $this->_apiKey     = $apiKey;
        $this->_serviceUri = $serviceUri;
    }

    public function setApiKey($apiKey)
    {
        $this->_apiKey = $apiKey;
        return $this;
    }

    public function getApiKey()
    {
        return $this->_apiKey;
    }

    public function setServiceUri($uri)
    {
        $this->_serviceUri = $uri;

        return $this;
    }

    public function getAllPhotos($idProperty)
    {
        $s = $this->_apiKey
           . $idProperty
           . self::ADDITIONAL_PASSWD;
        $digestKey = sha1($s);

        $serviceUri = $this->_serviceUri . '/' . 'property/%1/photos';
        $serviceUri = str_replace('%1', $idProperty, $serviceUri);

        $headers = array (
            'x-apikey: ' . $this->_apiKey,
            'x-digestkey: ' . $digestKey,
            'Accept: application/vnd.hpw.photo+json; version: 1.0;'
        );

        $ch = curl_init($serviceUri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, false);

        // wait for the RESTful response
        $curl_response = curl_exec($ch);

        // close the connection
        curl_close($ch);

        return $curl_response;
    }

    public function getCalendar($idProperty, $idCalendar)
    {
        $s = $this->_apiKey
           . $idProperty
           . $idCalendar
           . self::ADDITIONAL_PASSWD;
        $digestKey = sha1($s);
        $serviceUri = $this->_serviceUri . '/' . 'property/%1/calendar/%2';
        $serviceUri = str_replace('%1', $idProperty, $serviceUri);
        $serviceUri = str_replace('%2', $idCalendar, $serviceUri);
        $headers = array (
            'x-apikey: ' . $this->_apiKey,
            'x-digestkey: ' . $digestKey,
            'Accept: application/vnd.hpw.calendar+json; version: 1.0;'
        );

        $ch = curl_init($serviceUri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, false);

        // wait for the RESTful response
        $curl_response = curl_exec($ch);

        // close the connection
        curl_close($ch);

        return $curl_response;
    }


    public function getAvailability($idProperty)
    {
        if (!$this->_apiKey) {
            throw new ApiKeyMissingException('Call the setApiKey() method first');
        }

        $digestKey = sha1($this->_apiKey
                          . $this->_resource
                          . $idProperty
                          . self::ADDITIONAL_PASSWD);

        $serviceUri = $this->serviceUri . '/' . $this->_resource . '/' . $idProperty . '/' . 'availability';
        //var_dump($serviceUri);

        $headers = array (
            'x-apikey: ' . $this->_apiKey,
            'x-digestkey: ' . $digestKey,
            'Accept: application/vnd.vfr.rate+json; version: 1.0;'
        );


        // connect to the HPW server
        $ch = curl_init($serviceUri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, false);

        // wait for the RESTful response
        $curl_response = curl_exec($ch);

        // close the connection
        curl_close($ch);

        return $curl_response;
    }

    public function getRates($idProperty)
    {
        if (!$this->_apiKey) {
            throw new ApiKeyMissingException('Call the setApiKey() method first');
        }

        $digestKey = sha1($this->_apiKey
                          . $this->_resource
                          . $idProperty
                          . self::ADDITIONAL_PASSWD);

        $serviceUri = $this->_serviceU . '/' . $this->_resource . '/' . $idProperty . '/rates';

        $headers = array (
            'x-apikey: ' . $this->_apiKey,
            'x-digestkey: ' . $digestKey,
            'Accept: application/vnd.vfr.rate+json; version: 1.0;'
        );

        //var_dump($serviceUri);
        //var_dump($headers);

        // connect to the HPW server
        $ch = curl_init($serviceUri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, false);

        // wait for the RESTful response
        $curl_response = curl_exec($ch);

        // close the connection
        curl_close($ch);

        return $curl_response;
    }

    /**
     * @param int $idProperty the property id
     * @param int $idPhoto the photo id
     * @param int $x the width in pixels
     * @param int $y the height in pixels
     */
    public static function photoUrl($idProperty, $idPhoto, $x, $y)
    {
        $interval = 50;
        $range = $idProperty - 10000;
        $dirId = 10000 + (floor($range / $interval) * $interval);

        $url = self::WEBSITE_URL . '/photos/' . $dirId . '/' . $idProperty . '/' . $idPhoto . '_' . $x . 'x' . $y . '.jpg';

        return $url;
    }

    public function getVersion()
    {
        return "1.2.0";
    }
}

class ApiKeyMissingException extends Exception {}
class PropertyMissingException extends Exception {}
