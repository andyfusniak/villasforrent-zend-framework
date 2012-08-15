<?php
class AuthenticationController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $this->_helper->ensureSecure();
    }

    public function loginAction()
    {
        $request = $this->getRequest();

        $form = new Frontend_Form_Member_LoginForm();

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                try {
                    $memberModel = new Common_Model_Member();

                    $memberRow = $memberModel->login(
                        $form->getValue('emailAddress'),
                        $form->getValue('passwd')
                    );

                    $memberAuth = Vfr_Auth_Member::getInstance();
                    $memberAuth->getStorage()->write($memberRow);

                    $this->_helper->redirector->gotoSimple(
                        'home',
                        'dashboard',
                        'frontend'
                    );

                } catch (Vfr_Exception_MemberPasswordFail $e) {
                    $this->view->errorMessage = "Login failed";
                    die('login failed');
                } catch (Exception $e) {
                    throw $e;
                }
            } else {
                $form->populate($request->getParams());
            }
        }

        $this->view->assign(
            array(
                'form' => $form
            )
        );
    }

    public function logoutAction()
    {
        Vfr_Auth_Member::getInstance()->clearIdentity();
    }
}
