<?php
class Frontend_Form_Step5AvailabilityDeleteConfirmForm extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
	}
	
	public function init()
	{ 
		$this->setMethod('post');
		$this->setAction('/advertiser-availability/delete-confirm');
	}
}