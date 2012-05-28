<?php
class Admin_Form_LocationSelectForm extends Zend_Form
{
    protected $idProperty = null;
    protected $idLocation = null;

    public function setIdProperty($idProperty)
    {
        $this->idProperty = $idProperty;
    }

    public function setIdLocation($idLocation)
    {
        $this->idLocation = $idLocation;
    }

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/admin/property/set-location');

        $this->addElement('hidden', 'idProperty', array(
            'required'      => true,
            'value'         => $this->idProperty,
            'validators'    => array(
            array('NotEmpty', true,
                array('messages' => array ('isEmpty' => 'The hidden idProperty is missing')))
            )
        ));

        $this->addElement('hidden', 'idLocation', array(
            'required'      => true,
            'value'         => $this->idLocation,
            'validators'    => array(
                array('NotEmpty', true,
                    array('messages' => array ('isEmpty' => 'Select a location for this property'))
                )
            )
        ));
    }
}
