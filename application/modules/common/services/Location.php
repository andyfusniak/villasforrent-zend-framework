<?php
class Common_Service_Location
{
    protected $_locationModel;

    public function __construct()
    {
        $this->_locationModel = new Common_Model_Location();
    }

    public function getAllLocationsAssocArrays()
    {
        $locationRowset = $this->_locationModel->getAllLocations();

        // create two associative arrays for quick lookups
        $leftLookup  = array();
        $rightLookup = array();

        foreach ($locationRowset as $locationRow) {
            $lt = $locationRow->lt;
            $rt = $locationRow->rt;

            $leftLookup[$lt]  = $locationRow;
            $rightLookup[$rt] = $locationRow;
        }

        return array(
            $locationRowset,
            $leftLookup,
            $rightLookup
        );
    }
}
