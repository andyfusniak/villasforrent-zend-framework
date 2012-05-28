<?php
class Common_Resource_Facility extends Vfr_Model_Resource_Db_Table_Abstract implements Common_Resource_Facility_Interface
{
    protected $_name = 'Facilities';
    protected $_primary = 'facilityCode';
    protected $_rowClass = 'Common_Resource_Facility_Row';
    protected $_rowsetClass = 'Common_Resource_Facility_Rowset';
    protected $_dependentTables = array('PropertiesFacilities');

    public function getAllFacilities($inUse=true)
    {
        $query = $this->select()
                      ->where('inUse = ?', ((true === $inUse) ? '1' : '0'))
                      ->order('displayPriority');

        $result = $this->fetchAll($query);
        $this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
        return $result;
    }

    public function getAllFacilitiesByPropertyId($idProperty, $inUse)
    {
        $this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
        $query = $this->select()
                      ->where('idProperty = ?', $idProperty)
                      ->where('inUse = ?', ((true === $inUse) ? '1' : '0'))
                      ->order('displayPriority');

        $result = $this->fetchAll($query);
        $this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
        return $result;
    }
}
