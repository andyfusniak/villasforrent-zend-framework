<?php
class Common_Resource_InvoicePayment extends Vfr_Model_Resource_Db_Table_Abstract
{
    protected $_name = 'InvoicePayments';
    protected $_primary = 'idInvoicePayment';
    protected $_rowClass = 'Common_Resource_InvoicePayment_Row';
    protected $_rowsetClass = 'Common_Resource_InvoicePayment_Rowset';

    /**
     * Link a payment to an invoice
     *
     * @param int $idInvoice the invoice id (foreign key)
     * @param int $idPayment the payment id (foreign key)
     *
     * @return int the invoice-payment id (last insert id)
     */
    public function addInvoicePayment($idInvoice, $idPayment)
    {
        $nowExpr = new Zend_Db_Expr('NOW()');

        $data = array(
            'idInvoicePayment' => new Zend_Db_Expr('NULL'),
            'idInvoice'        => (int) $idInvoice,
            'idPayment'        => (int) $idPayment,
            'added'            => $nowExpr
        );

        try {
            $this->insert($data);
            $idInvoicePayment = $this->_db->lastInsertId();

            return $idInvoicePayment;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get an invoice-payment association by primary key
     * @param int $idInvoicePayment the invoice-payment id (primary key)
     *
     * @return Common_Resource_InvoicePayment_Row
     */
    public function getInvoicePaymentByInvoicePaymentId($idInvoicePayment)
    {
        try {
            $query = $this->select()
                          ->where('idInvoicePayment = ?', (int) $idInvoicePayment);
            $invoicePaymentRow = $this->fetchRow($query);

            return $invoicePaymentRow;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getInvoicePaymentsByInvoiceId($idInvoice)
    {
        $query = $this->select()
                      ->where('idInvoice = ?', (int) $idInvoice)
                      ->order('idInvoicePayment ASC');
        try {
            $invoicePaymentRowset = $this->fetchAll($query);

            return $invoicePaymentRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getInvoicePaymentsByPaymentId($idPayemnt)
    {
        $query = $this->select()
                      ->where('idPayment = ?', (int) $idPayment)
                      ->order('idInvoicePayment ASC');
        try {
            $invoicePaymentRowset = $this->fetchAll($query);

            return $invoicePaymentRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getInvoicePaymentByCompositeKey($idInvoice, $idPayment)
    {
        $query = $this->select()
                      ->where('idInvoice = ?', (int) $idInvoice)
                      ->where('idPayment = ?', (int) $idPayment)
                      ->order('idInvoicePayment ASC');
        try {
            $invoicePaymentRowset = $this->fetchAll($query);

            return $invoicePaymentRowset;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
