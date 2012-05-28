<?php
class Admin_PropertyController extends Zend_Controller_Action
{
    public function init() {}

    public function listAwaitingUpdateApprovalAction()
    {
        $modelProperty = new Common_Model_Property();
        $propertyRowset = $modelProperty->getAllPropertiesAwaitingUpdateApproval();

        $this->view->propertyRowset = $propertyRowset;
    }

    public function viewUpdateApprovalAction()
    {
        $request = $this->getRequest();
        $idProperty = (int) $request->getParam('idProperty');

        $form = new Admin_Form_PropertyUpdateApprovalForm(
            array(
                'idProperty' => $idProperty
            )
        );

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $propertyModel = new Common_Model_Property();
                $propertyModel->copyUpdateToMaster($idProperty);

                $this->_helper->redirector->gotoSimple(
                    'list-awaiting-update-approval',
                    'property',
                    'admin',
                    array()
                );
            }
        }

        $propertyModel = new Common_Model_Property();
        $propertyContentMaster = $propertyModel->getPropertyContentByPropertyId(
            $idProperty,
            Common_Resource_PropertyContent::VERSION_MAIN
        );

        $propertyContentUpdate = $propertyModel->getPropertyContentByPropertyId(
            $idProperty,
            Common_Resource_PropertyContent::VERSION_UPDATE
        );

        //var_dump($propertyContentMaster);
        //var_dump($propertyContentUpdate);

        //die();

        //var_dump(Common_Resource_PropertyContent::generateChecksum(
            //$propertyContentMaster, true));
        //var_dump(Common_Resource_PropertyContent::generateChecksum(
            //$propertyContentUpdate, true));

        $changedList = array ();
        for ($i=0; $i < sizeof($propertyContentMaster); $i++) {
            $propertyRowMaster = $propertyContentMaster[$i];
            $propertyRowUpdate = $propertyContentUpdate[$i];

            //var_dump($i);
            //var_dump($propertyRowMaster);
            //var_dump($propertyRowUpdate->cs);

            // if the row checksums differ then changes need moderation
            if ($propertyRowMaster->cs != $propertyRowUpdate->cs) {
                $idPropertyContentField = $propertyRowMaster->idPropertyContentField;
                $changedList[$idPropertyContentField] = true;
            }
        }

        $this->view->assign(
            array(
                'form'        => $form,
                'master'      => $propertyContentMaster,
                'update'      => $propertyContentUpdate,
                'changedList' => $changedList
            )
        );
    }

    public function listAction()
    {
        $request = $this->getRequest();
        $page       = $request->getParam('page');
        $interval   = $request->getParam('interval');
        $order      = $request->getParam('order');
        $direction  = $request->getParam('direction');

        $propertyModel = new Common_Model_Property();

        $paginator = $propertyModel->getAllPaginator(
            $page,
            $interval,
            $order,
            $direction
        );

        $session = new Zend_Session_Namespace(Common_Model_Property::SESSION_NS_ADMIN_PROPERTY);

        // get a list of advertisers and locatiokn
        // to initise the view helper
        $advertisersList = array ();
        $locationList    = array ();

        foreach ($paginator as $property) {
            array_push($advertisersList, $property->idAdvertiser);
            array_push($locationList, $property->idLocation);
        }

        $this->view->advertiserIdToName($advertisersList);
        $this->view->locationIdToName($locationList);

        $this->view->assign(
            array (
                'paginator' => $paginator,
                'order'     => isset($session->order) ? $session->order : $order,
                'direction' => isset($session->direction) ? $session->direction : $direction
            )
        );
    }

    private function indent($depth=1) {
        $tabs = "";
        for ($i=0; $i<$depth; $i++) {
            $tabs .= "\t";
        }

        return $tabs;
    }

    public function setLocationAction()
    {
        $request = $this->getRequest();
        $idProperty = $request->getParam('idProperty');
        $idLocation = $request->getParam('idLocation');

        $locationModel = new Common_Model_Location();
        $locationsRowset = $locationModel->getAllLocations();

        $form = new Admin_Form_LocationSelectForm (
        array(
            'idProperty' => $idProperty,
            'idLocation' => $idLocation
            )
        );

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $propertyModel = new Common_Model_Property();

                $propertyModel->updatePropertyLocationIdAndUrl(
                    $idProperty,
                    $form->getValue('idLocation')
                );

                $this->_helper->redirector->gotoSimple(
                    'list-awaiting-initial-approval',
                    'index',
                    'admin',
                    array()
                );
            }
        }

        $propertyModel = new Common_Model_Property();
        $propertyContentRowset= $propertyModel->getPropertyContentArrayById(
            $idProperty,
            Common_Resource_PropertyContent::VERSION_MAIN,
            'EN',
            array(
                Common_Resource_PropertyContent::FIELD_COUNTRY,
                Common_Resource_PropertyContent::FIELD_REGION,
                Common_Resource_PropertyContent::FIELD_LOCATION
            )
        );


        //$this->view->headScript()->appendFile('/js/jquery-plugins/jstree-1.0/jquery.jstree.js')
        //                         ->appendFile('/js/jquery-plugins/jstree-1.0/jquery.cookie.js')
        //                         ->appendFile('/js/admin/set-location.js');
        //$this->view->headLink()->appendStylesheet('/js/jquery-plugins/jstree-1.0/themes/default/style.css');
        //$this->view->hierarchy = $hierarchy;

        // Enable jQuery to pickup the headers etc
        ZendX_JQuery::enableForm($form);
        ZendX_JQuery::enableView($this->view);
        $jquery = $this->view->jQuery();
        $jquery->enable()->uiEnable();

        $this->view->headLink()->appendStylesheet('/js/dynatree/skin/ui.dynatree.css');
        $this->view->headScript()->appendFile('/js/dynatree/jquery.dynatree.min.js');
        $this->view->headScript()->appendFile('/js/admin/set-location-tree.js');

        $this->view->assign(
            array(
                'form'                  => $form,
                'propertyContentRowset' => $propertyContentRowset,
                'locationRowset'        => $locationsRowset
            )
        );
    }

    public function setUrlNameAction()
    {
        $request = $this->getRequest();
        $idProperty = $request->getParam('idProperty');
        $urlName    = $request->getParam('urlName');

        $propertyModel = new Common_Model_Property();
        $propertyRow = $propertyModel->getPropertyById($idProperty);

        $form = new Admin_Form_UrlNameForm(
            array(
                'idProperty' => $idProperty,
                'urlName'    => $urlName
            )
        );

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $propertyModel = new Common_Model_Property();
                $propertyModel->setPropertyUrlName(
                    $idProperty,
                    $form->getValue('urlName')
                );

                $this->_helper->redirector->gotoSimple(
                    'list-awaiting-initial-approval',
                    'index',
                    'admin',
                    array()
                );
            }
        }

        $this->view->assign(
            array(
                'form'      => $form,
                'shortName' => $propertyRow->shortName
            )
        );
    }

    public function setExpiryAction()
    {
        $request = $this->getRequest();
        $idProperty = $request->getParam('idProperty');
        $expiry     = $request->getParam('expiry');

        $form = new Admin_Form_PropertyExpiryForm(
            array(
                'idProperty' => $idProperty,
                'expiry'     => $expiry
            )
        );

        // Enable jQuery to pickup the headers etc
        ZendX_JQuery::enableForm($form);
        $jquery = $this->view->jQuery();
        $jquery->enable()->uiEnable();

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $propertyModel = new Common_Model_Property();
                $propertyModel->setPropertyExpiryDate($idProperty,
                    $form->getValue('expiry')
                );

                $this->_helper->redirector->gotoSimple(
                    'list-awaiting-initial-approval',
                    'index',
                    'admin',
                    array()
                );
            }
        }

        $this->view->headScript()->appendFile('/js/admin/set-property-expiry.js');
        $this->view->form = $form;
    }

    public function approvePropertyAction()
    {
        $request = $this->getRequest();
        $idProperty = $request->getParam('idProperty');

        try {
            $propertyModel = new Common_Model_Property();
            $propertyRow = $propertyModel->getPropertyById($idProperty);

            if ($propertyModel) {
                $locationModel = new Common_Model_Location();
                $locationRow = $locationModel->getLocationByPk($propertyRow->idLocation);
            }
        } catch (Exception $e) {
            throw $e;
        }

        $form = new Admin_Form_PropertyApprovalForm(
            array(
                'idProperty' => $idProperty
            )
        );

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $propertyModel = new Common_Model_Property();
                $propertyModel->initialApproveProperty($propertyRow);

                $this->_helper->redirector->gotoSimple(
                    'list-awaiting-initial-approval',
                    'index',
                    'admin',
                    array()
                );
            }
        }

        $this->view->assign(
            array(
                'form'        => $form,
                'propertyRow' => $propertyRow,
                'locationRow' => $locationRow
            )
        );
    }
}
