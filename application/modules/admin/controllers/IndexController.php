<?php
class Admin_IndexController extends Zend_Controller_Action
{
    public function init() {}

    public function indexAction()
    {
        //Zend_Date::setOptions(array('format_type' => 'iso'));
        //$n = new Common_Model_Advertiser();
        //$date = new Zend_Date();
        //var_dump($date);
        //var_dump($date->get(Zend_Date::W3C));
    }

    public function listAwaitingInitialApprovalAction()
    {
        //$this->_forward('authfail');
        //var_dump("in controller");

        $modelProperty = new Common_Model_Property();
        $propertyRowset = $modelProperty->getAllPropertiesAwaitingInitialApproval();

        // get a list of advertisers and locatiokn
        // to initise the view helper
        $advertisersList = array ();
        foreach ($propertyRowset as $property) {
            array_push($advertisersList, $property->idAdvertiser);
        }
        $this->view->advertiserIdToName($advertisersList);

        $form = new Controlpanel_Form_Property_Step4RatesForm();

        // Enable jQuery to pickup the headers etc
        ZendX_JQuery::enableForm($form);
        $jquery = $this->view->jQuery();
        $jquery->enable()->uiEnable();

        $this->view->headScript()->appendFile('/js/vfr/admin/seturl.js');

         $this->view->assign(
            array(
                'propertyRowset' => $propertyRowset,
            )
        );
    }
}
