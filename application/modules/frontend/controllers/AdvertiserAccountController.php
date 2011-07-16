<?php
class AdvertiserAccountController extends Zend_Controller_Action
{
    protected $_model;

    public function init()
    {
        $this->_advertiserModel = new Common_Model_Advertiser();
        $this->_propertyModel   = new Common_Model_Property();
	}

    public function homeAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $advertiserRow = Zend_Auth::getInstance()->getIdentity();
            $idAdvertiser = $advertiserRow->idAdvertiser;

            // get a list of properties belonging to this advertiser
            $propertyRowset = $this->_propertyModel->getPropertiesByAdvertiserId($idAdvertiser);

            // setup the view
            $this->view->propertyRowset = $propertyRowset;
            $this->view->name = $advertiserRow->firstname . ' ' . $advertiserRow->lastname;

            //var_dump($advertiserRow);
            //var_dump($results);
        }

		//if (!$this->_helper->acl('Advertiser')) {
        if (!$this->_advertiserModel->checkAcl('listAccount')) {
            throw new Vfr_Exception('Access Denied');
        }
    }
	
	public function digestKeyFailAction()
	{
	}
	
}
