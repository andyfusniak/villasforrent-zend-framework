<?php
class Controlpanel_ImageManagerController extends Zend_Controller_Action
{
    public function init()
    {
        // ensure the advertiser is logged in
        if (!Vfr_Auth_Advertiser::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoSimple(
                'login',
                'account',
                'controlpanel'
            );
        }

        $this->identity = Vfr_Auth_Advertiser::getInstance()->getIdentity();
    }

    public function preDispatch()
    {
        $this->_helper->ensureSecure();
    }

    public function confirmAction()
    {
        // get the request params
        $request = $this->getRequest();
        $idProperty = $request->getParam('idProperty');
        $idPhoto    = $request->getParam('idPhoto');
        $digestKey  = $request->getParam('digestKey');

        if (! $this->_helper->digestKey->isValid($digestKey, array($idProperty, $idPhoto))) {
            $this->_helper->redirector->gotoSimple(
                'digest-key-fail',
                'account',
                'controlpanel'
            );
        }

        // decide what size to use based on original image aspect
        $photoModel = new Common_Model_Photo();
        $photoRow   = $photoModel->getPhotoByPhotoId($idPhoto);
        $evaluation = $photoModel->getPhotoEvaluation($photoRow->widthPixels, $photoRow->heightPixels);
        switch ($evaluation['aspectString']) {
            case '1:1':
                $size = '400x400';
                break;

            case '4:3':
                if ($evaluation['orientation'] == 'landscape')
                    $size = '400x300';
                else
                    $size = '300x400';
                break;

            case '3:2':
                if ($evaluation['orientation'] == 'landscape')
                    $size = '400x267';
                else
                    $size = '267x400';
                break;

            case '16:9':
                if ($evaluation['orientation'] == 'landscape')
                    $size = '400x225';
                else
                    $size = '225x400';
                break;

            default:
                $size = '400x400';
        }

        $topLevelDir = $photoModel->topLevelDirByPropertyId($photoRow->idProperty);

        //var_dump($topLevelDir);
        //die();

        $fullPath    = DIRECTORY_SEPARATOR . 'photos'
                     . DIRECTORY_SEPARATOR . $topLevelDir
                     . DIRECTORY_SEPARATOR . $photoRow->idProperty
                     . DIRECTORY_SEPARATOR . $idPhoto . '_' . $size . '.jpg';

        $this->view->assign(
            array(
                'image'      => $fullPath,
                'idProperty' => $idProperty,
                'idPhoto'    => $idPhoto,
                'digestKey'  => $digestKey
            )
        );
    }

    public function deleteAction()
    {
        // get the request parameters
        $request = $this->getRequest();
        $idProperty = $request->getParam('idProperty');
        $idPhoto    = $request->getParam('idPhoto');
        $digestKey  = $request->getParam('digestKey');

        if (!$this->_helper->digestKey->isValid($digestKey, array($idProperty, $idPhoto))) {
            $this->_helper->redirector->gotoSimple(
                'digest-key-fail',
                'account',
                'controlpanel'
            );
        }

        $photoModel = new Common_Model_Photo();
        $photoModel->deletePhotoByPhotoId($idProperty, $idPhoto);

        $this->_helper->redirector->gotoSimple(
            'step3-pictures',
            'property',
            'controlpanel',
            array(
                'idProperty' => $idProperty,
                'digestKey'  => Vfr_DigestKey::generate(array($idProperty))
            )
        );
    }

    public function cancelAction()
    {
        // get the request parameters
        $request = $this->getRequest();
        $idProperty = $request->getParam('idProperty');
        $idPhoto    = $request->getParam('idPhoto');
        $digestKey  = $request->getParam('digestKey');

        if (!$this->_helper->digestKey->isValid($digestKey, array($idProperty, $idPhoto))) {
            $this->_helper->redirector->gotoSimple(
                'digest-key-fail',
                'account',
                'controlpanel'
            );
        }

        $this->_helper->redirector->gotoSimple(
            'step3-pictures',
            'property',
            'controlpanel',
            array(
                'idProperty' => $idProperty,
                'digestKey'  => Vfr_DigestKey::generate(array($idProperty))
            )
        );
    }

    public function moveAction()
    {
        // get the request parameters
        $request = $this->getRequest();
        $idProperty     = $request->getParam('idProperty');
        $idPhoto        = $request->getParam('idPhoto');
        $moveDirection  = $request->getParam('moveDirection');
        $digestKey      = $request->getParam('digestKey');

        if (!$this->_helper->digestKey->isValid($digestKey, array($idProperty, $idPhoto, $moveDirection))) {
            $this->_helper->redirector->gotoSimple(
                'digest-key-fail',
                'account',
                'controlpanel'
            );
        }

        if (($moveDirection !== 'up') && ($moveDirection !== 'down')) {
            throw new Exception('Invalid move direction');
        }

        $photoModel = new Common_Model_Photo();
        //$photoModel->fixBrokenDisplayPriorities($idProperty);
        $photoModel->updateMovePosition(
            $idProperty,
            $idPhoto,
            $moveDirection
        );

        $this->_helper->redirector->gotoSimple(
            'step3-pictures',
            'property',
            'controlpanel',
            array(
                'idProperty' => $idProperty,
                'digestKey'  => Vfr_DigestKey::generate(array ($idProperty))
            )
        );
    }
}
