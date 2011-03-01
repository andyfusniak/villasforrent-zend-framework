<?php
class AdvertiserAccountController extends Zend_Controller_Action
{
    protected $_model;

    public function init()
    {
        $this->_model = new Common_Model_Advertiser();
	}

    public function homeAction()
    {
		//if (!$this->_helper->acl('Advertiser')) {
        if (!$this->_model->checkAcl('listAccount')) {
            throw new Vfr_Exception('Access Denied');
        }

        var_dump("index action");
    }
}
