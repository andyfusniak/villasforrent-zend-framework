<?php
class Admin_View_Helper_ToggleDirection extends Zend_View_Helper_Abstract
{
    public function toggleDirection($direction)
    {
        switch ($direction) {
            case 'ASC':
                return 'DESC';
            break;

            case 'DESC':
                return 'ASC';
            break;

            default:
                return 'ASC';
        }
    }
}
