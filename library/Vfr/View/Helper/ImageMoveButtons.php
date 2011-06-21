<?php
class Vfr_View_Helper_ImageMoveButtons extends Zend_View_Helper_Abstract
{
    public function imageMoveButtons($idProperty, $idPhoto, $current, $total)
    {
        $last = $total - 1;
        
        //var_dump($current, $last);
        // there is only one item we don't need any up or down arrows
        if ($total == 0) {
            return '';
        }
        
        switch ($current) {
            case 0:
                $moveDownUrl = '/advertiser-image-manager/move/idProperty/' . $idProperty . '/idPhoto/' . $idPhoto .'/moveDirection/down';
                return '<div class="left"><img alt="" src="/images/admin/move_up_inactive.gif" /></div><div class="right"><a href="' . $moveDownUrl . '"><img alt="" src="/images/admin/move_down.gif" /></a></div>';
            break;
        
            case $last:
                $moveUpUrl = '/advertiser-image-manager/move/idProperty/' . $idProperty . '/idPhoto/' . $idPhoto .'/moveDirection/up';
                return '<div class="left"><a href="' . $moveUpUrl . '"><img alt="" src="/images/admin/move_up.gif" /></a></div><div class="right"><img alt="" src="/images/admin/move_down_inactive.gif" /></div>';
            break;
        
            default:
                $moveUpUrl   = '/advertiser-image-manager/move/idProperty/' . $idProperty . '/idPhoto/' . $idPhoto .'/moveDirection/up';
                $moveDownUrl = '/advertiser-image-manager/move/idProperty/' . $idProperty . '/idPhoto/' . $idPhoto .'/moveDirection/down';
                return '<div class="left"><a href="' . $moveUpUrl . '"><img alt="" src="/images/admin/move_up.gif" /></a></div><div class="right"><a href="' . $moveDownUrl . '"><img alt="" src="/images/admin/move_down.gif" /></a></div>';
        }
    }
}