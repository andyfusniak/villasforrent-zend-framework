<?php
class Vfr_Validate_UniqueAdvertiserEmail extends Zend_Validate_Abstract
{
    const version = '1.0.0';

    const EMAIL_EXISTS = 'emailExists';

    protected $_messageTemplates = array(
        self::EMAIL_EXISTS =>
            '%value% already exists in our system'
    );

    public function __construct(Common_Model_Advertiser $model)
    {
        $this->_model = $model;
    }

    public function isValid($value, $context = null)
    {
        $this->_setValue($value);

        $advertiser = $this->_model->getAdvertiserByEmail($value);

        if (null === $advertiser) {
            return true;
        }

        $this->_error(self::EMAIL_EXISTS);
        return false;
    }
}
