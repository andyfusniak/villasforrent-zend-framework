<?php
class Common_Resource_Property
    extends Vfr_Model_Resource_Db_Table_Abstract
    implements Common_Resource_Property_Interface
{

    protected $_name = 'Properties';
    protected $_primary = 'idProperty';
    protected $_rowClass = 'Common_Resource_Property_Row';
    protected $_rowsetClass = 'Common_Resource_Property_Rowset';
    protected $_referenceMap = array (
        'PropertyType' => array (
            'columns' => array('idPropertyType'),
            'refTableClass' => 'Common_Resource_PropertyType'
        ),

        'Advertiser' => array (
            'columns' => array('idAdvertiser'),
            'refTableClass' => 'Common_Resource_Advertiser'
        )
    );

    private $_approved = null;
    private $_visible = null;
    private $_count = null;

    const DEFAULT_PROPERTY_ID = 10000;

    const STEP_1_LOCATION       = 1;
    const STEP_2_CONTENT        = 2;
    const STEP_3_PICTURES       = 3;
    const STEP_4_RATES          = 4;
    const STEP_5_AVAILABILITY   = 5;
    const COMPLETE              = 6;

    const FEATURE_MASK_HOMEPAGE     = 0;
    const FEATURE_MASK_COUNTRY      = 1;
    const FEATURE_MASK_REGION       = 2;
    const FEATURE_MASK_DESTINATION  = 3;

    private function _initDefaults()
    {
        $this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

        // setup the default search settings
        $this->_approved = false;
        $this->_visible  = false;
        $this->_count = false;

        $this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
    }

    private function _overrideDefaults($options)
    {
        $this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

        // override the default settings with the search options passed
        if (isset($options['approved'])) {
            $this->_approved = $options['approved'];
        }

        if (isset($options['visible'])) {
            $this->_visible = $options['visible'];
        }

        if (isset($options['count'])) {
            // use Zend_Filter_Boolean here
            $this->_count = $options['count'];
        }

        $this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
    }

    public function doSearch($options)
    {
        $this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

        $this->_initDefaults();

        if (null !== $options) {
            $this->_overrideDefaults($options);
        }

        $properties = null;

        $query = $this->select();
        $query->from(array('P' => 'Properties'));

        if (true === $this->_approved) {
            $query->where('P.approved = ?', (int) $this->_approved);
        }

        if (true === $this->_visible) {
            $query->where('P.visible = ?', (int) $this->_visible);
        }

        if (true === $this->_count) {
            $query->reset(Zend_Db_Select::COLUMNS);
            $query->reset(Zend_Db_Select::FROM);
            $query->from(
                array('P' => 'Properties'),
                new Zend_Db_Expr('COUNT(*)')
            );
            $this->_logger->log(__METHOD__ . ' SQL Query=' . $query->__toString(), Zend_Log::INFO);
            $result = $this->_db->fetchOne($query);
            return $result;
        }

        $this->_logger->log(__METHOD__ . ' SQL Query=' . $query->__toString(), Zend_Log::INFO);

        $propertyRowset = $this->fetchAll($query);

        $this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
        return $propertyRowset;
    }

    //
    // CREATE
    //

    public function createProperty($options)
    {
        $bootstrapOptions =  Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
        $vfrConfig = $bootstrapOptions['vfr'];

        $nullExpr = new Zend_Db_Expr('NULL');
        $nowExpr  = new Zend_Db_Expr('NOW()');
        $data = array (
            'idProperty'        => $nullExpr,
            'idPropertyType'    => $options['params']['idPropertyType'],
            'idAdvertiser'      => $options['params']['idAdvertiser'],
            'idHolidayType'     => $options['params']['idHolidayType'],
            'idLocation'        => $nullExpr, // default location not set
            'urlName'           => '_' . md5(microtime(true).mt_rand(10000,90000)), // underscore denotes temporary url
            'shortName'         => $options['params']['shortName'],
            'locationUrl'       => 'default/default/default',
            'numBeds'           => $options['params']['numBeds'],
            'numSleeps'         => $options['params']['numSleeps'],
            'approved'          => 0,
            'visible'           => 0,
            'expiry'            => $nullExpr,
            'featureMask'       => 0,
            'featureExpiry'     => $nullExpr,
            'emailAddress'      => $options['params']['emailAddress'],
            'photoLimit'        => $vfrConfig['photo']['max_limit_per_property'],
            'added'             => $nowExpr,
            'updated'           => $nowExpr,
            'awaitingApproval'  => 0,
            'status'            => self::STEP_2_CONTENT,
            'lastModifiedBy'    => 'system'
        );

        try {
            $this->insert($data);
            $idProperty = $this->_db->lastInsertId();

            return $idProperty;
        } catch (Exception $e) {
            //$dbAdapter = Zend_Db_Table::getDefaultAdapter();
            //$profiler = $dbAdapter->getProfiler();
            //$lastDbProfilerQuery = $profiler->getLastQueryProfile();
            //var_dump($lastDbProfilerQuery->getQuery());
            throw $e;
        }
    }

    //
    // READ
    //

    public function getAllProperties()
    {
        $query = $this->select();

        try {
            $propertyRowset = $this->fetchAll($query);

            return $propertyRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAllPropertyIds()
    {
        $query = $this->select()
                      ->from($this->_name, 'idProperty');

        try {
            $propertyRowset = $this->fetchAll($query);

            return $propertyRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAllPaginator($page=1, $interval=30, $order='idProperty', $direction='ASC')
    {
        $query = $this->select()
                      ->order($order . ' ' . $direction);

        $adapter = new Zend_Paginator_Adapter_DbTableSelect($query);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage($interval)
                  ->setCurrentPageNumber($page);

        return $paginator;
    }

    public function getPropertiesInLocationCount($idLocation)
    {
        $query = $this->select()
                      ->from($this->_name, 'COUNT(1) AS cnt')
                      ->where('idLocation = ?', $idLocation);
        try {
            $row = $this->fetchRow($query);

            if ($row->cnt > 0)
                return true;

            return false;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getPropertiesInLocation($idLocation)
    {
        $query = $this->select()
                      ->where("idLocation = ?", $idLocation);
        try {
            $propertyRowset = $this->fetchAll($query);

            return $propertyRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get all properties
     *
     * @param boolean $page Use Zend_Paginator?
     * @return Common_Resource_Property_Rowset|Zend_Paginator
     */
    public function getProperties($page, $itemCountPerPage, $order, $direction)
    {
        $query = $this->select();

        if ($order)
            $query->order($order . ' ' . $direction);

        if ($page) {
            $adapter = new Zend_Paginator_Adapter_DbTableSelect($query);
            $paginator = new Zend_Paginator($adapter);
            $paginator->setItemCountPerPage($itemCountPerPage)
                      ->setCurrentPageNumber((int) $page);

            return $paginator;
        }

        try {
            $propertyRowset = $this->fetchAll($query);

            return $propertyRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getPropertiesByGeoUri($uri, $page, $itemCountPerPage, $order, $direction)
    {
        $query = $this->select()
                      ->where("locationUrl like ?", $uri . "%");

        if ($order)
            $query->order($order . ' ' . $direction);

        if ($page) {
            $adapter = new Zend_Paginator_Adapter_DbTableSelect($query);
            $paginator = new Zend_Paginator($adapter);
            $paginator->setItemCountPerPage($itemCountPerPage)
                      ->setCurrentPageNumber((int) $page);

            return $paginator;
        }

        try {
            $propertyRowset = $this->fetchAll($query);

            return $propertyRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getPropertyByUrl($url)
    {
        $concat = new Zend_Db_Expr("CONCAT(locationUrl, '/', urlName)");
        $query = $this->select('idProduct')
                      ->where($concat . ' = ?', $url);
        try {
            $result = $this->fetchRow($query);

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getPropertyById($idProperty)
    {
        $this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

        $query = $this->select();
        $query->from(array('P' => 'Properties'), array('*'));

        //$query->join(array('C' => 'PropertiesContent'), 'P.idProperty = C.idProperty', array());
        $query->where('P.idProperty = ?', $idProperty);

        try {
            $result = $this->fetchRow($query);

            return $result;
        } catch (Exception $e) {
            echo $query;
            exit;
            if ($this->_profiler->getEnabled()) {
                $profilerQuery = $this->_profiler->getLastQueryProfile();
                Zend_Debug::dump($profilerQuery->getQuery(), 'Profiler: ');
                Zend_Debug::dump($profilerQuery->getQueryParams(), 'Profiler: ');
                exit;
            }
            throw $e;
        }

        //$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
    }

    public function getPropertiesByAdvertiserId($idAdvertiser)
    {
        $query = $this->select()
                      ->where('idAdvertiser = ?', $idAdvertiser)
                      ->order('added DESC');
        try {
            $propertyRowset = $this->fetchAll($query);

            return $propertyRowset;
        } catch (Exception $e) {
            die("Failed to execute sql " . $query);
        }
    }

    public function getAllPropertiesAwaitingInitialApproval()
    {
        $query = $this->select()
                      ->where('approved = ?', (int) 0)
                      ->where('awaitingApproval = ?', (int) 1)
                      ->order('updated DESC');

        try {
            $propertyRowset = $this->fetchAll($query);

            return $propertyRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAllPropertiesAwaitingUpdateApproval()
    {
        $query = $this->select()
                      ->where('approved = ?', (int) 1)
                      ->where('checksumMaster != checksumUpdate')
                      ->where('awaitingApproval = ?', 1)
                      ->order('updated DESC');
        try {
            $propertyRowset = $this->fetchAll($query);

            return $propertyRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getStatusByPropertyId($idProperty)
    {
        $query = $this->select()
                      ->from($this->_name, 'status')
                      ->where('idProperty=?', $idProperty)
                      ->limit(1);
        try {
            $row = $this->fetchRow($query);
            return $row->status;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getHolidayTypeByPropertyId($idProperty)
    {
        $query = $this->select('idHolidayType')
                      ->where('idProperty = ?', $idProperty)
                      ->limit(1);
        try {
            $row = $this->fetchRow($query);

            return $row->idHolidayType;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function isUrlNameTaken($idProperty, $urlName)
    {
        $query = $this->select()
                      ->from($this->_name, 'COUNT(1) AS cnt')
                      ->where('urlName = ?', $urlName)
                      ->where('idProperty != ?', $idProperty);

        try {
            $row = $this->fetchRow($query);

            if ($row->cnt > 0)
                return true;

            return false;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getFeaturedProperties($mask, $limit, $uri)
    {
        $query = $this->select()
                      ->where('featureMask >> ? & 0x01', $mask)
                      ->where('locationUrl LIKE ?', $uri . "%")
                      ->order('featurePriority')
                      ->limit($limit);
        try {
            $propertyRowset = $this->fetchAll($query);

            return $propertyRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    //
    // UPDATE
    //

    public function updatePropertyStatus($idProperty, $status)
    {
        $params = array (
            'status'    => $status
        );

        $where = $this->getAdapter()->quoteInto('idProperty = ?', $idProperty);
        try {
            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function setPropertyUrlName($idProperty, $urlName)
    {
        $params = array (
            'urlName'   => $urlName
        );

        $where = $this->getAdapter()->quoteInto('idProperty = ?', $idProperty);
        try {
            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function setPropertyExpiryDate($idProperty, $expiry)
    {
        $params = array (
            'expiry'    => $expiry
        );

        $where = $this->getAdapter()->quoteInto('idProperty = ?', $idProperty);
        try {
            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function setAwaitingApproval($idProperty)
    {
        $params = array (
            'awaitingApproval'  => 1
        );

        $where = $this->getAdapter()->quoteInto('idProperty = ?', $idProperty);
        try {
            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function initialApproveProperty($idProperty)
    {
        $params = array (
            'awaitingApproval'  => 0,
            'approved'          => 1,
            'visible'           => 1
        );

        $whereClause = $this->getAdapter()->quoteInto("
            approved = 0
            AND awaitingApproval = 1
            AND idProperty = ?
        ", $idProperty);

        try {
            $query = $this->update($params, $whereClause);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateApproveProperty($idProperty)
    {
        $params = array (
            'awaitingApproval' => 0
        );

        $where = $this->getAdapter()->quoteInto("
            approved = 1
            AND awaitingApproval = 1
            AND idProperty = ?
        ", $idProperty);

        try {
            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updatePropertyLocationIdAndUrl($idProperty, $idLocation, $locationUrl)
    {
        $params = array (
            'idLocation'  => $idLocation,
            'locationUrl' => $locationUrl
        );

        $whereClause = $this->getAdapter()->quoteInto('idProperty=?', $idProperty);
        try {
            $query = $this->update($params, $whereClause);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updatePropertyLocation($idProperty, $idLocation, $locationUrl)
    {
        $params = array (
            'idLocation'  => $idLocation,
            'locationUrl' => $locationUrl
        );

        $where = $this->getAdapter()->quoteInto('idProperty = ?', $idProperty);
        try {
            $query = $this->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Update the property checksum to reflect the changes made to the
     * indvidual content fields, for the given version
     *
     * @param $idProperty the property id
     * @param $version either the master or update copy
     */
    public function _updateChecksum($idProperty, $version)
    {
        $version = (int) $version;

        $adapter = $this->getAdapter();
        if ($version == 1)
            $field = 'checksumMaster';
        else
            $field = 'checksumUpdate';

        $query = $this->getAdapter()->quoteInto("
            UPDATE Properties
            SET $field = (
                SELECT SHA1(GROUP_CONCAT(cs SEPARATOR ''))
                FROM PropertiesContent
                WHERE version = ?", $version);
            $query .= $adapter->quoteInto(" AND idProperty = ?", $idProperty);
            $query .= $adapter->quoteInto(" ORDER BY idPropertyContentField ASC),
                updated = NOW() WHERE idProperty=?", $idProperty);
        try {
            $this->_db->query($query);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the property 'master' checksum to reflect the changes made to the
     * individual content fields for the master copy
     *
     * @param $idProperty the property id
     */
    public function updateMasterChecksum($idProperty)
    {
        $this->_updateChecksum($idProperty, 1);
    }

    /**
     * Update the property 'update' checksum to reflect the changes made to the
     * individual content fields for the update copy
     *
     * @param $idProperty the property id
     */
    public function updateUpdateChecksum($idProperty)
    {
        $this->_updateChecksum($idProperty, 2);
    }
}
