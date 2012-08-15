<?php
class Common_Resource_CreditNoteItem extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'CreditNoteItems';
    protected $_primary = 'idCreditNoteItem';
    protected $_rowClass = 'Common_Resource_CreditNoteItem_Row';
    protected $_rowsetClass = 'Common_Resource_CreditNoteItem_Rowset';


    /**
     * Add a credit note item for a given credit note
     * @param int $idCreditNote the credit-note id (foreign key)
     * @param int $qty the number of items
     * @param float $unitAmount the unit cost to two decimal places e.g. 12.95
     * @param string $description details of the item
     * @return int the credit-note id (last insert id)
     */
    public function addCreditNoteItem($idCreditNote, $qty, $unitAmount, $description)
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $data = array(
            'idCreditNoteItem' => new Zend_Db_Expr('NULL'),
            'idCreditNote'     => (int) $idCreditNote,
            'qty'              => (int) $qty,
            'unitAmount'       => (float) $unitAmount,
            'description'      => $description,
            'lineTotal'        => floatval($qty) * (float) $unitAmount,
            'added'            => $nowExpr,
            'updated'          => $nowExpr
        );

        try {
            $this->insert($data);
            $idCreditNoteItem = $this->_db->lastInsertId();

            return $idCreditNoteItem;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the credit-note-item by primary key
     * @param int $idCreditNoteItem the credit-note-item id (primary key)
     *
     * @return Common_Resource_CreditNoteItem_Row
     */
    public function getCreditNoteItemByCreditNoteItemId($idCreditNoteItem)
    {
        $query = $this->select()
                      ->where('idCreditNoteItem = ?', (int) $idCreditNoteItem);

        try {
            $creditNoteItemRow = $this->fetchRow($query);

            return $creditNoteItemRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get all credit-note items associated to a given credit-note
     *
     * @param int $idCreditNote the credit-note id (primary key)
     * @return Common_Resource_CreditNoteItem_Rowset
     */
    public function getCreditNoteItemsByCreditNoteId($idCreditNote)
    {
        $query = $this->select()
                      ->where('idCreditNote = ?', (int) $idCreditNote)
                      ->order('idCreditNoteItem ASC');
        try {
            $creditNoteItemRowset = $this->fetchAll($query);

            return $creditNoteItemRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateCreditNoteItem($idCreditNoteItem, $params)
    {
        $params['update'] = new Zend_Db_Expr('NOW()');

        $adapter = $this->getAdapter();
        if ($status) {
            $where = $adapter->quoteInto('idCreditNoteItem = ?', (int) $idCreditNoteItem);
        } else {
            $where = $adapter->quoteInto('idCreditNoteItem = ?', (int) $idCreditNoteItem);
        }

        try {
            $query = $this->update($params, $where);

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
