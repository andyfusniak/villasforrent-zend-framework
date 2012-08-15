<?php
class FavouritesController extends Zend_Controller_Action
{
    const ERR_NOT_A_POST_REQUEST   = 900;
    const ERR_MEMBER_NOT_LOGGED_IN = 901;
    const ERR_INSUFFICENT_PARAMETERS = 902;

    public function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $this->_logger = Zend_Registry::get('logger');
    }

    public function toggleAction()
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
        $idProperty = $request->getParam('idProperty', null);

        if (null === $idProperty) {
            $responseData = array(
                'error'   => self::ERR_INSUFFICENT_PARAMETERS,
                'message' => 'Must pass idProperty as a minial requirement'
            );
            echo Zend_Json::encode($responseData);
            return;
        }

        if ($request->isPost()) {
            $memberFavouriteMapper = new Frontend_Model_MemberFavouriteMapper();

            $memberFavouriteObj = $memberFavouriteMapper->getFavouriteByMemberAndPropertyId(
                $identity->idMember,
                $idProperty
            );

            $this->_logger->log(__METHOD__ . ' here ' . isset($memberFavouriteObj) ? 'yes' : 'no', Zend_Log::DEBUG);


            if (null == $memberFavouriteObj) {
                $memberFavouriteObj = new Frontend_Model_MemberFavourite();
                $memberFavouriteObj->setMemberId($identity->idMember)
                                   ->setPropertyId($idProperty);

                $idMemberFavourite = $memberFavouriteMapper->addToFavourites($memberFavouriteObj);
            } else {
                $idMemberFavourite = $memberFavouriteObj->getMemberFavouriteId();
                $this->_logger->log(__METHOD__ . ' deleting ' . $idMemberFavourite, Zend_Log::DEBUG);
                $memberFavouriteMapper->removeFavouriteByMemberAndPropertyId(
                    $identity->idMember,
                    $idProperty
                );
            }

            $responseData = array(
                'response' => 'success',
                'idMemberFavourite' => $idMemberFavourite
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
