<?php
class AdvertiserImageManagerController extends Zend_Controller_Action
{
    public function init()
    {
		// ensure the advertiser is logged in
		if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoSimple('login', 'advertiser-account', 'frontend');
		}
		
		$this->identity = Zend_Auth::getInstance()->getIdentity();
    }
    
    public function confirmAction()
	{
        // get the request params
        $idProperty = $this->getRequest()->getParam('idProperty');
        $idPhoto    = $this->getRequest()->getParam('idPhoto');
        
        // decide what size to use based on original image aspect
        $photoModel = new Common_Model_Photo();
        $photoRow   = $photoModel->getPhotoByPhotoId($idPhoto);
        $evaluation = $photoModel->getPhotoEvaluation($photoRow->widthPixels, $photoRow->heightPixels);       
        switch ($evaluation['aspectString'])
        {
            case '1:1':
                $size = '400x400';
            break;
            
            case '4:3':
                if ($evaluation['orientation'] == 'landscape')
                    $size = '400x300';
                else
                    $size = '300x400';
            break;
            
            case '3:2':
                if ($evaluation['orientation'] == 'landscape')
                    $size = '400x267';
                else
                    $size = '267x400';
            break;
                
            case '16:9':
                if ($evaluation['orientation'] == 'landscape')
                    $size = '400x225';
                else
                    $size = '225x400';
            break;
        
            default:
                $size = '400x400';
        }

        $topLevelDir = $photoModel->topLevelDirByPropertyId($photoRow->idProperty);
        
        //var_dump($topLevelDir);
        //die();
        
        $fullPath    = DIRECTORY_SEPARATOR . 'photos'
                     . DIRECTORY_SEPARATOR . $topLevelDir
                     . DIRECTORY_SEPARATOR . $photoRow->idProperty
                     . DIRECTORY_SEPARATOR . $idPhoto . '_' . $size . '.jpg';
        
        $this->view->image      = $fullPath;
        $this->view->idProperty = $idProperty;
        $this->view->idPhoto    = $idPhoto;
    }
    
    public function deleteAction()
    {
        // get the request parameters
        $idProperty = $this->getRequest()->getParam('idProperty');
        $idPhoto    = $this->getRequest()->getParam('idPhoto');
        
        $photoModel = new Common_Model_Photo();
        $photoModel->deletePhotoByPhotoId($idProperty, $idPhoto);
                
        $this->_helper->redirector->gotoSimple('step3-pictures', 'advertiser-property', 'frontend', array('idProperty' => $idProperty));
    }
    
    public function cancelAction()
    {
        // get the request parameters
        $idProperty = $this->getRequest()->getParam('idProperty');
        $idPhoto = $this->getRequest()->getParam('idPhoto');
               
        $this->_helper->redirector->gotoSimple('step3-pictures', 'advertiser-property', 'frontend', array('idProperty' => $idProperty));
    }
	
	public function moveAction()
	{
		// get the request parameters
        $idProperty 	= $this->getRequest()->getParam('idProperty');
        $idPhoto 		= $this->getRequest()->getParam('idPhoto');
		$moveDirection 	= $this->getRequest()->getParam('moveDirection');
		
		if (($moveDirection !== 'up') && ($moveDirection !== 'down')) {
			throw new Exception('Invalid move direction');
		}
		
		$photoModel = new Common_Model_Photo();
		//$photoModel->fixBrokenDisplayPriorities($idProperty);
		$photoModel->updateMovePosition($idProperty, $idPhoto, $moveDirection);
		
		$this->_helper->redirector->gotoSimple('step3-pictures', 'advertiser-property', 'frontend', array('idProperty' => $idProperty));
	}
}