<?php
class Admin_AuthenticationController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $this->_helper->ensureSecure();
        $this->_helper->layout()->disableLayout();
    }

    public function loginAction()
    {
        $namespace = new Zend_Session_Namespace();
        if (isset($namespace->adminUsername)) {
            $this->_helper->redirector->gotoSimple('index', 'index', 'admin');
        }

        $request = $this->getRequest();
        $form = new Admin_Form_LoginForm();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $blowfishHasher = new Vfr_BlowfishHasher(8);
                
                // hardcoded password for admin area
                $hash = '$2a$08$4rr1aUnqqnz45/CtoonzH.KUIOfpMm3ZcieV5R5EkVABRv1HOjDhy';
                $result = $blowfishHasher->checkPassword($request->getParam('passwd'), $hash);
                if ((true === $result) && ($request->getParam('username') === 'greycadmin')) {
                    $namespace->adminUsername = 'greycadmin';
                    $this->_helper->redirector->gotoSimple('login', 'authentication', 'admin');
                } else {
                    sleep(5);
                }
            }
        }
        $this->view->assign(array(
            'form' => $form
        ));
    }
}
