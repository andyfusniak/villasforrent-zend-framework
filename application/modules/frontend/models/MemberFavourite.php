<?php
class Frontend_Model_MemberFavourite
{
    private $_idMemberFavourite;
    private $idMember;
    private $idProperty;
    private $rank;
    private $priority;
    private $added;
    private $updated;

    public function setMemberFavouriteId($idMemberFavourite)
    {
        $this->_idMemberFavourite = (int) $idMemberFavourite;
        return $this;
    }

    public function getMemberFavouriteId()
    {
        return $this->_idMemberFavourite;
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

    public function setPropertyId($idProperty)
    {
        $this->idProperty = (int) $idProperty;
    }

    public function getPropertyId()
    {
        return $this->idProperty;
    }

    public function setRank($rank)
    {
        $this->rank = (int) $rank;
        return $this;
    }

    public function getRank()
    {
        return $this->rank;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setAdded($added)
    {
        $this->added = $added;
    }

    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    public function getUpdated()
    {
        return $this->updated;
    }
}
