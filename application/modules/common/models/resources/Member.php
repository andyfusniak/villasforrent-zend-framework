<?php
class Common_Resource_Member extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'Members';
    protected $_primary = 'idMember';
    protected $_rowClass = 'Common_Resource_Member_Row';
    protected $_rowsetClass = 'Common_Resource_Member_Rowset';

    /**
     * Adds a new member to the database
     *
     * @param string $emailAddress member's email address
     * @param string $passwd plain text password
     * @param string|null $firstname the first name of the member
     * @param string|null $lastname the last name of the member
     *
     * @return int the last insert id of the row added
     */
    public function addMember($emailAddress, $passwd, $firstname = null, $lastname = null)
    {
        $nullExpr = new Zend_Db_Expr('NULL');
        $nowExpr  = new Zend_Db_Expr('NOW()');

        // 8 is the iteration count
        $blowfishHasher = new Vfr_BlowfishHasher(8);

        // generate a one-way blowfish hash for the given password
        // as we don't want to store plain text password in the DB
        $hash = $blowfishHasher->hash($passwd);

        $data = array(
            'idMember'           => $nullExpr,
            'emailAddress'       => $emailAddress,
            'changeEmailAddress' => $nullExpr,
            'hash'               => $hash,
            'firstname'          => $firstname,
            'lastname'           => $lastname,
            'added'              => $nowExpr,
            'updated'            => $nowExpr,
            'lastLogin'          => $nullExpr,
            'emailLastConfirmed' => $nullExpr,
            'lastModifiedBy'     => 'system'
        );

        try {
            $this->insert($data);
            $idMember = $this->_db->lastInsertId();

            return $idMember;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Check the login details
     *
     * @param string $emailAddress email address given for login
     * @param string $passwd password given in plain text
     * @return Common_Resource_Member_Row
     */
    public function login($emailAddress, $passwd)
    {
        $query = $this->select()
                      ->where('emailAddress=?', $emailAddress);
        try {
            $memberRow = $this->fetchRow($query);

            if (null == $memberRow) {
                throw new Vfr_Exception_MemberNotFound("Member Not Found");
            }

            // 8 is the iteration count
            $blowfishHasher = new Vfr_BlowfishHasher(8);

            try {
                $valid = $blowfishHasher->checkPassword($passwd, $memberRow->hash);
            } catch (Vfr_Exception_BlowfishInvalidHash $e) {
                throw $e;
            }

            if (!$valid)
                throw new Vfr_Exception_MemberPasswordFail();

            return $memberRow;
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

    public function getMemberByMemberId($idMember)
    {
        try {
            $query = $this->select()
                          ->where('idMember=?', $idMember);

            return $this->fetchRow($query);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getMemberByEmail($emailAddress)
    {
        try {
            $query = $this->select()
                          ->where('emailAddress=?', $emailAddress)
                          ->limit(1);
            $memberRow = $this->fetchRow($query);

            return $memberRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updatePassword($idMember, $passwd)
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

        $where = $this->getAdapter()->quoteInto('idMember=?', $idMember);
        try {
            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateLastLogin($idMember)
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $params = array(
            'lastlogin' => $nowExpr
        );

        $where = $this->getAdapter()->quoteInto('idMember=?', $idMember);
        try {
            $query = $this->update($params, $where);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function changeEmailAddress($idMember, $newEmailAddress)
    {
        $params = array(
            'emailAddress'   => $newEmailAddress,
            'updated'        => new Zend_Db_Expr('NOW()'),
            'lastModifiedBy' => 'system'
        );

        $where = $this->getAdapter()->quoteInto('idMember = ?', $idMember);
        try {
            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateEmailLastConfirmed($idMember)
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $params = array(
            'emailLastConfirmed' => $nowExpr,
            'updated'            => $nowExpr
        );

        $where = $this->getAdapter()->quoteInto('idMember = ?', $idMember);
        try {
            $query = $this->update($params, $where);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateChangeEmailAddress($idMember, $changeEmailAddress)
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $params = array(
            'changeEmailAddress' => $changeEmailAddress,
            'updated' => $nowExpr
        );

        $where = $this->getAdapter()->quoteInto('idMember = ?', $idMember);
        try {
            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function switchToChangeEmailAddress($idMember)
    {
        // the last part of the clause is to avoid orphaned accounts
        $query = $this->getAdapter()->quoteInto("
            UPDATE Members
            SET emailAddress = changeEmailAddress,
            changeEmailAddress = NULL,
            updated = NOW()
            WHERE idMember=? AND changeEmailAddress IS NOT NULL",
            $idMember
        );

        try {
            $this->_db->query($query);
        } catch (Exception $e) {
            // if it is a Duplicate entry code
            if ($e->getCode() == 1062)
                throw new Vfr_Exception_Member_Db_DuplicateEntry($e);

            throw $e;
        }
    }

    public function resetChangeEmailAddress($idMember)
    {
        $params = array(
            'changeEmailAddress' => new Zend_Db_Expr('NULL'),
            'updated'            => new Zend_Db_Expr('NOW()'),
            'lastModifiedBy'     => 'system'
        );

        $where = $this->getAdapter()->quoteInto('idMember=?', $idMember);
        try {
            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
