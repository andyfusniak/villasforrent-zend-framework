<?php
class Common_Resource_Refund extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'Refunds';
    protected $_primary = 'idRefund';
    protected $_rowClass = 'Common_Resource_Refund_Row';
    protected $_rowsetClass = 'Common_Resource_Refund_Rowset';

    /**
     * Add a refund
     *
     * @param string $dateSent the date the refund was sent YYYY-MM-DD
     * @param float $amount the amount refunded to two decimal places e.g. 12.95
     * @param string $currency the currency code expressed as a 3-digit iso code
     * @param string $method the method of refund e.g. SECPAY, BACS
     * @param string $notes details of the transaction
     *
     * @return the refund id (last insert id)
     */
    public function addRefund($dateSent, $amount, $currency, $method, $notes)
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $data = array(
            'idRefund' => new Zend_Db_Expr(),
            'dateSent' => $dateSent,
            'amount'   => (float) $amount,
            'currency' => $currency,
            'method'   => $method,
            'added'    => $nowExpr,
            'updated'  => $nowExpr
        );

        try {
            $this->insert($data);
            $idRefund = $this->_db->lastInsertId();

            return $idRefund;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getRefundByRefundId($idRefund)
    {
        $query = $this->select()
                      ->where('idRefund = ?', (int) $idRefund);
        try {
            $refundRow = $this->fetchRow($query);

            return $refundRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Pagingate through the refund results
     *
     * @param int $page the page number
     * @param int $interval the number of results per page
     * @param string $order the DB field to order by
     * @param string $direction either ASC or DESC
     *
     * @return Zend_Paginator
     */
    public function getRefundsPaginator($page, $interval, $order, $direction)
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