<?php
class AdvertiserAccountController extends Zend_Controller_Action
{
    protected $_model;

    public function init()
    {
        $this->_advertiserModel = new Common_Model_Advertiser();
        $this->_propertyModel   = new Common_Model_Property();
	}
	
	public function preDispatch()
	{
		$this->_helper->ensureSecure();
	}

    public function homeAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $advertiserRow = Zend_Auth::getInstance()->getIdentity();
            $idAdvertiser = $advertiserRow->idAdvertiser;

            // get a list of properties belonging to this advertiser
            $propertyRowset = $this->_propertyModel->getPropertiesByAdvertiserId($idAdvertiser);

			// create a list of property id's that we need primary photos for
			$idPropertyList = array ();
			foreach ($propertyRowset as $propertyRow) {
				array_push($idPropertyList, $propertyRow->idProperty);
			}
			
			//var_dump($idPropertyList);
			
			$photoRowsetLookup = $this->_propertyModel->getPrimaryPhotosByPropertyLookup($idPropertyList);

			//var_dump($photoRowsetLookup);
			//die();

		    // setup the view
			$this->view->assign(
				array(
					'propertyRowset'    => $propertyRowset,
					'name'			    => $advertiserRow->firstname . ' ' . $advertiserRow->lastname,
					'photoRowsetLookup'	=> null
				)
			);
        }

		//if (!$this->_helper->acl('Advertiser')) {
        if (!$this->_advertiserModel->checkAcl('listAccount')) {
            throw new Vfr_Exception('Access Denied');
        }
    }
	
	public function digestKeyFailAction() {}
}
