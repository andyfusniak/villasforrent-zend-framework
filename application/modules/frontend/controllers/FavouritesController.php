<?php
class FavouritesController extends Zend_Controller_Action
{
    const ERR_NOT_A_POST_REQUEST     = 900;
    const ERR_MEMBER_NOT_LOGGED_IN   = 901;
    const ERR_INSUFFICENT_PARAMETERS = 902;
    const ERR_NOT_A_DELETE_REQUEST   = 903;
    const ERR_FAVOURITE_NOT_FOUND    = 904;

    protected $_request;
    protected $_identity = null;
    protected $_idProperty = null;

    public function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $this->_logger = Zend_Registry::get('logger');

        $this->_request = $this->getRequest();

var_dump($this->_request->getParams());
return;

        if (!Vfr_Auth_Member::getInstance()->hasIdentity()) {
            $this->getResponse()->setHttpResponseCode(403); // forbidden

            $responseData = array(
                'error' => self::ERR_MEMBER_NOT_LOGGED_IN,
                'messsage' => 'The member is not logged in.  Could not detect a session'
            );

            echo Zend_Json::encode($responseData);
            return;
        }

        // Common_Resource_Member_Row
        $this->_identity = Vfr_Auth_Member::getInstance()->getIdentity();
        $this->_idProperty = $this->_request->getParam('idProperty', null);

        if (null === $this->_idProperty) {
            // 400 Bad Request
            $this->getResponse()->setHttpResponseCode(400);

            $responseData = array(
                'error'   => self::ERR_INSUFFICENT_PARAMETERS,
                'message' => 'Must pass idProperty as a minial requirement'
            );

            echo Zend_Json::encode($responseData);
            return;
        }
    }

    public function deleteAction()
    {
        $this->_logger->log(__METHOD__ . $this->_request->getParam('idProperty'), Zend_Log::DEBUG);

        if ($this->_request->isDelete()) {
            // look up the favourite in the database
            $memberFavouriteObj = $memberFavouriteMapper->getFavouriteByMemberAndPropertyId(
                $this->_identity->idMember,
                $this->idProperty
            );

            // if we've found it, delete it
            if ($memberFavouriteObj) {
                // 204 No Content (The server successfully processed the request, but is not returning any content)
                $this->getResponse()->setHttpResponseCode(204);
                return;
            } else {
                // 404 Not Found
                $this->getResponse()->setHttpResponseCode(404);

                $responseData = array(
                    'error'   => self::ERR_FAVOURITE_NOT_FOUND,
                    'message' => 'Favourite for member id ' . $this->_identity->idMember . ' for property id ' . $this->_idProperty
                    . ' was not found'
                );

                echo Zend_Json::encode($responseData);
                return;
            }
        } else {
            // 405 Method not allowed
            $this->getResponse()->setHttpResponseCode(405);

            $responseData = array(
                'error'   => self::ERR_NOT_A_DELETE_REQUEST,
                'message' => 'Not an HTTP DELETE request'
            );

            echo Zend_Json::encode($responseData);
            return;
        }
    }

    public function toggleAction()
    {
        if ($this->_request->isPost()) {
            $memberFavouriteMapper = new Frontend_Model_MemberFavouriteMapper();

            $memberFavouriteObj = $memberFavouriteMapper->getFavouriteByMemberAndPropertyId(
                $this->_identity->idMember,
                $this->idProperty
            );

            $this->_logger->log(__METHOD__ . ' here ' . isset($memberFavouriteObj) ? 'yes' : 'no', Zend_Log::DEBUG);

            if (null == $memberFavouriteObj) {
                $memberFavouriteObj = new Frontend_Model_MemberFavourite();
                $memberFavouriteObj->setMemberId($this->_identity->idMember)
                                   ->setPropertyId($this->_idProperty);

                $idMemberFavourite = $memberFavouriteMapper->addToFavourites($memberFavouriteObj);
            } else {
                $idMemberFavourite = $memberFavouriteObj->getMemberFavouriteId();
                $this->_logger->log(__METHOD__ . ' deleting ' . $idMemberFavourite, Zend_Log::DEBUG);
                $memberFavouriteMapper->removeFavouriteByMemberAndPropertyId(
                    $this->_identity->idMember,
                    $this->_idProperty
                );
            }

            // 200 OK
            $this->getResponse()->setHttpResponseCode(200);

            $responseData = array(
                'response'          => 'success',
                'idMemberFavourite' => $idMemberFavourite
            );

            echo Zend_Json::encode($responseData);
            return;
        } else {
            // 405 Method not allowed
            $this->getResponse()->setHttpResponseCode(405);

            $responseData = array(
                'error'   => self::ERR_NOT_A_POST_REQUEST,
                'message' => 'Not an HTTP POST request'
            );

            echo Zend_Json::encode($responseData);
            return;
        }
    }
}
