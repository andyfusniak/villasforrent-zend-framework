<?php
class Common_Model_Photo extends Vfr_Model_Abstract
{
    public function getPhotoEvaluation($width, $height)
    {
        $width  = (int) $width;
        $height = (int) $height;
        
        $details = array();
        if ($width > $height) {
            $details['orientation'] = 'landscape';
            $aspect = (float) $width / $height;
        } elseif ($width == $height) {
            $details['orientation'] = 'square';
            $aspect = (float) 1.00;
        } elseif ($width < $height) {
            $details['orientation'] = 'portrait';
            $aspect = (float) $height / $width;
        }
        
        $details['aspect'] = $aspect;
        $details['aspectString'] = $this->aspectRatioToString($aspect); 
        
        return $details;
    }
    
    
    public function aspectRatioToString($aspect)
    {
        $aspect = (float) round($aspect, 2);
        
        if ($aspect == 1.00) {
            return '1:1';
        } elseif (($aspect == 1.33) || ($aspect == 1.34)) {
            return '4:3';
        } elseif ($aspect == 1.5) {
            return '3:2';
        } elseif (($aspect == 1.77) || ($aspect == 1.78)) {
            return '16:9';
        } else {
            return strval(round($aspect, 3));
        }
    }
    
    //
    // CREATE
    //
    
    public function addPhotoByPropertyId($idProperty, $params)
    {
        $idProperty = (int) $idProperty;
        
        $photoResource = $this->getResource('Photo');
		return $photoResource->addPhotoByPropertyId($idProperty, $params);
    }
    
    //
    // UPDATE
    //
    
    public function updateMovePosition($idProperty, $idPhoto, $moveDirection)
    {
        $idProperty     = (int) $idProperty;
        $idPhoto        = (int) $idPhoto;
        
        $photoResource = $this->getResource('Photo');
        return $photoResource->updateMovePosition($idProperty, $idPhoto, $moveDirection);
    }
    
    
    //
    // READ
    //
    
    public function getAllPhotos()
    {
        $photoResource = $this->getResource('Photo');
        return $photoResource->getAllPhotos();
    }
    
    public function getPhotoByPhotoId($idPhoto)
    {
        $idPhoto = (int) $idPhoto;
        
        $photoResource = $this->getResource('Photo');
        return $photoResource->getPhotoByPhotoId($idPhoto);
    }
    
    public static function topLevelDirByPropertyId($idProperty, $interval=50)
    {   
        if (!$idProperty) throw new Vfr_Exception("Bad value passed as idProperty");
        if ($idProperty < 10000) throw Vfr_Exception("idProperty value below 10000");
        
        $range = $idProperty - 10000;
        $value = 10000 + (floor($range / $interval) * $interval);
        
        return (int) $value;
    }
    
    public function generateDirectoryStructure($idProperty, $idPhoto, $rootPath)
    {
        $idProperty = (int) $idProperty;
        $idPhoto    = (int) $idPhoto;
        
        if (!$idProperty) throw new Vfr_Exception("Illegal value passed to idProperty parameter");
        if ($idProperty < 10000) throw new Vfr_Exception("idProperty below 10000");
        
        $topLevelId     = $this->topLevelDirByPropertyId($idProperty);
        $topLevelDir    = $rootPath . DIRECTORY_SEPARATOR . $topLevelId;
        $secondLevelDir = $topLevelDir . DIRECTORY_SEPARATOR . $idProperty;
        
        if (!file_exists($topLevelDir)) {
            // assumes the web-server is running as www-data
            // and sets user + group wrx bits (not other for added security)
            mkdir($topLevelDir);
            chmod($topLevelDir, 0770);
            
            // create the sub-dir (we know it doesn't exist because
            // we just made the top level dir)
            mkdir($secondLevelDir);
            chmod($secondLevelDir, 0770);
        } else if (!file_exists($secondLevelDir)) {
            // only need to make the second level directory
            mkdir($secondLevelDir);
            chmod($secondLevelDir, 0770);
        }
        
        return $secondLevelDir;
    }
    
    //
    // UPDATE
    //
    
    public function fixBrokenDisplayPriorities($idProperty)
    {
        $idProperty = (int) $idProperty;
        
        $photoResource = $this->getResource('Photo');
        return $photoResource->fixBrokenDisplayPriorities($idProperty);
    }
    
    //
    // DELETE
    //
    
    public function deletePhotoByPhotoId($idProperty, $idPhoto)
    {
        $idProperty = (int) $idProperty;
        $idPhoto    = (int) $idPhoto;
        
        if (!$idProperty) throw new Vfr_Exception("Illegal value passed to idProperty parameter");
        if ($idProperty < 10000) throw new Vfr_Exception("idProperty below 10000");
     
        // get the vfr config from the configuration
		$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
		$vfrConfig = $bootstrap['vfr'];
        
        // get the group dir
        $topLevelDir = $this->topLevelDirByPropertyId($idProperty);
        
        $photoResource 	= $this->getResource('Photo');
        $photoRow = $photoResource->getPhotoByPhotoIdAndPropertyID($idProperty, $idPhoto);
                
        // destroy the dynamically cache generated images
        $fileWildcard   = $vfrConfig['photo']['images_dynamic_dir']
                        . DIRECTORY_SEPARATOR . $topLevelDir
                        . DIRECTORY_SEPARATOR . $idProperty
                        . DIRECTORY_SEPARATOR . $idPhoto . '_*.jpg';
        foreach (glob($fileWildcard) as $filename) {    
            if (file_exists($filename))
                unlink($filename);
        }
        
        // destroy the original
        $original = $vfrConfig['photo']['images_original_dir']
                  . DIRECTORY_SEPARATOR . $topLevelDir
                  . DIRECTORY_SEPARATOR . $idProperty
                  . DIRECTORY_SEPARATOR . $idPhoto . '.' . strtolower($photoRow->fileType);
        if (file_exists($original))
            unlink($original);
        
        if ($photoRow)
            $photoRow->delete();
        return $photoRow;
    }
}
