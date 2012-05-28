<?php
class Controlpanel_AccountSettingsController extends Zend_Controller_Action
{
    protected $_advertiserRow;

    public function init()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            if (null == $this->_advertiserRow)
                $this->_advertiserRow = Zend_Auth::getInstance()->getIdentity();
        }
    }

    public function preDispatch()
    {
        $this->_helper->ensureLoggedIn();
        $this->_helper->ensureSecure();
        $this->_helper->ensureAccountEmailConfirmed();
    }

    public function listAction()
    {
        $form = new Controlpanel_Form_ChangeEmailAddress();
        $form->setAction('/controlpanel/account-settings/list');

        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $advertiserModel = new Common_Model_Advertiser();

                // the change request will update the advertiser table
                // and insert a token for confirmation
                $token = $advertiserModel->addEmailAccountChangeRequest(
                    $this->_advertiserRow->idAdvertiser,
                    $form->getValue('emailAddress')
                );

                // send the template
                $vfrMail = new Vfr_Mail(
                    '/modules/controlpanel/views/emails',
                    'email-address-change-request'
                );

                $vfrMail->send(
                    $form->getValue('emailAddress'),
                    "HolidayPropertyWorldwide.com Confirm New Email",
                    array(
                        'firstname' => $this->_advertiserRow->firstname,
                        'token'     => $token
                    ),
                    Vfr_Mail::MODE_ONLY_TXT
                );

                $changeSuccessXhtml = '<div class="successbox">A confirmaton request email has been sent, please check your inbox '
                                    . $form->getValue('emailAddress')  . '</div>';

                // clear the form (last)
                $form->clearElements();
            } else {
                $form->populate($request->getPost());
            }
        }

        $this->view->assign(
            array(
                'form'               => $form,
                'advertiserRow'      => $this->_advertiserRow,
                'changeSuccessXhtml' => isset($changeSuccessXhtml) ? $changeSuccessXhtml : ''
            )
        );
    }
}
