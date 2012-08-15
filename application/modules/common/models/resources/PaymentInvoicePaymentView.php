<?php
class Common_Resource_PaymentInvoicePaymentView extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'PaymentsInvoicePaymentsView';
    protected $_primary = 'idPayment';
    protected $_rowClass = 'Common_Resource_PaymentInvoicePaymentView_Row';
    protected $_rowsetClass = 'Common_Resource_PaymentInvoicePaymentView_Rowset';

    /**
     * Pagingate through the payments with invoice id's
     *
     * @param int $page the page number
     * @param int $interval the number of results per page
     * @param string $order the DB field to order by
     * @param string $direction either ASC or DESC
     *
     * @return Zend_Paginator
     */
    public function getPaymentsInvoicePaymentsViewPaginator($page, $interval, $order, $direction)
    {
        $query = $this->select()
                      ->order($order . ' ' . $direction);

        $adapter = new Zend_Paginator_Adapter_DbTableSelect($query);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage($interval)
                  ->setCurrentPageNumber($page);

        return $paginator;
    }


    public function getPaymentInvoicePaymentByPaymentId($idPayment)
    {
        $query = $this->select()
                      ->where('idPayment = ?', (int) $idPayment);
        try {
            $paymentInvoicePaymentRow = $this->fetchRow($query);

            return $paymentInvoicePaymentRow;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
