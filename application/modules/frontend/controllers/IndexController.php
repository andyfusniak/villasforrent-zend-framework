<?php
class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		$locationModel	= new Common_Model_Location();
		$propertyModel 	= new Common_Model_Property();
		
		$searchOptions = array (
			'approved'		=> true,
			'visible' 		=> true,
			'idCountry' 	=> null,
			'idRegion' 		=> null,
			'idDestination' => null
		);
		
		$countryRowset = $locationModel->getCountries($visible=true,$orderBy='displayPriority');
		
		$results = $propertyModel->doSearch();
		
		//var_dump($results);
		//var_dump($countryRowset);
		
		$this->view->countryRowset = $countryRowset;
    }

	public function testAction()
	{
		$this->view->form = new Frontend_Form_TestForm();

		//echo($this->view->form);
		//exit;
	}
}

