<?php
class Frontend_Model_Acl_Frontend extends Zend_Acl implements Vfr_Acl_Interface
{
	public function  __construct()
	{
		$this->addRole(new Common_Model_Acl_Role_Guest())
			 ->addRole(new Common_Model_Acl_Role_Member())
			 ->addRole(new Common_Model_Acl_Role_Advertiser())
			 ->addRole(new Common_Model_Acl_Role_Admin());
		
		$this->deny();
        
	}
}
