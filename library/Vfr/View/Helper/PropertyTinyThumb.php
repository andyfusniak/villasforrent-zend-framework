<?php
class Vfr_View_Helper_PropertyTinyThumb extends Zend_View_Helper_Abstract
{
    public function propertyTinyThumb($idProperty)
    {
        $idProperty = (int) $idProperty;
        $propertyModel = new Common_Model_Property();
        $photorow = $propertyModel->getPrimaryPhotoByPropertyId($idProperty);
        
        if (! $photorow) {
            return 'no images';
        }
        
        return $this->view->tinyThumb($idProperty, $photorow->idPhoto, $photorow->widthPixels, $photorow->heightPixels, $photorow->caption);
    }
    
    /*    
        // decide which size thumb to use
        $photoModel = new Common_Model_Photo();
        $evaluation = $photoModel->getPhotoEvaluation($photorow->widthPixels, $height);
       
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
                $size = '80x80';
        }
        
        // get the group container dir        
        $modelPhoto  = new Common_Model_Photo();
        $topLevelDir = $modelPhoto->topLevelDirByPropertyId($idProperty);
        $fullPath    = DIRECTORY_SEPARATOR . 'photos'
                     . DIRECTORY_SEPARATOR . $topLevelDir
                     . DIRECTORY_SEPARATOR . $idProperty
                     . DIRECTORY_SEPARATOR . $idPhoto . '_' . $size . '.jpg';
        
        
        echo '<img alt="' . $caption . '" src="' . $fullPath . '" />';
    } */
}
