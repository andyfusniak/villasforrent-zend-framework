<?php
class Vfr_Controller_Router_UrlExcemptionChecker
{
    private $_excemptPrefixes = array (
        'admin',
        'availability-image',
        'controlpanel',
        'favicon.ico'
    );
    
    public function __construct()
    {
    }
    
    public function isExcempt($uri)
    {
        foreach ($this->_excemptPrefixes as $prefix) {
            if (preg_match("/^$prefix/", $uri)) {
                return true;
            }
        }
        
        return false;
    }
}