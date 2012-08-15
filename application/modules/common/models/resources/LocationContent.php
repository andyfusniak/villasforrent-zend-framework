<?php
class Common_Resource_LocationContent extends
    Vfr_Model_Resource_Db_Table_Abstract
    implements Common_Resource_LocationContent_Interface
{
    protected $_name = 'LocationsContent';
    protected $_primary = 'idLocationsContent';
    protected $_rowClass = 'Common_Resource_LocationContent_Row';
    protected $_rowsetClass = 'Common_Resource_LocationContent_Rowset';
    protected $_referenceMap = array(
        'Location' => array(
            'columns' => array('idLocation'),
            'refTableClass' => 'Common_Resource_Location'
        )
    );

    /**
     * Add a new LocationContent entry
     *
     * @param int $idLocation
     * @param string $fieldTag
     * @param string $content
     * @param int $priority
     * @param string $lang
     */
    public function addLocationContent($idLocation, $fieldTag, $content, $priority = 1, $lang = 'EN')
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $data = array(
            'idLocationsContent' => new Zend_Db_Expr('NULL'),
            'idLocation'         => (int) $idLocation,
            'lang'               => strtoupper($lang),
            'fieldTag'           => $fieldTag,
            'priority'           => (int) $priority,
            'content'            => $content,
            'added'              => $nowExpr,
            'updated'            => $nowExpr
        );
        if ($priority == 0)
        {
            var_dump($data);
            die();
        }

        try {
            $this->insert($data);

            return $this->_db->lastInsertId();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getLocationContentByLocationId($idLocation, $lang = 'EN')
    {
        try {
            $query = $this->select()
                          ->where('idLocation = ?', (int) $idLocation);

            if (strlen($lang) > 0)
                $query->where('lang = ?', $lang);


            $locationContentRowset = $this->fetchAll($query);
            return $locationContentRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAllLocationSummarys()
    {
        try {
            $query = $this->select($this::SELECT_WITHOUT_FROM_PART)
                          ->reset(Zend_Db_Select::COLUMNS)
                          ->setIntegrityCheck(false)
                          ->from(
                              array('C' => $this->_name),
                              array(
                                  'idLocation',
                                  'lang',
                                  'fieldTag',
                                  'priority',
                                  'content',
                                  'added',
                                  'updated'
                              )
                          )
                          ->join(
                              array('L' => 'Locations'),
                              'C.idLocation = L.idLocation',
                              array(
                                  'name',
                                  'url'
                              )
                          )
                          ->where('fieldTag IN (?)', array('image-uri', 'image-caption', 'heading'))
                          ->order('L.url ASC');
            $locationContentRowset = $this->fetchAll($query);

            return $locationContentRowset;
        } catch (Exceptiokn $e) {
            throw $e;
        }
    }

    /**
     * Return a set of composite keys for a given location
     *
     * @param int $idLocation
     */
    public function getComposeKeysByLocationId($idLocation)
    {
        try {
            $query = $this->select()
                          ->from($this->_name, array('idLocation', 'lang', 'fieldTag', 'priority'))
                          ->where('idLocation = ?', (int) $idLocation);

            return $this->fetchAll($query);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getLocationContentByCompositeKey($idLocation, $lang, $fieldTag, $priority)
    {
        try {
            $query = $this->select()
                          ->where('idLocation =?', (int) $idLocation)
                          ->where('lang = ?', $lang)
                          ->where('fieldTag = ?', $fieldTag)
                          ->where('priority = ?', (int) $priority);
            return $this->fetchRow($query);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Updates the LocationContent for a given location
     *
     * @param int $idLocation
     * @param string $fieldTag
     * @param string $content
     * @param string $cs
     * @param int $priority
     * @param string $lang
     */
    public function updateLocationContent($idLocation, $fieldTag, $content, $cs, $priority, $lang = 'EN')
    {
        $params = array(
            'content' => $content,
            'updated' => new Zend_Db_Expr('NOW()')
        );

        $adapter = $this->getAdapter();
        $where  = $adapter->quoteInto('idLocation = ?', $idLocation);
        $where .= $adapter->quoteInto(' AND lang = ?', strtoupper($lang));
        $where .= $adapter->quoteInto(' AND fieldTag = ?', $fieldTag);
        $where .= $adapter->quoteInto(' AND cs != ?', $cs);

        try {
            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deleteLocationContentByLocationId($idLocation)
    {
        $adapter = $this->getAdapter();

        $query1 = $adapter->quoteInto("
            DELETE FROM LocationsContent
            WHERE idLocation = ?",
            (int) $idLocation
        );

        $query2 = $adapter->quoteInto("
            ALTER TABLE LocationsContent AUTO_INCREMENT = 1",
            null
        );

        try {
           $this->_db->query($query1);
           $this->_db->query($query2);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
