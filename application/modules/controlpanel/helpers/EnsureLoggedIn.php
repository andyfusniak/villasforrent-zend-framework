<?php
class Controlpanel_Helper_EnsureLoggedIn extends Zend_Controller_Action_Helper_Abstract
{
    public function init() {}
    
    public function redirectIfNotLoggedIn($disableLayout)
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
            $redirectUri = $this->getRequest()->getRequestUri();
            
            $disableLayoutString = "";
            if (true == $disableLayout) {
                $disableLayoutString = "disable_layout=1";
            }
            
            $redirector->gotoUrl(
                'controlpanel/authentication/login?'
                . $disableLayoutString
                . '&redirect_uri=' . urlencode($redirectUri)
            );
        }
    }
    
    public function direct($disableLayout=false)
    {
        $this->redirectIfNotLoggedIn($disableLayout);
    }
}