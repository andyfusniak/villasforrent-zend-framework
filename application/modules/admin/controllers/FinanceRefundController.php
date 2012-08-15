<?php
class Admin_FinanceRefundController extends Zend_Controller_Action
{
    public function init() {}

    public function listAction()
    {
        $financeModel = new Common_Model_Finance();

        $request = $this->getRequest();
        $page      = $request->getParam('page');
        $interval  = $request->getParam('interval');
        $order     = $request->getParam('order');
        $direction = $request->getParam('direction');

        $paginator = $financeModel->getRefundsPaginator($page, $interval, $order, $direction);

        $this->view->assign(
            array(
                'order'     => $order,
                'direction' => $direction,
                'paginator' => $paginator
            )
        );
    }
}
