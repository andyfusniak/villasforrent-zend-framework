<?php
class Common_Resource_Region extends Vfr_Model_Resource_Db_Table_Abstract implements Common_Resource_Region_Interface
{
	protected $_name = 'Regions';
	protected $_primary = 'idRegion';
	protected $_rowClass = 'Common_Resource_Region_Row';
	protected $_rowsetClass = 'Common_Resource_Region_Rowset';
	protected $_dependantTables = array('Properties');
	
	public function getRegionById($idRegion)
	{
		$query = $this->select()
					  ->where('idRegion = ?', $idRegion);
		return $this->fetchAll($query);
	}

	public function getRegionsByCountryId($idCountry, $visible=true)
	{
		$query = $this->select()
					  ->where('idCountry = ?', $idCountry)
					  ->where('visible = ?', (($visible) ? '1' : '0'));
		return $this->fetchAll($query);
	}

	public function getRegionsCountByCountryId($idCountry, $visible=true)
	{
		$query = $this->select('COUNT(*)')
					  ->where('idCountry = ?', $idCountry)
					  ->where('visible = ?', (($visible) ? '1' : '0'));
		return $this->fetchOne($query);
	}

	public function getRegions($visible=true)
	{
		$query = $this->select()
					  ->where('visible = ?', (($visible) ? '1' : '0'))
					  ->order('idRegion');
		return $this->fetchAll($query);
	}

	public function getRegionsCount($visible=true)
	{
		$query = $this->select('COUNT(*)')
					  ->where('visible = ?', (($visible) ? '1' : '0'));
		return $this->fetchOne($query);
	}
}

