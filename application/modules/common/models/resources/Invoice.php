<?php
class Common_Resource_Invoice extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'Invoices';
    protected $_primary = 'idInvoice';
    protected $_rowClass = 'Common_Resource_Invoice_Row';
    protected $_rowsetClass = 'Common_Resource_Invoice_Rowset';

    /**
     * Raise a new invoice
     *
     * @param string $invoiceDate the date of the invoice in YYYY-MM-DD format
     * @param float $total the invoice total to two decimal places e.g. 1345.95
     * @param string 3 digit iso currency code
     *
     * @return int the invoice id created
     */
    public function addInvoice($invoiceDate, $total, $currency, $idBillingAddress)
    {
        $bootstrapOptions =  Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
        $vfrConfig = $bootstrapOptions['vfr'];
        $idFromAddress = $vfrConfig['finances']['invoice']['defaultCompanyAddressId'];

        $nowExpr = new Zend_Db_Expr('NOW()');

        $data = array(
            'idInvoice'        => new Zend_Db_Expr('NULL'),
            'invoiceData'      => $invoiceDate,
            'total'            => (float) $total,
            'currency'         => $currency,
            'idFromAddress'    => (int) $idFromAddress,
            'idBillingAddress' => (int) $idBillingAddress,
            'itemLastAdded'    => $nowExpr,
            'status'           => 'OPEN',
            'added'            => $nowExpr,
            'update'           => $nowExpr
        );

        try {
            $this->insert($data);
            $idInvoice = $this->_db->lastInsertId();

            return $idInvoice;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get an invoice by invoice id (primary key)
     *
     * @param int $idInvoice the invoice id (primary key)
     *
     * @return Common_Resource_Invoice_Row
     */
    public function getInvoiceByInvoiceId($idInvoice)
    {
        $query = $this->select()
                      ->where('idInvoice = ?', (int) $idInvoice);
        try {
            $invoiceRow = $this->fetchRow($query);

            return $invoiceRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get an entire list of the invoices.  Warning could be slow and take
     * up a lot of memory
     *
     * @return Common_Resource_Invoice_Rowset
     */
    public function getAllInvoices()
    {
        $query = $this->select()
                      ->order('idInvoice ASC');
        try {
            $invoiceRowset = $this->fetchAll($query);

            return $invoiceRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Check to see if an invoice exists
     *
     * @param int $idInvoice invoice id
     * @return bool true or false
     */
    public function invoiceExists($idInvoice)
    {
        $query = $this->select()
                      ->from($this->_name, array('idInvoice'))
                      ->where('idInvoice = ?', (int) $idInvoice);
        try {
            $invoiceRow = $this->fetchRow($query);

            if (null === $invoiceRow)
                return false;

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Pagingate through the invoices
     *
     * @param int $page the page number
     * @param int $interval the number of results per page
     * @param string $order the DB field to order by
     * @param string $direction either ASC or DESC
     *
     * @return Zend_Paginator
     */
    public function getInvoicesPaginator($page, $interval, $order, $direction)
    {
        $query = $this->select()
                      ->order($order . ' ' . $direction);

        $adapter = new Zend_Paginator_Adapter_DbTableSelect($query);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage($interval)
                  ->setCurrentPageNumber($page);

        return $paginator;
    }

    /**
     * Update the status of an invoice
     * @param int $idInvoice the invoice id (primary key)
     * @param string $status either 'OPEN' or 'CLOSED'
     *
     * @return Common_Resource_Invoice fluent interface
     */
    public function updatePaidStatus($idInvoice, $status)
    {
        $params = array(
            'status'  => $status,
            'updated' => new Zend_Db_Expr('NOW()')
        );

        $adapter = $this->getAdapter();
        if ($status) {
            $where = $adapter->quoteInto('idInvoice = ?', (int) $idInvoice);
        } else {
            $where = $adapter->quoteInto('idInvoice = ?', (int) $idInvoice);
        }

        try {
            $query = $this->update($params, $where);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateInvoice($idInvoice, $params)
    {
        $params['update'] = new Zend_Db_Expr('NOW()');

        $adapter = $this->getAdapter();
        if ($status) {
            $where = $adapter->quoteInto('idInvoice = ?', (int) $idInvoice);
        } else {
            $where = $adapter->quoteInto('idInvoice = ?', (int) $idInvoice);
        }

        try {
            $query = $this->update($params, $where);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
