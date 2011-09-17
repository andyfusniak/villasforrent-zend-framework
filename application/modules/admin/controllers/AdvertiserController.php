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
		$request = $this->getRequest();
		$page      = $request->getParam('page');
		$interval  = $request->getParam('interval', 30);
		$sort      = $request->getParam('sort', 'idAdvertiser');
		$direction = $request->getParam('direction', 'ASC');
		
		$advertiserPaginator = $this->_advertiserModel->getAll($page, $interval, $sort, $direction);
		
		
		$this->view->assign(
			array (
				'advertiserPaginator' => $advertiserPaginator,
				'sort'				  => $sort,
				'direction'		      => $direction
			)
		);
		
	}
}
