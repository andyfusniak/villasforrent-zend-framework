<?php
class Common_Resource_PropertyFacility extends Vfr_Model_Resource_Db_Table_Abstract implements Common_Resource_PropertyFacility_Interface
{
    protected $_name = 'PropertiesFacilities';
	protected $_primary = 'facilityCode';
	protected $_rowClass = 'Common_Resource_Facility_Row';
	protected $_rowsetClass = 'Common_Resource_Facility_Rowset';
	protected $_referenceMap = array (
		'Property' => array (
			'columns'       => array('idProperty'),
			'refTableClass' => 'Common_Resource_Property'
		),
        
        'Facility' => array (
            'columns'       => array('facilityCode'),
            'refTableClass' => 'Common_Resource_Facility'
        )
    );
    
    public function createPropertyFacility($idProperty, $facilityCode)
    {
        $this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
        
        $data = array (
            'idPropertyFacility'    => new Zend_Db_Expr('NULL'),
            'idProperty'            => $idProperty,
            'facilityCode'          => $facilityCode,
            'isOn'                  => 0
        );
        
        try {
            $this->insert($data);
        } catch (Exception $e) {
            throw $e;
        }
        
        $this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
    }
    
    public function getAllFacilitiesByPropertyId($idProperty)
    {
        $query = $this->select()
		              ->where('idProperty = ?', $idProperty);
        try {
            $rowset = $this->fetchAll($query);    
        } catch (Exception $e) {
            throw $e;
        }
        
        return $rowset;
    }
    
    private function listAsCommaSeparatedString($list)
    {
        if (empty($list))
            return '';
        
        // create a comma separated list of facilities
        $s = '';
        
        $size = sizeof($list);
        for ($i=0; $i < $size; $i++) {
            $next = $list[$i];
            
            $s .= "'" . $next . "'";
        
            if ($i < ($size - 1)) {
                $s .= ',';
            }
        }
        
        return $s;
    }
    
    public function updateFacilitiesByPropertyId($idProperty, $facilitiesList)
    {
        $propertyFacilityRowset = $this->getAllFacilitiesByPropertyId($idProperty);
        
		$onList 	= array();
		$offList 	= array();
		
		foreach ($propertyFacilityRowset as $row) {
			$facilityCode 	= (string) $row->facilityCode;
			$isOn 			= (int) $row->isOn;
			
			// if its in the list, it should be switched on (if its not on already)
			// if its not in the list, its should be switched off (if its not off already)
            if ($facilitiesList) {
                if (in_array($facilityCode, $facilitiesList)) {
                    if ($isOn === 0)
                        $onList[] 	=  $facilityCode;
                } else {
                    if ($isOn === 1)
                        $offList[] 	= $facilityCode;
                }
            }
		}
			    
        try {
            if (!empty($onList)) {
                $dataOn = array(
                    'isOn'  => 1
                );
                $whereOn = $this->getAdapter()->quoteInto('idProperty = ? ', $idProperty)  . str_replace('%A', $this->listAsCommaSeparatedString($onList), 'AND facilityCode IN (%A)');
                $query = $this->update($dataOn,  $whereOn);
            }
            
            if (!empty($offList)) {
                $dataOff = array(
                    'isOn' => 0
                );
                $whereOff = $this->getAdapter()->quoteInto('idProperty = ? ', $idProperty) . str_replace('%A', $this->listAsCommaSeparatedString($offList), 'AND facilityCode IN (%A)');
                $query = $this->update($dataOff, $whereOff);    
            }
        } catch (Exception $e) {
            throw $e;
        }
        
    }
}