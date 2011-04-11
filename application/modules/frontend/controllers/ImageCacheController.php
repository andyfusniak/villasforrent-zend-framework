<?php
class ImageCacheController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_logger = Zend_Registry::get('logger');
    }
    
    public function preDispatch()
    {
       $this->_helper->layout()->disableLayout();
       $this->_helper->viewRenderer->setNoRender(true);
    }
    
    
    public function generateAction()
	{
        // get the destination from the configuration
		$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
		$vfrConfig = $bootstrap['vfr'];
        $acceptableSizes = $vfrConfig['photo']['acceptable_sizes'];
        
        //var_dump("Inside ImageCacheController");
        //var_dump($this->getRequest()->getParams());
       
        $requestObj = $this->getRequest();
        $topLevel   = $requestObj->getParam('topLevel');
        $idProperty = $requestObj->getParam('idProperty');
        $idPhoto    = $requestObj->getParam('idPhoto');
        $width      = $requestObj->getParam('width');
        $height     = $requestObj->getParam('height');
        $ext        = $requestObj->getParam('ext');
        
        
        if (isset($acceptableSizes[$width]) && (isset($acceptableSizes[$width][$height]))) {
            $acceptedTypeString = $acceptableSizes[$width][$height];
        } else {
            $this->getResponse()->setHttpResponseCode(404);
            return;
        }
        
        //var_dump($requestObj->getParams());
     
        $modelPhoto = new Common_Model_Photo();
        $photoRow   = $modelPhoto->getPhotoByPhotoId($idPhoto);
        $fileType   = strtolower($photoRow->fileType);
        $origWidth  = $photoRow->widthPixels;
        $origHeight = $photoRow->heightPixels;
        $origPhoto  = $modelPhoto->getPhotoEvaluation($origWidth, $origHeight);
        
        //var_dump($photoRow);
        //die();
        
        // get the full path to the original image
        $originalImageFullPath = $vfrConfig['photo']['images_original_dir']
                               . DIRECTORY_SEPARATOR . $topLevel
                               . DIRECTORY_SEPARATOR . $idProperty
                               . DIRECTORY_SEPARATOR . $idPhoto
                               . '.' . $fileType;
                               
        if (is_file($originalImageFullPath)) {
            switch ($fileType) {
                case 'jpg':
                    $sourceGdImage = imagecreatefromjpeg($originalImageFullPath);
                break;
            
                case 'png':
                    $sourceGdImage = imagecreatefrompng($originalImageFullPath);
                break;
            
                default:
                    throw new Vfr_Exception("Unsupported image type " . $fileType);
            }
        }
        
        if (!$sourceGdImage)
            throw new Vfr_Exception("Failed to create GD image for " . $originalImageFullPath);
        
        
        
        // check to see if the target image is the same aspect ratio as the original
        $destPhoto  = $modelPhoto->getPhotoEvaluation($width, $height);
        
        
        $this->_logger->log(__METHOD__ . ' ImageCreate (original (x,y) = (' . $origWidth . ',' . $origHeight . ')  new (x,y) = (' . $width . ',' . $height . ')', Zend_Log::DEBUG);
        $destGdImage = imagecreatetruecolor($width, $height);
        if (!imagecopyresampled($destGdImage, $sourceGdImage,
                           0, 0,
                           0, 0,
                           $width, $height,
                           $origWidth, $origHeight))
            throw new Vfr_Exception("Failed to resize the image " . $originalImageFullPath);
        
        // write the newly generated each to the file cache
        $cacheTopLevel      = $modelPhoto->topLevelDirByPropertyId($photoRow->idProperty);
        $cacheSecondLevel   = $modelPhoto->generateDirectoryStructure($photoRow->idProperty, $idPhoto, $vfrConfig['photo']['images_dynamic_dir']);
        $newFileFullPath    = $cacheSecondLevel . DIRECTORY_SEPARATOR . $idPhoto . '_' . $width . 'x' . $height . '.' . $ext;
                       
        // write the new image
        imagejpeg($destGdImage, $newFileFullPath, $vfrConfig['photo']['gd_quality']);
        
        //var_dump($originalImageFullPath);
        $this->getResponse()->setHeader('Content-type', 'image/jpeg');
        imagejpeg($destGdImage);
    }
}