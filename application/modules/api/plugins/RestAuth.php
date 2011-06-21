<?php
class Api_Plugin_RestAuth extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        if ($request->getModuleName() !== 'api')
            return;
       
        
        //echo "APIKEY:$apiKey";
        
        var_dump("preDispatch");
    }
}