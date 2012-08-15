<?php
class Common_Service_Location
{
    protected $_locationModel;

    public function __construct()
    {
        $this->_locationModel = new Common_Model_Location();
    }

    /**
     * @return array
     */
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

    /**
     * Returns an indexed hash of loction id's to details for a given location
     *
     * @param int $idLocation the start location node
     * @return array hash map of locations
     */
    public function getAllDirectAncestorsBackToRootHashMap($parentIdLocation)
    {
        $locationRowset = $this->_locationModel->getAllDirectAncestorsBackToRoot($parentIdLocation);

        $locations = array();

        foreach ($locationRowset as $locationRow) {
            $idLocation = $locationRow->idLocation;

            $locations[$idLocation] = $locationRow->name . ' (' . $locationRow->url . ')';
        }

        return $locations;
    }
}
