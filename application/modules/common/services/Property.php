<?php
class Common_Service_Property
{
    public function getPropertiesByPropertyListHashMap($properties)
    {
        $propertyModel = new Common_Model_Property();
        $propertyRowset = $propertyModel->getPropertiesByPropertyList($properties);

        $propertiesLookup = array();
        foreach ($propertyRowset as $propertyRow) {
            $idProperty = $propertyRow->idProperty;

            $propertiesLookup[$idProperty] = $propertyRow;
        }

        return $propertiesLookup;
    }
}
