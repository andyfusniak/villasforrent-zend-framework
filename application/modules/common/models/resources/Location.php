<?php
class Common_Resource_Location
    extends Vfr_Model_Resource_Db_Table_Abstract
    implements Common_Resource_Location_Interface
{
    protected $_name = 'Locations';
    protected $_primary = 'idLocation';
    protected $_rowClass = 'Common_Resource_Location_Row';
    protected $_rowsetClass = 'Common_Resource_Location_Rowset';

    const ROOT_NODE_ID = 321;

    const NODE_BEFORE = 1;
    const NODE_AFTER  = 2;

    //
    // CREATE
    //

    //public function addLocation($idParent=null, $url, $totalVisible=null, $total=null)
    //{
    //  //
    //  // needs modifying for nested set DB structure
    //  //
    //    $nowExpr  = new Zend_Db_Expr('NOW()');
    //  $nullExpr = new Zend_Db_Expr('NULL');
    //
    //    $data = array (
    //        'idLocation'    => $nullExpr,
    //        'idParent'      => $idParent,
    //        'url'           => $url,
    //        'added'           =>  new Zend_Db_Expr('NOW()'),
    //      'updated'       =>  new Zend_Db_Expr('NOW()')
    //    );
    //
    //    try {
    //        $this->insert($data);
    //    } catch (Exception $e) {
    //        throw $e;
    //    }
    //}


    public function addLocationToParentFirst($parentLocationRow, $rowname)
    {
        // create a space for the new node
        $adapter = $this->getAdapter();
        $query1a = $adapter->quoteInto("
            UPDATE Locations
            SET lt = lt + 2
            WHERE lt > ?",
            $parentLocationRow->rt
        );

        $query1b = $adapter->quoteInto("
            UPDATE Locations
            SET rt = rt + 2
            WHERE rt >= ?",
            $parentLocationRow->rt
        );

        // insert the new node
        $nullExpr = new Zend_Db_Expr('NULL');
        $nowExpr  = new Zend_Db_Expr('NOW()');
        $rowurl = Common_Model_Location::convertLocationNameToUrl(
            $rowname
        );

        // special case - parent is root node
        if ($parentLocationRow->url == '') {
            $url   = $rowurl;
            $name  = $rowname;
            $depth = 1;
        } else {
            $url  = $parentLocationRow->url . '/' . $rowurl;
            $name = $parentLocationRow->name . ' : ' . $rowname;
            $depth = substr_count($url, '/') + 1;
        }

        $data = array(
            'idLocation'      => $nullExpr,
            'idParent'        => $parentLocationRow->idLocation,
            'url'             => $url,
            'lt'              => $parentLocationRow->rt,
            'rt'              => $parentLocationRow->rt + 1,
            'depth'           => $depth,
            'rowurl'          => $rowurl,
            'displayPriority' => 1,
            'totalVisible'    => 0,
            'total'           => 0,
            'name'            => $name,
            'rowname'         => $rowname,
            'prefix'          => '',
            'postfix'         => '',
            'visible'         => 1,
            'added'           => $nowExpr,
            'updated'         => $nowExpr
        );

        try {
            $this->_db->getConnection()->query('LOCK TABLE Locations WRITE');

            // begin transaction
            $this->_db->beginTransaction();

            // step 1 - create a gap for the new node
            $this->_db->query($query1a);
            $this->_db->query($query1b);

            // put the new node in place
            $this->insert($data);
            $idLocation = $this->_db->lastInsertId();

            $this->_db->commit();

            $this->_db->getConnection()->query('UNLOCK TABLES');

            $query = $this->select()
                          ->where('idLocation = ?' , $idLocation)
                          ->limit(1);

            return $this->fetchRow($query);
        } catch (Exception $e) {
            // roll back the transaction
            $this->_db->rollBack();

            $this->_db->getConnection()->query('UNLOCK TABLES');
            throw $e;
        }
    }

    public function rebuildTree($idParent, $lt)
    {
        // the right value of this node is the left value + 1
        $rt = $lt + 1;

        // get all children of this node
        $childrenRowset = $this->getAllLocationsIn($idParent);

        foreach ($childrenRowset as $row) {
            // recursively execute this function for each child of this node
            // $rt is the current right value, which is
            // incremented by the rebuildTree function
            $rt = $this->rebuildTree($row->idLocation, $rt);
        }

        // we've got the left value, and now that we've processed
        // the children of this node we also know the right value
        $this->updateLeftRightOnNode($idParent, $lt, $rt);

        // return the right value of this node + 1
        return $rt + 1;
    }

    //
    // READ
    //

    public function lookup($url)
    {
        $query = $this->select()
                      ->where('url = ?', $url)
                      ->limit(1);
        try {
            $locationRow = $this->fetchRow($query);

            return $locationRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getLocationByPk($idLocation)
    {
        $query = $this->select()
                      ->where('idLocation=?', $idLocation)
                      ->limit(1);
        try {
            $locationRow = $this->fetchRow($query);

            return $locationRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAllLocations($depth=null)
    {
        $query = $this->select()
                      ->order('lt');

        if ($depth)
            $query->where('depth = ?', $depth);

        try {
            $locationRowset = $this->fetchAll($query);

            return $locationRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getLocationList($locationList)
    {
        try {
            $query = $this->select()
                      ->where('idLocation IN (?)', $locationList);

            $locationRowset = $this->fetchAll($query);

            return $locationRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAllLocationsIn($idLocation=null)
    {
        $query = $this->select();
        if ($idLocation == null)
            $query->where('idParent IS NULL');
        else
            $query->where('idParent = ?', $idLocation);

        $query->order('idLocation');
        try {
            $locationRowset = $this->fetchAll($query);

            return $locationRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getPathFromRootNode($idLocation)
    {
        $query = $this->select($this::SELECT_WITHOUT_FROM_PART)
                      ->reset(Zend_Db_Select::COLUMNS)
                      ->from(
                          array ('ancestor' => $this->_name),
                          array (
                              'ancestor.idLocation',
                              'ancestor.rowname',
                              'ancestor.url',
                              'ancestor.totalVisible'
                          )
                      )
                      ->from(array ('child' => $this->_name), array())
                      ->where('child.lt BETWEEN ancestor.lt AND ancestor.rt')
                      ->where('child.idLocation = ?', $idLocation)
                      ->where('ancestor.url != ?', '')
                      ->group('ancestor.idLocation');
        try {
            $locationRowset = $this->fetchAll($query);

            return $locationRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }


    //
    // UPDATE
    //
    public function updateLeftRightOnNode($idLocation, $lt, $rt)
    {
        $params = array (
            'lt' => (int) $lt,
            'rt' => (int) $rt
        );

        $where = $this->getAdapter()->quoteInto('idLocation = ?', $idLocation);

        try {
            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function _makeGapForNewEntry($idParent)
    {
        $exprRt = new Zend_Db_Expr('rt + 2');
        $dataRt = array (
            'rt' => $exprRt
        );

        $exprLt = new Zend_Db_Expr('lt + 2');
        $dataLt = array (
            'lt' => $exprLt
        );

        try {
            $whereClauseRt = $this->getAdapter()->quoteInto('rt >= ?', $idParent);
            $queryRt = $this->update($dataRt, $whereClauseRt);

            $whereClauseLt = $this->getAdapter()->quoteInto('lt > ?', $idParent);
            $queryLt = $this->update($dataLt, $whereClauseLt);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param Common_Resource_LocationRow $sourceLocationRow proposed child node
     * @param Common_Resource_LocationRow $destLocationRow proposed 2nd child node
     * @return bool Returns true if the two nodes are sibliings
     */
    public function haveSameParent($sourceLocationRow, $destLocationRow)
    {
        $sourceParentRow = $this->getParentNode($sourceLocationRow);
        $destParentRow   = $this->getParentNode($destLocationRow);

        if (($sourceParentRow) && ($destParentRow)) {
            if ($sourceParentRow->idLocation == $destParentRow->idLocation)
                return true;
        }

        return false;
    }

    /**
     * @param Common_Resource_LocationRow child node
     * @return Common_Resource_LoationRow|null
     */
    public function getParentNode($locationRow)
    {
        try {
            $query = $this->select($this::SELECT_WITHOUT_FROM_PART)
                          ->reset(Zend_Db_Select::COLUMNS)
                          ->from(
                              array ('parent' => $this->_name),
                              array (
                                  'parent.idLocation',
                                  'parent.rowname'
                              )
                          )
                          ->from(
                                array ('child' => $this->_name),
                                array()
                          )
                          ->where('child.lt > parent.lt AND child.lt < parent.rt')
                          ->where('child.idLocation = ?', $locationRow->idLocation)
                          ->order('parent.lt DESC')
                          ->limit(1);
            $locationRow = $this->fetchRow($query);

            return $locationRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param Common_Resource_LocationRow $sourceLocationRow location node to be moved
     * @param Common_Resource_LocationRow $destLocationRow position in which to place the node
     * @return void
     */
    public function moveNode($sourceLocationRow, $destLocationRow, $position=self::NODE_BEFORE)
    {
        // width of the tree to be moved
        $width = $sourceLocationRow->rt - $sourceLocationRow->lt + 1;

        // new left position
        if ($position == self::NODE_BEFORE)
            $newLt = $destLocationRow->lt;
        else if ($position == self::NODE_AFTER)
            $newLt = $destLocationRow->rt + 1;
        else
            throw Exception('Unknowning positioning passed to method function');
        $tmppos = $sourceLocationRow->lt;
        $distance = $newLt - $sourceLocationRow->lt;

        // if we are moving from right to left, we have to add the extra
        if ($distance < 0) {
            $distance -= $width;
            $tmppos   += $width;
        }

        // step 1 - Create a new space for the sub tree
        $adapter = $this->getAdapter();
        $query1a = $adapter->quoteInto("
            UPDATE Locations
            SET lt = lt + ?", $width);
        $query1a .= $adapter->quoteInto(" WHERE lt >= ?", $newLt);

        $query1b = $adapter->quoteInto("
            UPDATE Locations
            SET rt = rt + ?", $width);
        $query1b .= $adapter->quoteInto(" WHERE rt >= ?", $newLt);

        // step 2 - Move the sub tree in to this space
        $query2 = $adapter->quoteInto("
            UPDATE Locations
            SET lt = lt + ?, ", $distance)
        . $adapter->quoteInto("rt = rt + ?", $distance)
        . $adapter->quoteInto(" WHERE lt >= ?", $tmppos)
        . $adapter->quoteInto(" AND rt < ?", $tmppos) . $adapter->quoteInto(" + ?", $width);

        // step 3 - Repair the gaps in the tree
        $query3a = $adapter->quoteInto("
            UPDATE Locations
            SET lt = lt - ?", $width)
        . $adapter->quoteInto("
            WHERE lt > ?", $sourceLocationRow->rt);

        $query3b = $adapter->quoteInto("
            UPDATE Locations
            SET rt = rt - ?", $width)
        . $adapter->quoteInto("
            WHERE rt > ?", $sourceLocationRow->rt);

        try {
            $this->_db->getConnection()->query('LOCK TABLE Locations WRITE');

            // begin transaction
            $this->_db->beginTransaction();

            // step 1
            $this->_db->query($query1a);
            $this->_db->query($query1b);

            // step 2
            $this->_db->query($query2);

            // step 3
            $this->_db->query($query3a);
            $this->_db->query($query3b);

            $this->_db->commit();

            $this->_db->getConnection()->query('UNLOCK TABLES');
        } catch (Exception $e) {
            // roll back the transaction
            $this->_db->rollBack();

            $this->_db->getConnection()->query('UNLOCK TABLES');
            throw $e;
        }
    }

    //
    // DELETE
    //

    /**
     * @param Common_Resource_LocationRow object to be deleted
     * @return
     */

    public function deleteLeafNode($locationRow)
    {
        if ($locationRow->lt != ($locationRow->rt - 1))
            throw new Vfr_Exception_Location_NotALeafNode(
                            'Location node ' . $locationRow->idLocation . ' is not a leaf node');

        $adapter = $this->getAdapter();
        $query1 = $adapter->quoteInto("
            DELETE FROM Locations
            WHERE idLocation =?",
            $locationRow->idLocation
        );

        $query2a = $adapter->quoteInto("
            UPDATE Locations
            SET lt = lt - 2
            WHERE lt > ?",
            $locationRow->rt
        );

        $query2b = $adapter->quoteInto("
            UPDATE Locations
            SET rt = rt - 2
            WHERE rt > ?",
            $locationRow->rt
        );


        try {
            $this->_db->getConnection()->query('LOCK TABLE Locations WRITE');

            // begin transaction
            $this->_db->beginTransaction();

             // step 1
            $this->_db->query($query1);

            // step 2
            $this->_db->query($query2a);
            $this->_db->query($query2b);

            $this->_db->commit();

            $this->_db->getConnection()->query('UNLOCK TABLES');
        } catch (Exception $e) {
            // roll back the transaction
            $this->_db->rollBack();

            $this->_db->getConnection()->query('UNLOCK TABLES');
            throw $e;
        }
    }
}
