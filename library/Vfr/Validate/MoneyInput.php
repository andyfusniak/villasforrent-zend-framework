<?php
class Vfr_Validate_MoneyInput extends Zend_Validate_Abstract
{
    const version = '1.00';

    const AMOUNT_FORMAT = 'amountFormat';

    protected $_messageTemplates = array(
        self::AMOUNT_FORMAT => "Enter the amount to two decimal places e.g. 49.95"
    );

    public function __construct($options = array())
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }
    }

    public function isValid($value)
    {
        $validNumberPattern = '/^[0-9]+(\.[0-9]{2})$/';

        if (!preg_match($validNumberPattern, $value)) {
            $this->_error(self::AMOUNT_FORMAT, $value);
        }

        if (sizeof($this->_messages) > 0)
            return false;

        return true;
    }
}
