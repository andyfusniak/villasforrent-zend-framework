<?php
class Vfr_View_Helper_ImageConfirmDeleteButton extends Zend_View_Helper_Abstract
{
    public function imageConfirmDeleteButton($idProperty, $idPhoto)
    {
        return '<a href="/advertiser-image-manager/confirm/idProperty/' . $idProperty . '/idPhoto/' . $idPhoto . '"><img alt="" src="/images/admin/bluecross.png" /></a>';
    }
}