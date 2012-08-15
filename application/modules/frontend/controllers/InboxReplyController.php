<?php
class InboxReplyController extends Zend_Controller_Action
{
    const ERR_NOT_A_POST_REQUEST   = 900;
    const ERR_MEMBER_NOT_LOGGED_IN = 901;
    const ERR_INSUFFICENT_PARAMETERS = 902;

    public function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
    }

    public function postMessageAction()
    {
        $this->getResponse()->setHttpResponseCode(200);

        // the member must be logged in to add a property to their favourites
        if (!Vfr_Auth_Member::getInstance()->hasIdentity()) {
            $responseData = array(
                'error' => self::ERR_MEMBER_NOT_LOGGED_IN,
                'messsage' => 'The member is not logged in.  Could not detect a session'
            );
            echo Zend_Json::encode($responseData);
            return;
        }

        // Common_Resource_Member_Row
        $identity = Vfr_Auth_Member::getInstance()->getIdentity();

        $request = $this->getRequest();
        $idMessageThread = $request->getParam('idMessageThread', null);
        $body = $request->getParam('body', null);

        if ((null === $body) || (null === $idMessageThread)) {
            $responseData = array(
                'error'   => self::ERR_INSUFFICENT_PARAMETERS,
                'message' => 'Must pass a message-thread id and message body as a minial requirement'
            );
            echo Zend_Json::encode($responseData);
            return;
        }

        if ($request->isPost()) {
            $messageMapper = new Frontend_Model_MessageMapper();

            $messageObj = new Frontend_Model_Message(null,
                'MEMBER',
                $request->getParam('body'),
                null
            );

            // write the message to the database filtering the body and creating a sentDate
            $idMessage = $messageMapper->sendMessage($idMessageThread, $messageObj);

            $messageObj = $messageMapper->getMessageByMessageId($idMessage);

            $responseData = array(
                'response'  => 'success',
                'idMessage' => $messageObj->getMessageId(),
                'body'      => $messageObj->getBody(), // purified
                'added'     => $this->view->prettyDate(
                    $messageObj->getAdded(),
                    Vfr_View_Helper_PrettyDate::STYLE_DD_MMM_YY_HH_MM_AMPM
                ),
                'image' => '<img src="https://secure.gravatar.com/avatar/02b5939ce50939e58dac8090b9aa5236?s=80&d=wavatar&r=pg">'
                //'image' => '<span style="color: red;">image</span>'
            );
        } else {
            $responseData = array(
                'error' => self::ERR_NOT_A_POST_REQUEST,
                'message' => 'Not an HTTP Post request'
            );
        }

        echo Zend_Json::encode($responseData);
    }
}