<?php
class Frontend_Model_LocationMapper
{
    protected $_locationModel = null;

    /**
     * @return array a list of Location objects
     */
    public function getAllLocations()
    {
        if (null === $this->_locationModel)
            $this->_locationModel = new Common_Resource_Location();

        $locationRowset = $this->_locationModel->getAllLocations();

        $locationObjs = array();

        foreach ($locationRowset as $locationRow) {
            $locationObjs[] = new Frontend_Model_Location(
                $locationRow->idLocation,
                $locationRow->idParent,
                $locationRow->url,
                $locationRow->lt,
                $locationRow->rt,
                $locationRow->depth,
                $locationRow->rowurl,
                $locationRow->totalVisible,
                $locationRow->total,
                $locationRow->name,
                $locationRow->rowname,
                $locationRow->prefix,
                $locationRow->postfix,
                $locationRow->visible,
                $locationRow->added,
                $locationRow->updated
            );
        }

        return $locationObjs;
    }
}
