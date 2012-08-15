<?php
class Vfr_View_Helper_MessageName extends Zend_View_Helper_Abstract
{
    private $_memberModel = null;
    private $_advertiserModel = null;

    private $_memberMap = array();
    private $_advertiserMap = array();

    public function messageName($sentBy, $idMember, $idAdvertiser)
    {
        switch ($sentBy) {
            case Common_Resource_Message::SENT_BY_MEMBER:
                // if the member is in the cache no further computation is required
                if (isset($this->_memberMap[$idMember])) {
                    return $this->_memberMap[$idMember];
                }

                // load the model if it's not already in the local cache
                if (null === $this->_memberModel)
                    $this->_memberModel = new Common_Model_Member();

                $memberRow = $this->_memberModel->getMemberByMemberId($idMember);
                $this->_memberMap[$idMember] = $memberRow->firstname;

                return $memberRow->firstname;
                break;
            case Common_Resource_Message::SENT_BY_ADVERTISER:
                // if the advertiser is in the cache no further computation is required
                if (isset($this->_advertiserMap[$idAdvertiser])) {
                    return $this->_advertiserMap[$idAdvertiser];
                }

                // load the model if it's not already in the local cache
                if (null === $this->_advertiserModel)
                    $this->_advertiserModel = new Common_Model_Advertiser();

                $advertiserRow = $this->_advertiserModel->getAdvertiserById($idAdvertiser);
                $this->_advertiserMap[$idAdvertiser] = $advertiserRow->firstname;

                return $advertiserRow->firstname;
                break;
            default:
                throw new Exception("Unknown sentBy value passed");
        }
    }
}