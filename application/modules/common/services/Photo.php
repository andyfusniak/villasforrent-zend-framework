<?php
class Common_Service_Photo
{
    /**
     * Create an associative array of property indexes mapping to photo rows
     * @param array $idPropertyList a list of propertys to retrieve photos for
     *
     * @return array an associative array of property-ids to photo rows
     */
    public function getPrimaryPhotosByPropertyListHashMap($idPropertyList)
    {
        $photoModel = new Common_Model_Photo();
        $photoRowset = $photoModel->getPrimaryPhotosByPropertyList($idPropertyList);

        $photoRowHash = array();
        foreach ($photoRowset as $photoRow) {
            $photoRowHash[$photoRow->idProperty] = $photoRow;
        }

        return $photoRowHash;
    }
}
