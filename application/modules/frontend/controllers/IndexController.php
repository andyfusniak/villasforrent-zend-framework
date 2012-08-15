<?php
class IndexController extends Zend_Controller_Action
{
    private $locationModel;

    public function init() {}

    public function indexAction()
    {
        $locationModel = new Common_Model_Location();
        $propertyModel = new Common_Model_Property();

        $idLocationRow = $locationModel->lookup('');

        // inject the top level locations into the view
        $this->view->locationRowset = $locationModel->getAllLocationsIn(
            Common_Resource_Location::ROOT_NODE_ID
        );

        $this->_helper->featuredProperty($idLocationRow->idLocation);

        $this->view->assign(
            array(
                'view' => $this->view
            )
        );
    }
}
