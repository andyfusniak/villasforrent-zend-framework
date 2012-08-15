<?php
class Admin_FeaturedController extends Zend_Controller_action
{
    public function featureAction()
    {
        ZendX_JQuery::enableView($this->view);
        $jquery = $this->view->jQuery();
        $jquery->enable()->uiEnable();

        $this->view->headScript()->appendFile('/js/admin/featured/featured-property.js');

        $request = $this->getRequest();


        $propertyModel = new Common_Model_Property();

        $mode = 'add';
        $params = array(
            'mode' => $mode
        );

        if ($request->isGet()) {
            $idFeaturedProperty = $request->getParam('idFeaturedProperty', null);

            if ($idFeaturedProperty) {
                $featuredPropertyRow = $propertyModel->getFeaturedPropertyByFeaturedPropertyId($idFeaturedProperty);

                // get the property's location id
                $propertyRow = $propertyModel->getPropertyById($featuredPropertyRow->idProperty);

                $helper = $this->view->getHelper('InvoiceDateDdMmYyyy');

                $params = array(
                    'mode'               => 'edit',
                    'propertyLocationId' => $propertyRow->idLocation,
                    'idFeaturedProperty' => $featuredPropertyRow->idFeaturedProperty,
                    'idLocation'         => $featuredPropertyRow->idLocation,
                    'idProperty'         => $featuredPropertyRow->idProperty,
                    'startDate'          => $helper->invoiceDateDdMmYyyy($featuredPropertyRow->startDate),
                    'expiryDate'         => $helper->invoiceDateDdMmYyyy($featuredPropertyRow->expiryDate),
                    'position'           => $featuredPropertyRow->position
                );
            }
        }

        $form = new Admin_Form_Featured_FeaturedPropertyForm($params);

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $mode = $request->getParam('mode', 'add');

                $featuredObj = new Frontend_Model_Feature();
                $featuredObj->setFeaturedPropertyId($request->getParam('idFeatured'))
                            ->setPropertyId($request->getParam('idProperty'))
                            ->setLocationId($request->getParam('idLocation'))
                            ->setStartDate($request->getParam('startDate'))
                            ->setExpiryDate($request->getParam('expiryDate'))
                            ->setPositions($request->getParam('position'))
                            ->setAdded(null)
                            ->setUpdated(null)
                            ->setLastModifiedBy(null);

                $featuredMapper = new Frontend_Model_FeaturedMapper();
                if ($mode == 'add') {
                    $featuredMapper->addFeatured($featuredObj);
                } else {
                    $featuredMapper->saveFeatured($featuredObj);
                }
                die('saving');
            } else {
                var_dump($form->getMessages());
                die('err');
            }
        }

        $this->view->assign(
            array(
                'form' => $form
            )
        );
    }


    public function listByLocationAction()
    {

    }

    public function listByPropertyAction()
    {
        $propertyModel = new Common_Model_Property();

        $request = $this->getRequest();
        $page      = $request->getParam('page');
        $interval  = $request->getParam('interval');
        $order     = $request->getParam('order');
        $direction = $request->getParam('direction');

        $featuredPropertiesPaginator = $propertyModel->getAllFeaturedPropertiesPaginator(
            $page,
            $interval,
            $order,
            $direction
        );

        $session = new Zend_Session_Namespace(Common_Model_Property::SESSION_NS_ADMIN_PROPERTY_LIST_BY_PROPERTY);

        $this->view->assign(
            array(
                'featuredPropertiesPaginator' => $featuredPropertiesPaginator,
                'order'                       => isset($session->order) ? $session->order : $order,
                'direction'                   => isset($session->direction) ? $session->direction : $direction
            )
        );
    }
}
