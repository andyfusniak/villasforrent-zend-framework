<?php
class ImageCacheController extends Zend_Controller_Action
{
	protected $_vfrConfig = null;
	private $_photoModel;
	
    public function init()
    {
        $this->_logger = Zend_Registry::get('logger');
		
		// get the destination from the configuration
		$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
		$this->_vfrConfig = $bootstrap['vfr'];
		
		$this->_imageProcessor = Vfr_Image_Processor::getInstance();
    }
    
    public function preDispatch()
    {
       $this->_helper->layout()->disableLayout();
       $this->_helper->viewRenderer->setNoRender(true);
    }
    
    
    public function generateAction()
	{
        $acceptableSizes = $this->_vfrConfig['photo']['acceptable_sizes'];
        
        //var_dump("Inside ImageCacheController");
        //var_dump($this->getRequest()->getParams());
       
        $topLevel   = $this->_request->getParam('topLevel');
        $idProperty = $this->_request->getParam('idProperty');
        $idPhoto    = $this->_request->getParam('idPhoto');
        $width      = $this->_request->getParam('width');
        $height     = $this->_request->getParam('height');
        $ext        = $this->_request->getParam('ext');

        
        //if (isset($acceptableSizes[$width]) && (isset($acceptableSizes[$width][$height]))) {
        //    $acceptedTypeString = $acceptableSizes[$width][$height];
        //} else {
        //    $this->getResponse()->setHttpResponseCode(404);
        //    return;
        //}
        
        //var_dump($this->_request->getParams());
		//die();
	 
		if (!$this->_photoModel)
			$this->_modelPhoto = new Common_Model_Photo();
			
        $photoRow   = $this->_modelPhoto->getPhotoByPhotoId($idPhoto);
		
        $fileType   = strtolower($photoRow->fileType);
        $origWidth  = $photoRow->widthPixels;
        $origHeight = $photoRow->heightPixels;
        $origPhoto  = $this->_modelPhoto->getPhotoEvaluation($origWidth, $origHeight);
        
        //var_dump($origPhoto);
        //die();
        
        // get the full path to the original image
        $originalImageFullPath = $this->_vfrConfig['photo']['images_original_dir']
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
        $destPhoto  = $this->_modelPhoto->getPhotoEvaluation($width, $height);
        
		//var_dump($destPhoto);
		//die();
		
		// bool imagecopyresampled ( resource $dst_image , resource $src_image,
		// int $dst_x , int $dst_y ,
		// int $src_x , int $src_y ,
		// int $dst_w , int $dst_h ,
		// int $src_w , int $src_h )
		
		// if the original image is smaller than the target image, don't stretch it up
		// instead position it inside the canvas of the target
		if (($width > $origWidth) && ($height > $origHeight)) {
			$this->_logger->log(__METHOD__ . ' Original image is smaller than target', Zend_Log::DEBUG);
			$this->_logger->log(__METHOD__ . ' ImageCreate (original (x,y) = (' . $origWidth . ',' . $origHeight . ')  new (x,y) = (' . $width . ',' . $height . ')', Zend_Log::DEBUG);
			$destGdImage = imagecreatetruecolor($origWidth, $origHeight);
			
			if (!imagecopyresampled($destGdImage, $sourceGdImage,
									0, 0,
									0, 0,
									$origWidth, $origHeight,
									$origWidth, $origHeight))
            throw new Vfr_Exception("Failed to resize the image " . $originalImageFullPath);
		} else {
			//var_dump("before processing");
			//var_dump(imagesx($sourceGdImage));
			//var_dump(imagesy($sourceGdImage));
			
			$destGdImage = $this->_imageProcessor->gdImageToNewAspect($sourceGdImage, $width, $height);
		}
		
		//var_dump($destGdImage, $width, $height);
		//die();
		
        // write the newly generated each to the file cache
        //$cacheTopLevel      = $this->_modelPhoto->topLevelDirByPropertyId($photoRow->idProperty);
        //$cacheSecondLevel   = $this->_modelPhoto->generateDirectoryStructure($photoRow->idProperty, $idPhoto, $this->_vfrConfig['photo']['images_dynamic_dir']);
        //$newFileFullPath    = $cacheSecondLevel . DIRECTORY_SEPARATOR . $idPhoto . '_' . $width . 'x' . $height . '.' . $ext;
                       
        // write the new image
        //imagejpeg($destGdImage, $newFileFullPath, $this->_vfrConfig['photo']['gd_quality']);
        
        //var_dump($originalImageFullPath);
        $this->getResponse()->setHeader('Content-type', 'image/jpeg');
        imagejpeg($destGdImage);
    }
}