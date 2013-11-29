<?php
class Common_Service_LocationContent
{
    protected $locationContentResource = null;

    public function __construct()
    {
        if (null === $this->locationContentResource)
            $this->locationContentResource = new Common_Resource_LocationContent();
    }

    public function getComposeKeysArrayByLocationId($idLocation)
    {
        $locationContentRowset = $this->locationContentResource->getComposeKeysByLocationId($idLocation);

        $composeKeyLookup = array();
        foreach ($locationContentRowset as $locationRow) {
            $idLocation = $locationRow->idLocation;
            $lang       = $locationRow->lang;
            $fieldTag   = $locationRow->fieldTag;
            $priority   = $locationRow->priority;

            $composeKeyLookup[$idLocation][$lang][$fieldTag][$priority] = true;
        }

        return $composeKeyLookup;
    }


}
