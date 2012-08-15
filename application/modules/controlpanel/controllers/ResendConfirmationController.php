<?php
class Controlpanel_ResendConfirmationController extends Zend_Controller_Action
{
    protected $_form;
    protected $_advertiserRow = null;
    protected $_advertiserModel = null;

    public function init()
    {
        $this->_form = new Controlpanel_Form_ChangeEmailAddress();
        $this->_form->setAction('/controlpanel/resend-confirmation/resend');

        if (Vfr_Auth_Advertiser::getInstance()->hasIdentity()) {
            $this->_advertiserRow = Vfr_Auth_Advertiser::getInstance()->getIdentity();
        }

        // see if the advertiser has clicked the email confirmation link in the meantime
        if (null == $this->_advertiserModel)
            $this->_advertiserModel = new Common_Model_Advertiser();

        $dbAdvertiserRow = $this->_advertiserModel->getAdvertiserById(
            $this->_advertiserRow->idAdvertiser
        );

        if ($dbAdvertiserRow) {
            if ($dbAdvertiserRow->emailLastConfirmed != null) {
                // update the current login session
                $auth = Vfr_Auth_Advertiser::getInstance();
                $auth->getStorage()->write($dbAdvertiserRow);

                $this->_redirect(
                    '/controlpanel/account/home'
                );
                return;
            }
        }
    }

    public function resendAction()
    {
        $request = $this->getRequest();
        if ($request->isPost() && ($request->getParam('process') == 'change-email-address')) {
            if ($this->_form->isValid($request->getPost())) {
                if (null == $this->_advertiserModel)
                    $this->_advertiserModel = new Common_Model_Advertiser();

                try {
                    $emailAddress= $this->_form->getValue('emailAddress');

                    // change the email address
                    $this->_advertiserModel->changeEmailAddress(
                        $this->_advertiserRow->idAdvertiser,
                        $emailAddress
                    );

                    // reload the advertiser details
                    $this->_advertiserRow = $this->_advertiserModel->getAdvertiserByEmail(
                        $emailAddress
                    );

                    // retrieve the email confirmation token associated to this advertiser
                    $tokenRow = $this->_advertiserModel->getEmailConfirmationTokenByAdvertiserId(
                        $this->_advertiserRow->idAdvertiser
                    );

                    $vfrMail = new Vfr_Mail(
                        '/modules/controlpanel/views/emails',
                        'register-confirm-email' // no extensions required
                    );

                    $vfrMail->send(
                        $this->_advertiserRow->emailAddress,
                        "HolidayPropertyWorldwide.com Confirm Your Email Address",
                        array(
                            'idAdvertiser'  => $this->_advertiserRow->idAdvertiser,
                            'firstname'     => $this->_advertiserRow->firstname,
                            'lastname'      => $this->_advertiserRow->lastname,
                            'emailAddress'  => $this->_advertiserRow->emailAddress,
                            'token'         => $tokenRow->token
                        ),
                        Vfr_Mail::MODE_ONLY_TXT
                    );

                    // update the current login session
                    $auth = Vfr_Auth_Advertiser::getInstance();
                    $auth->getStorage()->write($this->_advertiserRow);

                    $this->_form->clearElements();

                    $changeSuccessXhtml = '<div class="successbox">Your email address has been updated</div>';
                } catch (Vfr_Exception_Advertiser_EmailAlreadyExists $e) {
                    throw $e;
                }
            }
        } else if ($request->isPost() && ($request->getParam('process') == 'resend')) {
            $resendSuccessXhtml = '<div class="successbox">A confirmation email has been resent</div>';
        } else {
            $this->_form->populate($request->getPost());
        }

        $this->view->assign(
            array(
                'advertiserRow' => $this->_advertiserRow,
                'form'          => $this->_form,
                'resendSuccessXhtml'  => isset($resendSuccessXhtml) ? $resendSuccessXhtml : '',
                'changeSuccessXhtml'  => isset($changeSuccessXhtml) ? $changeSuccessXhtml : ''
            )
        );
    }

    public function sentAction()
    {
        if (null == $this->_advertiserModel)
            $this->_advertiserModel = new Common_Model_Advertiser();

        // retrieve the email confirmation token associated to this advertiser
        $tokenRow = $this->_advertiserModel->getEmailConfirmationTokenByAdvertiserId(
            $this->_advertiserRow->idAdvertiser
        );

        if (null == $tokenRow) {
            // create a confirmation token
            // first, generate a new random token
            $tokenObj = new Vfr_Token();
            $token = $tokenObj->generateUniqueToken();

            $this->_advertiserModel->addEmailConfirmation(
                $this->_advertiserRow->idAdvertiser,
                $token
            );
        } else {
            $token = $tokenRow->token;
        }

        $vfrMail = new Vfr_Mail(
            '/modules/controlpanel/views/emails',
            'register-confirm-email' // no extensions required
        );

        $vfrMail->send(
            $this->_advertiserRow->emailAddress,
            "HolidayPropertyWorldwide.com Confirm Your Email Address",
            array(
                'idAdvertiser'  => $this->_advertiserRow->idAdvertiser,
                'firstname'     => $this->_advertiserRow->firstname,
                'lastname'      => $this->_advertiserRow->lastname,
                'emailAddress'  => $this->_advertiserRow->emailAddress,
                'token'         => $token
            ),
            Vfr_Mail::MODE_ONLY_TXT
        );

        $this->_forward(
            'resend',
            'resend-confirmation',
            'controlpanel'
        );
    }
}
