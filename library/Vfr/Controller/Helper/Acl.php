<?php
/**
 * Acl action helper used for when we went to control access to a resource
 * that does not have a Model
 * @category Vfr
 * @package Vfr_Controller_Helper
 * @copyright Copyright (c) 2011 Andy Fusniak
 */
class Vfr_Controller_Helper_Acl extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var Zend_Acl
     */
    protected $_acl;

    /**
     * @var string
     */
    protected $_identity;


    public function init()
	{
		$module = ucfirst($this->getRequest()->getModuleName());
		$acl = $module . '_Model_Acl_' . $module;
        
        if (class_exists($acl)) {
            $this->_acl = new $acl;
        }
	}

    public function getAcl()
    {
        return $this->_acl;
    }

    /**
     * Set the identity of the current request
     *
     * @param array|string|null|Zend_Acl_Role_Interface $identity
     */
    public function setIdentity($identity)
    {
        
        if ($identity instanceof Common_Resource_Advertiser_Row) {
            $this->_identity = new Common_Model_Acl_Role_Advertiser();
        } else {
            $this->_identity = new Common_Model_Acl_Role_Guest();
        }

        #var_dump($this->_identity);
        #die();
        
        return $this;
    }


    
    public function isAllowed($resource=null, $privilege=null)
    {
        if (null === $this->_acl) {
            return null;
        }
        #var_dump("identity:" . $this->getIdentity());
        #var_dump("resource: " . $resource);
        #var_dump("privilege: " . $privilege);
        #var_dump($this->_acl);
        #die();
        #var_dump($this->_acl->isAllowed($this->getIdentity(), $resource, $privilege));
        #die();

        return $this->_acl->isAllowed($this->getIdentity(), $resource, $privilege);
    }

    /**
     * @return string
     */
    public function getIdentity()
    {
        if (null === $this->_identity) {
            $auth = Zend_Auth::getInstance();

            if (!$auth->hasIdentity()) {
                return 'Guest';
            }
            $this->setIdentity($auth->getIdentity());
        }

        return $this->_identity;
    }
    
    /**
     * Proxy to the isAllowed method
     */
    public function direct($resource=null, $privilege=null)
    {
        #var_dump("resource: " . $resource);
        #var_dump("privilege: " . $privilege);
        #die();
        return $this->isAllowed($resource, $privilege);
    }

}
