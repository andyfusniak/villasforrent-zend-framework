<?php
class AdvertiserAvailabilityController extends Zend_Controller_Action
{
    public function deleteConfirmAction()
	{
		$idProperty = $this->getRequest()->getParam('idProperty');
		$startDate	= $this->getRequest()->getParam('startDate');
		$endDate	= $this->getRequest()->getParam('endDate');
        
        $form = new Frontend_Form_Step5AvailabilityDeleteConfirmForm();
        
        if ($this->getRequest()->isPost()) {
			if ($form->isValid($this->getRequest()->getPost())) {
                
                if ($this->getRequest()->getParam('do') == 'cancel') {
					$this->_helper->redirector->gotoSimple('step5-availability', 'advertiser-property', 'frontend', array('idProperty' => $idProperty));
				}
                
                $propertyModel = new Common_Model_Property();
				$idCalendar = $propertyModel->getCalendarIdByPropertyId($idProperty);
				
				$calendarModel = new Common_Model_Calendar();
				$startDate 	= $this->getRequest()->getParam('startDate');
				$endDate	= $this->getRequest()->getParam('endDate');
				$rateRow 	= $calendarModel->getAvailabilityByStartAndEndDate($idCalendar, $startDate, $endDate);
                
				$rateRow->delete();
				$this->_helper->redirector->gotoSimple('step5-availability', 'advertiser-property', 'frontend', array('idProperty' => $idProperty));
			}
		}
        
        $this->view->assign(array(
           'form'       => $form,
           'idProperty' => $idProperty,
           'startDate'  => $startDate,
           'endDate'    => $endDate
        ));
    }
	
	public function editAction()
	{
		$idProperty 	= $this->getRequest()->getParam('idProperty');
		$idAvailability = $this->getRequest()->getParam('idAvailability');
		
		$propertyModel = new Common_Model_Property();
		$calendarModel = new Common_Model_Calendar();
		
		$propertyRow = $propertyModel->getPropertyById($idProperty);
		$idCalendar  = $propertyModel->getCalendarIdByPropertyId($idProperty);
		$availabilityRowset = $calendarModel->getAvailabilityByCalendarId($idCalendar);
		
		$availabilityRow = $calendarModel->getAvailabilityByPk($idAvailability);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form = new Frontend_Form_Step5AvailabilityEditForm(array(
				'idProperty'  	 => $idProperty,
				'idAvailability' => $availabilityRow->idAvailability,
				'availability'	 => $request->getParam('availability')));
			
			if ($form->isValid($request->getPost())) {
                $calendarModel->updateAvailabilityByPk($availabilityRow->idAvailability, $form->getValues());
				$this->_helper->redirector->gotoSimple('step5-availability', 'advertiser-property', 'frontend', array('idProperty' => $form->getValue('idProperty')));
            }
		} else {
			$form = new Frontend_Form_Step5AvailabilityEditForm(array(
				'idProperty'		=> $idProperty,
				'idAvailability'	=> $availabilityRow->idAvailability,
				'availability'		=> array ('start'	=> $availabilityRow->startDate,
											  'end'		=> $availabilityRow->endDate)
			));
		}
		
		// Enable jQuery to pickup the headers etc
		ZendX_JQuery::enableForm($form);
        $jquery = $this->view->jQuery();
		$jquery->enable()
			   ->uiEnable();
		
		$this->view->headScript()->appendFile('/js/vfr/step5-availability.js');
		
		$this->view->assign(array (
			'form'					=> $form,
			'propertyRow'			=> $propertyRow,
			'availabilityRowset'	=> $availabilityRowset
			
		));
	}
}