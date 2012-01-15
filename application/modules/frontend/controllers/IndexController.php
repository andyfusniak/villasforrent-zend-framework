<?php
class IndexController extends Zend_Controller_Action
{
	private $locationModel;
	
    public function init() {}

    public function indexAction()
    {
		$locationModel = new Common_Model_Location();
		$propertyModel = new Common_Model_Property();
		
		$idLocationRow = $locationModel->lookup('');
		
		$this->_helper->featuredProperty($idLocationRow->idLocation);
    }
}