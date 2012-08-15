<?php
class Common_Resource_WebService extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'WebServices';
    protected $_primary = 'idWebService';
    protected $_rowClass = 'Common_Resource_WebService_Row';
    protected $_rowsetClass = 'Common_Resource_WebService_Rowset';

    public function addWebService()
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $data = array(
            'idWebService' => new Zend_Db_Expr('NULL'),
            'description'  => $description,
            'added'        => $nowExpr,
            'updated'      => $nowExpr
        );

        try {
            $this->insert($data);
            $idWebService = $this->_db->lastInsertId();

            return $idWebService;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get a web-service by web-service id (primary key)
     *
     * @param int $idWebService the web-service id (primary key)
     * @return Common_Resource_WebService_Row
     */
    public function getWebServiceByWebServiceId($idWebService)
    {
        $query = $this->select()
                      ->where('idWebService = ?', (int) $idWebService);
        try {
            $webServiceRow = $this->fetchRow($query);

            return $webServiceRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get an entire list of the web-services
     *
     * @return Common_Resource_WebService_Rowset
     */
    public function getAllWebServices()
    {
        $query = $this->select()
                      ->order('idWebService ASC');
        try {
            $webServiceRowset = $this->fetchAll($query);

            return $webServiceRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Pagingate through the web-service results
     *
     * @param int $page the page number
     * @param int $interval the number of results per page
     * @param string $order the DB field to order by
     * @param string $direction either ASC or DESC
     *
     * @return Zend_Paginator
     */
    public function getWebServicesPagingator($page, $interval, $order, $direction)
    {
        $query = $this->select()
                      ->order($order . ' ' . $direction);

        $adapter = new Zend_Paginator_Adapter_DbTableSelect($query);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage($interval)
                  ->setCurrentPageNumber($page);

        return $paginator;
    }

    public function updateWebServiceDescription($idWebService, $description)
    {
        $params = array(
            'description' => $description,
            'updated' => new Zend_Db_Expr('NOW()')
        );

        $adapter = $this->getAdapter();
        $where = $adapter->quoteInto('idWebService = ?', (int) $idWebService);

        try {
            $query = $this->update($params, $where);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
