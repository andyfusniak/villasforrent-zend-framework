<?php
class Common_Resource_Advertiser extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'Advertisers';
    protected $_primary = 'idAdvertiser';
    protected $_rowClass = 'Common_Resource_Advertiser_Row';
    protected $_rowsetClass = 'Common_Resource_Advertiser_Rowset';
    protected $_dependentTables = array('Properties');
    protected $_referenceMap = array(
        'idAdministrator' => array(
            'columns'       => array('idAdministrator'),
            'refTableClass' => 'Common_Resource_Administrator'
        )
    );

    public function addNewAdvertiser($params)
    {
        $nullExpr = new Zend_Db_Expr('NULL');
        $nowExpr  = new Zend_Db_Expr('NOW()');

        // 8 is the iteration count
        $blowfishHasher = new Vfr_BlowfishHasher(8);

        // generate a one-way blowfish hash for the given password
        // as we don't want to store plain text password in the DB
        $hash = $blowfishHasher->hash($params['passwd']);

        $data = array(
            'idAdvertiser'    => $nullExpr,
            'iso2char'        => $nullExpr,
            'idAdministrator' => 1,
            'username'        => $nullExpr,
            'hash'            => $hash,
            'emailAddress'    => $params['emailAddress'],
            'firstname'       => $params['firstname'],
            'lastname'        => $params['lastname'],
            'address'         => $nullExpr,
            'postcode'        => $nullExpr,
            'telephone'       => $nullExpr,
            'fax'             => $nullExpr,
            'mobile'          => $nullExpr,
            'added'           => $nowExpr,
            'updated'         => $nowExpr,
            'lastlogin'       => $nullExpr,
            'lastModifiedBy'  => 'system'
        );

        try {
            $this->insert($data);
            $rowId = $this->_db->lastInsertId();

            return $rowId;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function login($emailAddress, $passwd)
    {
        $query = $this->select()
                      ->where('emailAddress=?', $emailAddress);
        try {
            $advertiserRow = $this->fetchRow($query);

            if ($advertiserRow == null)
                throw new Vfr_Exception_AdvertiserNotFound();

            //if ($advertiserRow->emailLastConfirmed == null)
            //  throw new Vfr_Exception_AdvertiserEmailNotConfirmed();

            // 8 is the iteration count
            $blowfishHasher = new Vfr_BlowfishHasher(8);

            //var_dump($passwd);
            //var_dump($advertiserRow->hash);
            try {
                $valid = $blowfishHasher->checkPassword($passwd, $advertiserRow->hash);
            } catch (Vfr_Exception_BlowfishInvalidHash $e) {
                throw $e;
            }

            //var_dump($valid);
            //var_dump($passwd, $valid, $advertiserRow);

            if (!$valid) {
                throw new Vfr_Exception_AdvertiserPasswordFail();
            }

            return $advertiserRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get all advertisers
     *
     * @param boolean $page Use Zend_Paginator?
     * @return Common_Resource_Advertiser_Rowset|Zend_Paginator
     */
    public function getAll($page = null, $interval = 30, $sort = 'idAdvertiser', $direction = 'ASC')
    {
        $adapter = $this->getAdapter();
        $query = $this->select()
                      ->order($sort . ' ' . $direction);

        if (null !== $page) {
            $adapter = new Zend_Paginator_Adapter_DbTableSelect($query);
            $paginator = new Zend_Paginator($adapter);
            $paginator->setItemCountPerPage($interval)
                      ->setCurrentPageNumber((int) $page);

            return $paginator;
        }

        try {
            $rowSet = $this->fetchAll($query);

            return $rowSet;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAllPaginator($page, $interval, $order, $direction)
    {
        $query = $this->select()
                      ->order($order . ' ' . $direction);

        $adapter = new Zend_Paginator_Adapter_DbTableSelect($query);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage($interval)
                  ->setCurrentPageNumber($page);

        return $paginator;
    }

    public function getAdvertiserById($idAdvertiser)
    {
        try {
            $query = $this->select()
                          ->where('idAdvertiser=?', $idAdvertiser);

            return $this->fetchRow($query);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAdvertiserList($advertiserList)
    {
        try {
            $query = $this->select()
                          ->where('idAdvertiser IN (?)', $advertiserList);

            $advertiserRowset = $this->fetchAll($query);

            return $advertiserRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAdvertiserByEmail($emailAddress)
    {
        try {
            $query = $this->select()
                          ->where('emailAddress=?', $emailAddress)
                          ->limit(1);
            $result = $this->fetchRow($query);

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updatePassword($idAdvertiser, $passwd)
    {
        // 8 is the iteration count
        $blowfishHasher = new Vfr_BlowfishHasher(8);

        // generate a one-way blowfish hash for the given password
        // as we don't want to store plain text password in the DB
        $hash = $blowfishHasher->hash($passwd);

        $params = array(
            'hash'           => $hash,
            'updated'        => new Zend_Db_Expr('NOW()'),
            'lastModifiedBy' => 'system'
        );

        $where = $this->getAdapter()->quoteInto('idAdvertiser=?', $idAdvertiser);
        try {
            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateLastLogin($idAdvertiser)
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $params = array(
            'lastlogin' => $nowExpr
        );

        $where = $this->getAdapter()->quoteInto('idAdvertiser=?', $idAdvertiser);
        try {
            $query = $this->update($params, $where);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function changeEmailAddress($idAdvertiser, $newEmailAddress)
    {
        $params = array(
            'emailAddress'   => $newEmailAddress,
            'updated'        => new Zend_Db_Expr('NOW()'),
            'lastModifiedBy' => 'system'
        );

        $where = $this->getAdapter()->quoteInto(
            'idAdvertiser=?', $idAdvertiser
        );

        try {
            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateEmailLastConfirmed($idAdvertiser)
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $params = array(
            'emailLastConfirmed' => $nowExpr,
            'updated'            => $nowExpr
        );

        $where = $this->getAdapter()->quoteInto('idAdvertiser=?', $idAdvertiser);
        try {
            $query = $this->update($params, $where);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateChangeEmailAddress($idAdvertiser, $changeEmailAddress)
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $params = array(
            'changeEmailAddress' => $changeEmailAddress,
            'updated' => $nowExpr
        );

        $where = $this->getAdapter()->quoteInto('idAdvertiser=?', $idAdvertiser);
        try {
            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function switchToChangeEmailAddress($idAdvertiser)
    {
        // the last part of the clause is to avoid orphaned accounts
        $query = $this->getAdapter()->quoteInto("
            UPDATE Advertisers
            SET emailAddress = changeEmailAddress,
            changeEmailAddress = NULL,
            updated = NOW()
            WHERE idAdvertiser=? AND changeEmailAddress IS NOT NULL",
            $idAdvertiser
        );

        try {
            $this->_db->query($query);
        } catch (Exception $e) {
            // if it is a Duplicate entry code
            if ($e->getCode() == 1062)
                throw new Vfr_Exception_Advertiser_Db_DuplicateEntry($e);

            throw $e;
        }
    }

    public function resetChangeEmailAddress($idAdvertiser)
    {
        $params = array(
            'changeEmailAddress' => new Zend_Db_Expr('NULL'),
            'updated'            => new Zend_Db_Expr('NOW()'),
            'lastModifiedBy'     => 'system'
        );

        $where = $this->getAdapter()->quoteInto('idAdvertiser=?', $idAdvertiser);
        try {
            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
