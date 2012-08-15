<?php
class Common_Resource_Message extends Vfr_Model_Resource_Db_Table_Abstract
{
    const SENT_BY_MEMBER = 'MEMBER';
    const SENT_BY_ADVERTISER = 'ADVERTISER';

    protected $_name = 'Messages';
    protected $_primary = 'idMessage';
    protected $_rowClass = 'Common_Resource_Message_Row';
    protected $_rowsetClass = 'Common_Resource_Message_Rowset';

    /**
     * Sends a message to a given message thread
     *
     * @param int $idMessageThread the message thread id
     * @param string $sentBy set this to either 'MEMBER' or 'ADVERTISER'
     * @body string the utf-8 encoded message to be sent
     * @return int the last databsae insert id for the new row
     */
    public function sendMessage($idMessageThread, $sentBy, $body)
    {
        switch ($sentBy) {
            case self::SENT_BY_MEMBER:
            case self::SENT_BY_ADVERTISER:
                break;
            default:
                throw new Exception("Unknown sentBy type $sentBy");
        }

        $data = array(
            'idMessage'       => new Zend_Db_Expr('NULL'),
            'idMessageThread' => (int) $idMessageThread,
            'sentBy'       => $sentBy,
            'body'         => $body,
            'added'        => new Zend_Db_Expr('NOW()')
        );

        try {
            $this->insert($data);
            $idMessage = $this->_db->lastInsertId();

            return $idMessage;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Retrieve an individiual message using the primary key
     *
     * @param int $idMessage the primary key of the message
     * @return Common_Resource_Message_Rowset
     */
    public function getMessageByMessageId($idMessage)
    {
        $query = $this->select()
                      ->where('idMessage = ?', (int) $idMessage)
                      ->limit(1);
        try {
            $messageRow = $this->fetchRow($query);

            return $messageRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Retrieve all messages for this thread most recent last
     *
     * @param int $idMessageThread the message thread id
     * @return Common_Resource_Message_Rowset
     */
    public function getMessages($idMessageThread)
    {
        $query = $this->select()
                      ->where('idMessageThread = ?', (int) $idMessageThread)
                      ->order('added ASC');
        try {
            $messageRowset = $this->fetchAll($query);

            return $messageRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getMessagesPaginator($idMessageThread, $page, $interval, $order, $direction)
    {
        $query = $this->select()
                      ->where('idMessageThread = ?', (int) $idMessageThread)
                      ->order($order . ' ' . $direction);

        $adapter = new Zend_Paginator_Adapter_DbTableSelect($query);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage($interval)
                  ->setCurrentPageNumber($page);

        return $paginator;
    }

}