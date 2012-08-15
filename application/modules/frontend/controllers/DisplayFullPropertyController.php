<?php
class DisplayFullPropertyController extends Zend_Controller_Action
{
    public function init() {}

    public function indexAction()
    {
        $locationModel  = new Common_Model_Location();
        $propertyModel  = new Common_Model_Property();
        $calendarModel  = new Common_Model_Calendar();

        $uri        = $this->getRequest()->getParam('uri');
        $idProperty = $this->getRequest()->getParam('idProperty');

        // get the main property details
        $propertyRow = $propertyModel->getPropertyById($idProperty);

        if (null === $propertyRow) {
            var_dump('not found');
            exit;
        }

        // get the content for this property
        $propertyContent = $propertyModel->getPropertyContentArrayById($propertyRow->idProperty);

        // fetch the rates and availability
        $idCalendar = $propertyModel->getCalendarIdByPropertyId($propertyRow->idProperty);
        $calendarRow = $propertyModel->getCalendarById($idCalendar);

        //$availabilityRowset = $calendarModel->getAvailabilityByCalendarId($idCalendar);
        $rateRowset = $calendarModel->getRatesByCalendarId($idCalendar);

        // fetch the photos for this property
        $photoRowset = $propertyModel->getAllPhotosByPropertyId($propertyRow->idProperty);
        //var_dump($photoRowset);

        $allFacilities  = $propertyModel->getAllFacilities();
        $facilityRowset = $propertyModel->getAllFacilities($propertyRow->idProperty);
        //var_dump($facilityRowset);

        $locationRow = $locationModel->lookup($propertyRow->locationUrl);



        //var_dump($propertyRow);
        //var_dump($locationRow);
        //die();

        // enable jQuery Core Library
        ZendX_JQuery::enableView($this->view);
        $jquery = $this->view->jQuery();
        $jquery->enable()
               ->uiEnable();

        // only include the member favourites javascript if the member is logged in
        if (Vfr_Auth_Member::getInstance()->hasIdentity()) {
            $identity = Vfr_Auth_Member::getInstance()->getIdentity();

            $this->view->headScript()->appendFile('/js/vfr/frontend/member-favourites.js');

            // check to see if this property has already been added to the member's favourites
            $memberFavouriteMapper = new Frontend_Model_MemberFavouriteMapper();
            $memberFavouriteObj = $memberFavouriteMapper->getFavouriteByMemberAndPropertyId(
                $identity->idMember,
                $propertyRow->idProperty
            );

            if ($memberFavouriteObj) {
                $classFavourited = 'class="favourited"';
            }
        }

        // Set the title of the page
        $this->view->headTitle(
            $propertyRow->shortName,
            Zend_View_Helper_Placeholder_Container_Abstract::SET
        );

        // Set the description of the page
        $this->view->headMeta($propertyContent['headline1'], 'description');

        $this->view->assign(
            array(
                'propertyRow'     => $propertyRow,
                'calendarRow'     => $calendarRow,
                'locationRow'     => $locationRow,
                'propertyContent' => $propertyContent,
                'photoRowset'     => $photoRowset,
                'rateRowset'      => $rateRowset,
                'classFavourited' => isset($classFavourited) ? $classFavourited : ''
            )
        );
    }
}
