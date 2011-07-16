<?php
class Frontend_Form_Step3PicturesForm extends Zend_Form
{
	protected $_idProperty;
	
	protected $_digestKey = null;
	
    public function __construct($options = null)
    {
        parent::__construct($options);
	}
	
	public function setIdProperty($idProperty)
    {
		$this->_idProperty = $idProperty;
    }
	
	public function setDigestKey($digestKey)
	{
		$this->_digestKey = $digestKey;
	}
	
	public function init()
	{
		$this->setMethod('post');
		$this->setAction('/advertiser-property/step3-pictures');
		
		$this->addElement('file', 'filename', array (
			'required'		=> true,
			'validators'	=> array (
				//array ('Count', true, array	('min' => 1, 'max' => 1)),
				array ('IsImage', true, array('image/jpeg', 'image/png'))
			)
		));
		
		$this->addElement('text', 'caption', array (
			'required'	=> false,
			'label'		=> 'Photo Caption Text',
		));
			
		$this->addElement('hidden', 'idProperty', array(
			'value' => $this->_idProperty,
		));
		
		$this->addElement('hidden', 'digestKey', array(
			'value' => $this->_digestKey,
		));
		
		$this->addElement('hidden', 'MAX_FILE_SIZE', array (
			'value'		=> $this->getElement('filename')->getMaxFileSize()
		));
		
		$this->addElement('hidden', ini_get('apc.rfc1867_name'), array (
			'value'		=> uniqid()
		));
	}
}