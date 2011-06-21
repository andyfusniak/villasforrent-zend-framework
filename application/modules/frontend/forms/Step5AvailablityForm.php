<?php
class Frontend_Form_Step5AvailablityForm extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
	}
	
	public function init()
	{
		$this->setMethod('post');
		$this->setAction('/advertiser-property/step5-availability');
		
		$this->addPrefixPath('Vfr_Form', 'Vfr/Form');
        $this->addElementPrefixPath('Vfr_Validate', 'Vfr/Validate', 'validate');
		
		$this->addElement('availabilityRangePicker', 'availability', array (
            'validators' => array (
                array('AvailabilityRange', true, array() )
            )
        ));
	}
}