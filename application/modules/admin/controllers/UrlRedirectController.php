<?php
class Admin_UrlRedirectController extends Zend_Controller_Action
{
    protected $_urlRedirectModel = null;

    public function listAction()
    {
        if (null === $this->_urlRedirectModel) {
            $this->_urlRedirectModel = new Common_Model_UrlRedirect();
        }

        $request = $this->getRequest();
        $page      = $request->getParam('page');
        $interval  = $request->getParam('interval');
        $order     = $request->getParam('order');
        $direction = $request->getParam('direction');

        $urlRedirectPaginator = $this->_urlRedirectModel->getAllPaginator(
            $page,
            $interval,
            $order,
            $direction
        );

        $session = new Zend_Session_Namespace(Common_Model_UrlRedirect::SESSION_NS_URLREDIRECTION);

        $this->view->assign(
            array(
                'urlRedirectPaginator' => $urlRedirectPaginator,
                'order'                => isset($session->order) ? $session->order : $order,
                'direction'            => isset($session->direction) ? $session->direction : $direction
            )
        );
    }

    public function createAction()
    {
        $request = $this->getRequest();
        $incomingUrl = $request->getParam('incomingUrl');
        $redirectUrl = $request->getParam('redirectUrl');
        $responseCode = $request->getParam('responseCode');
        $groupName   = $request->getParam('groupName');

        if (null === $this->_urlRedirectModel) {
            $this->_urlRedirectModel = new Common_Model_UrlRedirect();
        }

        $form = new Admin_Form_UrlRedirectForm(
            array(
                'incomingUrl'  => $incomingUrl,
                'redirectUrl'  => $redirectUrl,
                'responseCode' => $responseCode,
                'groupName'    => $groupName
            )
        );

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                if (null === $this->_urlRedirectModel) {
                    $this->_urlRedirectModel = new Common_Model_UrlRedirect();
                }

                $this->_urlRedirectModel->createRedirect(
                    $incomingUrl,
                    $redirectUrl,
                    $responseCode,
                    $groupName
                );

                $this->_helper->redirector->gotoSimple(
                    'list',
                    'url-redirect',
                    'admin',
                    array()
                );
            }
        }

        $this->view->assign(
            array(
                'form' => $form
            )
        );
    }
}
