<?php
class Admin_FeaturedPropertyController extends Zend_Controller_Action
{
    const ERR_NOT_A_POST_REQUEST     = 900;
    const ERR_PROPERTY_NOTFOUND      = 901;
    const ERR_INSUFFICENT_PARAMETERS = 902;

    public function init()
    {
        $request = $this->getRequest();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
    }

    public function lookupAction()
    {
        $request = $this->getRequest();
        $idProperty = $request->getParam('idProperty', null);

        // make sure the property has been passed
        if (null === $idProperty) {
            // 400 Bad Request
            $this->getResponse()->setHttpResponseCode(400);

            $response = array(
                'error'   => self::ERR_INSUFFICENT_PARAMETERS,
                'message' => 'You must pass the property id as idProperty'
            );

            echo Zend_Json::encode($response);
            return;
        }

        if ($request->isGet()) {
            $propertyModel = new Common_Model_Property();
            $propertyRow = $propertyModel->getPropertyById($idProperty);

            // return a response if the property cant be found
            if (null === $propertyRow) {
                $this->getResponse()->setHttpResponseCode(404);

                $response = array(
                    'error' => self::ERR_PROPERTY_NOTFOUND,
                    'message' => 'Property id ' . $idProperty . ' not found'
                );

                echo Zend_Json::encode($response);
                return;
            }

            $response = array(
                'response'         => 'success',
                'idProperty'       => $propertyRow->idProperty,
                'idPropertyType'   => $propertyRow->idPropertyType,
                'idAdvertiser'     => $propertyRow->idAdvertiser,
                'idHolidayType'    => $propertyRow->idHolidayType,
                'idLocation'       => $propertyRow->idLocation,
                'urlName'          => $propertyRow->urlName,
                'shortName'        => $propertyRow->shortName,
                'locationUrl'      => $propertyRow->locationUrl,
                'numBeds'          => $propertyRow->numBeds,
                'numSleeps'        => $propertyRow->numSleeps,
                'approved'         => $propertyRow->approved,
                'visible'          => $propertyRow->visible,
                'expiry'           => $propertyRow->expiry,
                'emailAddress'     => $propertyRow->emailAddress,
                'photoLimit'       => $propertyRow->photoLimit,
                'added'            => $propertyRow->added,
                'updated'          => $propertyRow->updated,
                'awaitingApproval' => $propertyRow->awaitingApproval,
                'changesMade'      => $propertyRow->changesMade,
                'status'           => $propertyRow->status,
                'checksumMaster'   => $propertyRow->checksumMaster,
                'checksumUpdate'   => $propertyRow->checksumUpdate,
                'lastModifiedBy'   => $propertyRow->lastModifiedBy
            );

            sleep(3);
            echo Zend_Json::encode($response);
            return;
        } else {
            $response = array(
                'error' => self::ERR_NOT_A_POST_REQUEST,
                'message' => 'Not an HTTP Post request'
            );

            echo Zend_Json::encode($response);
            return;
        }
    }
}
