<?php
class Common_Resource_MessageThread extends Vfr_Model_Resource_Db_Table_Abstract
{
    const STORE_INBOX = 'INBOX';
    const STORE_ARCHIVED = 'ARCHIVED';

    const STATUS_READ = 1;
    const STATUS_UNREAD = 0;

    const MEMBER = 1;
    const ADVERTISER = 2;

    protected $_name = 'MessageThreads';
    protected $_primary = 'idMessageThread';
    protected $_rowClass = 'Common_Resource_MessageThread_Row';
    protected $_rowsetClass = 'Common_Resource_MessageThread_Rowset';

    /**
     * Creates a new message thread between a member and advertiser
     * Note a message thread does not contain data about direction flow
     * of messages.  This is done in the Message itself attached to this thread
     * later
     *
     * @param int $idMember the id of the member
     * @param int $idAdvertiser the id of the advertiser
     *
     * @return int the last insert id of the row added
     */
    public function addMessageThread($idMember, $idAdvertiser)
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $data = array(
            'idMessageThread' => new Zend_Db_Expr('NULL'),
            'idMember'        => (int) $idMember,
            'idAdvertiser'    => (int) $idAdvertiser,
            'storeMember'     => self::STORE_INBOX,
            'storeAdvertiser' => self::STORE_INBOX,
            'readMember'      => (int) 0,
            'readAdvertiser'  => (int) 0,
            'messageCount'    => (int) 0,
            'lastUpdated'     => $nowExpr, // trigger updates this
            'updated'         => $nowExpr
        );

        try {
            $this->insert($data);
            $idMessageThread = $this->_db->lastInsertId();

            return $idMessageThread;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Gets the message thread row by message thread primary key
     * @param int $idMessageThread the primary key
     * @return Common_Resource_MessageThread_Row
     */
    public function getMessageThreadById($idMessageThread)
    {
        try {
            $query = $this->select()
                          ->where('idMessageThread = ?', (int) $idMessageThread);

            $messageThreadRow = $this->fetchRow($query);

            return $messageThreadRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Retrieves all threads sent/recieved by this member
     *
     * @param int $idMember the id of the member
     * @return Common_Resource_MessageThread_Rowset a collection of message threads for this member
     */
    public function getThreadsByMemberId($idMember, $storedIn = null)
    {
        $query = $this->select()
                      ->where('idMember = ?', (int) $idMember);
        if (null !== $storedIn)
            $query->where('storeMember = ?', $storedIn);

        try {
            $messageThreadRowset = $this->fetchAll($query);

            return $messageThreadRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Retrieves all threads sent/recieved by this advertiser
     *
     * @param int $idAdvertiser the id of the advertiser
     * @return Common_Resource_MessageThread_Rowset a collection of message threads for this advertiser
     */
    public function getThreadsByAdvertiserId($idAdvertiser)
    {
        $query = $this->select()
                      ->where('idAdvertiser = ?', (int) $idAdvertiser);
        try {
            $messageThreadRowset = $this->fetchAll($query);

            return $messageThreadRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getThreadByCompositeKey($idMember, $idAdvertiser)
    {
        $query = $this->select()
                      ->where('idMember = ?', (int) $idMember)
                      ->where('idAdvertiser = ?', (int) $idAdvertiser)
                      ->limit(1);
        try {
            $messageThreadRow = $this->fetchRow($query);

            return $messageThreadRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Mark a given message thread as read by the member
     * @param int $idMessageThread the message thread id
     * @param int $read set to either STATUS_READ or STATUS_UNREAD
     * @return Common_Resource_MessageThread
     */
    public function changeMemberThreadStatus($idMessageThread, $read = self::STATUS_READ)
    {
        $params = array(
            'readMember' => (int) $read
        );

        $adapter = $this->getAdapter();

        // only change this thread status if its not already marked
        if ($read == self::STATUS_READ)
            $where = $adapter->quoteInto('idMessageThread = ? AND readMember = 0', $idMessageThread);
        else
            $where = $adapter->quoteInto('idMessageThread = ? AND readMember = 1', $idMessageThread);

        try {
            $query = $this->update($params, $where);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Mark this thread as read by the advertiser
     * @param int $idMessageThread the message thread id
     * @param int $read set to either STATUS_READ or STATUS_UNREAD
     * @return Common_Resource_MessageThread
     */
    public function changeAdvertiserThreadStatus($idMessageThread, $read = self::STATUS_READ)
    {
        $params = array(
            'readAdvertiser' => (int) $read
        );

        $adapter = $this->getAdapter();

        // only change this thread status if its not already marked
        if ($read == self::STATUS_READ)
            $where = $adapter->quoteInto('idMessageThread = ? AND readAdvertiser = 0', $idMessageThread);
        else
            $where = $adapter->quoteInto('idMessageThread = ? AND readAdvertiser = 1', $idMessageThread);

        try {
            $query = $this->update($params, $where);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Move a member message thread to a different storage area i.e. inbox or archived
     * @param int $idMessageThread the message thread id
     * @param int $moveTo set to STORE_INBOX or STORE_ARCHIVED
     * @return Common_Resource_MessageThread
     */
    public function moveMemberThread($idMessageThread, $moveTo = self::STORE_ARCHIVED)
    {
        $params = array(
            'storeMember' => $moveTo,
            'updated'     => new Zend_Db_Expr('NOW()')
        );

        $adapter = $this->getAdapter();

        $where = $adapter->quoteInto('idMessageThread = ?', (int) $idMessageThread);

        // only move this message thread if it is not already there
        if ($moveTo == self::STORE_ARCHIVED) {
            $where = $adapter->quoteInto('idMessageThread = ?', $idMessageThread);
            $where .= $adapter->quoteInto(' AND storeAdvertiser = ?', self::STORE_INBOX);
        } else {
            $where = $adapter->quoteInto('idMessageThread = ?', $idMessageThread);
            $where .= $adapter->quoteInto(' AND storeAdvertiser = ?', self::STORE_ARCHIVED);
        }

        try {
            $query = $this->update($params, $where);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Move an advertiser message thread to a different storage area
     * @param int $idMessageThread the message thread id
     * @param int $moveTo set to STORE_INBOX or STORE_ARCHIVED
     * @return Common_Resource_MessageThread
     */
    public function moveAdvertiserThread($idMessageThead, $moveTo = self::STORE_ARCHIVED)
    {
        $params = array(
            'storeAdvertiser' => $moveTo
        );

        $adapter = $this->getAdapter();

        $where = $this->getAdapter()->quoteInto('idMessageThread = ?', $idMesssageThread);

        // only move this message thread if it is not already there
        if ($moveTo == self::STORE_ARCHIVED) {
            $where = $adapter->quoteInto('idMessageThread = ? ', $idMessageThread);
            $where .= $adapter->quoteInto(' AND storeMember = ?', self::STORE_INBOX);
        } else {
            $where = $adapter->quoteInto('idMessageThread = ? ', $idMessageThread);
            $where .= $adapter->quoteInto(' AND storeMember = ?', self::STORE_ARCHIVED);
        }

        try {
            $query = $this->update($params, $where);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

}
