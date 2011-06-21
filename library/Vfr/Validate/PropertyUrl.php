<?php
class Vfr_Validate_PropertyUrl extends Zend_Validate_Abstract
{
    const URL_INVALID   = 'urlInvalid';
    const URL_IN_USE    = 'urlAlreadyInUse';
    
    protected $_messageTemplates = array (
        self::URL_INVALID   => "Invalid url must be all lower case including 0-9 and the '-' symbol only, and start with a-z character and not end with a - character.",
        self::URL_IN_USE    => "The url %value% is already taken"
    );
    
    public function isValid($value)
    {
        if (!preg_match('/^(\w{1,}-)*\w{1,}$/', $value)) {
        //if (!preg_match('/^[a-z][a-z0-9-]{3,}[^-]$/', $value)) {
            $this->_error(self::URL_INVALID);
            return false;
        }
        
        $frontController = Zend_Controller_Front::getInstance();
		$post = $frontController->getRequest()->getPost();
        
        $propertyModel = new Common_Model_Property();
        if ($propertyModel->isUrlNameTaken($post['idProperty'], $value)) {
            $this->_error(self::URL_IN_USE);
            return false;
        }
        
        return true;
    }
}