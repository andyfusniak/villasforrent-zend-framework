<?php
interface Common_Resource_Property_Interface
{
	public function createProperty($params);
	public function doSearch($options);
	public function getPropertyById($idProperty);
}
