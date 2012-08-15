<?php
class Frontend_Helper_EnsureMemberLoggedIn extends Zend_Controller_Action_Helper_Abstract
{
    public function init() {}

    public function redirectIfNotLoggedIn($disableLayout)
    {
        if (!Vfr_Auth_Member::getInstance()->hasIdentity()) {
            $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
            $redirectUri = $this->getRequest()->getRequestUri();

            $disableLayoutString = "";
            if (true == $disableLayout) {
                $disableLayoutString = "disable_layout=1";
            }

            $redirector->gotoUrl(
                'authentication/login?' . $disableLayoutString
                . '&redirect_uri=' . urlencode($redirectUri)
            );
        }
    }

    public function direct($disableLayout=false)
    {
        $this->redirectIfNotLoggedIn($disableLayout);
    }
}
