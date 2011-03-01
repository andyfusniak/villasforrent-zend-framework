<?php
interface Common_Resource_Destination_Interface
{
	public function getDestinationById($idDestination);
	public function getDestinationsByRegionId($idRegion, $visible=true);
	public function getDestinationsCountByRegionId($idRegion, $visible=true);
	public function getDestinations($visible=true);
	public function getDestinationsCount($visible=true);
}
