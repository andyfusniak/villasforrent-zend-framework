<?php
class Frontend_Form_Step4RentalBasis extends Zend_Form
{
	protected $_idProperty = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
	}
    
    public function setIdProperty($idProperty)
    {
		$this->_idProperty = $idProperty;
    }
    
    public function init()
    {
        $this->setMethod('post');
        $this->setName('step4-rental-basis');
        $this->setAttrib('id', 'step4-rental-basis');
        
        $this->addDecorators(array(
			'FormElements',
			array('Fieldset', array('legend' => 'Rental Basis',
									'id' => 'step4_rental_basis_legend')),
			'Form'
		));
        
        $rentalBasis = array (
            'PR'    => 'Per Property',
            'SN'    => 'Per Person',
            'RM'    => 'Per Room'
        );
        
        $this->addElement('select', 'rentalBasis', array(
			'required' => true,
			'label' => 'Type of property:',
			'multiOptions' => $rentalBasis
		));
        
        $this->addElement('hidden', 'idProperty', array(
			'decorators' => array(
				'ViewHelper'
			),
			'value' => $this->_idProperty
		));
		
		$this->addElement('submit', 'submit', array('required' => false,
                                                    'ignore' => true,
                                                    'decorators' => array('ViewHelper',array('HtmlTag',
                                                        array('tag' => 'dd', 'id' => 'form-submit')))
                                                    ));
    }
    
}