<?php
class SignupController extends Zend_Controller_Action
{
    /**
     * @var Common_Model_Member
     */
    protected $_memberModel = null;

    public function init()
    {
        if (null === $this->_memberModel) {
            $this->_memberModel = new Common_Model_Member();
        }
    }

    public function joinAction()
    {
        $form = new Frontend_Form_Member_RegistrationForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $token = $this->_memberModel->addMember(
                    $form->getValue('emailAddress'),
                    $form->getValue('passwd'),
                    $form->getValue('firstname')
                );

                // get the new member row
                $identity = $this->_memberModel->getMemberByEmail(
                    $form->getValue('emailAddress')
                );

                $vfrMail = new Vfr_Mail(
                    '/modules/frontend/views/emails',
                    'member-signup-confirm-email' // no extensions required
                );

                $vfrMail->send(
                    $identity->emailAddress,
                    "HolidayPropertyWorldwide.com Confirm Your Email Address",
                    array(
                        'firstname' => $identity->firstname,
                        'token'     => $token
                    ),
                    Vfr_Mail::MODE_ONLY_TXT
                );
            } else {
                $form->populate($request->getPost());
            }
        }

        $this->view->assign(
            array(
                'form' => $form
            )
        );
    }
}
