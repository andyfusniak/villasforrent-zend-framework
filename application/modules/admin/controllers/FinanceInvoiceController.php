<?php
class Admin_FinanceInvoiceController extends Zend_Controller_Action
{
    const version = '1.0.0';

    public function init()
    {

    }

    public function listAction()
    {
        //$idInvoice = 1;

        //$invoiceMapper = new Frontend_Model_InvoiceMapper();

        //$invoiceObj = $invoiceMapper->getInvoice($idInvoice);

        $financeModel = new Common_Model_Finance();

        $request = $this->getRequest();
        $page      = $request->getParam('page');
        $interval  = $request->getParam('interval');
        $order     = $request->getParam('order');
        $direction = $request->getParam('direction');

        $paginator = $financeModel->getInvoiceListViewPaginator($page, $interval, $order, $direction);

        $this->view->assign(
            array(
                'order'     => $order,
                'direction' => $direction,
                'paginator' => $paginator
            )
        );
    }


    public function viewInvoiceAction()
    {
        $request = $this->getRequest();
        $idInvoice = $request->getParam('idInvoice', null);

        $invoiceMapper = new Frontend_Model_InvoiceMapper();

        $invoiceObj = $invoiceMapper->getInvoice($idInvoice);

        $this->view->assign(
            array(
                'invoiceObj' => $invoiceObj
            )
        );
    }
}
