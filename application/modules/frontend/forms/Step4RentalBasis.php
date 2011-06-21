<?php
class Frontend_Form_Step4RentalBasis extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
	}
    
    public function init()
    {
        $this->setMethod('post');
		$this->setAction('/advertiser-rates/rental-basis');
        
        $this->addElement('select', 'rentalBasis', array(
			'required' => true,
			'multiOptions' => array (
				'PR'    => 'Per Property',
				'SN'    => 'Per Person',
				'RM'    => 'Per Room'
			)
		));
    }
}