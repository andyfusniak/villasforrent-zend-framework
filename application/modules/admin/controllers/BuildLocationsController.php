<?php
class Admin_BuildLocationsController extends Zend_Controller_Action
{
    private $_locationModel;

    public function init()
    {
        $this->_locationModel = new Common_Model_Location();
    }

    public function indexAction()
    {
    }

    public function rebuildTreeAction()
    {
        $this->_locationModel->rebuildTree(null, 0);
    }

    public function purgeTableAction()
    {
        $this->_locationModel->purgeLocationTable();
    }

    public function fillTableAction()
    {
        $this->_locationModel->fillTable();
    }
}
