<?php
class Common_Model_Finance extends Vfr_Model_Abstract
{
    const SESSION_NS_ADMIN_INVOICE_LIST = 'AdminInvoiceListNS';
    const SESSION_NS_ADMIN_CREDITNOTE_LIST = 'AdminCreditNoteListNS';
    const SESSION_NS_ADMIN_PAYMENT_LIST = 'AdminPaymentListNS';
    const SESSION_NS_ADMIN_INVOICEPAYMENT_LIST = 'AdminInvoicePaymentListNS';
    const SESSION_NS_ADMIN_REFUND_LIST = 'AdminRefundListNS';

    public function getInvoiceListViewPaginator($page = 1, $interval = 30, $order = 'idInvoice', $direction = 'ASC')
    {
        $session = new Zend_Session_Namespace(self::SESSION_NS_ADMIN_INVOICE_LIST);

        // defaults
        if ($page !== null)
            $session->page = $page;

        if ($interval !== null)
            $session->interval = $interval;

        if ($order !== null)
            $session->order = $order;

        if ($direction !== null)
            $session->direction = $direction;

        return $this->getResource('InvoiceListView')->getInvoicesPaginator(
            isset($session->page)      ? $session->page : 1,
            isset($session->interval)  ? $session->interval : 30,
            isset($session->order)     ? $session->order : 'idInvoice',
            isset($session->direction) ? $session->direction : 'ASC'
        );
    }

    public function getPaymentsInvoicePaymentsViewPaginator($page = 1, $interval = 30, $order = 'idPayment', $direction = 'ASC')
    {
        $session = new Zend_Session_Namespace(self::SESSION_NS_ADMIN_PAYMENT_LIST);

        // defaults
        if ($page !== null)
            $session->page = $page;

        if ($interval !== null)
            $session->interval = $interval;

        if ($order !== null)
            $session->order = $order;

        if ($direction !== null)
            $session->direction = $direction;

        $session->filter = '';

        return $this->getResource('PaymentInvoicePaymentView')->getPaymentsInvoicePaymentsViewPaginator(
            isset($session->page)      ? $session->page : 1,
            isset($session->interval)  ? $session->interval : 30,
            isset($session->order)     ? $session->order : 'idPayment',
            isset($session->direction) ? $session->direction : 'ASC'
        );
    }

    public function getCreditNoteListViewPaginator($page = 1, $interval = 30, $order = 'idCreditNote', $direction = 'ASC')
    {
        $session = new Zend_Session_Namespace(self::SESSION_NS_ADMIN_CREDITNOTE_LIST);

        // defaults
        if ($page !== null)
            $session->page = $page;

        if ($interval !== null)
            $session->interval = $interval;

        if ($order !== null)
            $session->order = $order;

        if ($direction !== null)
            $session->direction = $direction;

        return $this->getResource('CreditNoteListView')->getCreditNotesPaginator(
            isset($session->page)      ? $session->page : 1,
            isset($session->interval)  ? $session->interval : 30,
            isset($session->order)     ? $session->order : 'idCreditNote',
            isset($session->direction) ? $session->direction : 'ASC'
        );
    }

    public function getPaymentsPaginator($page = 1, $interval = 30, $order = 'idPayment', $direction = 'ASC')
    {
        $session = new Zend_Session_Namespace(self::SESSION_NS_ADMIN_PAYMENT_LIST);

        // defaults
        if ($page !== null)
            $session->page = $page;

        if ($interval !== null)
            $session->interval = $interval;

        if ($order !== null)
            $session->order = $order;

        if ($direction !== null)
            $session->direction = $direction;

        $session->filter = '';

        return $this->getResource('Payment')->getPaymentsPaginator(
            isset($session->page)      ? $session->page : 1,
            isset($session->interval)  ? $session->interval : 30,
            isset($session->order)     ? $session->order : 'idPayment',
            isset($session->direction) ? $session->direction : 'ASC'
        );
    }

    public function getPaymentsUnappliedViewPaginator($page = 1, $interval = 30, $order = 'idPayment', $direction = 'ASC')
    {
        $session = new Zend_Session_Namespace(self::SESSION_NS_ADMIN_PAYMENT_LIST);

        // defaults
        if ($page !== null)
            $session->page = $page;

        if ($interval !== null)
            $session->interval = $interval;

        if ($order !== null)
            $session->order = $order;

        if ($direction !== null)
            $session->direction = $direction;

        $session->filter = 'unapplied';

        return $this->getResource('PaymentUnappliedView')->getUnappliedPaymentsViewPaginator(
            isset($session->page)      ? $session->page : 1,
            isset($session->interval)  ? $session->interval : 30,
            isset($session->order)     ? $session->order : 'idPayment',
            isset($session->direction) ? $session->direction : 'ASC'
        );
    }

    public function getInvoicePaymentsViewPaginator($page = 1, $interval = 30, $order = 'idPayment', $direction = 'ASC')
    {
        $session = new Zend_Session_Namespace(self::SESSION_NS_ADMIN_PAYMENT_LIST);

        // defaults
        if ($page !== null)
            $session->page = $page;

        if ($interval !== null)
            $session->interval = $interval;

        if ($order !== null)
            $session->order = $order;

        if ($direction !== null)
            $session->direction = $direction;

        $session->filter = 'applied';

        return $this->getResource('InvoicePaymentView')->getInvoicePaymentsViewPaginator(
            isset($session->page)      ? $session->page : 1,
            isset($session->interval)  ? $session->interval : 30,
            isset($session->order)     ? $session->order : 'idPayment',
            isset($session->direction) ? $session->direction : 'ASC'
        );
    }

    public function getRefundsPaginator($page = 1, $interval = 30, $order = 'idRefund', $direction = 'ASC', $filter = '')
    {
        $session = new Zend_Session_Namespace(self::SESSION_NS_ADMIN_REFUND_LIST);

        // defaults
        if ($page !== null)
            $session->page = $page;

        if ($interval !== null)
            $session->interval = $interval;

        if ($order !== null)
            $session->order = $order;

        if ($direction !== null)
            $session->direction = $direction;

        if ($filter !== null)
            $session->filter = $filter;

        return $this->getResource('Refund')->getRefundsPaginator(
            isset($session->page)      ? $session->page : 1,
            isset($session->interval)  ? $session->interval : 30,
            isset($session->order)     ? $session->order : 'idRefund',
            isset($session->direction) ? $session->direction : 'ASC'
        );
    }
}
