<?php
class Common_Resource_InvoiceCredit extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'InvoiceCredits';
    protected $_primary = 'idInvoiceCredit';
    protected $_rowClass = 'Common_Resource_InvoiceCredit_Row';
    protected $_rowsetClass = 'Common_Resource_InvoiceCredit_Rowset';

    /**
     * Apply a credit note to an invoice
     *
     * @param int $idInvoice the invoice id
     * @param int $idCreditNote the credit note id
     * @return int the last insert id
     */
    public function addInvoiceCreditNote($idInvoice, $idCreditNote)
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $data = array(
            'idInvoiceCreditNote' => new Zend_Db_Expr('NULL'),
            'idInvoice'           => (int) $idInvoice,
            'idCreditNote'        => (int) $idCreditNote,
            'added'               => $nowExpr,
            'updated'             => $nowExpr
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
     * Get an invoice-credit-note association by primary key
     * @param int $idInvoiceCreditNote the invoice-creditnote id (primary key)
     *
     * @return Common_Resource_InvoiceCreditNote_Row
     */
    public function getInvoiceCreditNoteByInvoiceCreditNoteId($idInvoiceCreditNote)
    {
        try {
            $query = $this->select()
                          ->where('idInvoiceCreditNote = ?', (int) $idInvoiceCreditNote);
            $invoiceCreditNoteRow = $this->fetchRow($query);

            return $invoiceCreditNoteRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getInvoiceCreditNotesByCreditNoteId($idCreditNote)
    {
        $query = $this->select()
                      ->where('idCreditNote = ?', (int) $idCreditNote)
                      ->order('idInvoiceCreditNote ASC');
        try {
            $invoiceCreditNoteRowset = $this->fetchAll($query);

            return $invoiceCreditNoteRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getInvoiceCreditNotesCreditNoteId($idCreditNote)
    {
        $query = $this->select()
                      ->where('idCreditNote = ?', (int) $idCreditNote)
                      ->order('idInvoiceCreditNote ASC');
        try {
            $invoiceCreditNoteRowset = $this->fetchAll($query);

            return $invoiceCreditNoteRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getInvoiceCreditNoteByCompositeKey($idInvoice, $idCreditNote)
    {
        $query = $this->select()
                      ->where('idInvoice = ?', (int) $idInvoice)
                      ->where('idCreditNote = ?', (int) $idCreditNote)
                      ->order('idInvoiceCreditNote ASC');
        try {
            $invoiceCreditNoteRowset = $this->fetchAll($query);

            return $invoiceCreditNoteRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
