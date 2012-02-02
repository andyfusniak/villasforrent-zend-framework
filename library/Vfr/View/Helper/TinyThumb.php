<?php
class Vfr_View_Helper_TinyThumb extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';

    public function tinyThumb($idProperty, $idPhoto, $width, $height, $caption)
    {
        $idPhoto = (int) $idPhoto;

        // decide which size thumb to use
        $photoModel = new Common_Model_Photo();
        $evaluation = $photoModel->getPhotoEvaluation($width, $height);
       
        //var_dump($evaluation);
        
        switch ($evaluation['aspectString'])
        {
            case '1:1':
                $size = '80x80';
            break;
            
            case '4:3':
                if ($evaluation['orientation'] == 'landscape')
                    $size = '80x60';
                else
                    $size = '60x80';
            break;
            
            case '3:2':
                if ($evaluation['orientation'] == 'landscape')
                    $size = '80x53';
                else
                    $size = '53x80';
            break;
                
            case '16:9':
                if ($evaluation['orientation'] == 'landscape')
                    $size = '80x45';
                else
                    $size = '45x80';
            break;
        
            default:
                if ($evaluation['orientation'] == 'portrait') {
                    $height = 60;
                    $width  = intval( round($height * $evaluation['aspect']) );
                    //var_dump($width);
                    $size = strval($width) . 'x' . strval($height);
                    //var_dump($size);
                } else {
                    $size = '80x80';
                }
        }
        
        // get the group container dir        
        $modelPhoto  = new Common_Model_Photo();
        $topLevelDir = $modelPhoto->topLevelDirByPropertyId($idProperty);
        $fullPath    = DIRECTORY_SEPARATOR . 'photos'
                     . DIRECTORY_SEPARATOR . $topLevelDir
                     . DIRECTORY_SEPARATOR . $idProperty
                     . DIRECTORY_SEPARATOR . $idPhoto . '_' . $size . '.jpg';
        
        
        echo '<img alt="' . $caption . '" src="' . $fullPath . '" />';
    }
}
