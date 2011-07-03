<?php
class IndexController extends Zend_Controller_Action
{
    public function init() {}

    public function indexAction()
    {	
		$this->_helper->featuredProperty(Common_Resource_Property::FEATURE_MASK_HOMEPAGE);
    }

	public function testAction()
	{
		$this->view->form = new Frontend_Form_TestForm();
	}
}