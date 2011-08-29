<?php
class HpwApi {
    const SERVICE_BASE_URL  = 'http://www.holidaypropertyworldwide.com/api';
    const SERVICE_BASE_URL_DEV = 'http://rd.zendvfr/api';
    const DEFAULT_SERVICE_RESOURCE  = 'property/%1/calendar/%2';
    const ADDITIONAL_PASSWD = 'v1ll454r3nt.!';
    
    private $_apiKey = null;
    private $_idProperty = null;
    private $_resource = null;
        
    public function __construct($apiKey=null, $resource=self::DEFAULT_SERVICE_RESOURCE)
    {
        $this->_apiKey   = $apiKey;
        $this->_resource = $resource;
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
    
    public function setResource($name)
    {
        $this->_resource = $name;
        return $this;
    }

    public function getCalendar($idProperty, $idCalendar)
    {
        $s = $this->_apiKey
           . $idProperty
           . $idCalendar
           . self::ADDITIONAL_PASSWD;
        $digestKey = sha1($s); 
        $serviceUri = self::SERVICE_BASE_URL . '/' . 'property/%1/calendar/%2';
        $serviceUri = str_replace('%1', $idProperty, $serviceUri);
        $serviceUri = str_replace('%2', $idCalendar, $serviceUri);
        //var_dump($serviceUri);
        
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
        
        $serviceUri = self::SERVICE_BASE_URL . '/' . $this->_resource . '/' . $idProperty . '/' . 'availability';
        var_dump($serviceUri);
        
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
        
        $serviceUri = self::SERVICE_BASE_URL_DEV . '/' . $this->_resource . '/' . $idProperty . '/rates';
 
        $headers = array (
            'x-apikey: ' . $this->_apiKey,
            'x-digestkey: ' . $digestKey,
            'Accept: application/vnd.vfr.rate+json; version: 1.0;'
        );

        var_dump($serviceUri);
        var_dump($headers);
        
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
    
    
    public function getVersion()
    {
        return "1.00";
    }
}

class ApiKeyMissingException extends Exception {}
class PropertyMissingException extends Exception {}
