<?php
class DashboardController extends Zend_Controller_Action
{
    protected $_identity = null;

    public function init() {}

    public function preDispatch()
    {
        $this->_helper->ensureMemberLoggedIn();
        $this->_identity = Vfr_Auth_Member::getInstance()->getIdentity();
    }

    public function homeAction()
    {
        //var_dump(Vfr_Auth_Member::getInstance()->getIdentity());
        //var_dump(Vfr_Auth_Advertiser::getInstance()->getIdentity());
    }

    public function favouritesAction()
    {
        // enable jQuery Core Library
        ZendX_JQuery::enableView($this->view);
        $jquery = $this->view->jQuery();
        $jquery->enable()
               ->uiEnable();

        $this->view->headScript()->appendFile('/js/vfr/frontend/member-favourites.js');
        
        $propertyService = new Common_Service_Property();
        $propertyContentService = new Common_Service_PropertyContent();
        $photoService = new Common_Service_Photo();
        $memberFavouriteMapper = new Frontend_Model_MemberFavouriteMapper();

        $favourites = $memberFavouriteMapper->getMemberFavourites(
            $this->_identity->idMember
        );

        // create a list of properties
        $idPropertyList = array();
        if ($favourites) {
            foreach ($favourites as $memberFavouriteObj) {
                $idProperty = $memberFavouriteObj->getPropertyId();

                array_push($idPropertyList, $idProperty);
            }
        }

        // get an associative array of property to photo-rows
        $photoLookup = $photoService->getPrimaryPhotosByPropertyListHashMap(
            $idPropertyList
        );

        // get the property content for these properties
        $idPropertyContentFieldList = array(
            Common_Resource_PropertyContent::FIELD_SUMMARY,
            Common_Resource_PropertyContent::FIELD_HEADLINE_1
        );

        // get an associative array of property rows
        $propertyLookup = $propertyService->getPropertiesByPropertyListHashMap(
            $idPropertyList
        );


        // get an associative array of property-content rows
        $propertyContentLookup = $propertyContentService->getPropertyContentByPropertyListHashMap(
            $idPropertyList,
            Common_Resource_PropertyContent::VERSION_MAIN,
            'EN',
            $idPropertyContentFieldList
        );

        $this->view->assign(
            array(
                'idPropertyList' => $idPropertyList,
                'photoLookup'    => $photoLookup,
                'propertyLookup' => $propertyLookup,
                'propertyContentLookup' => $propertyContentLookup
            )
        );
    }
}
