<?php
class Admin_View_Helper_DirectionArrow extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';
    
    public function DirectionArrow($current, $sort, $direction)
    {
        if ($current == $sort) {
            return '<span class="direction-' . strtolower($direction) . '">&nbsp;&nbsp;&nbsp;</span>';
        }
    }
}