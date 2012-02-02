<?php
class Common_Model_Location extends Vfr_Model_Abstract
{
	//
	// CREATE
	//
	
	public function fillTable()
	{
		return $this->getResource('Location')->fillTable();
	}
	
    /**
     * @param int $idParent the id of the root by which to start rebuilding the nested set model
     * @param int $lt  the initial left-value for the nested set
     * @return void
     */
	public function rebuildTree($idParent=null, $lt=0)
	{
		return $this->getResource('Location')->rebuildTree($idParent, $lt);
	}
    
    public function addLocationToParentFirst($idParentLocation, $name)
    {
        $idParentLocation = (int) $idParentLocation;
        
        $idParentLocationRow = $this->getLocationByPk($idParentLocation);
        
        $locationResource = $this->getResource('Location');
        $locationRow = $locationResource->addLocationToParentFirst(
            $idParentLocationRow,
            $name
        );
        
        return $locationRow;
    }
    
	
	//
	// READ
	//
	
	public function lookup($url)
	{
		return $this->getResource('Location')->lookup($url);
	}
	
	private function _findNode($dataset, $idParent, $depth)
	{
		$rowset = array();
		
		foreach($dataset as $row) {
			if (($row->depth == $depth) && ($row->idParent == $idParent)) {
				$rowset[] = $row;
			}
		}
		
		return $rowset;
	}
	
	public function getLocationHierarchy()
	{
		$locationRowset = $this->getResource('Location')->getAllLocations();
		
		return $locationRowset;
	}
	
	public function getAllLocations()
	{
		return $this->getResource('Location')->getAllLocations();
	}
    
    public function getLocationList($location)
    {
        return $this->getResource('Location')
                    ->getLocationList(
            $location
        );
    }
	
	public function getLocationByPk($idLocation)
	{
		$idLocation = (int) $idLocation;
		
		return $this->getResource('Location')->getLocationByPk($idLocation);
	}
	
	public function getAllLocationsIn($idLocation=null)
	{
		return $this->getResource('Location')->getAllLocationsIn($idLocation);
	}
	
	public function getPathFromRootNode($idLocation)
	{
		$idLocation = (int) $idLocation;
		
		return $this->getResource('Location')->getPathFromRootNode($idLocation);
	}
    
    public function getPropertiesInLocationCount($idLocation)
    {
        $idLocation = (int) $idLocation;
        
        $propertyResource = $this->getResource('Property');
        return $propertyResource->getPropertiesInLocationCount(
            $idLocation
        );
    }
	
    public function getParentIdByChildId($idLocation)
    {
        $idLocation = (int) $idLocation;
        $locationResource = $this->getResource('Location');
        
        $locationRow = $locationResource->getLocationByPk($idLocation);
        
        $parentRow = $locationResource->getParentNode($locationRow);
        
        if ($parentRow)
            return $parentRow->idLocation;
        else
            return null;
    }
    
    public function areSiblings($idLocationA, $idLocationB)
    {
        $idLocationA = (int) $idLocationA;
        $idLocationB = (int) $idLocationB;
        
        if ($idLocationA == $idLocationB)
            throw Exception('Location ids do not differ');
        
        $locationResource = $this->getResource('Location');
        
        // locate the new records
        $idLocationARow = $this->getLocationByPk(
            $idLocationA
        );
        $idLocationBRow = $this->getLocationByPk(
            $idLocationB
        );
        
        return $locationResource->haveSameParent(
            $idLocationARow,
            $idLocationBRow
        );
    }
    
    public function moveLocation($sourceLocationId, $destLocationId, $position=Common_Resource_Location::NODE_BEFORE)
    {
        $sourceLocationId = (int) $sourceLocationId;
        $destLocationId   = (int) $destLocationId;
        
        $locationResource = $this->getResource('Location');
        
        // ensure the source and destination are siblings
        $sourceLocationRow = $this->getLocationByPk(
            $sourceLocationId
        );
        
        $destLocationRow = $this->getLocationByPk(
            $destLocationId   
        );
        
        if (! $locationResource->haveSameParent(
                    $sourceLocationRow,
                    $destLocationRow
           ))
            throw new Vfr_Exception_Locations_NotSiblings(
                'Cannot move nodes that are not siblings'
            );
        
        
        $locationResource->moveNode(
            $sourceLocationRow,
            $destLocationRow,
            $position
        );
    }
    
	//
	// DELETE
	//
    
    public function deleteLocation($idLocation)
    {
        $idLocation = (int) $idLocation;
        $locationResource = $this->getResource('Location');
        
        try {
            $locationRow = $this->getLocationByPk($idLocation);
            if ($locationRow) {
                // make sure this node is a leaf node
                if ($locationRow->lt != $locationRow->rt - 1)
                    throw new Vfr_Exception_Location_NotALeafNode(
                        'Location node ' . $idLocation . ' is not a leaf node'
                    );
             
                // ensure there are no properties in this location
                $numProperties = $this->getPropertiesInLocationCount($idLocation);
                if ($numProperties > 0)
                    throw new Vfr_Exception_Location_LeafNodeHasProperties(
                        'Location node ' . $idLocation . ' still contain ' . $numProperties. ' properties'
                    );
            } else {
                throw new Vfr_Exception_Location_NodeNotFound(
                    'Location node id ' . $idLocation . ' could not be found'
                );
            }
            
            $locationResource->deleteLeafNode(
                $locationRow
            );
            
            // not yet implemented, but needs to disallow
            // deleting non-leaf nodes
            // disallow deleting nodes in which properties
            // already reside
        } catch (Exception $e) {
            throw $e;
        }
    }
	
	public function purgeLocationTable()
	{
		return $this->getResource('Location')->purgeLocationTable();
	}
    
    // Utils
    public static function convertLocationNameToUrl($name)
    {
        $newname = strtolower($name);
        $newname = str_replace(' ', '-', $newname);
        $newname = str_replace('---', '-', $newname);
        $newname = str_replace('--', '-', $newname);
        
        return $newname;
    }
}