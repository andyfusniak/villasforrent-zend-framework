<?php
class Vfr_View_Helper_RatesNotifier extends Zend_View_Helper_Abstract
{
    public function ratesNotifier($idProperty)
    {
		$step5url = $this->view->url(array('module' 	=> 'frontend',
										   'controller' => 'advertiser-property',
										   'action'	    => 'progress-step5',
										   'idProperty' => $idProperty,
										   'digestKey'  => Vfr_DigestKey::generate(array($idProperty))), null, true);
		
        return '<div class="complete">After adding all of your property rates <a href="' . $step5url . '">click here to continue to step 5.</a></div>';
    }
}

