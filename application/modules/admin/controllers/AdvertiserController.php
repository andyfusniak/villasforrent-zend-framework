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
		 $advertiser = $this->_advertiserModel->getAdvertiserById(3);

		 var_dump($advertiser);
	}
}
