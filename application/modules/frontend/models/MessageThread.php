<?php
class Frontend_Model_MessageThread
{
    const STORE_INBOX = 'INBOX';
    const STORE_ARCHIVED = 'ARCHIVED';

    const READ = 1;
    const UNREAD = 0;

    private $_idMessageThread = null;
    private $idMember;
    private $idAdvertiser;
    private $summary;
    private $storeMember;
    private $storeAdvertiser;
    private $readMember;
    private $readAdvertiser;
    private $messageCount;
    private $lastMessage;
    private $updated;

    private $messages = array();

    public function __construct() {}

    public function setMessageThreadId($idMessageThread)
    {
        $this->_idMessageThread = $idMessageThread;
        return $this;
    }

    public function getMessageThreadId()
    {
        return $this->_idMessageThread;
    }

    public function setMemberId($idMember)
    {
        $this->idMember = (int) $idMember;
        return $this;
    }

    public function getMemberId()
    {
        return $this->idMember;
    }

    public function setAdvertiserId($idAdvertiser)
    {
        $this->idAdvertiser = (int) $idAdvertiser;
        return $this;
    }

    public function getAdvertiserId()
    {
        return $this->idAdvertiser;
    }

    public function setSummary($summary)
    {
        $this->summary = $summary;
        return $this;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function setMemberStoreThreadIn($storeMember)
    {
        $this->storeMember = $storeMember;
        return $this;
    }

    public function getMemberStoreThreadIn()
    {
        return $this->storeMember;
    }

    public function setAdvertiserStoreThreadIn($storeAdvertiser)
    {
        $this->storeAdvertiser = $storeAdvertiser;
        return $this;
    }

    public function getAdvertiserStoreThreadIn()
    {
        return $this->storeAdvertiser;
    }

    public function setMemberThreadStatus($status)
    {
        $this->readMember = (int) $status;
        return $this;
    }

    public function getMemberThreadStatus()
    {
        return $this->readMember;
    }

    public function setAdvertiserThreadStatus($status)
    {
        $this->readAdvertiser = (int) $status;
        return $this;
    }

    public function getAdvertierThreadStatus()
    {
        return $this->readAdvertiser;
    }

    public function setMessageCount($messageCount)
    {
        $this->messageCount = $messageCount;
        return $this;
    }

    public function getMessageCount()
    {
        return $this->messageCount;
    }

    public function setLastMessage($lastUpdated)
    {
        $this->lastMessage = $lastUpdated;
        return $this;
    }

    public function getLastMessage()
    {
        return $this->lastMessage;
    }

    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    public function addMessage(Frontend_Model_Message $obj)
    {
        array_push($this->messages, $obj);

        return $this;
    }

    /**
     * Check is there are any messages in on this thread
     * @return bool true or false
     */
    public function hasMessages()
    {
        return (sizeof($this->messages) > 0);
    }

    /**
     * @return array of message objects
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
