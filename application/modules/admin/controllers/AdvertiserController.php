<?php
class Admin_AdvertiserController extends Zend_Controller_Action
{
	protected $_advertisersModel;
	
	public function init()
	{	
		$this->_advertiserModel = new Common_Model_Advertiser();
	}

	public function indexAction()
	{
		//$advertiser = $this->_advertiserModel->getAdvertiserById(3);
	
		// we expect a Zend_Paginator to be returned because we have
		// specifed a page number.  If we set page=null we will get
		// the entire result set as a Common_Resource_Advertiser_Rowset
		$page = $this->getRequest()->getParam('page');
		$advertiserPaginator = $this->_advertiserModel->getAll($page);
		
		$this->view->advertiserPaginator = $advertiserPaginator;
		
		
		//var_dump($advertiserPaginator);
		
	}
}
