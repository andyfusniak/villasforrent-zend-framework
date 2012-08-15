<?php
class Common_Resource_FeaturedProperty
    extends Vfr_Model_Resource_Db_Table_Abstract
    implements Common_Resource_FeaturedProperty_Interface
{
    protected $_name = 'FeaturedProperties';
    protected $_primary = 'idFeaturedProperty';
    protected $_rowClass = 'Common_Resource_FeaturedProperty_Row';
    protected $_rowsetClass = 'Common_Resource_FeaturedProperty_Rowset';

    protected $_referenceMap = array(
        'Property' => array(
            'columns' => array('idProperty'),
            'refTableClass' => 'Common_Resource_Property'
        ),

        'Location' => array(
            'columns' => array('idLocation'),
            'refTableClass' => 'Common_Resource_Location'
        )
    );

    //
    // CREATE
    //
    public function featureProperty($idProperty, $idLocation, $startDate, $expiryDate, $position)
    {
        $nowExpr  = new Zend_Db_Expr('NOW()');

        $data = array(
            'idFeaturedProperty' => new Zend_Db_Expr('NULL'),
            'idProperty'     => $idProperty,
            'idLocation'     => $idLocation,
            'startDate'      => $startDate,
            'expiryDate'     => $expiryDate,
            'position'       => $position,
            'added'          => $nowExpr,
            'updated'        => $nowExpr,
            'lastModifiedBy' => 'system'
        );

        try {
            $this->insert($data);

            $idFeaturedProperty = $this->_db->lastInsertId();

            return $idFeaturedProperty;
        } catch (Exception $e) {
            throw $e;
        }
    }

    //
    // READ
    //

    /**
     * Retrievs the featured property row by primary key
     * @param int $idFeaturedProperty the primary key
     * @return Common_Resource_FeaturedPropertyRow
     */
    public function getFeaturedPropertyByFeaturedPropertyId($idFeaturedProperty)
    {
        $query = $this->select()
                     ->where('idFeaturedProperty = ?', (int) $idFeaturedProperty)
                     ->limit(1);
        try {
            $featuredPropertyRow = $this->fetchRow($query);
            return $featuredPropertyRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getFeaturedProperties($idLocation)
    {
        $bootstrapOptions = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
        $vfrConfig = $bootstrapOptions['vfr'];
        try {
            $query = $this->select()
                          ->where('idLocation = ?', $idLocation)
                          ->order('position ASC')
                          ->limit($vfrConfig['featured']['limit_per_page']);
            $featuredPropertyRowset = $this->fetchAll($query);

            return $featuredPropertyRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAllFeaturedPropertiesByLocationId($idLocation)
    {
        try {
            $query = $this->select()
                          ->where('idLocation = ?', $idLocation)
                          ->order('position ASC');
            $featuredPropertyRowset = $this->fetchAll($query);

            return $featuredPropertyRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAllFeaturedPropertiesPaginator($page, $interval, $order, $direction)
    {
        $query = $this->select($this::SELECT_WITHOUT_FROM_PART)
                      ->reset(Zend_Db_Select::COLUMNS)
                      ->setIntegrityCheck(false)
                      ->from(
                          array('F' => $this->_name),
                          array(
                              'F.idFeaturedProperty',
                              'F.idProperty',
                              'F.position',
                              'F.startDate',
                              'F.expiryDate'
                          )
                      )
                      ->join(
                          array('L' => 'Locations'),
                          'F.idLocation = L.idLocation',
                          array(
                              'L.name'
                          )
                      )
                      ->limit($interval);

        $adapter = new Zend_Paginator_Adapter_DbTableSelect($query);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage($interval)
                  ->setCurrentPageNumber($page);

        return $paginator;
    }

    public function updateFeaturedProperty($idFeaturedProperty, $idProperty, $idLocation, $startDate, $expiryDate, $position)
    {
        $params = array(
            'idProperty' => (int) $idProperty,
            'idLocation' => (int) $idLocation,
            'startDate'  => $startDate,
            'expiryDate' => $expiryDate,
            'position'   => $position,
            'updated'    => new Zend_Db_Expr('NOW()')
        );

        $adapter = $this->getAdapter();
        $where = $adapter->quoteInto('idFeaturedProperty = ?', (int) $idFeaturedProperty);
        try {
            $query = $this->update($params, $where);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
