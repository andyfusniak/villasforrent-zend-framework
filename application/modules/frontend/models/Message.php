<?php
class Frontend_Model_Message
{
    const SENT_BY_MEMBER = 'MEMBER';
    const SENT_BY_ADVERTISER = 'ADVERTISER';
    
    private $_idMessage = null;
    private $sentBy;
    private $body;
    private $_added;

    public function __construct($idMessage, $sentBy, $body, $added)
    {
        $this->_idMessage = (int) $idMessage;
        $this->sentBy = $sentBy;
        $this->body = $body;
        $this->_added = $added;
    }

    public function setMessageId($idMessage)
    {
        $this->_idMessage = (int) $idMessage;
        return $this;
    }

    public function getMessageId()
    {
        return $this->_idMessage;
    }

    public function setSentBy($sentBy)
    {
        $this->sentBy = $sentBy;
        return $this;
    }

    public function getSentBy()
    {
        return $this->sentBy;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setAdded($added)
    {
        $this->_added = $added;
        return $this;
    }

    public function getAdded()
    {
        return $this->_added;
    }
}