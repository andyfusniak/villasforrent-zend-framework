<?php
class Vfr_View_Helper_ImageUploadNotifier extends Zend_View_Helper_Abstract
{
    public function imageUploadNotifier($idProperty, $count)
    {
        // get the destination from the configuration
		$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
		$photoConfig = $bootstrap['vfr']['photo'];
        
		$step4url = $this->view->url(array('module' 	=> 'frontend',
										   'controller' => 'advertiser-property',
										   'action'	    => 'progress-step4',
										   'idProperty' => $idProperty,
										   'digestKey'  => Vfr_DigestKey::generate(array($idProperty))), null, true);
		
        if ($count < $photoConfig['min_limit_per_property']) {
            return '<div class="below_min">You must upload at least ' . $photoConfig['min_limit_per_property'] . ' photos to continue to step 4</div>';    
        } elseif (($count >= $photoConfig['min_limit_per_property']) && ($count < $photoConfig['max_limit_per_property'])) {
            return '<div class="in_range">You have uploaded the required number of photos.  You can continue to upload more photos to the limit of '
                  . $photoConfig['max_limit_per_property'] . ' photos, or <a href="' . $step4url . '">click here to continue to step 4.</a></div>';  
        } elseif ($count >= $photoConfig['min_limit_per_property']) {
            return '<div class="in_range">You have reached the maximum number of photo uploads.  If you require more photos, you should contact us directly. <a href="' . $step4url . '">Click here to contine to move to step 4.</a></div>';
        }
    }
}