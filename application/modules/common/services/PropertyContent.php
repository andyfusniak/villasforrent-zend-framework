<?php
class Common_Service_PropertyContent
{
    public function getPropertyContentByPropertyListHashMap($idPropertyList, $version, $lang, $idPropertyContentFieldList)
    {
        $propertyModel = new Common_Model_Property();

        $propertyContentRowset = $propertyModel->getPropertyContentByPropertyList(
            $idPropertyList,
            Common_Resource_PropertyContent::VERSION_MAIN,
            'EN',
            $idPropertyContentFieldList
        );

        $propertyContentList = array();
        foreach ($propertyContentRowset as $propertyContentRow) {
            $idProperty = $propertyContentRow->idProperty;
            $idPropertyContentField = $propertyContentRow->idPropertyContentField;

            $propertyContentList[$idProperty][$idPropertyContentField] = $propertyContentRow;
        }

        return $propertyContentList;
    }
}
