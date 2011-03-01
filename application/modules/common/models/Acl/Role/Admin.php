<?php
class Common_Model_Acl_Role_Admin implements Zend_Acl_Role_Interface
{
    public function getRoleId()
    {
        return 'Admin';
    }
}
