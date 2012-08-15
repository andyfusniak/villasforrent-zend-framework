<?php
require_once 'purify/HTMLPurifier.auto.php';

class Frontend_Model_MessageMapper extends Frontend_Model_MapperAbstract
{
    protected $_purifier;

    public function __construct()
    {
        // setup an HTML purifier
        $config = HTMLPurifier_Config::createDefault();
        $config->set('Core.Encoding', 'UTF-8');
        $config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
        $config->set('HTML.Allowed', 'no');

        $this->_purifier = new HTMLPurifier($config);
    }

    private function messageThreadObject($messageThreadRow)
    {
        $messageObj = new Frontend_Model_MessageThread();
        $messageObj->setMessageThreadId($messageThreadRow->idMessageThread)
                   ->setMemberId($messageThreadRow->idMember)
                   ->setAdvertiserId($messageThreadRow->idAdvertiser)
                   ->setSummary($messageThreadRow->summary)
                   ->setMemberStoreThreadIn($messageThreadRow->storeMember)
                   ->setAdvertiserStoreThreadIn($messageThreadRow->storeAdvertiser)
                   ->setMemberThreadStatus($messageThreadRow->readMember)
                   ->setAdvertiserThreadStatus($messageThreadRow->readAdvertiser)
                   ->setMessageCount($messageThreadRow->messageCount)
                   ->setLastMessage($messageThreadRow->lastMessage)
                   ->setUpdated($messageThreadRow->updated);

        return $messageObj;
    }

    /**
     * Add a message to the given message-thread
     *
     * @param int $idMesageThread the message-thread id container
     * @param Frontend_Model_Message $messageObj the message to be sent
     * @return array hash containing the last insert id and purified body
     */
    public function sendMessage($idMessageThread, Frontend_Model_Message $messageObj)
    {
        $messageResource = $this->getResource('Message');

        $body = $this->_purifier->purify($messageObj->getBody());

        $idMesage = $messageResource->sendMessage($idMessageThread,
            $messageObj->getSentBy(),
            $body
        );

        return $idMesage;
    }

    /**
     * Get a message object by primary key unique id
     *
     * @param int $idMessage the message id (primary key)
     * @return Frontend_Model_Message
     */
    public function getMessageByMessageId($idMessage)
    {
        $messageResource = $this->getResource('Message');

        $messageRow = $messageResource->getMessageByMessageId($idMessage);
        $messageObj = new Frontend_Model_Message(
            $messageRow->idMessage,
            $messageRow->sentBy,
            $messageRow->body,
            $messageRow->added
        );

        return $messageObj;
    }

    /**
     * Get a list of message-thread objects for a given store
     *
     * @param int $idMember the member's id
     * @param int $storeMember a constant to indicate the store
     * @return array a list of message-thread objects
     */
    public function getMessageThreadOverview($idMember, $storeMember = Common_Resource_MessageThread::STORE_INBOX)
    {
        $threads = array();

        $messageThreadResource = $this->getResource('MessageThread');
        $messsageThreadRowset = $messageThreadResource->getThreadsByMemberId(
            $idMember,
            $storeMember
        );

        foreach ($messsageThreadRowset as $messageThreadRow) {
            $messageThreadObj = $this->messageThreadObject($messageThreadRow);
            array_push($threads, $messageThreadObj);
        }

        return $threads;
    }

    public function getMessageByThreadId($idMessageThread)
    {
        $messageThreadResource = $this->getResource('MessageThread');
        $messageResource = $this->getResource('Message');
        $messageThreadRow = $messageThreadResource->getMessageThreadById($idMessageThread);
        $messageThreadObject = $this->messageThreadObject($messageThreadRow);

        $messageRowset = $messageResource->getMessages($idMessageThread);
        foreach ($messageRowset as $messageRow) {
            $messageObject = new Frontend_Model_Message(
                $messageRow->idMessage,
                $messageRow->sentBy,
                $messageRow->body,
                $messageRow->added
            );

            $messageThreadObject->addMessage($messageObject);
        }

        return $messageThreadObject;
    }

    /**
     *  Change the thread status to either READ or UNREAD
     *  @param int $idMessageThread the id of the message thread
     *  @param int $status either READ or UNREAD
     *
     *  @return Common_Resource_MessageMapper fluent interface
     */
    public function changeMemberThreadStatus($idMessageThread, $status)
    {
        $messageThreadResource = $this->getResource('MessageThread');
        $messageThreadResource->changeMemberThreadStatus($idMessageThread, $status);
        return $this;
    }

    /**
     *  Change the thread status to either READ or UNREAD
     *  @param int $idMessageThread the id of the message thread
     *  @param int $moveTo either STORE_INBOX or STORE_ARCHIVED
     *
     *  @return Common_Resource_MessageMapper fluent interface
     */
    public function moveMemberThread($idMessageThread, $moveTo)
    {
        $messageThreadResource = $this->getResource('MessageThread');
        $messageThreadResource->moveMemberThread($idMessageThread, $moveTo);
        return $this;
    }
}
