<?php
class Admin_PropertyController extends Zend_Controller_Action
{
    public function init() {}
    
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
    
	private function indent($depth=1) {
		$tabs = "";
		for ($i=0; $i<$depth; $i++) {
			$tabs .= "\t";
		}
		
		return $tabs;
	}
	
    public function setLocationAction()
    {
		$request = $this->getRequest();
		$idProperty = $request->getParam('idProperty');
		$idLocation	= $request->getParam('idLocation');
		
		$form = new Admin_Form_LocationSelectForm (
			array (
				'idProperty' => $idProperty,
				'idLocation' => $idLocation
			)
		);
		
		if ($this->getRequest()->isPost()) {
			if ($form->isValid($this->getRequest()->getPost())) {
				$propertyModel = new Common_Model_Property();
				
				$propertyModel->updatePropertyLocationId (
					$idProperty,
					$form->getValue('idLocation')
				);
				
				$this->_helper->redirector->gotoSimple('list-awaiting-initial-approval', 'index', 'admin', array());
			}
		}

		
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
        
		$xhtml = '<div id="lhierarchy">' . "\n";
		$xhtml .= '<ul id="browser" class="filetree treeview">' . "\n";
		$currentDepth = $hierarchy[0]->depth;
		$starting = true;
		foreach ($hierarchy as $row) {
			if ($row->depth > $currentDepth) {
				$xhtml .= $this->indent($currentDepth) . "<ul>\n";
				$currentDepth = $row->depth;
			} elseif ($row->depth < $currentDepth) {
				$num = $currentDepth - $row->depth;
				//var_dump($num);
				for ($i=0; $i<$num; $i++) {
					$xhtml .= $this->indent($currentDepth-1) . "</ul>\n" . $this->indent($currentDepth-1) . "</li>\n\n";					
				}
				$currentDepth = $row->depth;
			}// else if (($row->depth == $currentDepth) && (!$starting)) {
			//	$xhtml .= "</li>\n";
			//	$starting = false;
			//}
							
			
			if (($row->lt == ($row->rt - 1)) && ($row->idProperty)) {
				$xhtml .= $this->indent($currentDepth) . '<li class="jstree-leaf jstree-locked" id="' . $row->idLocation . '"><a href="#">' . $row->rowname . "</a></li>\n";
			} else {
				$xhtml .= $this->indent($currentDepth) . '<li class="jstree-closed location" id="' . $row->idLocation . '"><a href="#">' . $row->rowname . "</a>\n";
			}
			//var_dump($row);
	
			//var_dump($xhtml);
			//echo "<hr />";
		}
		
		
		$xhtml .= "</ul></li>\n";
		$xhtml .= "</div>\n";
		
		$this->view->hierarchy = $xhtml;
		
        
        
        // Enable jQuery to pickup the headers etc
		ZendX_JQuery::enableForm($form);
        $jquery = $this->view->jQuery();
		$jquery->enable()
			   ->uiEnable();
        
		
		
		
		$this->view->form = $form;
		$this->view->propertyContentRowset = $propertyContentRowset;
        $this->view->headScript()->appendFile('/js/jquery-plugins/jstree-1.0/jquery.jstree.js')
								 ->appendFile('/js/jquery-plugins/jstree-1.0/jquery.cookie.js')
                                 ->appendFile('/js/admin/set-location.js');
        $this->view->headLink()->appendStylesheet('/js/jquery-plugins/jstree-1.0/themes/default/style.css');
        //$this->view->hierarchy = $hierarchy;
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