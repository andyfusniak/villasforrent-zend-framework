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
	
	public function preDispatch()
	{
		$this->_helper->ensureSecure();
	}
	
    public function step1LocationAction()
    {
        $form = new Frontend_Form_Step1LocationForm();
        
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $propertyModel = new Common_Model_Property();
				
				$options = array();
				$options['params'] = $form->getValues();
                $options['params']['idAdvertiser']  = $this->identity->idAdvertiser;
				$options['params']['emailAddress']  = $this->identity->emailAddress;
				$idProperty = $propertyModel->createProperty($options);
                
				$this->_helper->redirector->gotoSimple(
                    'step2-content', 'advertiser-property', 'frontend',
					array(
                        'idProperty' => $idProperty,
						'digestKey'  => Vfr_DigestKey::generate(array($idProperty, 'add'))
                    )
                );
            }
        }
		
		$this->view->form = $form;
    }
	
	public function step2ContentAction()
	{
		$idProperty = $this->getRequest()->getParam('idProperty');
		$mode		= $this->getRequest()->getParam('mode', 'add');
		$digestKey  = $this->getRequest()->getParam('digestKey', null);
		
		if (! $this->_helper->digestKey->isValid($digestKey, array ($idProperty, $mode))) {
			$this->_helper->redirector->gotoSimple('digest-key-fail', 'advertiser-account', 'frontend');
		}
		
		//var_dump('mode ' . $mode);
		
		// get the holiday type. we need this to decide what form elements to render
		// for example, a golf holiday doesn't require a skiing box and so on
		$propertyModel = new Common_Model_Property();
		$idHolidayType = $propertyModel->getHolidayTypeByPropertyId($idProperty);
		
		// generate the digest key
		$newDigestKey = Vfr_DigestKey::generate(
			array (
				$idProperty,
				$mode
			)
		);
		
		// create the form and set the hidden form element	
		$form = new Frontend_Form_Step2ContentForm(
            array (
                'idProperty' 	=> $idProperty,
				'idHolidayType'	=> $idHolidayType,
				'mode'			=> $mode,
				'digestKey'		=> $newDigestKey
            )
        );


		$propertyContentList = $propertyModel->getPropertyContentArrayById(
			$idProperty,
			($mode == 'add') ? Common_Resource_PropertyContent::VERSION_MAIN : Common_Resource_PropertyContent::VERSION_UPDATE
		);
		
		$form->populate($propertyContentList);    
		
		if ($this->getRequest()->isPost()) {
			// if the client posts back the form, first check if the params were altered
			if ($form->isValid($this->getRequest()->getPost())) {
				$propertyModel = new Common_Model_Property();
				
				if ($mode == 'add') {
					$propertyModel->updateContent(
                        $idProperty,
                        array (
                            Common_Resource_PropertyContent::VERSION_MAIN,
                            Common_Resource_PropertyContent::VERSION_UPDATE
                        ),
                        $form->getValues()
                    )->updatePropertyStatus($idProperty, Common_Resource_Property::STEP_3_PICTURES);
					
					$this->_helper->redirector->gotoSimple(
						'step3-pictures',
						'advertiser-property',
						'frontend',
						array (
							'idProperty' => $idProperty,
							'digestKey'  => Vfr_DigestKey::generate(array($idProperty))
						)
					);
				} else {
					// update mode
					$propertyModel->updateContent(
                        $idProperty,
                        array (
                            Common_Resource_PropertyContent::VERSION_UPDATE
                        ),
                        $form->getValues()
                    );
					$this->_helper->redirector->gotoSimple('home', 'advertiser-account', 'frontend');
				}
				
            }
        }
	
		$this->view->form = $form;
	}
	
	public function step3PicturesAction()
	{
		$idProperty = $this->getRequest()->getParam('idProperty');
		$digestKey  = $this->getRequest()->getParam('digestKey');
		
		if (! $this->_helper->digestKey->isValid($digestKey, array($idProperty))) {
			$this->_helper->redirector->gotoSimple('digest-key-fail', 'advertiser-account', 'frontend');
		}
		
		$newDigestKey = Vfr_DigestKey::generate(array($idProperty));
		
		// get the destination from the configuration
		$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
		$vfrConfig = $bootstrap['vfr'];
		
		
		//var_dump($idProperty, $newDigestKey);
		$form = new Frontend_Form_Step3PicturesForm(
			array (
				'idProperty' => $idProperty,
				'digestKey'  => $newDigestKey
			)
		);
		
		// get the file information, we need this to write the photo DB entry if the
		// file is valid, or if it's not a valid we'll use to to log the type for future support
		$fileElement 		= $form->getElement('filename');
		$transferAdapter 	= $fileElement->getTransferAdapter();
		$fileInfo 			= $transferAdapter->getFileInfo();
		//var_dump($fileInfo);
				
		if ($this->getRequest()->isPost()) {         
			if ($form->isValid($this->getRequest()->getPost())) {
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
            }
        }
		
		// get a list of the photos belonging to this property
		$propertyModel = new Common_Model_Property();
		$propertyRow = $propertyModel->getPropertyById($idProperty);
		$photoRowset = $propertyModel->getAllPhotosByPropertyId($idProperty);
		
		$this->view->assign(array (
			'photoRowset'			=> $photoRowset,
			'photoCount'			=> sizeof($photoRowset),
			'propertyRow'			=> $propertyRow,
			'imagesOriginalDir'		=> $vfrConfig['photo']['images_original_dir'],
			'maxLimitPerProperty'	=> $vfrConfig['photo']['max_limit_per_property']
		));
		
		if ($this->view->photoCount < $this->view->maxLimitPerProperty)
			$this->view->form = $form;
	}
	
	public function step4RatesAction()
	{
        $idProperty = $this->getRequest()->getParam('idProperty');	
        $digestKey  = $this->getRequest()->getParam('digestKey');
		
		if (! $this->_helper->digestKey->isValid($digestKey, array($idProperty))) {
			$this->_helper->redirector->gotoSimple('digest-key-fail', 'advertiser-account', 'frontend');
		}
		
		$propertyModel = new Common_Model_Property();
        $calendarModel = new Common_Model_Calendar();
		$propertyRow = $propertyModel->getPropertyById($idProperty);
        $idCalendar  = $propertyModel->getCalendarIdByPropertyId($idProperty);
		
		$newDigestKey = Vfr_DigestKey::generate(array($idProperty));
		
		//var_dump($this->_getAllParams());
        $form = new Frontend_Form_Step4RatesForm(array(
			'idProperty' => $idProperty,
			'digestKey'  => $newDigestKey));
		
		// Enable jQuery to pickup the headers etc
		ZendX_JQuery::enableForm($form);
		$jquery = $this->view->jQuery();
		$jquery->enable()
			   ->uiEnable();
				   
		$request = $this->getRequest();
		if ($request->isPost()) {
			//var_dump($request->getParam('rates'));
        	if ($form->isValid($request->getPost())) {
				$calendarModel->addNewRate($idCalendar, $form->getValues());
				$this->_helper->redirector->gotoSimple('step4-rates', 'advertiser-property', 'frontend',
													   array('idProperty' => $idProperty,
															 'digestKey'  => $newDigestKey));
			}
		}
		
		$ratesRowset = $calendarModel->getRatesByCalendarId($idCalendar);
		
		// find out the rental basis and base currency for this calendar
        $calendarRow = $propertyModel->getCalendarById($idCalendar);

		$this->view->headScript()->appendFile('/js/vfr/step4-rates.js');

		$this->view->assign(array(
			'form'		    => $form,
			'propertyRow'	=> $propertyRow,
			'rentalBasis'	=> $calendarRow->rentalBasis,
			'baseCurrency'	=> $calendarRow->currencyCode,
			'ratesRowset'	=> $ratesRowset
		));
	}
	
	public function step5AvailabilityAction()
	{
		$idProperty = $this->getRequest()->getParam('idProperty');
		$digestKey  = $this->getRequest()->getParam('digestKey');
		
		if (! $this->_helper->digestKey->isValid($digestKey, array($idProperty))) {
			$this->_helper->redirector->gotoSimple('digest-key-fail', 'advertiser-account', 'frontend');
		}
		
		$propertyModel = new Common_Model_Property();
        $calendarModel = new Common_Model_Calendar();
		
		$propertyRow = $propertyModel->getPropertyById($idProperty);
        $idCalendar  = $propertyModel->getCalendarIdByPropertyId($idProperty);
        
		$newDigestKey = Vfr_DigestKey::generate(array($idProperty));
		$form = new Frontend_Form_Step5AvailablityForm(array(
			'idProperty' => $idProperty,
			'digestKey'	 => $newDigestKey
		));
		
		// Enable jQuery to pickup the headers etc
		ZendX_JQuery::enableForm($form);
        $jquery = $this->view->jQuery();
		$jquery->enable()
			   ->uiEnable();
		
		if ($this->getRequest()->isPost()) {
			if ($form->isValid($this->getRequest()->getPost())) {
				$calendarModel->addNewBooking($idCalendar, $form->getValues());
				$this->_helper->redirector->gotoSimple('step5-availability', 'advertiser-property', 'frontend', array('idProperty' => $idProperty,
																													  'digestKey'  => $newDigestKey));
			}
		}
		
		$availbilityRowset = $calendarModel->getAvailabilityByCalendarId($idCalendar);
											 
		$this->view->headScript()->appendFile('/js/vfr/step5-availability.js');
		
		$this->view->assign(array (
			'form'					=> $form,
			'propertyRow'			=> $propertyRow,
			'availabilityRowset'	=> $availbilityRowset
		));
	}
	
	public function completeConfirmationAction()
	{
	}

	public function updateSendConfirmationAction()
	{
	}

    public function addAction()
    {
    }
	
	public function progressStep4Action()
	{
		$idProperty = $this->getRequest()->getParam('idProperty');
		$digestKey  = $this->getRequest()->getParam('digestKey');
		
		if (! $this->_helper->digestKey->isValid($digestKey, array($idProperty))) {
			$this->_helper->redirector->gotoSimple('digest-key-fail', 'advertiser-account', 'frontend');
		}
		
		$propertyModel = new Common_Model_Property();
		$propertyModel->updatePropertyStatus($idProperty, Common_Resource_Property::STEP_4_RATES);
		
		$newDigestKey = Vfr_DigestKey::generate(array($idProperty));
		$this->_helper->redirector->gotoSimple('step4-rates', 'advertiser-property', 'frontend', array('idProperty' => $idProperty,
																									   'digestKey'  => $digestKey));
	}
	
	public function progressStep5Action()
	{
		$idProperty = $this->getRequest()->getParam('idProperty');
		$digestKey  = $this->getRequest()->getParam('digestKey');
		
		if (! $this->_helper->digestKey->isValid($digestKey, array($idProperty))) {
			$this->_helper->redirector->gotoSimple('digest-key-fail', 'advertiser-account', 'frontend');
		}
		
		$propertyModel = new Common_Model_Property();
		$propertyModel->updatePropertyStatus($idProperty, Common_Resource_Property::STEP_5_AVAILABILITY);
		
		$newDigestKey = Vfr_DigestKey::generate(array($idProperty));
		$this->_helper->redirector->gotoSimple('step5-availability', 'advertiser-property', 'frontend', array('idProperty' => $idProperty,
																											  'digestKey'  => $digestKey));
	}
	
	public function sendForInitialApprovalAction()
	{
		$idProperty = $this->getRequest()->getParam('idProperty');
		$digestKey  = $this->getRequest()->getParam('digestKey');
		
		if (! $this->_helper->digestKey->isValid($digestKey, array($idProperty))) {
			$this->_helper->redirector->gotoSimple('digest-key-fail', 'advertiser-account', 'frontend');
		}
		
		$propertyModel = new Common_Model_Property();
		$propertyModel->updatePropertyStatus($idProperty, Common_Resource_Property::COMPLETE)
					  ->setAwaitingApproval($idProperty);
	
		$newDigestKey = Vfr_DigestKey::generate(array($idProperty));
		$this->_helper->redirector->gotoSimple('complete-confirmation', 'advertiser-property', 'frontend', array('idProperty' => $idProperty,
																												 'digestKey'  => $digestKey));
	}
	
	public function sendForUpdateApprovalAction()
	{
		$idProperty = $this->getRequest()->getParam('idProperty');
		$digestKey  = $this->getRequest()->getParam('digestKey');
		
		if (! $this->_helper->digestKey->isValid($digestKey, array($idProperty))) {
			$this->_helper->redirector->gotoSimple('digest-key-fail', 'advertiser-account', 'frontend');
		}
		
		$propertyModel = new Common_Model_Property();
		$propertyModel->advertiserSendForUpdateApproval($idProperty);
		
		$newDigestKey = Vfr_DigestKey::generate(array($idProperty));
		$this->_helper->redirector->gotoSimple('update-send-confirmation', 'advertiser-property', 'frontend', array('idProperty' => $idProperty,
																													'digestKey'  => $digestKey));	
	}
}
