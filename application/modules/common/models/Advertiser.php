<?php
class Common_Model_Advertiser extends Vfr_Model_Acl_Abstract
{
	public function getAll($page=null)
	{
		return $this->getResource('Advertiser')->getAll($page);
	}
	
	public function getAdvertiserById($idAdvertiser)
	{
		$idAdvertiser = (int) $idAdvertiser;

		return $this->getResource('Advertiser')->getAdvertiserById($idAdvertiser);
	}
	
	public function getAdvertiserByEmail($emailAddress)
	{
		$emailAddress = (string) $emailAddress;
		
		return $this->getResource('Advertiser')->getAdvertiserByEmail($emailAddress);
	}
	
	public function registerAdvertiser($post)
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		
		$form = new Frontend_Form_Advertiser_RegistrationForm(array('disableLoadDefaultDecorators' => true));
		if (!$form->isValid($post)) {
			return false;
		}

		// get filtered values
		$data = $form->getValues();
        $data['iso2char'] = 'ZZ';
        $data['added'] = new Zend_Db_Expr('now()');
        $data['updated'] = new Zend_Db_Expr('now()');
		
		return $this->getResource('Advertiser')->saveRow($data);
		
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
	}

	public function getAcl()
    {
        if (null === $this->_acl) {
            $this->setAcl(new Frontend_Model_Acl_Frontend());
        }
        return $this->_acl;
    }

    public function setAcl(Vfr_Acl_Interface $acl)
    {
        if (!$acl->has($this->getResourceId())) {
		    $acl->add($this)->allow('Advertiser', $this, 'listAccount');
        }

		$this->_acl = $acl;
		return $this;
    }

	public function getResourceId()
	{
		return 'Advertiser';
	}
}
