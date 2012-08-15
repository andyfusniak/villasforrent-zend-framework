<?php
class Vfr_Validate_UniqueMemberEmail extends Zend_Validate_Abstract
{
    const version = '1.0.0';
    const EMAIL_EXISTS = 'emailExists';

    protected $_model = null;

    protected $_messageTemplates = array(
        self::EMAIL_EXISTS => '%value% is already in use'
    );

    public function __construct(Common_Model_Member $model)
    {
        $this->_model = $model;
    }

    public function isValid($value, $context = null)
    {
        $this->_setValue($value);

        $member = $this->_model->getMemberByEmail($value);

        if (null === $member) {
            return true;
        }

        $this->_error(self::EMAIL_EXISTS);
        return false;
    }
}
