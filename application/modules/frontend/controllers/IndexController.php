<?php
class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		$propertyModel = new Common_Model_Property();
		
		$searchOptions = array (
			'approved'		=> true,
			'visible' 		=> true,
			'idCountry' 	=> null,
			'idRegion' 		=> null,
			'idDestination' => null
		);
		
		$results = $propertyModel->doSearch();
		
		var_dump($results);
    }

	public function testAction()
	{
		$this->view->form = new Frontend_Form_TestForm();

		//echo($this->view->form);
		//exit;
	}
}

