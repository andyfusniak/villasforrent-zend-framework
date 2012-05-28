<?php
class Controlpanel_Model_Acl_Controlpanel extends Zend_Acl implements Vfr_Acl_Interface
{
    public function  __construct()
    {
        // Resources
        $accountResource        = new Zend_Acl_Resource('account');
        $authenticationResource = new Zend_Acl_Resource('authentication');

        //$authentication = new Zend_Acl_Resource('authentication');
        //$account        = $this->add(new Zend_Acl_Resource('account'));
        //$availability   = $this->add(new Zend_Acl_Resource('availability'));

        $this->add($accountResource);
        $this->add($authenticationResource);

        // Roles
        $guestRole      = new Common_Model_Acl_Role_Guest();
       // $memberRole     = new Common_Model_Acl_Role_Member();
        $advertiserRole = new Common_Model_Acl_Role_Advertiser();

        // guest is the lowest level
        // member and advertiser work independently
        $this->addRole($guestRole);
        //$this->addRole($memberRole, $guestRole);
        //$this->addRole($advertiserRole, $guestRole);

        // default policy, deny everything
        $this->deny();

        // guest privileges
        //$this->allow($guestRole, $authenticationResource, 'login');

        // advertiser privilegs
        //$this->allow($advertiserRole,)
    }
}
