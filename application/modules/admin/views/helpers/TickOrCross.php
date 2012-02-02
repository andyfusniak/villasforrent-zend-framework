<?php
class Admin_View_Helper_TickOrCross extends Zend_View_Helper_Abstract
{
    public function tickOrCross($t)
    {
        $t = (int) $t;
        
        if ($t == 1) {
            return '<img alt="Yes" src="/images/admin/icons/greentick.png" />';
        } else {
            return '<img alt="No" src="/images/admin/icons/red-cross-transparency.png" />';
        }
    }
}