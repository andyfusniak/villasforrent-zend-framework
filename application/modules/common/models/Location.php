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
	
	public function rebuildTree($idParent=null, $lt=1)
	{
		return $this->getResource('Location')->rebuildTree($idParent, $lt);
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
	
	public function getLocationByPk($idLocation)
	{
		$idLocation = (int) $idLocation;
		
		return $this->getResource('Location')->getLocationByPk($idLocation);
	}
	
	public function getAllLocationsIn($idLocation=null)
	{
		return $this->getResource('Location')->getAllLocationsIn($idLocation);
	}
		
	//
	// DELETE
	//
	
	public function purgeLocationTable()
	{
		return $this->getResource('Location')->purgeLocationTable();
	}
}
