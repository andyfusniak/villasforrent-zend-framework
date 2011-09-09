<?php
class Vfr_View_Helper_ImageMoveButtons extends Zend_View_Helper_Abstract
{
    public function imageMoveButtons($idProperty, $idPhoto, $current, $total)
    {
        $last = $total - 1;
        
        //var_dump($total);
        // there is only one item we don't need any active up or down arrows
        if ($total == 1) {
            return '
                <div class="left"><img alt="" src="/images/admin/move_up_inactive.gif" /></div>
                <div class="right"><img alt="" src="/images/admin/move_down_inactive.gif" /></div>
            ';
        }
        
        switch ($current) {
            case 0:
                //$moveDownUrl = '/advertiser-image-manager/move/idProperty/' . $idProperty . '/idPhoto/' . $idPhoto .'/moveDirection/down';
                $url = $this->view->url(array(
                    'module'        => 'frontend',
                    'controller'    => 'advertiser-image-manager',
                    'action'        => 'move',
                    'idProperty'    => $idProperty,
                    'idPhoto'       => $idPhoto,
                    'moveDirection' => 'down',
					'digestKey'     => Vfr_DigestKey::generate(array($idProperty, $idPhoto, 'down'))
                ), null, true);
                
                return '<div class="left"><img alt="" src="/images/admin/move_up_inactive.gif" /></div><div class="right"><a href="' . $url . '"><img alt="" src="/images/admin/move_down.gif" /></a></div>';
            break;
        
            case $last:
                $url = $this->view->url(array(
                    'module'        => 'frontend',
                    'controller'    => 'advertiser-image-manager',
                    'action'        => 'move',
                    'idProperty'    => $idProperty,
                    'idPhoto'       => $idPhoto,
                    'moveDirection' => 'up',
					'digestKey'     => Vfr_DigestKey::generate(array($idProperty, $idPhoto, 'up'))
                ), null, true);
                
                return '<div class="left"><a href="' . $url . '"><img alt="" src="/images/admin/move_up.gif" /></a></div><div class="right"><img alt="" src="/images/admin/move_down_inactive.gif" /></div>';
            break;
        
            default:
            
                $moveUpUrl = $this->view->url(array(
                    'module'        => 'frontend',
                    'controller'    => 'advertiser-image-manager',
                    'action'        => 'move',
                    'idProperty'    => $idProperty,
                    'idPhoto'       => $idPhoto,
                    'moveDirection' => 'up',
					'digestKey'     => Vfr_DigestKey::generate(array($idProperty, $idPhoto, 'up'))
                ), null, true);
            
                $moveDownUrl = $this->view->url(array(
                    'module'        => 'frontend',
                    'controller'    => 'advertiser-image-manager',
                    'action'        => 'move',
                    'idProperty'    => $idProperty,
                    'idPhoto'       => $idPhoto,
                    'moveDirection' => 'down',
					'digestKey'     => Vfr_DigestKey::generate(array($idProperty, $idPhoto, 'down'))
                ), null, true);
                
                return '<div class="left"><a href="' . $moveUpUrl . '"><img alt="" src="/images/admin/move_up.gif" /></a></div><div class="right"><a href="' . $moveDownUrl . '"><img alt="" src="/images/admin/move_down.gif" /></a></div>';
        }
    }
}
