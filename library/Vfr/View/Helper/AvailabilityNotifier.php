<?php
class Vfr_View_Helper_AvailabilityNotifier extends Zend_View_Helper_Abstract
{
    public function availabilityNotifier($idProperty)
    {
        $sendForApprovalUrl = '/advertiser-property/send-for-initial-approval/idProperty/' . $idProperty;
        
        return '<div class="complete">After adding of all of your bookings, <a href="' . $sendForApprovalUrl . '">send your property for initial approval</a></div>';
    }
}