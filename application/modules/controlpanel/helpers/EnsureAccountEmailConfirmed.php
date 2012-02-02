<?php
class Controlpanel_Helper_EnsureAccountEmailConfirmed extends Zend_Controller_Action_Helper_Abstract
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
                
        if (null === $this->_advertiserRow->emailLastConfirmed) {
            $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
            $redirector->gotoSimple(
                'resend',
                'resend-confirmation',
                'controlpanel'
            );   
        }
    }
    
    public function direct()
    {
        $this->blockIfEmailNotConfirmed();
    }
}