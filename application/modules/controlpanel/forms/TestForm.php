<?php
class Frontend_Form_TestForm extends Zend_Form
{
	public function init()
	{
		$this->setMethod('post');

		$this->clearDecorators()
			 ->addDecorator('FormElements')
			 ->addDecorator('Fieldset', array('legend' => 'My form'))
			 ->addDecorator('Form');
			 
		$this->firstname = new Zend_Form_Element_Text('firstname');
		$this->firstname->setLabel('First name:')
		                ->setRequired(true);
						
		$this->lastname = new Zend_Form_Element_Text('lastname');
		$this->lastname->setLabel('Last name:')
		                ->setRequired(true);

		$group = $this->addDisplayGroup(array('firstname', 'lastname'),
							   'main',
							   array('disableLoadDefaultDecorators' => true));
		$this->getDisplayGroup('main')->addDecorator('FormElements')
									  ->addDecorator('HtmlTag', array('tag' => 'dl'));
		
		$this->hidden = new Zend_Form_Element_Hidden('h1');
		$this->hidden->setValue('v1');
		$this->hidden->setDecorators(array('ViewHelper'));
	}
}

