<?php
class Controlpanel_Form_Property_Step4RentalBasis extends Zend_Form
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
        $this->setAction('/controlpanel/rates/rental-basis');

        $this->addElement('hidden', 'idProperty', array(
            'value' => $this->_idProperty
        ));

        $this->addElement('hidden', 'digestKey', array(
            'value' => $this->_digestKey
        ));

        $this->addElement('select', 'rentalBasis', array(
            'required' => true,
            'multiOptions' => array(
                'PR'    => 'Per Property',
                'SN'    => 'Per Person',
                'RM'    => 'Per Room'
            )
        ));
    }
}
