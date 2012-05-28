<?php
class Admin_View_Helper_SecurePassword extends Zend_View_Helper_Abstract
{
    public function SecurePassword($hash)
    {
        if (strlen($hash) == 60) {
            return '<span class="green-tick">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
        } else {
            return '';
        }
    }
}
