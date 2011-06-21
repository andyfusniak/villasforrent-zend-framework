<?php
class Admin_Form_PropertyApprovalForm extends Zend_Form
{
    protected $idProperty = null;
    
    public function setIdProperty($idProperty)
    {
        $this->idProperty = $idProperty;
    }
    
    public function __construct($options = null)
    {
        parent::__construct($options);
	}
    
    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/admin/property/approve-property');
        
        $this->addElement('hidden', 'idProperty', array (
            'required'      => true,
            'value'         => $this->idProperty,
            'validators'	=> array (
				array('NotEmpty', true, array('messages' => array ('isEmpty' => 'The hidden idProperty is missing')))
			)
        ));
    }
}