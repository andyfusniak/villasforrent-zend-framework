<?php
class AdvertiserContinueController extends Zend_Controller_Action
{
    protected $_front;

    public function init()
    {
        // Disable the ViewRenderer helper:
        $this->_front = Zend_Controller_Front::getInstance();
        $this->_front->setParam('noViewRenderer', true);

        $this->_redirector = $this->_helper->getHelper('Redirector');
    }

    public function indexAction()
    {
        $idProperty = $this->getRequest()->getParam('idProperty');
        $digestKey  = $this->getRequest()->getParam('digestKey');
        
        //var_dump($idProperty, $digestKey);
        //die();
		if (! $this->_helper->digestKey->isValid($digestKey, array($idProperty))) {
			$this->_helper->redirector->gotoSimple('digest-key-fail', 'advertiser-account', 'frontend');
		}
        
        $propertyModel = new Common_Model_Property();
        $status = $propertyModel->getStatusByPropertyId($idProperty);
        
        switch ($status) {
            case Common_Resource_Property::STEP_1_LOCATION:
                $this->_redirector->gotoSimple('step1-location', 'advertiser-property', 'frontend',
                                               array('idProperty' => $idProperty,
                                                     'digestKey'  => $digestKey));
            break;

            case Common_Resource_Property::STEP_2_CONTENT:
                $mode = 'add';
                $digestKey = Vfr_DigestKey::generate(array($idProperty, $mode));
                $this->_redirector->gotoSimple('step2-content', 'advertiser-property', 'frontend',
                                               array('idProperty' => $idProperty,
                                                     'digestKey'  => $digestKey));
            break;

            case Common_Resource_Property::STEP_3_PICTURES:
                $this->_redirector->gotoSimple('step3-pictures', 'advertiser-property', 'frontend',
                                               array('idProperty' => $idProperty,
                                                     'digestKey'  => $digestKey));
            break;

            case Common_Resource_Property::STEP_4_RATES:
                $this->_redirector->gotoSimple('step4-rates', 'advertiser-property', 'frontend',
                                               array('idProperty' => $idProperty,
                                                     'digestKey'  => $digestKey));
            break;

            case Common_Resource_Property::STEP_5_AVAILABILITY:
                $this->_redirector->gotoSimple('step5-availability', 'advertiser-property',
                                               'frontend', array('idProperty' => $idProperty,
                                                                 'digestKey'  => $digestKey));
            break;

            case Common_Resource_Property::COMPLETE:
                $this->_redirector->gotoSimple('home', 'advertiser-account', 'frontend');

            default:
                $this->_redirector->gotoSimple('home', 'advertiser-account', 'frontend');
        }
    }
}
