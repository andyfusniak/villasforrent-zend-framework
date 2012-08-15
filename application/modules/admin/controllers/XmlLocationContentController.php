<?php
class Admin_XmlLocationContentController extends Zend_Controller_Action
{
    protected $_locationContentMapper = null;

    public function listAction()
    {
        if (null === $this->_locationContentMapper)
            $this->_locationContentMapper = new Frontend_Model_LocationContentMapper();

        $locationContentRowset = $this->_locationContentMapper->getAllLocationsSummary();
        $this->view->assign(
            array(
                'locationContentRowset' => $locationContentRowset
            )
        );
    }
}