<?php
class Common_Model_Advertiser extends Vfr_Model_Acl_Abstract
{
    const version = '4.0.0';
	const SESSION_NS_ADMIN_ADVERTISER = 'AdminAdvertiserNS';
	
	//
	// CREATE
	//
	
	public function addPasswordReset($idAdvertiser, $token)
	{
		$tokenResource = $this->getResource('Token');
		
		// remove the old tokens first
		$tokenResource->voidOldToken(
			$idAdvertiser,
			Common_Resource_Token::TOKEN_TYPE_ADVERTISER_RESET_PASSWORD,
			$token
		);
		
		return $tokenResource->addPasswordReset($idAdvertiser, $token);
	}
	
	public function addNewAdvertiser($params)
	{
		// start a transaction
		$db = Zend_Db_Table::getDefaultAdapter();
		$db->beginTransaction();
		
		try {
			// add a new advertiser
			$idAdvertiser = $this->getResource('Advertiser')->addNewAdvertiser($params);
		
			// create a confirmation token
			// first, generate a new random token
			$tokenObj = new Vfr_Token();
			$token = $tokenObj->generateUniqueToken();
			
			$this->getResource('Token')->addEmailConfirmation($idAdvertiser, $token);
		
            // commit the transaction
		    $db->commit();		
			
			return $token;
		} catch (Exception $e) {
			// rollback as things didn't go to plan
			$db->rollBack();
			throw $e;
		}
	}
	
	public function addEmailAccountChangeRequest($idAdvertiser, $changeEmailAddress)
	{
		$advertiserResource = $this->getResource('Advertiser');
		$tokenResource 		= $this->getResource('Token');
		
		// check if change request has previously been made
		$tokenRow = $tokenResource->getChangeEmailTokenByAdvertiserId(
			$idAdvertiser
		);
		
		if ($tokenRow) {
			try {
				// set the update change email address
				$advertiserResource->updateChangeEmailAddress(
					$idAdvertiser,
					$changeEmailAddress
				);
				
				return $tokenRow->token;
			} catch (Exception $e) {
				throw $e;
			}
		} else {
			// first time request so generate a new token
			// and update inside a transaction
			
			// start a transaction
			// as we want the next two queries to happen as a pair
			$db = Zend_Db_Table::getDefaultAdapter();
			$db->beginTransaction();
			try {
				// set the update change email address
				$advertiserResource->updateChangeEmailAddress(
					$idAdvertiser,
					$changeEmailAddress
				);
				
				// add the confirmation token
				// create a confirmation token
				// first, generate a new andom token
				$tokenObj = new Vfr_Token();
				$token = $tokenObj->generateUniqueToken();
				$tokenResource->addEmailChange(
					$idAdvertiser,
					$token
				);
				
				$db->commit();
				
				return $token;
			} catch (Exception $e) {
				$db->rollBack();
				throw $e;
			}
		}
	}
	
	//
	// READ
	//
	
	public function getAllPaginator($page=1, $interval=30, $order='idAdvertiser', $direction='ASC')
	{
		$session = new Zend_Session_Namespace(self::SESSION_NS_ADMIN_ADVERTISER);
		
		// defaults
		if ($page != null)
			$session->page = $page;
		
		if ($interval !== null)
			$session->interval = $interval;
		
		if ($order !== null)
			$session->order = $order;
			
		if ($direction !== null)
			$session->direction = $direction;
		
		return $this->getResource('Advertiser')->getAllPaginator(
			isset($session->page)      ? $session->page : 1,
			isset($session->interval)  ? $session->interval : 30,
			isset($session->order)     ? $session->order : 'idAdvertiser',
			isset($session->direction) ? $session->direction : 'ASC'
		);
	}
	
	public function getAll($page=1, $interval=30, $sort='idAdvertiser', $direction='ASC')
	{
		return $this->getResource('Advertiser')->getAll(
			$page,
			$interval,
			$sort,
			$direction
		);
	}
	
	public function login($emailAddress, $passwd)
	{
		$advertiserResource = $this->getResource('Advertiser');
		
		try {
			$advertiserRow = $advertiserResource->login($emailAddress, $passwd);
			
			if ($advertiserRow)
				$advertiserResource->updateLastLogin($advertiserRow->idAdvertiser);
		} catch (Exception $e) {
			throw $e;
		}
		
		return $advertiserRow;
	}
	
	public function getAdvertiserById($idAdvertiser)
	{
		$idAdvertiser = (int) $idAdvertiser;

		return $this->getResource('Advertiser')->getAdvertiserById($idAdvertiser);
	}
	
	public function getAdvertiserList($advertiserList)
	{
		if (!is_array($advertiserList))
			throw new Exception('expects an array type');
			
		return $this->getResource('Advertiser')->getAdvertiserList(
			$advertiserList
		);
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
        $data['iso2char'] = new Zend_Db_Expr('now()');
        $data['added']    = new Zend_Db_Expr('now()');
        $data['updated']  = new Zend_Db_Expr('now()');
		
		return $this->getResource('Advertiser')->saveRow($data);
		
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
	}

	public function getAcl()
    {
        if (null === $this->_acl) {
            $this->setAcl(new Controlpanel_Model_Acl_Controlpanel());
        }
        return $this->_acl;
    }

   
	public function getResourceId()
	{
		return 'Advertiser';
	}
	
	public function getAdvertiserResetDetailsByToken($token)
	{
		return $this->getResource('Token')->getAdvertiserResetDetailsByToken($token);
	}
	
	public function getAdvertiserEmailConfirmationDetailsByToken($token)
	{
		return $this->getResource('Token')
		            ->getAdvertiserEmailConfirmationDetailsByToken(
			$token
		);
	}
	
	public function getEmailConfirmatinTokenByIdAdvertiser($idAdvertiser)
	{
		return $this->getResource('Token')
		            ->getEmailConfirmatinTokenByIdAdvertiser($idAdvertiser);
	}
	
	public function getAdvertiserChangeEmailAddressConfirmationDetailsByToken($idAdvertiser)
	{
		return $this->getResource('Token')
		            ->getAdvertiserChangeEmailAddressConfirmationDetailsByToken(
			$idAdvertiser
		);
	}
	
	//
	// UPDATE
	//

	public function setAcl(Vfr_Acl_Interface $acl)
    {
		if (!$acl->has($this->getResourceId())) {
		    $acl->add($this)->allow('Advertiser', $this, 'listAccount');
        }

		$this->_acl = $acl;
		return $this;
    }


	public function updatePassword($idAdvertiser, $passwd)
	{
		return $this->getResource('Advertiser')->updatePassword($idAdvertiser, $passwd);
	}
	
	
	public function updateLastLogin($idAdvertiser)
	{
		$advertiserResource = $this->getResource('Advertiser');
		$advertiserResource->updateLastLogin($idAdvertiser);
	}
	
	public function changeEmailAddress($idAdvertiser, $newEmailAddress)
	{
		$advertiserRow = $this->getAdvertiserByEmail(
			$newEmailAddress
		);
		
		if ($advertiserRow)
			throw new Vfr_Exception_Advertiser_EmailAlreadyExists('Email already exists');

		// update the advertiser emails				
		$this->getResource('Advertiser')->changeEmailAddress(
			$idAdvertiser,
			$newEmailAddress
		);
		
		// check there is a email confirmation token for this advertiser
		// and if not generate a new one
		$tokenResource = $this->getResource('Token');
		
		$tokenRow = $tokenResource->getEmailConfirmatinTokenByIdAdvertiser(
			$idAdvertiser
		);

		if (null == $tokenRow) {
			// create a confirmation token
			// first, generate a new random token
			$tokenObj = new Vfr_Token();
			$token = $tokenObj->generateUniqueToken();
			
			$tokenResource->addEmailConfirmation(
				$idAdvertiser,
				$token
			);
		}
	}
	
	public function applyNewEmail($idAdvertiser, $token)
	{
		// start a transaction
		// as we want the next two queries to happen as a pair
		$db = Zend_Db_Table::getDefaultAdapter();
		$db->beginTransaction();
		
		$advertiserResource = $this->getResource('Advertiser');
		$tokenResource      = $this->getResource('Token');
		
		try {
			// remove the old token
			$tokenResource->voidOldToken(
				$idAdvertiser,
				Common_Resource_Token::TOKEN_TYPE_ADVERTISER_EMAIL_CHANGE
			);
			
			// switch in the new email address
			$advertiserResource->switchToChangeEmailAddress($idAdvertiser);
			
			$db->commit();
		} catch (Exception $e) {
			$db->rollBack();
			throw $e;
		}
	}
	
	public function cancelChangeEmailRequst($idAdvertiser, $token)
	{
		// start a transaction
		// as we want the next two queries to happen as a pair
		$db = Zend_Db_Table::getDefaultAdapter();
		$db->beginTransaction();
		
		$advertiserResource = $this->getResource('Advertiser');
		$tokenResource      = $this->getResource('Token');
		
		try {
			// remove the old token
			$tokenResource->voidOldToken(
				$idAdvertiser,
				Common_Resource_Token::TOKEN_TYPE_ADVERTISER_EMAIL_CHANGE
			);
			
			// nullify the changeEmailAddress
			$advertiserResource->resetChangeEmailAddress($idAdvertiser);
			
			$db->commit();
		} catch (Exception $e) {
			$db->rollBack();
			throw $e;
		}
	}
	
	public function updateEmailLastConfirmed($idAdvertiser)
	{
		return $this->getResource('Advertiser')
	                ->updateEmailLastConfirmed($idAdvertiser);
	}
	
	public function activate($idAdvertiser, $token)
	{
		$advertiserResource = $this->getResource('Advertiser');
		$tokenResource      = $this->getResource('Token');
		
		$advertiserResource->updateEmailLastConfirmed($idAdvertiser);
		$tokenResource->voidOldToken(
			$idAdvertiser,
			Common_Resource_Token::TOKEN_TYPE_ADVERTISER_EMAIL_CONFIRM,
			null
		);
	}
}
