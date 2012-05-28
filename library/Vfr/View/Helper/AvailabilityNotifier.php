<?php
class Vfr_View_Helper_AvailabilityNotifier extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';

    public function availabilityNotifier($propertyRow)
    {
        // if this property has already been initially approved
        // no need to provide work-flow
        if ($propertyRow->approved)
            return '';

        $sendForApprovalUrl = $this->view->url(
            array(
                'module'     => 'controlpanel',
                'controller' => 'property',
                'action'     => 'send-for-initial-approval',
                'idProperty' => $propertyRow->idProperty,
                'digestKey'  => Vfr_DigestKey::generate(array($propertyRow->idProperty))
            ),
            null,
            true
        );

        return '<div class="complete">After adding of all of your bookings, <a href="' . $sendForApprovalUrl . '">send your property for initial approval</a></div>';
    }
}
