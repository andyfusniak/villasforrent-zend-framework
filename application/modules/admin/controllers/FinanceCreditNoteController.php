<?php
class Admin_FinanceCreditNoteController extends Zend_Controller_Action
{
    const version = '1.0.0';

    public function init()
    {
    }

    public function listAction()
    {
        $financeModel = new Common_Model_Finance();

        $request = $this->getRequest();
        $page      = $request->getParam('page');
        $interval  = $request->getParam('interval');
        $order     = $request->getParam('order');
        $direction = $request->getParam('direction');

        $paginator = $financeModel->getCreditNoteListViewPaginator($page, $interval, $order, $direction);

        $this->view->assign(
            array(
                'order'     => $order,
                'direction' => $direction,
                'paginator' => $paginator
            )
        );
    }

    public function viewCreditNoteAction()
    {
        $request = $this->getRequest();
        $idCreditNote = $request->getParam('idCreditNote', null);

        $creditNoteMapper = new Frontend_Model_CreditNoteMapper();

        $creditNoteObj = $creditNoteMapper->getCreditNote($idCreditNote);

        $this->view->assign(
            array(
                'order'         => $order,
                'direction'     => $direction,
                'creditNoteObj' => $creditNoteObj
            )
        );
    }
}
