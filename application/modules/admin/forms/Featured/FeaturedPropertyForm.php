<?php
class Admin_Form_Featured_FeaturedPropertyForm extends Zend_Form
{
    protected $mode;
    protected $propertyLocationId = null;
    protected $idFeaturedProperty = null;
    protected $idProperty;
    protected $idLocation;
    protected $startDate;
    protected $expiryDate;
    protected $position;

    private $positionOptions = array(
        ''  => '--select--',
        '1' => 'Box 1',
        '2' => 'Box 2',
        '3' => 'Box 3',
        '4' => 'Box 4'
    );

    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    public function setPropertyLocationId($propertyLocationId)
    {
        $this->propertyLocationId = $propertyLocationId;
    }

    public function setIdFeaturedProperty($idFeaturedProperty)
    {
        $this->idFeaturedProperty = $idFeaturedProperty;
    }

    public function setIdProperty($idProperty)
    {
        $this->idProperty = $idProperty;
    }

    public function setIdLocation($idLocation)
    {
        $this->idLocation = $idLocation;
    }

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/admin/featured/feature');

        $this->addElementPrefixPath('Vfr_Validate', 'Vfr/Validate', 'validate');

        $this->addElement('hidden', 'mode', array(
            'value' => $this->mode
        ));

        $this->addElement('hidden', 'idFeaturedProperty', array(
            'required' => true,
            'value'    => $this->idFeaturedProperty,
            'validators'    => array(
                array('NotEmpty', true,
                    array('messages' => array ('isEmpty' => 'The hidden idFeaturedProperty is missing'))
                )
            )
        ));

        $this->addElement('text', 'idProperty',
            array(
                'required' => true,
                'value'    => $this->idProperty,
                'validators' => array(
                    array('NotEmpty', true, array('messages' => array('isEmpty' => 'Enter the property ID')))
                )
            )
        );

        $locationService = new Common_Service_Location();
        $locationsList = $locationService->getAllDirectAncestorsBackToRootHashMap($this->propertyLocationId);

        $this->addElement('select', 'idLocation',
            array(
                'required'   => true,
                'value'      => $this->idLocation,
                'multiOptions' => $locationsList,
                'validators' => array(
                    array('NotEmpty', true, array('messages' => array('isEmpty' => 'Select a location ID')))
                )
            )
        );

        $this->addElement('text', 'startDate',
            array(
                'required'   => true,
                'value'      => $this->startDate,
                'validators' => array(
                    array('NotEmpty', true, array('messages' => array('isEmpty' => 'Select a start date'))),
                    array('DateInput', true)
                )
            )
        );

        $this->addElement('text', 'expiryDate',
            array(
                'required'   => true,
                'value'      => $this->expiryDate,
                'validators' => array(
                    array('NotEmpty', true, array('messages' => array('isEmpty' => 'Select an expiry date'))),
                    array('DateInput', true)
                )
            )
        );

        $this->addElement('select', 'position',
            array(
                'required'     => true,
                'value'        => $this->position,
                'multiOptions' => $this->positionOptions
            )
        );
    }
}
