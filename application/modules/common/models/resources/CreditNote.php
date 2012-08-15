<?php
class Common_Resource_CreditNote extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'CreditNotes';
    protected $_primary = 'idCreditNote';
    protected $_rowClass = 'Common_Resource_CreditNote_Row';
    protected $_rowssetClass = 'Common_Resource_CreditNote_Rowset';

    /**
     * Add a credit note
     *
     * @param string $creditNoteDate the date the credit-note was raised
     * @param float $total the total amount of the credit note to 2 decimal places e.g. 12.95
     * @param strng $currency the 3 digit iso currency code
     * @param int $idCreditAddress the id of the credit note address
     * @return int the last insert id
     */
    public function addCreditNote($creditNoteDate, $total, $currency, $idCreditAddress)
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $data = array(
            'idCreditNote'    => new Zend_Db_Expr('NULL'),
            'creditNoteDate'  => $creditNoteDate,
            'total'           => (float) $total,
            'currency'        => $currency,
            'idCreditAddress' => (int) $idCreditAddress,
            'refunded'        => (int) 0,
            'added'           => $nowExpr,
            'updated'         => $nowExpr
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
     * Get an credit-note by credit-note id (primary key)
     *
     * @param int $idCreditNote the credit-note id (primary key)
     *
     * @return Common_Resource_CreditNote_Row
     */
    public function getCreditNoteByCreditNoteId($idCreditNote)
    {
        $query = $this->select()
                      ->where('idCreditNote = ?', (int) $idCreditNote);
        try {
            $creditNoteRow = $this->fetchRow($query);

            return $creditNoteRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get an entire list of the credit-notes.  Warning could be slow and take
     * up a lot of memory
     *
     * @return Common_Resource_CreditNote_Rowset
     */
    public function getAllCreditNotes()
    {
        $query = $this->select()
                      ->order('idCreditNote ASC');
        try {
            $creditNoteRowset = $this->fetchAll($query);

            return $creditNoteRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Pagingate through the credit-notes
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
     * Update the paid status of an credit-note
     * @param int $idCreditNote the credit-note id (primary key)
     * @param int|bool $status either 0 (denoting not-refunded) or 1 (denoting refunded)
     * @return Common_Resource_CreditNite fluent interface
     */
    public function updateRefundedStatus($idCreditNote, $status)
    {
        $params = array(
            'refunded' => $status,
            'updated'  => new Zend_Db_Expr('NOW()')
        );

        $adapter = $this->getAdapter();
        if ($status) {
            $where = $adapter->quoteInto('idCreditNote = ?', (int) $idCreditNote);
        } else {
            $where = $adapter->quoteInto('idCreditNote = ?', (int) $idCreditNote);
        }

        try {
            $query = $this->update($params, $where);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateCreditNote($idCreditNote, $params)
    {
        $params['update'] = new Zend_Db_Expr('NOW()');

        $adapter = $this->getAdapter();
        if ($status) {
            $where = $adapter->quoteInto('idCreditNote = ?', (int) $idCreditNote);
        } else {
            $where = $adapter->quoteInto('idCreditNote = ?', (int) $idCreditNote);
        }

        try {
            $query = $this->update($params, $where);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
