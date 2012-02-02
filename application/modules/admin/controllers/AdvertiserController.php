<?php
class Admin_AdvertiserController extends Zend_Controller_Action
{
	const version = '1.0.0';
	
	public function indexAction()
	{
        $advertiserModel = new Common_Model_Advertiser();
        
		$request = $this->getRequest();
		$page      = $request->getParam('page');
		$interval  = $request->getParam('interval');
		$order     = $request->getParam('order');
		$direction = $request->getParam('direction');
		
		$advertiserPaginator = $advertiserModel->getAllPaginator(
			$page,
			$interval,
			$order,
			$direction
		);		
		
		$session = new Zend_Session_Namespace(Common_Model_Advertiser::SESSION_NS_ADMIN_ADVERTISER);
		
		$this->view->assign(
			array (
				'advertiserPaginator' => $advertiserPaginator,
				'order'				  => isset($session->order) ? $session->order : $order,
				'direction'		      => isset($session->direction) ? $session->direction : $direction
			)
		);
	}
}