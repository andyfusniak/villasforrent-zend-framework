<?php
class Common_Resource_InvoiceCreditNoteView extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'InvoiceCreditNotesView';
    protected $_primary = 'idInvoiceCreditNote';
    protected $_rowClass = 'Common_Resource_InvoiceCreditNoteView_Row';
    protected $_rowsetClass = 'Common_Resource_InvoiceCreditNoteView_Rowset';


    /**
     * Get all invoice credit notes applied to a given invoice id
     *
     * @param int $idInvoice the invoice id
     * @return Common_Reource_InvoiceCreditNoteView_Rowset
     */
    public function getAppliedCreditNotesByInvoiceId($idInvoice)
    {
        $query = $this->select()
                      ->where('idInvoice = ?', (int) $idInvoice)
                      ->order('idInvoiceCreditNote ASC');
        try {
            $invoiceCreditNoteViewRowset = $this->fetchAll($query);

            return $invoiceCreditNoteViewRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

}
