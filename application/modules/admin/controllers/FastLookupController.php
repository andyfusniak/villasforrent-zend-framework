<?php
class Admin_FastLookupController extends Zend_Controller_Action
{
	private $_fastLookupModel;
   
    public function init()
    {
		$this->_fastLookupModel = new Common_Model_FastLookup();
    }

    public function indexAction()
	{
    }

	public function purgeTableAction()
	{
		$this->_fastLookupModel->purgeFastLookupTable();
	}

	public function createTableAction()
	{
		$this->_fastLookupModel->createFastLookupTable();	
	}
}
