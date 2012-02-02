<?php
class Vfr_View_Helper_Photo extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';

    public function photo($photoRow, $x, $y)
    {
        if (!$photoRow)
            return '';
        
        $img = '/photos/' . Common_Model_Photo::topLevelDirByPropertyId($photoRow->idProperty) . '/' . $photoRow->idProperty . '/' . $photoRow->idPhoto. '_' . $x . 'x' . $y . '.jpg';
        $xhtml = '<img alt="' . $this->view->escape($photoRow->caption) . '" src="' . $img . '" />';
        return $xhtml;
    }
}
