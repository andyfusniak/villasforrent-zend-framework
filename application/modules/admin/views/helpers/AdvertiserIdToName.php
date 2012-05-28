<?php
class Admin_View_Helper_AdvertiserIdToName extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';

    protected $_advertiserModel = null;
    protected $_advertiserLookup = array ();

    public function advertiserIdToName($advertiser)
    {
        // if an array is passed, it is used to preload the view helper
        if (is_array($advertiser)) {
            if (null == $this->_advertiserModel)
                $this->_advertiserModel = new Common_Model_Advertiser();

            $advertiserRowset = $this->_advertiserModel->getAdvertiserList(
                $advertiser
            );

            foreach ($advertiserRowset as $advertiserRow) {
                $idAdvertiser = $advertiserRow->idAdvertiser;
                $contactName  = $advertiserRow->firstname . ' ' . $advertiserRow->lastname;

                $this->_advertiserLookup[$idAdvertiser] = $contactName;
            }

            return '';
        } else if (is_int($advertiser)) {
            // check to see if the advertiser is is cached on the view helper
            if (isset($this->_advertiserLookup[$advertiser])) {
                return $this->view->escape(
                    $this->_advertiserLookup[$advertiser] . ' (' . $advertiser . ')'
                );
            } else {
                // not cached, so use the model to get it
                // and then add it to the cache in case it's referenced again
                if (null == $this->_advertiserModel)
                    $this->_advertiserModel = new Common_Model_Advertiser();

                try {
                    $advertiserRow = $this->_advertiserModel->getAdvertiserById(
                        $advertiser
                    );

                    if ($advertiserRow) {
                        $idAdvertiser = $advertiserRow->idAdvertiser;
                        $contactName  = $advertiserRow->firstname . ' ' . $advertiserRow->lastname;

                        // add to the cache
                        $this->_advertiserLookup[$idAdvertiser] = $contactName;

                        return $this->view->escape(
                            $contactName . ' (' . $idAdvertiser . ')'
                        );
                    } else {
                        return 'bad lookup';
                    }
                } catch (Exception $e) {
                    throw $e;
                }
            }
        }

        return 'bad lookup';
    }
}
