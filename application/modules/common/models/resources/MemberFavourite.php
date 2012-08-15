<?php
class Common_Resource_MemberFavourite extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'MemberFavourites';
    protected $_primary = 'idMemberFavourite';
    protected $_rowClass = 'Common_Resource_MemberFavourite_Row';
    protected $_rowsetClass = 'Common_Resource_MemberFavourite_Rowset';

    /**
     * Adds a given property to the given member's favourites list
     *
     * @param int $idMember the member id
     * @param int $idProperty the property id
     * @param int $rank the member's private rank from 1 to 10
     * @param int $priority the display priority unsigned int
     *
     * @return Common_Resource_MemberFavourite fluent interface
     */
    public function addFavourite($idMember, $idProperty, $rank = 10, $priority = 1)
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $data = array(
            'idMemberFavourite' => new Zend_Db_Expr('NULL'),
            'idMember'          => (int) $idMember,
            'idProperty'        => (int) $idProperty,
            'rank'              => (int) $rank,
            'priority'          => (int) $priority,
            'added'             => $nowExpr,
            'updated'           => $nowExpr
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
     * Returns a set of favourites for a given member
     * @param $idMember the member id
     * @return Common_Resource_MemberFavourite_Rowset
     */
    public function getMemberFavourites($idMember)
    {
        $query = $this->select()
                      ->where('idMember = ?', (int) $idMember);
        try {
            $memberFavouriteRowset = $this->fetchAll($query);

            return $memberFavouriteRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Retrieves the member favourite given the primary key
     * @param $idMemberFavourite the primary key of the member favourite item
     * @return Common_Resource_MemberFavourite
     */
    public function getFavouriteByIdMemberFavourite($idMemberFavourite)
    {
        $query = $this->select()
                      ->where('idMemberFavourite = ?', (int) $idMemberFavourite);
        try {
            $memberFavouriteRow = $this->fetchRow($query);
            return $memberFavouriteRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param int $idMember the member id
     * @param int $idProperty the property id
     * @return Common_Resource_MemeberFavourite
     */
    public function getFavouriteByMemberAndPropertyId($idMember, $idProperty)
    {
        $query = $this->select()
                      ->where('idMember = ?', (int) $idMember)
                      ->where('idProperty = ?', (int) $idProperty);
        try {
            $memberFavouriteRow = $this->fetchRow($query);
            return $memberFavouriteRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the member favourites
     * @param $idMemberFavourite the primary key
     * @param $rank the rank of this favourite item in relation to the others the member has
     * @param $priority the display priority unsigned in
     *
     * @return Common_Resource_MemberFavourite
     */
    public function updateMemberFavourite($idMemberFavourite, $rank, $priority)
    {
        $params = array(
            'rank'     => (int) $rank,
            'priority' => (int) $priority,
            'updated'  => new Zend_Db_Expr('NOW()')
        );

        $where = $this->getAdapter()->quoteInto('idMemberFavourites = ?', $idMemberFavourite);
        try {
            $query = $this->update($params, $where);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
