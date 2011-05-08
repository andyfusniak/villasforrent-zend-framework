<?php
class Frontend_Form_Step1LocationForm extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
	}
	
	public function init()
	{
        $this->setAction('/advertiser-property/step1-location');
        
		// load the property types in use from the DB
		// and setup an array for the select box
		$propertyModel = new Common_Model_Property();
		$rowSet = $propertyModel->getAllPropertyTypes(true);
		$propertyTypeList = array('' => '--select--');
		foreach ($rowSet as $row) {
			$propertyTypeList[$row->idPropertyType] = $row->name;
		}
		
		$this->addElement('select', 'idPropertyType', array(
			'required' => true,
			'multiOptions' => $propertyTypeList,
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Select the nearest property type')))
			)
		));
		
		$this->addElement('text', 'shortName', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Enter the name of your property')))
			)
		));
		
		$this->addElement('text', 'country', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Enter the country of your property')))
		)
		));

		$this->addElement('text', 'region', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Enter the region of your property')))
			)
		));
		
		$this->addElement('text', 'destination', array(
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Enter the destination of your property')))
			)
		));
	}
}