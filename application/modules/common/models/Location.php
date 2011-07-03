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
		$allRowset = $this->getResource('Location')->getAllLocations();
				
		$h = array ();
		foreach ($allRowset as $row) {
			if ($row->depth == 1) {
				$idLocation = $row->idLocation;
				
				$h[$idLocation] = array (
					'name'	=> $row->rowname,
					'child'	=> null
				);
							
				$itemrows = $this->_findNode($allRowset, $idLocation, 2);
				if ($itemrows) {
					foreach ($itemrows as $item) {
						$h[$idLocation]['child'][$item->idLocation] = array (
							'name'  => $item->rowname,
							'child'	=> null
						);	
					}	
				}
				//var_dump($item);
			}
		}
		
		foreach ($allRowset as $row) {
			if ($row->depth == 2)
			{
				
			}
		}
		
		return $h;
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
	
	public function getAllLocationsIn($idParent=null)
	{
		return $this->getResource('Location')->getAllLocationsIn($idParent);
	}
		
	//
	// DELETE
	//
	
	public function purgeLocationTable()
	{
		return $this->getResource('Location')->purgeLocationTable();
	}
}
