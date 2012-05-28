<?php
class Controlpanel_Form_Property_Step2ContentForm extends Zend_Form
{
    protected $idProperty = null;
    protected $idHolidayType = null;
    protected $mode = null;
    protected $_digestKey = null;

    public function setIdProperty($idProperty)
    {
        $this->idProperty = $idProperty;
    }

    public function setIdHolidayType($idHolidayType)
    {
        $this->idHolidayType = $idHolidayType;
    }

    public function getIdHolidayType()
    {
        return $this->idHolidayType;
    }

    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    public function setDigestKey($digestKey)
    {
        $this->_digestKey = $digestKey;
    }

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/controlpanel/property/step2-content');

        $this->addElement('hidden', 'idProperty', array(
            'value' => $this->idProperty
        ));

        $this->addElement('hidden', 'mode', array(
            'value' => $this->mode
        ));

        $this->addElement('hidden', 'digestKey', array(
            'value' => $this->_digestKey
        ));

        $this->addElement('textarea', 'summary', array(
            'required'      => false,
        ));

        $this->addElement('text', 'headline1', array(
            'required'      => true,
            'validators'    => array(
                array('NotEmpty', true, array('messages' => array ('isEmpty' => 'Enter your primary headline for the property')))
            )
        ));

        $this->addElement('text', 'headline2', array(
            'required'      => true,
            'validators'    => array(
                array('NotEmpty', true, array('messages' => array ('isEmpty' => 'Enter your secondary headline for the property')))
            )
        ));

        $this->addElement('textarea', 'description', array(
            'required'      => false
        ));

        $this->addElement('textarea', 'bedroomDesc', array(
            'required'      => false
        ));

        $this->addElement('textarea', 'bathroomDesc', array(
            'required'      => false
        ));

        $this->addElement('textarea', 'kitchenDesc', array(
            'required'      => false
        ));

        $this->addElement('textarea', 'utilityDesc', array(
            'required'      => false
        ));

        $this->addElement('textarea', 'livingDesc', array(
            'required'      => false
        ));

        $this->addElement('textarea', 'otherDesc', array(
            'required'      => false
        ));

        $this->addElement('textarea', 'serviceDesc', array(
            'required'      => false
        ));

        /*
        $propertyModel = new Common_Model_Property();
        $facilityRowSet = $propertyModel->getAllFacilities(true);


        $facilities = new Zend_Form_Element_MultiCheckbox('facilities');
        foreach ($facilityRowSet as $row) {
            $facilities->addMultiOption($row->facilityCode, $row->name);
        }
        $this->addElement($facilities);
        */

        $this->addElement('textarea', 'notesDesc', array(
            'required'      => false
        ));

        if ($this->idHolidayType == Common_Resource_HolidayType::HOLIDAY_TYPE_ACCESS)
        {
            $this->addElement('textarea', 'accessDesc', array(
                'required'      => false
            ));
        }

        $this->addElement('textarea', 'outsideDesc', array(
            'required'      => false
        ));

        if ($this->idHolidayType == Common_Resource_HolidayType::HOLIDAY_TYPE_GOLF)
        {
            $this->addElement('textarea', 'golfDesc', array(
                'required'      => false
            ));
        }

        if ($this->idHolidayType == Common_Resource_HolidayType::HOLIDAY_TYPE_SKIING)
        {
            $this->addElement('textarea', 'skiingDesc', array(
                'required'      => false
            ));
        }

        $this->addElement('textarea', 'serviceDesc', array(
            'required'      => false
        ));

        if ($this->idHolidayType == Common_Resource_HolidayType::HOLIDAY_TYPE_INTEREST)
        {
            $this->addElement('textarea', 'specialDesc', array(
                'required'      => false
            ));
        }

        $this->addElement('textarea', 'beachDesc', array(
            'required'      => false
        ));

        $this->addElement('textarea', 'travelDesc', array(
            'required'      => false
        ));

        $this->addElement('textarea', 'bookingDesc', array(
            'required'      => false
        ));

        $this->addElement('textarea', 'changeoverDesc', array(
            'required'      => false
        ));

        $this->addElement('textarea', 'contactDesc', array(
            'required'      => false
        ));

        $this->addElement('textarea', 'testimonialsDesc', array(
            'required'      => false
        ));

        $this->addElement('text', 'website', array(
            'required'      => false
        ));
    }
}
