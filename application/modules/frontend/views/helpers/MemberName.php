<?php
class Frontend_View_Helper_MemberName extends Zend_View_Helper_Abstract
{
    const STYLE_FIRSTNAME = 1;
    const STYLE_BOTHNAMES = 2;
    const STYLE_LASTNAME  = 3;

    public function memberName($style = self::STYLE_FIRSTNAME)
    {
        if (Vfr_Auth_Member::getInstance()->hasIdentity()) {
            $identity = Vfr_Auth_Member::getInstance()->getIdentity();
            return $this->view->escape($identity->firstname);
        }

        return '';
    }
}