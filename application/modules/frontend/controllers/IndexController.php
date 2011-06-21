<?php
class IndexController extends Zend_Controller_Action
{
    public function init() {}

    public function indexAction()
    {
		$locationModel	= new Common_Model_Location();
		
		$searchOptions = array (
			'approved'		=> true,
			'visible' 		=> true,
			'idCountry' 	=> null,
			'idRegion' 		=> null,
			'idDestination' => null
		);
		
		// removed to use a more efficent Way
		//$countryRowset = $locationModel->getCountriesWithTotalVisible();
		$fastLookupRowset = $locationModel->getFastAllCountries();
		
		//var_dump($fastLookupRowset);
		
		//$results = $propertyModel->doSearch();
		
		//var_dump($results);
		//var_dump($countryRowset);
		
		
		$this->_helper->featuredProperty(Common_Resource_Property::FEATURE_MASK_HOMEPAGE);
		
		$this->view->fastLookupRowset = $fastLookupRowset;
    }

	public function testAction()
	{
		$this->view->form = new Frontend_Form_TestForm();

		//echo($this->view->form);
		//exit;
	}
}

