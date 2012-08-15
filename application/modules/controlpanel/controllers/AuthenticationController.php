<?php
class Controlpanel_AuthenticationController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_logger = Zend_Registry::get('logger');
        //$this->_logger->log(__METHOD__ . ' started method function init()', Zend_Log::INFO);
    }

    public function preDispatch()
    {
        $this->_helper->ensureSecure();
    }

    public function loginAction()
    {
        // check to see if the advertiser is already logged in
        if (Vfr_Auth_Advertiser::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoSimple(
                'home',
                'account',
                'controlpanel'
            );
        }

        $request = $this->getRequest();
        $redirectUri   = $request->getParam('redirect_uri');
        $disableLayout = $request->getParam('disable_layout', false);

        // convert string yes, true, 1 etc to PHP boolean type
        $booleanFilter = new Zend_Filter_Boolean(Zend_Filter_Boolean::ALL);
        $disableLayout = $booleanFilter->filter($disableLayout);

        // disable the MVC layout if required
        if (true === $disableLayout)
            $this->_helper->layout->disableLayout();

        $form = new Controlpanel_Form_LoginForm(
             array(
                'emailAddress'  => $request->getParam('emailAddress', null),
                'disableLayout' => $request->getParam('disable_layout', 'no'),
                'redirectUri'   => $request->getParam('redirect_uri', null)
            )
        );

        if ($request->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                try {
                    $advertiserModel = new Common_Model_Advertiser();
                    $advertiserRow = $advertiserModel->login(
                        $form->getValue('emailAddress'),
                        $form->getValue('passwd')
                    );

                    $advertiserAuth = Vfr_Auth_Advertiser::getInstance();
                    $advertiserAuth->getStorage()->write($advertiserRow);

                    if (strlen($redirectUri) > 0) {
                        $this->_helper->redirector->gotoUrl(
                            urldecode($redirectUri)
                        );
                    } else {
                        $this->_helper->redirector->gotoSimple(
                            'home',
                            'account',
                            'controlpanel'
                        );
                    }
                //} catch (Vfr_Exception_AdvertiserEmailNotConfirmed $e) {
                //    redit to /controlpanel/email-confirmation/activation-required
                } catch (Vfr_Exception_AdvertiserNotFound $e) {
                    $this->view->errorMessage = "Email address and password combination incorrect";
                } catch (Vfr_Exception_AdvertiserPasswordFail $e) {
                    //var_dump('password incorrect');
                    $this->view->errorMessage = "Email address and password combination incorrect";
                } catch (Vfr_Exception_BlowfishInvalidHash $e) {
                    $this->view->errorMessage = "You need to reset your password";
                }
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
        Vfr_Auth_Advertiser::getInstance()->clearIdentity();
    }
}
