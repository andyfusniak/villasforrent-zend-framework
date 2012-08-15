<?php
class Admin_FinancePdfController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function downloadAction()
    {
        $request = $this->getRequest();

        $idInvoice = $request->getParam('idInvoice');

        $invoiceMapper = new Frontend_Model_InvoiceMapper();
        $invoiceObj = $invoiceMapper->getInvoice($idInvoice);

        // create a new PDF invoice
        $invoice = new Vfr_Finance_Invoice($invoiceObj);

        header('Content-Disposition: inline; filename=' . str_pad($idInvoice, 8, "0", STR_PAD_LEFT) . '.pdf');
        header('Content-type: application/x-pdf');

        echo $invoice->renderPdf();
    }
}
