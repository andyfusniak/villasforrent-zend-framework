<?php
class Common_Model_DbTable_PropertyTypes extends Zend_Db_Table_Abstract
{
    protected $_name = 'property_types';
    protected $_primary = 'idPropertyType';

    public function getPropertyTypesInUseLookup()
    {
        // Build this query:
        //    SELECT *
        //    FROM propertyTypes
        //    WHERE inUse=1
        //    ORDER BY displayPriority

        $select = $this->select();
        $select->from('property_types')
               ->where('inUse=1')
               ->order('displayPriority');
        $results = $this->fetchAll($select);

        //
        // return the results as a hash lookup
        //
        $property_types = array();
        foreach($results as $item) {
            $idx  = $item['idPropertyType'];
            $property_types[$idx] = $item['name'];
        }

        return $property_types;
    }
}
