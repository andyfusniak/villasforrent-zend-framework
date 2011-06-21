<?php
class AdvertiserRatesController extends Zend_Controller_Action
{ 
    public function rentalBasisAction()
    {
        $idProperty = $this->getRequest()->getParam('idProperty');
		
        $form = new Frontend_Form_Step4RentalBasis();
        
		if ($this->getRequest()->isPost()) {
			if ($form->isValid($this->getRequest()->getPost())) {
				$propertyModel = new Common_Model_Property();
				$idCalendar = $propertyModel->getCalendarIdByPropertyId($idProperty);
			
				$calendarModel = new Common_Model_Calendar();
				$calendarModel->updateRentalBasis($idCalendar, $form->getValue('rentalBasis'));
				
				$this->_helper->redirector->gotoSimple('step4-rates', 'advertiser-property', 'frontend', array('idProperty' => $idProperty));
			}
		} else {
			$propertyModel = new Common_Model_Property();
			$idCalendar = $propertyModel->getCalendarIdByPropertyId($idProperty);
			
			$data = array ();
			$calendarModel = new Common_Model_Calendar();
			$rentalBasis = $calendarModel->getRentalBasis($idCalendar);
			if ($rentalBasis)
				$data['rentalBasis'] = $rentalBasis;
			$form->populate($data);
		}
		
        $this->view->form 		= $form;
		$this->view->idProperty = $idProperty;
    }
    
    public function baseCurrencyAction()
    {
        $idProperty = $this->getRequest()->getParam('idProperty');
		
		$form = new Frontend_Form_Step4BaseCurrency();
		
		if ($this->getRequest()->isPost()) {
			if ($form->isValid($this->getRequest()->getPost())) {
				$propertyModel = new Common_Model_Property();
				$idCalendar = $propertyModel->getCalendarIdByPropertyId($idProperty);
				
				$calendarModel = new Common_Model_Calendar();
				$calendarModel->updateBaseCurrency($idCalendar, $form->getValue('currencyCode'));
				
				$this->_helper->redirector->gotoSimple('step4-rates', 'advertiser-property', 'frontend', array('idProperty' => $idProperty));
			}
		} else {
			$propertyModel = new Common_Model_Property();
			$idCalendar = $propertyModel->getCalendarIdByPropertyId($idProperty);
			
			$data = array ();
			$calendarModel = new Common_Model_Calendar($idCalendar);
			$baseCurrency = $calendarModel->getBaseCurrency($idCalendar);
			if ($baseCurrency)
				$data['currencyCode'] = $baseCurrency;
			$form->populate($data);
		}
		
		$this->view->form 		= $form;
		$this->view->idProperty = $idProperty;
    }
	
	public function deleteConfirmAction()
	{
		$idProperty = $this->getRequest()->getParam('idProperty');
		$startDate	= $this->getRequest()->getParam('startDate');
		$endDate	= $this->getRequest()->getParam('endDate');
		
		$form = new Frontend_Form_Step4RateDeleteConfirmForm();
		
		if ($this->getRequest()->isPost()) {
			if ($form->isValid($this->getRequest()->getPost())) {
				
				if ($this->getRequest()->getParam('do') == 'cancel') {
					$this->_helper->redirector->gotoSimple('step4-rates', 'advertiser-property', 'frontend', array('idProperty' => $idProperty));
				}
				
				$propertyModel = new Common_Model_Property();
				$idCalendar = $propertyModel->getCalendarIdByPropertyId($idProperty);
				
				$calendarModel = new Common_Model_Calendar();
				$startDate 	= $this->getRequest()->getParam('startDate');
				$endDate	= $this->getRequest()->getParam('endDate');
				$rateRow 	= $calendarModel->getRateByStartAndEndDate($idCalendar, $startDate, $endDate);
				
				$rateRow->delete();
				$this->_helper->redirector->gotoSimple('step4-rates', 'advertiser-property', 'frontend', array('idProperty' => $idProperty));
			}
		}
		
		$this->view->assign(array(
			'form'			=> $form,
			'idProperty'	=> $idProperty,
			'startDate'		=> $startDate,
			'endDate'		=> $endDate
		));
	}
	
	public function editAction()
	{
		$idProperty = $this->getRequest()->getParam('idProperty');
		$idRate	    = $this->getRequest()->getParam('idRate');
		
		$propertyModel = new Common_Model_Property();
		$calendarModel = new Common_Model_Calendar();
		
		$propertyRow = $propertyModel->getPropertyById($idProperty);
		$idCalendar  = $propertyModel->getCalendarIdByPropertyId($idProperty);
		$ratesRowset = $calendarModel->getRatesByCalendarId($idCalendar);
		
		
		$rateRow = $calendarModel->getRateByPk($idRate);
		
		//die();
		$request = $this->getRequest();
		
		if ($request->isPost()) {
			//var_dump($request->getParams());
			$form = new Frontend_Form_Step4RateEditForm(array('idProperty'  => $idProperty,
															  'idRate'		=> $rateRow->idRate,
														      'name' 	    => $request->getParam('name'),
														      'rates'	    => $request->getParam('rates')));
			
			if ($form->isValid($request->getPost())) {
                $calendarModel->updateRateByPk($rateRow->idRate, $form->getValues());
				$this->_helper->redirector->gotoSimple('step4-rates', 'advertiser-property', 'frontend', array('idProperty' => $form->getValue('idProperty')));
            }
		} else {
			$form = new Frontend_Form_Step4RateEditForm(array('idProperty'  => $idProperty,
															  'name' 	    => $rateRow->name,
															  'idRate'	    => $rateRow->idRate,
															  'rates'	    => array ('start'	 			=> $rateRow->startDate,
																					  'end'					=> $rateRow->endDate,
																					  'weeklyRate'			=> $rateRow->weeklyRate,
																					  'weekendNightlyRate'	=> $rateRow->weekendNightlyRate,
																					  'midweekNightlyRate'  => $rateRow->midweekNightlyRate,
																					  'minStayDays'		    => $rateRow->minStayDays)));
		}
		
		//'2012-05-01#2012-05-08#300#340#400#7'
		
		// Enable jQuery to pickup the headers etc
		ZendX_JQuery::enableForm($form);
        $jquery = $this->view->jQuery();
		$jquery->enable()
			   ->uiEnable();
		
		$this->view->headScript()->appendFile('/js/vfr/step4-rates.js');
		
		$this->view->assign(array (
			'form'			=> $form,
			'propertyRow'	=> $propertyRow,
			'ratesRowset'	=> $ratesRowset
		));
	}
}