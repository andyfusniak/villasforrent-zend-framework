<?php
class Admin_MemberController extends Zend_Controller_Action
{
    const version = '1.0.0';

    public function listAction()
    {
        $memberModel = new Common_Model_Member();

        $request = $this->getRequest();
        $page      = $request->getParam('page');
        $interval  = $request->getParam('interval');
        $order     = $request->getParam('order');
        $direction = $request->getParam('direction');

        $memberPaginator = $memberModel->getAllPaginator(
            $page,
            $interval,
            $order,
            $direction
        );

        $session = new Zend_Session_Namespace(Common_Model_Member::SESSION_NS_ADMIN_MEMBER);

        $this->view->assign(
            array(
                'memberPaginator' => $memberPaginator,
                'order'         => isset($session->order) ? $session->order : $order,
                'direction'     => isset($session->direction) ? $session->direction : $direction
            )
        );
    }
}
