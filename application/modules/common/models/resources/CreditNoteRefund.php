<?php
class Common_Resource_CreditNoteRefund extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'CreditNoteRefunds';
    protected $_primary = 'idCreditNote';
    protected $_rowClass = 'Common_Resource_CreditNoteRefund_Row';
    protected $_rowssetClass = 'Common_Resource_CreditNoteRefund_Rowset';

    /**
     * Add a creditnote-refund association
     *
     * @param int $idCreditNote the credit-note id (foreign key)
     * @param int $idRefund the refund id (foreign key)
     * @return int the creditnote-refund id (last insert id)
     */
    public function addCreditNoteRefund($idCreditNote, $idRefund)
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $data = array(
            'idCreditNoteRefund' => new Zend_Db_Expr('NULL'),
            'idCreditNote'       => (int) $idCreditNote,
            'idRefund'           => (int) $idRefund,
            'added'              => $nowExpr,
            'updated'            => $nowExpr
        );

        try {
            $this->insert($data);
            $idCreditNoteRefund = $this->_db->lastInsertId();

            return $idCreditNoteRefund;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get an creditnote-refund association by primary key
     * @param int $idCreditNoteRefund the creditnote-refund id (primary key)
     *
     * @return Common_Resource_CreditNoteRefund_Row
     */
    public function getCreditNoteRefundByCreditNoteRefundId($idCreditNoteRefund)
    {
        try {
            $query = $this->select()
                          ->where('idCreditNoteRefund = ?', (int) $idCreditNoteRefund);
            $creditNoteRefundRow = $this->fetchRow($query);

            return $creditNoteRefundRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getCreditNoteRefundsByCreditNoteId($idCreditNote)
    {
        $query = $this->select()
                      ->where('idCreditNote = ?', (int) $idCreditNote)
                      ->order('idCreditNoteRefund ASC');
        try {
            $creditNoteRefundRowset = $this->fetchAll($query);

            return $creditNoteRefundRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getCreditNoteRefundsByRefundId($idRefund)
    {
        $query = $this->select()
                      ->where('idRefund = ?', (int) $idRefund)
                      ->order('idCreditNoteRefund ASC');
        try {
            $creditNoteRefundRowset = $this->fetchAll($query);

            return $creditNoteRefundRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getCreditNoteRefundByCompositeKey($idCreditNote, $idRefund)
    {
        $query = $this->select()
                      ->where('idCreditNote = ?', (int) $idCreditNote)
                      ->where('idRefund = ?', (int) $idRefund)
                      ->order('idCreditNoteRefund ASC');
        try {
            $creditNoteRefundRowset = $this->fetchAll($query);

            return $creditNoteRefundRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
