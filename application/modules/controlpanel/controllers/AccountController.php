<?php
class Controlpanel_AccountController extends Zend_Controller_Action
    implements Zend_Acl_Resource_Interface
{
    const version = '4.0.2';

    public static function getVersion() { return '4.0.0'; }

    protected $_advertiserModel;
    protected $_propertyModel;

    public function getResourceId()
    {
        return 'Controlpanel_AccountController';
    }

    public function init()
    {
        // ensure the advertiser is logged in
        if (!Vfr_Auth_Advertiser::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoSimple(
                'login',
                'authentication',
                'controlpanel'
            );
        }

        $this->_advertiserModel = new Common_Model_Advertiser();
        $this->_propertyModel   = new Common_Model_Property();
    }

    public function preDispatch()
    {
        $this->_helper->ensureLoggedIn();
        $this->_helper->ensureSecure();
        $this->_helper->ensureAccountEmailConfirmed();
    }

    public function homeAction()
    {
        //if (!$this->_helper->acl('Advertiser')) {
        //if (!$this->_advertiserModel->checkAcl('listAccount')) {
        //    throw new Vfr_Exception('Access Denied');
        //}
        
        // retrieve the identity object (an advertiser row)
        $advertiserRow = Vfr_Auth_Advertiser::getInstance()->getIdentity();

        // get a list of properties belonging to this advertiser
        $propertyRowset = $this->_propertyModel->getPropertiesByAdvertiserId(
            $advertiserRow->idAdvertiser
        );

        // create a list of property id's that we need primary photos for
        $idPropertyList = array();
        foreach ($propertyRowset as $propertyRow) {
            array_push($idPropertyList, $propertyRow->idProperty);
        }

        $photoRowsetLookup = $this->_propertyModel->getPrimaryPhotosByPropertyLookup(
            $idPropertyList
        );

        // setup the view
        $this->view->assign(
            array(
                'propertyRowset'    => $propertyRowset,
                'name'              => $advertiserRow->firstname . ' ' . $advertiserRow->lastname,
                'emailAddress'      => $advertiserRow->emailAddress,
                'photoRowsetLookup' => null
            )
        );
    }

    public function digestKeyFailAction() {}
}
