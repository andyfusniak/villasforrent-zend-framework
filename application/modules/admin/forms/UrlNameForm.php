<?php
class Admin_Form_UrlNameForm extends Zend_Form
{
    protected $idProperty = null;
    protected $urlName = null;
    
    public function setIdProperty($idProperty)
    {
        $this->idProperty = $idProperty;
    }
    
    public function setUrlName($urlName)
    {
        $this->urlName = $urlName;
    }
    
    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    
    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/admin/property/set-url-name');
        
        $this->addPrefixPath('Vfr_Form', 'Vfr/Form');
        $this->addElementPrefixPath('Vfr_Validate', 'Vfr/Validate', 'validate');
        
        $this->addElement('hidden', 'idProperty', array (
            //'required'  => true,
            'value'         => $this->idProperty,
            'validators'	=> array (
				array('NotEmpty', true, array('messages' => array ('isEmpty' => 'The hidden idProperty is missing')))
			)
        ));
        
        $this->addElement('text', 'urlName', array (
           'required'   => true,
           'value'      => $this->urlName,
           'filters' => array('StringTrim'),
           'validators' => array (
                array('PropertyUrl', true)
            )
        ));
    }
}