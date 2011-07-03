<?php
class Admin_PropertyController extends Zend_Controller_Action
{
    public function init()
    {
        //$this->_helper->layout->disableLayout();
    }
    
	public function listAwaitingUpdateApprovalAction()
	{
		$modelProperty = new Common_Model_Property();
		$propertyRowset = $modelProperty->getAllPropertiesAwaitingUpdateApproval();
		
		$this->view->propertyRowset = $propertyRowset;
	}
	
	public function viewUpdateApprovalAction()
	{
		$idProperty = (int) $this->getRequest()->getParam('idProperty');
		
		
		$propertyModel = new Common_Model_Property();
		$propertyContentMaster = $propertyModel->getPropertyContentArrayById($idProperty, Common_Resource_PropertyContent::VERSION_MAIN);
		$propertyContentUpdate = $propertyModel->getPropertyContentArrayById($idProperty, Common_Resource_PropertyContent::VERSION_UPDATE);
		//var_dump($propertyContentMaster);
		//var_dump($propertyContentUpdate);
		
		//die();
		
		//var_dump(Common_Resource_PropertyContent::generateChecksum($propertyContentMaster, true));
		//var_dump(Common_Resource_PropertyContent::generateChecksum($propertyContentUpdate, true));
		
		$changed = array ();
		foreach ($propertyContentMaster as $name=>$value) {
			if ($propertyContentUpdate[$name] != $value) {
				$changed[$name] = array ('master' => $propertyContentMaster[$name],
										 'update' => $propertyContentUpdate[$name]);
			}
		}
		
		$form = new Admin_Form_PropertyUpdateApprovalForm(array ('idProperty' => $idProperty));
		
		if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
				$propertyModel = new Common_Model_Property();
				$propertyModel->copyUpdateToMaster($idProperty);
				$this->_helper->redirector->gotoSimple('list-awaiting-update-approval', 'property', 'admin', array());
			}
		}
		//var_dump($changed);
		
		$this->view->form		= $form;
		$this->view->master 	= $propertyContentMaster;
		$this->view->update 	= $propertyContentUpdate;
		$this->view->changed 	= $changed;
	}
	
    public function listAction()
    {
        $page       = $this->getRequest()->getParam('page', 1);
        $interval   = $this->getRequest()->getParam('interval', 30);
        $order      = $this->getRequest()->getParam('order', null);
        $direction  = $this->getRequest()->getParam('direction', 'ASC');
        
        $propertyModel = new Common_Model_Property();
        $paginator = $propertyModel->getProperties($page, $interval, $order, $direction);
        
        $this->view->assign( array (
            'paginator' => $paginator,
            'direction' => $direction
        ));
    }
    
    public function setLocationAction()
    {
		$idProperty = $this->getRequest()->getParam('idProperty');
		$idLocation	= $this->getRequest()->getParam('idLocation');
		
		$propertyModel			= new Common_Model_Property();
		$propertyContentRowset	= $propertyModel->getPropertyContentArrayById($idProperty,
																		 Common_Resource_PropertyContent::VERSION_MAIN,
																		 'EN',
																		 array (Common_Resource_PropertyContent::FIELD_COUNTRY,
																				Common_Resource_PropertyContent::FIELD_REGION,
																				Common_Resource_PropertyContent::FIELD_LOCATION)
																		);
        $locationModel 	= new Common_Model_Location();
        $hierarchy = $locationModel->getLocationHierarchy();
        
		var_dump($hierarchy);
		die();
        $form = new Admin_Form_LocationSelectForm(array ('idProperty' => $idProperty,
														 'idLocation' => $idLocation));
        
        // Enable jQuery to pickup the headers etc
		ZendX_JQuery::enableForm($form);
        $jquery = $this->view->jQuery();
		$jquery->enable()
			   ->uiEnable();
        
		if ($this->getRequest()->isPost()) {
			if ($form->isValid($this->getRequest()->getPost())) {
				$propertyModel = new Common_Model_Property();
				$propertyModel->updatePropertyLocation($idProperty, $form->getValue('idFastLookup'));
				
				$this->_helper->redirector->gotoSimple('list-awaiting-initial-approval', 'index', 'admin', array());
			}
		}
		
		$this->view->form = $form;
		$this->view->propertyContentRowset = $propertyContentRowset;
        $this->view->headScript()->appendFile('/js/jquery-plugins/jstree-1.0/jquery.jstree.js')
								 ->appendFile('/js/jquery-plugins/jstree-1.0/jquery.cookie.js')
                                 ->appendFile('/js/admin/set-location.js');
        $this->view->headLink()->appendStylesheet('/js/jquery-plugins/jstree-1.0/themes/default/style.css');
        $this->view->hierarchy = $hierarchy;
    }
	
	public function setUrlNameAction()
	{
		$idProperty = $this->getRequest()->getParam('idProperty');
		$urlName	= $this->getRequest()->getParam('urlName');
		
		$propertyModel	= new Common_Model_Property();
		$propertyRow	= $propertyModel->getPropertyById($idProperty);
		
		
		$form = new Admin_Form_UrlNameForm(array ('idProperty' 	=> $idProperty,
												  'urlName'		=> $urlName));

		if ($this->getRequest()->isPost()) {
			if ($form->isValid($this->getRequest()->getPost())) {
				$propertyModel = new Common_Model_Property();
				$propertyModel->setPropertyUrlName($idProperty, $form->getValue('urlName'));
				
				$this->_helper->redirector->gotoSimple('list-awaiting-initial-approval', 'index', 'admin', array());
			}
		}
		
		$this->view->form 		= $form;
		$this->view->shortName	= $propertyRow->shortName;
	}
	
	public function setExpiryAction()
	{
		$idProperty = $this->getRequest()->getParam('idProperty');
		$expiry		= $this->getRequest()->getParam('expiry');
		
		$form = new Admin_Form_PropertyExpiryForm( array ('idProperty'	=> $idProperty,
														  'expiry'		=> $expiry));
		
		// Enable jQuery to pickup the headers etc
		ZendX_JQuery::enableForm($form);
        $jquery = $this->view->jQuery();
		$jquery->enable()
			   ->uiEnable();
		
		if ($this->getRequest()->isPost()) {
			if ($form->isValid($this->getRequest()->getPost())) {
				$propertyModel = new Common_Model_Property();
				$propertyModel->setPropertyExpiryDate($idProperty, $form->getValue('expiry'));
				
				$this->_helper->redirector->gotoSimple('list-awaiting-initial-approval', 'index', 'admin', array());
			}
		}
		
		$this->view->headScript()->appendFile('/js/admin/set-property-expiry.js');
		$this->view->form = $form;
	}
	
	public function approvePropertyAction()
	{
		$idProperty = $this->getRequest()->getParam('idProperty');
		
		$propertyModel		= new Common_Model_Property();
		$fastLookupModel	= new Common_Model_FastLookup();
		
		$propertyRow 	= $propertyModel->getPropertyById($idProperty);
		$fastLookupRow	= $fastLookupModel->getFastLookupByCountryRegionDestinationId($propertyRow->idCountry,
																					  $propertyRow->idRegion,
																					  $propertyRow->idDestination);
		
		$form = new Admin_Form_PropertyApprovalForm( array ('idProperty' => $idProperty));
		
		if ($this->getRequest()->isPost()) {
			if ($form->isValid($this->getRequest()->getPost())) {
				$propertyModel = new Common_Model_Property();
				$propertyModel->initialApproveProperty($idProperty,
													   $fastLookupRow->idCountry, $fastLookupRow->countryName,
													   $fastLookupRow->idRegion, $fastLookupRow->regionName,
													   $fastLookupRow->idDestination, $fastLookupRow->destinationName,
													   $fastLookupRow->url, $propertyRow->urlName);
				
				$this->_helper->redirector->gotoSimple('list-awaiting-initial-approval', 'index', 'admin', array());
			}
		}
		
		$this->view->form 			= $form;
		$this->view->propertyRow 	= $propertyRow;
		$this->view->fastLookupRow	= $fastLookupRow;
	}
}