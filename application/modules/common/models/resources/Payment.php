<?php
class Common_Resource_Payment extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'Payments';
    protected $_primary = 'idPayment';
    protected $_rowClass = 'Common_Resource_Payment_Row';
    protected $_rowsetClass = 'Common_Resource_Payment_Rowset';

    /**
     * Add a payment
     *
     * @param $dateReceived the date the payment was received YYYY-MM-DD
     * @param float $amount the amount recieved to two decimal places e.g. 12.95
     * @param string $currency currency expressed as a 3-digit currency code e.g. GBP, USD, EUR, THB
     * @param string $method the method by which the payment was made e.g. SECPAY, BACS
     * @param string|null notes any notes about this transaction
     *
     * @return int the payment id (last insert id)
     */
    public function addPayment($dateReceived, $amount, $currency, $method, $notes)
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $data = array(
            'idPayment' => new Zend_Db_Expr('NULL'),
            'dateReceived' => $dateReceived,
            'amount'       => (float) $amount,
            'currency'     => $currency,
            'method'       => $method,
            'notes'        => $notes,
            'added'        => $nowExpr,
            'updated'      => $nowExpr
        );

        try {
            $this->insert($data);
            $idPayment = $this->_db->lastInsertId();

            return $idPayment;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get a payment by payment id (primary key)
     *
     * @param int $idPayment the payement id (primary key)
     *
     * @return Common_Resource_Payment_Row
     */
    public function getPaymentByPaymentId($idPayment)
    {
        $query = $this->select()
                      ->where('idPayment = ?', (int) $idPayment);
        try {
            $paymentRow = $this->fetchRow($query);

            return $paymentRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Pagingate through the payments
     *
     * @param int $page the page number
     * @param int $interval the number of results per page
     * @param string $order the DB field to order by
     * @param string $direction either ASC or DESC
     *
     * @return Zend_Paginator
     */
    public function getPaymentsPaginator($page, $interval, $order, $direction)
    {
        $query = $this->select()
                      ->order($order . ' ' . $direction);

        $adapter = new Zend_Paginator_Adapter_DbTableSelect($query);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage($interval)
                  ->setCurrentPageNumber($page);

        return $paginator;
    }

    public function updatePayment($idPayment, $params)
    {
        $params['update'] = new Zend_Db_Expr('NOW()');

        $adapter = $this->getAdapter();
        if ($status) {
            $where = $adapter->quoteInto('idPayment= ?', (int) $idPayment);
        } else {
            $where = $adapter->quoteInto('idPayment = ?', (int) $idPayment);
        }

        try {
            $query = $this->update($params, $where);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateFullPayment($idPayment, $dateReceived, $amount, $currency, $method, $notes)
    {
        $params = array(
            'dateReceived' => $dateReceived,
            'amount'       => $amount,
            'currency'     => $currency,
            'method'       => $method,
            'notes'        => $notes,
            'updated'      => new Zend_Db_Expr('NOW()')
        );

        $where = $this->getAdapter()->quoteInto('idPayment = ?', (int) $idPayment);

        try {
            $query = $this->update($params, $where);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
