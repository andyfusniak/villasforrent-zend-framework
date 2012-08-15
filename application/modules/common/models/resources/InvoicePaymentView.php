<?php
class Common_Resource_InvoicePaymentView extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'InvoicePaymentsView';
    protected $_primary = 'idInvoicePayment';
    protected $_rowClass = 'Common_Resource_InvoicePaymentView_Row';
    protected $_rowsetClass = 'Common_Resource_InvoicePaymentView_Rowset';

    /**
     * Pagingate through the invoice payments for a given invoice
     *
     * @param int $page the page number
     * @param int $interval the number of results per page
     * @param string $order the DB field to order by
     * @param string $direction either ASC or DESC
     *
     * @return Zend_Paginator
     */
    public function getInvoicesPaginator($idInvoice, $page, $interval, $order, $direction)
    {
        $query = $this->select()
                      ->where('idInvoice = ?', (int) $idInvoice)
                      ->order($order . ' ' . $direction);

        $adapter = new Zend_Paginator_Adapter_DbTableSelect($query);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage($interval)
                  ->setCurrentPageNumber($page);

        return $paginator;
    }

    public function getInvoicePaymentsViewPaginator($page, $interval, $order, $direction)
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
     * retrieves a list of payments applied to the given invoice in the order
     * in which they were added
     *
     * @param int $idInvoice the invoice id
     * @return Common_Resource_InvoicePaymentView_Rowset
     */
    public function getAppliedPayments($idInvoice)
    {
        $query = $this->select()
                      ->where('idInvoice = ?', (int) $idInvoice)
                      ->order('idInvoicePayment ASC');
        try {
            $invoicePaymentViewRowset = $this->fetchAll($query);

            return $invoicePaymentViewRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
