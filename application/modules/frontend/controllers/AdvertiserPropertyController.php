<?php
class AdvertiserPropertyController extends Zend_Controller_Action
{
	protected $identity;
	
    public function init()
    {
        $this->_logger = Zend_Registry::get('logger');
		$this->_logger->log(__METHOD__ . ' started method function init()', Zend_Log::DEBUG);
		
		// ensure the advertiser is logged in
		if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoSimple('login', 'advertiser-account', 'frontend');
		}
		
		$this->identity = Zend_Auth::getInstance()->getIdentity();
    }
	
	public function step1LocationAction()
	{
		$form = new Frontend_Form_Step1LocationForm();
		$form->setAction(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-property/step1-location');
		
		if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
			if ($form->isValid($formData)) {
				$propertyModel = new Common_Model_Property();
                
				$options = array();
				$options['params'] = $form->getValues();
                $options['params']['idAdvertiser'] = $this->identity->idAdvertiser;
				$options['params']['emailAddress'] = $this->identity->emailAddress;
				$idProperty = $propertyModel->createProperty($options);
				
                $this->_helper->redirector->gotoSimple('step2-content', 'advertiser-property', 'frontend', array('idProperty' => $idProperty));
            } else {
                $form->populate($formData);
            }
        }
		
		$this->view->form = $form;
	}
	
	
	public function step2ContentAction()
	{
		$idProperty = $this->getRequest()->getParam('idProperty');

		// create the form and set the hidden form element		
		$form = new Frontend_Form_Step2ContentForm($idProperty);
		$form->setAction(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-property/step2-content');
		$form->setFormIdProperty($idProperty);
		
		if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
			if ($form->isValid($formData)) {
				$propertyModel = new Common_Model_Property();
				
				$params = $form->getValues();
				$propertyModel->updateContent($idProperty, $params)
							  ->updatePropertyStatus($idProperty, Common_Resource_Property::STEP_3_PICTURES);
				
                $this->_helper->redirector->gotoSimple('step3-pictures', 'advertiser-property', 'frontend');
            } else {
                $form->populate($formData);
            }
        }
		
		$this->view->form = $form;
	}
	
	public function step3PicturesAction()
	{
		$idProperty = $this->getRequest()->getParam('idProperty');
		
		// get the destination from the configuration
		$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
		$vfrConfig = $bootstrap['vfr'];
				
		$form = new Frontend_Form_Step3PicturesForm($idProperty);
		$form->setAction(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-property/step3-pictures');
		$form->setFormIdProperty($idProperty);
		
		// get the file information, we need this to write the photo DB entry if the
		// file is valid, or if it's not a valid we'll use to to log the type for
		// future support
		$fileElement 		= $form->getElement('filename');
		$transferAdapter 	= $fileElement->getTransferAdapter();
		$fileInfo 			= $transferAdapter->getFileInfo();
		//var_dump($fileInfo);
		
		if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
			if ($form->isValid($formData)) {
				// create a new photo entry in the DB
				// 1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP
				// 7 = TIFF(orden de bytes intel), 8 = TIFF(orden de bytes motorola)
				// 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF, 15 = WBMP, 16 = XBM. 
				list($width, $height, $type, $attr) = getimagesize($fileInfo['filename']['tmp_name']);
				$type = (int) $type;
				switch ($type) {
					case 2:
						$typeString = 'JPG';
						break;
					case 3:
						$typeString = 'PNG';
						break;
					default:
						$this->_logger->log(__METHOD__ . ' getimagesize returned strange file type: ' . $type, Zend_Log::DEBUG);
				}
				
				$params = array (
					'approved'			=> 0,
					'displayPriority'	=> 1,
					'originalFilename'	=> $fileInfo['filename']['name'],
					'fileType'			=> $typeString, //'fileType'			=> $fileInfo['filename']['type'],
					'widthPixels'		=> $width,
					'heightPixels'		=> $height,
					'sizeK'				=> $fileInfo['filename']['size'],
					'caption'			=> $this->getRequest()->getParam('caption'),
					'visible'			=> 1,
					'lastModifiedBy'	=> 'system'
				);
				
				$modelPhoto = new Common_Model_Photo();
				$idPhoto 	= $modelPhoto->addPhotoByPropertyId($idProperty, $params);
				try {
					$destinationDirectory = $modelPhoto->generateDirectoryStructure($idProperty, $idPhoto, $vfrConfig['photo']['images_original_dir']);
				} catch (Vfr_Exception $e) {
					throw $e;
				}
				
				try {
					// upload received file(s)
					$this->_logger->log(__METHOD__ . ' Upload dir = ' . $destinationDirectory, Zend_Log::DEBUG);
					$transferAdapter->setDestination($destinationDirectory);
					
					// Rename Uploaded File
					$renameFile 		= $idPhoto . '.' . strtolower($typeString);
					$fullFilePath 		= $destinationDirectory . DIRECTORY_SEPARATOR . $renameFile;			
					$filterFileRename 	= new Zend_Filter_File_Rename(array('target' => $fullFilePath, 'overwrite' => false));
					$transferAdapter->addfilter($filterFileRename);
					$transferAdapter->receive();
				} catch (Zend_File_Transfer_Exception $e) {
					$e->getMessage();
				}
            } else {
				$this->_logger->log(__METHOD__ . ' photo upload form rejected type: ' . $fileInfo['filename']['type'], Zend_Log::DEBUG);
                $form->populate($formData);
            }
        }
		
		$this->view->form = $form;
	}
	
	public function step4RatesAction()
	{
		$propertyModel = new Common_Model_Property();
        
        $idProperty = $this->getRequest()->getParam('idProperty');
        $idCalendar = $propertyModel->getCalendarIdByPropertyId($idProperty);
		
		$ratesRowSet = $propertyModel->getRatesByCalendarId($idCalendar);
		
        // find out the rental basis and base currency for this calendar
        $calendarRow = $propertyModel->getCalendarById($idCalendar);
        
		//$form = new Frontend_Form_Step4RatesForm();
		
		//if ($this->getRequest()->isPost()) {
        //    $formData = $this->getRequest()->getPost();
        //    
		//	if ($form->isValid($formData)) {
         //       echo 'success';
        //        exit;
        //    } else {
        //        $form->populate($formData);
        //    }
        //}
		
		//$this->view->form = $form;
        //var_dump($this->view->min(14));
        //exit;
        $this->view->idProperty   = $idProperty;
        $this->view->rentalBasis  = $calendarRow->rentalBasis;
        $this->view->baseCurrency = $calendarRow->currencyCode;
		$this->view->ratesRowSet  = $ratesRowSet;
	}
	
	public function step5AvailabilityAction()
	{	
	}

    public function addAction()
    {
    }
}
