<?php
class Common_Resource_Destination extends Vfr_Model_Resource_Db_Table_Abstract implements Common_Resource_Destination_Interface
{
	protected $_name = 'Destinations';
	protected $_primary = 'idDestination';
	protected $_rowClass = 'Common_Resource_Destination_Row';
	protected $_rowsetClass = 'Common_Resource_Destination_Rowset';
	protected $_dependantTables = array('Properties');

	public function getDestinationById($idDestination)
	{
		$query = $this->select()
					  ->where('idDestination = ?', $idDestination);
		return $this->fetchAll($query);
	}

	public function getDestinations($visible=true)
	{
		$query = $this->select()
					  ->order('idDestination')
					  ->where('visible = ?', (($visible) ? '1' : '0'));
		return $this->fetchAll($query);
	}

	public function getDestinationsCount($visible=true)
	{
		$query = $this->select('COUNT(*)')
					  ->where('visible = ?', (($visible) ? '1' : '0'));
		return $this->fetchOne($query);
	}

	public function getDestinationsByRegionId($idRegion, $visible=true)
	{
		$query = $this->select()
					  ->where('idRegion = ?', $idRegion)
					  ->where('visible = ?', (($visible) ? '1' : '0'));
		return $this->fetchAll($query);
	}

	public function getDestinationsCountByRegionId($idRegion, $visible=true)
	{
		$query = $this->select('COUNT(*)')
					  ->where('idRegion = ?', $idRegion)
					  ->where('visible = ?', (($visible) ? '1' : '0'));
		return $this->fetchOne($query); 
	}
}

