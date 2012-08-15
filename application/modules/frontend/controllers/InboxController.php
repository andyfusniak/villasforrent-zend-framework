<?php
require_once 'purify/HTMLPurifier.auto.php';

class InboxController extends Zend_Controller_Action
{
    /**
     * @var Common_Resource_Member_Row
     */
    protected $_identity;

    protected $_purifier;

    public function preDispatch()
    {
        $this->_helper->ensureMemberLoggedIn();
    }

    public function init()
    {
        $this->_identity = Vfr_Auth_Member::getInstance()->getIdentity();

        // enable jQuery Core Library
        ZendX_JQuery::enableView($this->view);
        $jquery = $this->view->jQuery();
        $jquery->enable()
               ->uiEnable();

        // setup an HTML purifier
        $config = HTMLPurifier_Config::createDefault();
        $config->set('Core.Encoding', 'UTF-8');
        $config->set('HTML.Doctype', 'XHTML 1.0 Transitional');

        $this->_purifier = new HTMLPurifier($config);

        $this->view->headScript()->appendFile('/js/vfr/frontend/inbox.js');
        $this->view->headLink()->appendStylesheet('/css/vfr/frontend/inbox.css');
    }

    public function indexAction()
    {
        $messageMapper = new Frontend_Model_MessageMapper();
        $threads = $messageMapper->getMessageThreadOverview($this->_identity->idMember);

        $this->view->threads = $threads;
    }

    public function viewArchivedAction()
    {
        $request = $this->getRequest();


    }

    public function viewInboxAction()
    {
        $request = $this->getRequest();

        $idMessageThread = $request->getParam('tid');
        $messageMapper = new Frontend_Model_MessageMapper();
        $messageThreadObj = $messageMapper->getMessageByThreadId($idMessageThread);
        $messageMapper->changeMemberThreadStatus($idMessageThread,
            Common_Resource_MessageThread::STATUS_READ
        );

        $form = new Frontend_Form_Inbox_ReplyForm(array(
            'idMessageThread' => $messageThreadObj->getMessageThreadId()
        ));

        $form->setAction('/inbox/send');
        $this->view->assign(
            array(
                'form'             => $form,
                'messageThreadObj' => $messageThreadObj,
                'purifier'         => $this->_purifier
            )
        );
    }

    public function sendAction()
    {
        $request = $this->getRequest();
        $idMessageThread = $request->getParam('idMessageThread');
        $body = $request->getParam('body');

        $messageObj = new Frontend_Model_Message(
            null,
            Frontend_Model_Message::SENT_BY_MEMBER,
            $body,
            null
        );

        $messageMapper = new Frontend_Model_MessageMapper();
        $messageThreadObj = $messageMapper->sendMessage($idMessageThread, $messageObj);
    }

    public function archiveAction()
    {
        $request = $this->getRequest();
        $idMessageThread = $request->getParam('tid');

        $messageMapper = new Frontend_Model_MessageMapper();
        $messageMapper->moveMemberThread($idMessageThread, Common_Resource_MessageThread::STORE_ARCHIVED);
    }
}
