<?php
interface Vfr_Model_Acl_Interface
{
	public function setIdentity($identity);
	public function getIdentity();
	public function checkAcl($action);
    public function setAcl(Vfr_Acl_Interface $acl);
}