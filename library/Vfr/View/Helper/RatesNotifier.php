<?php
class Vfr_View_Helper_RatesNotifier extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';

    public function ratesNotifier($propertyRow)
    {
        // if this property has already been initially approved
        // no need to provide work-flow
        if ($propertyRow->approved)
            return '';

        $step5url = $this->view->url(
            array(
                'module'     => 'controlpanel',
                'controller' => 'property',
                'action'     => 'progress-step5',
                'idProperty' => $propertyRow->idProperty,
                'digestKey'  => Vfr_DigestKey::generate(array($propertyRow->idProperty))
            ),
            null,
            true
        );

        return '<div class="complete">After adding all of your property rates <a href="' . $step5url . '">click here to continue to step 5.</a></div>';
    }
}
