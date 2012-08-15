<?php
class Frontend_Model_MemberFavouriteMapper extends Frontend_Model_MapperAbstract
{
    /**
     *
     * @param int $idMember the member id
     * @return array list of favourite objects
     */
    public function getMemberFavourites($idMember)
    {
        $memberFavouriteResource = $this->getResource('MemberFavourite');
        $memberFavouriteRowset = $memberFavouriteResource->getMemberFavourites($idMember);

        $favourites = array();

        foreach ($memberFavouriteRowset as $memberFavouriteRow) {
            $memberFavouriteObj = new Frontend_Model_MemberFavourite();
            $memberFavouriteObj->setMemberId($idMember);
            $memberFavouriteObj->setPropertyId($memberFavouriteRow->idProperty);
            $memberFavouriteObj->setRank($memberFavouriteRow->rank);
            $memberFavouriteObj->setPriority($memberFavouriteRow->priority);
            $memberFavouriteObj->setAdded($memberFavouriteRow->added);
            $memberFavouriteObj->setUpdated($memberFavouriteRow->updated);

            array_push($favourites, $memberFavouriteObj);
        }

        return $favourites;
    }

    /**
     * @param Frontend_Model_MemberFavourite the member favourite object
     * @return int the last insert id of the DB row
     */
    public function addToFavourites(Frontend_Model_MemberFavourite $memberFavouriteObj)
    {
        $memberFavouriteResource = $this->getResource('MemberFavourite');

        $idMemberFavourite = $memberFavouriteResource->addFavourite(
            $memberFavouriteObj->getMemberId(),
            $memberFavouriteObj->getPropertyId(),
            $memberFavouriteObj->getRank(),
            $memberFavouriteObj->getPriority()
        );

        return $idMemberFavourite;
    }

    /**
     * @param int $idMember the member id
     * @param int $idProperty the property id
     * @return Frontend_Model_MemberFavourite
     */
    public function getFavouriteByMemberAndPropertyId($idMember, $idProperty)
    {
        $memberFavouriteResource = $this->getResource('MemberFavourite');

        $memberFavouriteRow = $memberFavouriteResource->getFavouriteByMemberAndPropertyId(
            $idMember,
            $idProperty
        );

        if (null === $memberFavouriteRow)
            return null;

        $memberFavouriteObj = new Frontend_Model_MemberFavourite();
        $memberFavouriteObj->setMemberFavouriteId($memberFavouriteRow->idMemberFavourite);
        $memberFavouriteObj->setMemberId($memberFavouriteRow->idMember);
        $memberFavouriteObj->setPropertyId($memberFavouriteRow->idProperty);
        $memberFavouriteObj->setPriority($memberFavouriteRow->priority);
        $memberFavouriteObj->setRank($memberFavouriteRow->rank);
        $memberFavouriteObj->setAdded($memberFavouriteRow->added);
        $memberFavouriteObj->setUpdated($memberFavouriteRow->updated);

        return $memberFavouriteObj;
    }

    /**
     * @param int $idMember the member id
     * @param int $idProperty the property id
     */
    public function removeFavouriteByMemberAndPropertyId($idMember, $idProperty)
    {
        $memberFavouriteResource = $this->getResource('MemberFavourite');

        $memberFavouriteRow = $memberFavouriteResource->getFavouriteByMemberAndPropertyId(
            $idMember,
            $idProperty
        );

        $memberFavouriteRow->delete();
    }
}
