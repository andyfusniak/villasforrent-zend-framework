<?php
class Controlpanel_AvailabilityController extends Zend_Controller_Action
{
	public function preDispatch()
	{
		$this->_helper->ensureSecure();
	}
	
    public function deleteConfirmAction()
	{
		$idProperty     = $this->getRequest()->getParam('idProperty');
		$idAvailability	= $this->getRequest()->getParam('idAvailability');
		$digestKey      = $this->getRequest()->getParam('digestKey');
		
		if (! $this->_helper->digestKey->isValid($digestKey, array($idProperty, $idAvailability))) {
			$this->_helper->redirector->gotoSimple(
                'digest-key-fail',
                'account',
                'controlpanel'
            );
		}
		
        $form = new Controlpanel_Form_Property_Step5AvailabilityDeleteConfirmForm(array(
			'idProperty'     => $idProperty,
			'idAvailability' => $idAvailability,
			'digestKey'		 => $digestKey
		));
        
		$calendarModel = new Common_Model_Calendar();
		$availabilityRow = $calendarModel->getAvailabilityByPk($idAvailability);
		
        if ($this->getRequest()->isPost()) {
			if ($form->isValid($this->getRequest()->getPost())) {
                
                if ($this->getRequest()->getParam('do') == 'cancel') {
					$this->_helper->redirector->gotoSimple(
                        'step5-availability',
                        'property',
                        'controlpanel',
						array (
                            'idProperty' => $idProperty,
							'digestKey'  => Vfr_DigestKey::generate(array($idProperty))
                        )
                    );
				}
                
                $propertyModel = new Common_Model_Property();
				$idCalendar = $propertyModel->getCalendarIdByPropertyId($idProperty);
				
				
                
				$availabilityRow->delete();
				$this->_helper->redirector->gotoSimple(
                    'step5-availability',
                    'property',
                    'controlpanel',
					array (
                        'idProperty' => $idProperty,
						'digestKey'  => Vfr_DigestKey::generate(array($idProperty))
                    )
                );
			}
		}
        
        $this->view->assign(
            array (
                'form'            => $form,
                'idProperty'      => $idProperty,
                'idAvailability'  => $idAvailability,
                'availabilityRow' => $availabilityRow
            )
        );
    }
	
	public function editAction()
	{
		$idProperty 	= $this->getRequest()->getParam('idProperty');
		$idAvailability = $this->getRequest()->getParam('idAvailability');
		$digestKey      = $this->getRequest()->getParam('digestKey');
		
		if (! $this->_helper->digestKey->isValid($digestKey, array($idProperty, $idAvailability))) {
			$this->_helper->redirector->gotoSimple(
                'digest-key-fail',
                'account',
                'controlpanel'
            );
		}
		
		$propertyModel = new Common_Model_Property();
		$calendarModel = new Common_Model_Calendar();
		
		$propertyRow = $propertyModel->getPropertyById($idProperty);
		$idCalendar  = $propertyModel->getCalendarIdByPropertyId($idProperty);
		$availabilityRowset = $calendarModel->getAvailabilityByCalendarId($idCalendar);
		
		$availabilityRow = $calendarModel->getAvailabilityByPk($idAvailability);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form = new Controlpanel_Form_Property_Step5AvailabilityEditForm(
                array (
                    'idProperty'  	 => $idProperty,
                    'idAvailability' => $availabilityRow->idAvailability,
                    'availability'	 => $request->getParam('availability'),
                    'digestKey'		 => Vfr_DigestKey::generate(array($idProperty, $idAvailability))
                )
            );
			
			if ($form->isValid($request->getPost())) {
                $calendarModel->updateAvailabilityByPk($availabilityRow->idAvailability, $form->getValues());
                
				$this->_helper->redirector->gotoSimple(
                    'step5-availability',
                    'property',
                    'controlpanel',
					array (
                        'idProperty' => $form->getValue('idProperty'),
						'digestKey'  => Vfr_DigestKey::generate(array($idProperty))
                    )
                );
            }
		} else {
			$form = new Controlpanel_Form_Property_Step5AvailabilityEditForm(
                array (
                    'idProperty'		=> $idProperty,
                    'idAvailability'	=> $availabilityRow->idAvailability,
                    'availability'		=> array (
                                               'start' => $availabilityRow->startDate,
											   'end'   => $availabilityRow->endDate
                                           ),
                    'digestKey'			=> Vfr_DigestKey::generate(array($idProperty, $idAvailability))
                )
            );
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
