<?php
class Controlpanel_RatesController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $this->_helper->ensureSecure();
    }

    public function rentalBasisAction()
    {
        $request = $this->getRequest();
        $idProperty = $request->getParam('idProperty');
        $digestKey  = $request->getParam('digestKey');

        if (!$this->_helper->digestKey->isValid($digestKey, array($idProperty))) {
            $this->_helper->redirector->gotoSimple(
                'digest-key-fail',
                'account',
                'controlpanel'
            );
        }

        $newDigestKey = Vfr_DigestKey::generate(array($idProperty));
        $form = new Controlpanel_Form_Property_Step4RentalBasis(
            array(
                'idProperty' => $idProperty,
                'digestKey'  => $newDigestKey
            )
        );

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $propertyModel = new Common_Model_Property();
                $idCalendar = $propertyModel->getCalendarIdByPropertyId($idProperty);

                $calendarModel = new Common_Model_Calendar();
                $calendarModel->updateRentalBasis(
                    $idCalendar,
                    $form->getValue('rentalBasis')
                );

                $this->_helper->redirector->gotoSimple(
                    'step4-rates',
                    'property',
                    'controlpanel',
                    array(
                        'idProperty' => $idProperty,
                        'digestKey'  => $digestKey
                    )
                );
            }
        } else {
            $propertyModel = new Common_Model_Property();
            $idCalendar = $propertyModel->getCalendarIdByPropertyId($idProperty);

            $data = array();
            $calendarModel = new Common_Model_Calendar();
            $rentalBasis = $calendarModel->getRentalBasis($idCalendar);
            if ($rentalBasis)
                $data['rentalBasis'] = $rentalBasis;
            $form->populate($data);
        }

        $this->view->assign(
            array(
                'form'       => $form,
                'idProperty' => $idProperty
            )
        );
    }

    public function baseCurrencyAction()
    {
        $request = $this->getRequest();
        $idProperty = $request->getParam('idProperty');
        $digestKey  = $request->getParam('digestKey');

        if (!$this->_helper->digestKey->isValid($digestKey, array($idProperty))) {
            $this->_helper->redirector->gotoSimple(
                'digest-key-fail',
                'account',
                'controlpanel'
            );
        }

        $newDigestKey = Vfr_DigestKey::generate(array($idProperty));
        $form = new Controlpanel_Form_Property_Step4BaseCurrency(
            array(
                'idProperty' => $idProperty,
                'digestKey'  => $newDigestKey
            )
        );

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $propertyModel = new Common_Model_Property();
                $idCalendar = $propertyModel->getCalendarIdByPropertyId($idProperty);

                $calendarModel = new Common_Model_Calendar();

                $calendarModel->updateBaseCurrency(
                    $idCalendar,
                    $form->getValue('currencyCode')
                );

                $this->_helper->redirector->gotoSimple(
                    'step4-rates',
                    'property',
                    'controlpanel',
                    array(
                        'idProperty' => $idProperty,
                        'digestKey'  => $newDigestKey
                    )
                );
            }
        } else {
            $propertyModel = new Common_Model_Property();
            $idCalendar = $propertyModel->getCalendarIdByPropertyId($idProperty);

            $data = array();
            $calendarModel = new Common_Model_Calendar($idCalendar);
            $baseCurrency = $calendarModel->getBaseCurrency($idCalendar);
            if ($baseCurrency)
                $data['currencyCode'] = $baseCurrency;

            $form->populate($data);
        }

        $this->view->assign(
            array(
                'form'       => $form,
                'idProperty' => $idProperty
            )
        );
    }

    public function deleteConfirmAction()
    {
        $request = $this->getRequest();
        $idProperty = $request->getParam('idProperty');
        $idRate     = $request->getParam('idRate');
        $digestKey  = $request->getParam('digestKey');

        if (! $this->_helper->digestKey->isValid($digestKey, array($idProperty, $idRate))) {
            $this->_helper->redirector->gotoSimple(
                'digest-key-fail',
                'account',
                'controlpanel'
            );
        }

        $newDigestKey = Vfr_DigestKey::generate(array($idProperty, $idRate));
        $form = new Controlpanel_Form_Property_Step4RateDeleteConfirmForm(
            array(
                'idProperty' => $idProperty,
                'idRate'     => $idRate,
                'digestKey'  => $newDigestKey
            )
        );

        // lookup the rate row
        $calendarModel = new Common_Model_Calendar();
        $rateRow = $calendarModel->getRateByPk($idRate);

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                if ($request->getParam('do') == 'cancel') {
                    $this->_helper->redirector->gotoSimple(
                        'step4-rates',
                        'property',
                        'controlpanel',
                        array(
                            'idProperty' => $idProperty,
                            'digestKey'  => Vfr_DigestKey::generate(array($idProperty))
                        )
                    );
                }

                //$propertyModel = new Common_Model_Property();
                //$idCalendar = $propertyModel->getCalendarIdByPropertyId($idProperty);

                // and delete it
                $rateRow->delete();

                // redirect back to the step4 page
                $this->_helper->redirector->gotoSimple(
                    'step4-rates',
                    'property',
                    'controlpanel',
                    array(
                        'idProperty' => $idProperty,
                        'digestKey'  => Vfr_DigestKey::generate(array($idProperty))
                    )
                );
            }
        }

        $this->view->assign(
            array(
                'form'       => $form,
                'idProperty' => $idProperty,
                'rateRow'    => $rateRow
            )
        );
    }

    public function editAction()
    {
        $request = $this->getRequest();
        $idProperty = $request->getParam('idProperty');
        $idRate     = $request->getParam('idRate');
        $digestKey  = $request->getParam('digestKey');

        //var_dump($idProperty, $idRate, $digestKey);
        //die();
        if (!$this->_helper->digestKey->isValid($digestKey, array($idProperty, $idRate))) {
            $this->_helper->redirector->gotoSimple(
                'digest-key-fail',
                'account',
                'controlpanel'
            );
        }

        $propertyModel = new Common_Model_Property();
        $calendarModel = new Common_Model_Calendar();

        $propertyRow = $propertyModel->getPropertyById($idProperty);
        $idCalendar  = $propertyModel->getCalendarIdByPropertyId($idProperty);
        $ratesRowset = $calendarModel->getRatesByCalendarId($idCalendar);
        $rateRow = $calendarModel->getRateByPk($idRate);

        //var_dump($ratesRowset);
        //die();

        $newDigestKey = Vfr_DigestKey::generate(array($idProperty, $idRate));
        if ($request->isPost()) {
            //var_dump($request->getParams());
            $form = new Controlpanel_Form_Property_Step4RateEditForm(
                array(
                    'idProperty' => $idProperty,
                    'idRate'     => $rateRow->idRate,
                    'name'       => $request->getParam('name'),
                    'rates'      => $request->getParam('rates'),
                    'digestKey'  => $newDigestKey
                )
            );

            if ($form->isValid($request->getPost())) {
                $calendarModel->updateRateByPk($rateRow->idRate, $form->getValues());
                $this->_helper->redirector->gotoSimple(
                    'step4-rates',
                    'property',
                    'controlpanel',
                    array(
                        'idProperty' => $form->getValue('idProperty'),
                        'digestKey'  => Vfr_DigestKey::generate(array($idProperty))
                    )
                );
            }
        } else {
            $form = new Controlpanel_Form_Property_Step4RateEditForm(
                array(
                    'idProperty'         => $idProperty,
                    'name'               => $rateRow->name,
                    'idRate'             => $rateRow->idRate,
                    'rates'              => array ('start' => $rateRow->startDate,
                    'end'                => $rateRow->endDate,
                    'weeklyRate'         => $rateRow->weeklyRate,
                    'weekendNightlyRate' => $rateRow->weekendNightlyRate,
                    'midweekNightlyRate' => $rateRow->midweekNightlyRate,
                    'minStayDays'        => $rateRow->minStayDays),
                    'digestKey'          => $newDigestKey
                )
            );
        }

        //'2012-05-01#2012-05-08#300#340#400#7'

        // find out the rental basis and base currency for this calendar
        $calendarRow = $propertyModel->getCalendarById($idCalendar);

        // Enable jQuery to pickup the headers etc
        ZendX_JQuery::enableForm($form);
        $jquery = $this->view->jQuery();
        $jquery->enable()->uiEnable();
        $this->view->headScript()->appendFile('/js/vfr/step4-rates.js');
        $this->view->assign(
            array(
                'form'          => $form,
                'propertyRow'   => $propertyRow,
                'rentalBasis'   => $calendarRow->rentalBasis,
                'baseCurrency'  => $calendarRow->currencyCode,
                'ratesRowset'   => $ratesRowset
            )
        );
    }
}
