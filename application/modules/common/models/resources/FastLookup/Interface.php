<?php
interface Common_Resource_FastLookup_Interface
{
	public function addNewLookup($cItem, $rItem, $dItem, $totalVisible=0, $total=0, $url='', $idProperty=null);
	public function lookup($url);
}
