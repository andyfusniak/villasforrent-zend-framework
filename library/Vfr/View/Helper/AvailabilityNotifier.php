<?php
class Vfr_View_Helper_AvailabilityNotifier extends Zend_View_Helper_Abstract
{
    public function availabilityNotifier($idProperty)
    {
        $sendForApprovalUrl = $this->view->url(array('module' 	  => 'frontend',
                                                     'controller' => 'advertiser-property',
                                                     'action'	  => 'send-for-initial-approval',
                                                     'idProperty' => $idProperty,
                                                     'digestKey'  => Vfr_DigestKey::generate(array($idProperty))), null, true);
                
        return '<div class="complete">After adding of all of your bookings, <a href="' . $sendForApprovalUrl . '">send your property for initial approval</a></div>';
    }
}