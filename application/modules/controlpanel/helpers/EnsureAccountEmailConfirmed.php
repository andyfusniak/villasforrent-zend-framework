<?php
class Frontend_Helper_EnsureAccountEmailConfirmed extends Zend_Controller_Action_Helper_Abstract
{
    private $_advertiserRow;
    
    public function init()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_advertiserRow = Zend_Auth::getInstance()->getIdentity();
        }
    }
    
    public function blockIfEmailNotConfirmed()
    {
        if ($this->_advertiserRow == null)
            return;
        
        $emailLastConfirmed = $this->_advertiserRow->emailLastConfirmed;
        
        if ($emailLastConfirmed == null) {
            $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
            $redirector->setGoToUrl(
                Zend_Controller_Front::getInstance()->getBaseUrl()
                . '/advertiser-resend-confirmation/resend'
            );
            $redirector->redirectAndExit();   
        }
    }
    
    public function direct()
    {
        $this->blockIfEmailNotConfirmed();
    }
}