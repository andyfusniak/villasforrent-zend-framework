<?php
class Common_Resource_PaymentUnappliedView extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'PaymentsUnappliedView';
    protected $_primary = 'idPayment';
    protected $_rowClass = 'Common_Resource_PaymentUnappliedView_Row';
    protected $_rowsetClass = 'Common_Resource_PaymentUnappliedView_Rowset';

    /**
     * Pagingate through the unapplied payments
     *
     * @param int $page the page number
     * @param int $interval the number of results per page
     * @param string $order the DB field to order by
     * @param string $direction either ASC or DESC
     *
     * @return Zend_Paginator
     */
    public function getUnappliedPaymentsViewPaginator($page, $interval, $order, $direction)
    {
        $query = $this->select()
                      ->order($order . ' ' . $direction);

        $adapter = new Zend_Paginator_Adapter_DbTableSelect($query);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage($interval)
                  ->setCurrentPageNumber($page);

        return $paginator;
    }
}
