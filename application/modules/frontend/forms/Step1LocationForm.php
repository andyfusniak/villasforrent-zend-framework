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
        $this->setName('step1');
		$this->setAttrib('id', 'step1');
		
		$this->addDecorators(array(
			'FormElements',
			array('Fieldset', array('legend' => 'Location',
									'id' => 'step1location_legend')),
			'Form'
		));
		
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
			'label' => 'Type of property:',
			'multiOptions' => $propertyTypeList,
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Select the nearest property type')))
			)
		));
		
		$this->addElement('text', 'shortName', array(
			'required' => true,
			'label' => 'Name of your property',
			'filters' => array('StringTrim'),
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Enter the name of your property')))
			)
		));
		
		$this->addElement('text', 'country', array(
			'required' => true,
			'label' => 'Country:',
			'filters' => array('StringTrim'),
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Enter the country of your property')))
		)
		));

		$this->addElement('text', 'region', array(
			'required' => true,
			'label' => 'Region:',
			'filters' => array('StringTrim'),
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Enter the region of your property')))
			)
		));
		
		$this->addElement('text', 'destination', array(
			'required' => true,
			'label' => 'Destination:',
			'filters' => array('StringTrim'),
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Enter the destination of your property')))
			)
		));		
		
		$group = $this->addDisplayGroup(array('idPropertyType', 'shortName', 'country', 'region', 'destination'),
							   'main',
							   array('disableLoadDefaultDecorators' => true));
		$this->getDisplayGroup('main')
			 ->addDecorators(array(
				'FormElements',
				array('HtmlTag', array('tag' => 'dl'))
			 ));
			 
		$this->addElement('submit', 'submit', array(
			'label' => 'Send',
			'ignore' => true,
			'decorators' => array(
				'ViewHelper'
			)
		));	
		
		$this->addElement('submit', 'submit', array(
			'label' => 'Send',
			'ignore' => true,
			'decorators' => array(
				'ViewHelper'
			)
		));
	}
}