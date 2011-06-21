<?php
class Frontend_Form_Step4RateDeleteConfirmForm extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
	}
	
	public function init()
	{ 
		$this->setMethod('post');
		$this->setAction('/advertiser-rates/delete-confirm');
	}
}