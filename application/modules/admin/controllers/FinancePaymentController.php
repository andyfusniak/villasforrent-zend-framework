<?php
class Admin_FinancePaymentController extends Zend_Controller_Action
{
    public function init() {}

    public function listAction()
    {
        ZendX_JQuery::enableView($this->view);
        $jquery = $this->view->jQuery();
        $jquery->enable();

        $this->view->headScript()->appendFile('/js/admin/finance/invoice-payment-filter.js');

        $financeModel = new Common_Model_Finance();

        $request = $this->getRequest();
        $page      = $request->getParam('page');
        $interval  = $request->getParam('interval');
        $order     = $request->getParam('order');
        $direction = $request->getParam('direction');
        $filter    = $request->getParam('filter');

        switch ($filter) {
            case '':
                $paginator = $financeModel->getPaymentsInvoicePaymentsViewPaginator(
                    $page, $interval, $order, $direction
                );
                break;
            case 'applied':
                $paginator = $financeModel->getInvoicePaymentsViewPaginator(
                    $page, $interval, $order, $direction
                );
                break;
            case 'unapplied':
                $paginator = $financeModel->getPaymentsUnappliedViewPaginator(
                    $page, $interval, $order, $direction
                );
                break;
        }

        $this->view->assign(
            array(
                'order'     => $order,
                'direction' => $direction,
                'paginator' => $paginator,
                'filter'    => $filter
            )
        );
    }

    public function receiveAction()
    {
        ZendX_JQuery::enableView($this->view);
        $jquery = $this->view->jQuery();
        $jquery->enable()->uiEnable();

        $this->view->headScript()->appendFile('/js/admin/finance/finance-payment-recieve.js');

        $request = $this->getRequest();

        $mode = 'add';
        $params = array(
            'mode' => $mode
        );

        if ($request->isGet()) {
            $idPayment = $request->getParam('idPayment', null);

            if ($idPayment) {
                $paymentMapper = new Frontend_Model_PaymentMapper();
                $paymentObj = $paymentMapper->getPaymentByPaymentId($idPayment);

                $mode = 'edit';
                $helper = $this->view->getHelper('InvoiceDateDdMmYyyy');

                $params = array(
                    'mode'          => $mode,
                    'idPayment'     => $paymentObj->getPaymentId(),
                    'idInvoice'     => $paymentObj->getInvoiceId(),
                    'dateReceived'  => $helper->invoiceDateDdMmYyyy($paymentObj->getDateReceived()),
                    'amount'        => $paymentObj->getAmount(),
                    'currency'      => $paymentObj->getCurrency(),
                    'paymentMethod' => $paymentObj->getMethod(),
                    'notes'         => $paymentObj->getNotes()
                );
            }
        }

        $form = new Admin_Form_Finance_ReceivePaymentForm($params);

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $mode = $request->getParam('mode', 'add');

                $paymentObj = new Frontend_Model_Payment();
                $paymentObj->setPaymentId($request->getParam('idPayment', null))
                           ->setDateReceived($request->getParam('dateReceived'))
                           ->setAmount($request->getParam('amount'))
                           ->setCurrency($request->getParam('currency'))
                           ->setMethod($request->getParam('paymentMethod'))
                           ->setNotes($request->getParam('notes'));
                $idInvoice = $request->getParam('idInvoice', null);

                $paymentMapper = new Frontend_Model_PaymentMapper();

                if ("add" == $mode) {
                    $paymentMapper->addPayment($paymentObj, $idInvoice);
                } else {
                    $paymentMapper->savePayment($paymentObj, $idInvoice);
                }

                $this->_helper->redirector->goToSimple(
                    'list',
                    'finance-payment',
                    'admin'
                );
            }
        }

        $this->view->assign(
            array(
                'form' => $form
            )
        );
    }

    public function applyFilterAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $request = $this->getRequest();
        if ($request->getPost()) {

        }
    }
}
