<?php
class Common_Resource_InvoiceItem extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'InvoiceItems';
    protected $_primary = 'idInvoiceItem';
    protected $_rowClass = 'Common_Resource_InvoiceItem_Row';
    protected $_rowsetClass = 'Common_Resource_InvoiceItem_Rowset';

    /**
     * Add an invoice item for a given invoice
     * @param int $idInvoice the invoice id
     * @param int|null $idWebProduct the foreign key for the web product or null
     * @param int|null $idWebService the foreign key for the web service or null
     * @param int $qty the quantity of this item
     * @param int $unitAmount the unit cost of each item to two decimal places e.g. 12.95
     * @param string $description a utf-8 description
     * @param string|null $startDate the start date of this web product or service YYYY-MM-DD
     * @param string|null $expiryDate the expiry date of this web prouct or service YYYY-MM-DD
     * @param string $repeats the frequency that this service is billed e.g. YEARLY, MONTHLY, WEEKLY
     * @return int the invoice item id (last insert id)
     */
    public function addInvoiceItem($idInvoice, $idWebProduct, $idWebService, $qty, $unitAmount, $description,
                                   $startDate, $expiryDate, $repeats)
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $data = array(
            'idInvoiceItem'  => new Zend_Db_Expr('NULL'),
            'idInvoice'      => (int) $idInvoice,
            'idWebProduct'   => (int) $idWebProduct,
            'idWebService'   => (int) $idWebService,
            'qty'            => (int) $qty,
            'unitAmount'     => (float) $unitAmount,
            'description'    => $description,
            'startDate'      => $startDate,
            'expiryDate'     => $expiryDate,
            'repeats'        => $repeats,
            'lineAmount'     => floatval($qty) * (float) $unitAmount,
            'added'          => $nowExpr,
            'updaed'         => $nowExpr
        );


        try {
            $this->insert($data);
            $idInvoiceItem = $this->_db->lastInsertId();

            return $idInvoiceItem;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get an invoice item by primary key
     * @param int $idInvoiceItem the invoice item id
     *
     * @return Common_Resource_InvoiceItem_Row
     */
    public function getInvoiceItemByInvoiceItemId($idInvoiceItem)
    {
        try {
            $query = $this->select()
                          ->where('idInvoiceItem = ?', (int) $idInvoiceItem);
            $invoiceItemRow = $this->fetchRow($query);

            return $invoiceItemRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get all invoice items associated to a given invoice
     *
     * @param int $idInvoice the invoice id
     * @return Commmon_Resource_InvoiceItem_Rowset
     */
    public function getInvoiceItemsByInvoiceId($idInvoice)
    {
        $query = $this->select()
                      ->where('idInvoice = ?', (int) $idInvoice)
                      ->order('idInvoiceItem ASC');
        try {
            $invoiceItemRowset = $this->fetchAll($query);

            return $invoiceItemRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateInvoiceItem($idInvoiceItem, $params)
    {
        $params['update'] = new Zend_Db_Expr('NOW()');

        $adapter = $this->getAdapter();
        if ($status) {
            $where = $adapter->quoteInto('idInvoiceItem = ?', (int) $idInvoiceItem);
        } else {
            $where = $adapter->quoteInto('idInvoiceItem = ?', (int) $idInvoiceItem);
        }

        try {
            $query = $this->update($params, $where);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

}
