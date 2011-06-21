<?php
class Admin_MigratePhotosController extends Zend_Controller_Action
{
    protected $_photoConfig = null;
    
    public function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
		$this->_photoConfig = $bootstrap['vfr']['photo'];
    }
    
    public function migratePhotosAction()
    {
        $photoModel = new Common_Model_Photo();
        
        $photoRowset = $photoModel->getAllPhotos();
        
        foreach ($photoRowset as $photoRow) {
            $idPhoto    = $photoRow->idPhoto;
            $idProperty = $photoRow->idProperty;
            
            $photoModel->generateDirectoryStructure($idProperty, $idPhoto, $this->_photoConfig['images_original_dir']);
            
            
            $topLevelId     = $photoModel->topLevelDirByPropertyId($idProperty);
            $topLevelDir    = $this->_photoConfig['images_original_dir'] . DIRECTORY_SEPARATOR . $topLevelId;
            $secondLevelDir = $topLevelDir . DIRECTORY_SEPARATOR . $idProperty;
        
            $sourceFile = APPLICATION_PATH . '/../public/properties/' . $idPhoto . ".jpg";
            $destFile   = $secondLevelDir . DIRECTORY_SEPARATOR . $idPhoto. ".jpg";
            
            var_dump($sourceFile);
            var_dump($destFile);
            
            copy ($sourceFile, $destFile);
            chmod($destFile, 0660);
        }
    }
}