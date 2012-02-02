<?php
class Vfr_View_Helper_WebsiteUrl extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';

    public function websiteUrl($url)
    {
        if  ((strlen($url) > 8) && (substr($url, 0, 7) == "http://")) {
            $url = substr($url, 7, strlen($url));
        }
        
        return '<a href="http://' . $url . '">' . $url . '</a>';          
    }
}
