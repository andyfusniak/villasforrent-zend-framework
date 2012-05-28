<?php
abstract class Vfr_Model_Acl_Abstract extends Vfr_Model_Abstract implements Vfr_Model_Acl_Interface, Zend_Acl_Resource_Interface
{
    /**
     * @var Zend_Acl
     */
    protected $_acl = null;

    /**
     * @var string
     */
    protected $_identity = null;

    /**
     * Set the identity of the current request
     *
     * @param array|string|null|Zend_Acl_Role_Interface $identity
     * @return SF_Model_Abstract
     */
    public function setIdentity($identity)
    {
        if ($identity instanceof Common_Resource_Advertiser_Row) {
            $this->_identity = new Common_Model_Acl_Role_Advertiser();
        } else {
            $this->_identity = new Common_Model_Acl_Role_Guest();
        }

        return $this;
    }

    /**
     * Get the identity, if no ident use guest
     *
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

    public function checkAcl($action)
    {
        return $this->getAcl()->isAllowed(
            $this->getIdentity(),
            $this,
            $action
        );
    }
}
