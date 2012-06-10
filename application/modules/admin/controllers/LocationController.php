<?php
class Admin_LocationController extends Zend_Controller_Action
{
    protected $_vfrConfig = null;

    public function treeViewAction()
    {
        $locationModel = new Common_Model_Location();
        $locationsRowset = $locationModel->getAllLocations();

        // Enable jQuery to pickup the headers etc
        //ZendX_JQuery::enableForm($form);
        ZendX_JQuery::enableView($this->view);
        $jquery = $this->view->jQuery();
        $jquery->enable()->uiEnable();

        // context menu JavaScript and CSS
        $this->view->headScript()->appendFile('/js/context-menu/jquery.contextMenu.js');
        $this->view->headLink()->appendStylesheet('/js/context-menu/jquery.contextMenu.css');

        // dynatree
        $this->view->headScript()->appendFile('/js/dynatree/jquery.dynatree.min.js');
        $this->view->headScript()->appendFile('/js/admin/location-tree.js');

        $this->view->headLink()->appendStylesheet('/js/dynatree/skin/ui.dynatree.css');

        $this->view->assign(
            array(
                'locationRowset' => $locationsRowset
            )
        );
    }

    public function xmlAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $locationModel = new Common_Model_Location();
        $domDocument = $locationModel->xmlDomTree();

        header("Content-Type: text/plain");
        // (?!name|content)
        $xml = preg_replace("/(\s+)\<([a-zA-Z-]+)\>/", "\n$1<$2>", $domDocument->saveXML());
        $xml = preg_replace("/( ){2}/", "    ", $xml);

        $locationModel->dumpXmlToServer($xml);

        $this->_helper->redirector->gotoSimple(
            'overview',
            'xml-rebuild',
            'admin'
        );
    }
}
