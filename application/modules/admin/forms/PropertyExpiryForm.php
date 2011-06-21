<?php
class Admin_Form_PropertyExpiryForm extends Zend_Form
{
    protected $idProperty = null;
    protected $expiry = null;
    
    public function setIdProperty($idProperty)
    {
        $this->idProperty = $idProperty;
    }
    
    public function setExpiry($expiry)
    {
        if (($expiry == null) || ($expiry == '0000-00-00')) {
            $this->expiry = '';
            return;
        }
        
        // convert YYYY-DD-MM to jquery calendar format
        $this->expiry = trim(strftime("%e-%b-%Y", strtotime($expiry)));
    }
    
    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    
    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/admin/property/set-expiry');
        
        $this->addElement('hidden', 'idProperty', array (
            'required'  => true,
            'value'         => $this->idProperty,
            'validators'	=> array (
				array('NotEmpty', true, array('messages' => array ('isEmpty' => 'The hidden idProperty is missing')))
			)
        ));
        
        
        $this->addElement('text', 'expiry', array (
           'required'   => true,
           'value'      => $this->expiry,
           'validators' => array (
                array('NotEmpty', true, array('messages' => array ('isEmpty' => 'Select an expiry date')))
            )
        ));
    }
}