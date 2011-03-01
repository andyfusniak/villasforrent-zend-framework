<?php
class AdvertiserPropertyController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_logger = Zend_Registry::get('logger');
		$this->_logger->log(__METHOD__ . ' started method function init()', Zend_Log::DEBUG);
    }
	
	public function step1LocationAction()
	{
		$form = new Frontend_Form_Step1LocationForm();
		$form->setAction(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-property/step1-location');
		
		if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
			if ($form->isValid($formData)) {
                echo 'success';
				var_dump(Zend_Auth::getIdentity());
				exit;
				if (Zend_Auth::getInstance()->hasIdentity()) {
					var_dump(Zend_Auth::getInstance()->getIdentity());
					exit;
				}
				$propertyModel = new Common_Property_Model();
				$options = array();
				$options['params'] = $form->getValues();
				$propertyModel->createProperty($options);
                exit;
            } else {
                $form->populate($formData);
            }
        }
		
		$this->view->form = $form;
	}
	
	
	public function step2PropertyAction()
	{
		$form = new Frontend_Form_Step2PropertyForm();
		
		if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
			if ($form->isValid($formData)) {
                echo 'success';
                exit;
            } else {
                $form->populate($formData);
            }
        }
		
		$this->view->form = $form;
	}
	
	public function step3PicturesAction()
	{
		$form = new Frontend_Form_Step3PicturesForm();
		
		if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
			if ($form->isValid($formData)) {
				// Uploading Document File on Server
				$upload = new Zend_File_Transfer_Adapter_Http();
				$upload->setDestination("/var/www/zendvfr/trunk/data/uploads");
				try {
					// upload received file(s)
					$upload->receive();
				} catch (Zend_File_Transfer_Exception $e) {
					$e->getMessage();
				}

				// so, Finally lets See the Data that we received on Form Submit
				$uploadedData = $form->getValues();
				Zend_Debug::dump($uploadedData, 'Form Data:');
				
				// you MUST use following functions for knowing about uploaded file
				// Returns the file name for 'doc_path' named file element
				$name = $upload->getFileName('filename');
				
				// Returns the size for 'doc_path' named file element
				// Switches of the SI notation to return plain numbers
				$upload->setOption(array('useByteString' => false));
				$size = $upload->getFileSize('filename');
				
				// Returns the mimetype for the 'doc_path' form element
				$mimeType = $upload->getMimeType('fllename');
				
				// following lines are just for being sure that we got data
				print "Name of uploaded file: $name";
				print "File Size: $size";
				print "File's Mime Type: $mimeType";
				
				// New Code For Zend Framework :: Rename Uploaded File
				$renameFile = 'newName.jpg';
				$fullFilePath = '/images/'.$renameFile;
				
				// Rename uploaded file using Zend Framework
				$filterFileRename = new Zend_Filter_File_Rename(array(
														'target' => $fullFilePath,
														'overwrite' => true));
				$filterFileRename -> filter($name);
            } else {
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
}