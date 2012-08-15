<?php
class Common_Resource_Secpay extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'Secpay';
    protected $_primary = 'idSecpay';
    protected $_rowClass = 'Common_Resource_Secpay_Row';
    protected $_rowsetClass = 'Common_Resource_Secpay_Rowset';

    public function addSecpay($params)
    {
        $params['idSecpay'] = new Zend_Db_Expr('NULL');
        $params['added'] = new Zend_Db_Expr('NOW()');

        try {
            $this->insert($params);
            $idSecpay = $this->_db->lastInsertId();

            return $idSecpay;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Pagingate through the secpay results
     *
     * @param int $page the page number
     * @param int $interval the number of results per page
     * @param string $order the DB field to order by
     * @param string $direction either ASC or DESC
     *
     * @return Zend_Paginator
     */
    public function getSecpayPaginator($page, $interval, $order, $direction)
    {
        $query = $this->select()
                      ->order($order . ' ' . $direction);

        $adapter = new Zend_Paginator_Adapter_DbTableSelect($query);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage($interval)
                  ->setCurrentPageNumber($page);

        return $paginator;
    }

}