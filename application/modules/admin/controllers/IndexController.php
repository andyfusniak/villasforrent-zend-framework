<?php

class Admin_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		//	Zend_Date::setOptions(array('format_type' => 'iso'));
		//	$n = new Common_Model_Advertiser();
		$date = new Zend_Date();
		var_dump($date);
		var_dump($date->get(Zend_Date::W3C));
    }

}

