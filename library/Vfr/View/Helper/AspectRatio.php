<?php
class Vfr_View_Helper_AspectRatio extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';

    public function aspectRatio($width, $height)
    {
        $photoModel = new Common_Model_Photo();
        $evaluation = $photoModel->getPhotoEvaluation($width, $height);
        
        //return $width/$height;
        return $evaluation['aspectString']; 
    }
}
