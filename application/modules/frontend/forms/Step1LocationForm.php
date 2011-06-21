<?php
class Frontend_Form_Step1LocationForm extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
	}
	
	public function init()
	{
		$this->setMethod('post');
        $this->setAction('/advertiser-property/step1-location');
        
		// load the property types in use from the DB
		// and setup an array for the select box
		$propertyModel = new Common_Model_Property();
		$rowSet = $propertyModel->getAllPropertyTypes(true);
		$propertyTypeList = array('' => '--select--');
		foreach ($rowSet as $row) {
			$propertyTypeList[$row->idPropertyType] = $row->name;
		}
		
		$this->addElement('select', 'idPropertyType', array (
			'required' => true,
			'multiOptions' => $propertyTypeList,
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Select the nearest property type')))
			)
		));
		
		$this->addElement('text', 'shortName', array (
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Enter the name of your property')))
			)
		));
		
		$sel = array('' => '--select--');
		$holidayTypes = $propertyModel->getAllHolidayTypesArray();
		$holidayTypes = array_merge($sel, $holidayTypes);
		
		$this->addElement('select', 'idHolidayType', array (
			'required' => true,
			'multiOptions'	=> $holidayTypes,
			'validators'	=> array (
				array ('NotEmpty', true, array( 'messages' => array('isEmpty' => 'Select the type of holiday')))
			)
		));
		
		$list = array();
		$list[''] = '--select--';
		for ($i=1; $i<=26; $i++) {
			$list[$i] = $i;	
		}
		$this->addElement('select', 'numBeds', array (
			'required'		=> true,
			'multiOptions'	=> $list,
			'validators'	=> array (
				array('NotEmpty', true, array ('messages' => array ('isEmpty' => 'Select the number of bedrooms')))
			)
		));
		
		$list = array();
		$list[''] = '--select--';
		for ($i=1; $i<=40; $i++) {
			$list[$i] = $i;	
		}
		$this->addElement('select', 'numSleeps', array (
			'required'		=> true,
			'multiOptions'	=> $list,
			'validators'	=> array (
				array('NotEmpty', true, array ('messages' => array ('isEmpty' => 'Select the number of people this property sleeps')))
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