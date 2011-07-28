<?php
class Vfr_View_Helper_ImageConfirmDeleteButton extends Zend_View_Helper_Abstract
{
    public function imageConfirmDeleteButton($idProperty, $idPhoto)
    {
        $url = $this->view->url(array(
            'module'     => 'frontend',
            'controller' => 'advertiser-image-manager',
            'action'     => 'confirm',
            'idProperty' => $idProperty,
            'idPhoto'    => $idPhoto,
			'digestKey'  => Vfr_DigestKey::generate(array($idProperty, $idPhoto))
        ), null, true);
        
        return '<a href="' . $url . '"><img alt="" src="/images/admin/bluecross.png" /></a>';
    }
}
