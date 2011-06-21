<?php
class Vfr_View_Helper_AdvertiserControlPanelStatus extends Zend_View_Helper_Abstract
{
    public function advertiserControlPanelStatus($idProperty, $urlName, $locationUrl, $status, $approved)
    {
        if ($status == 6) {
            if ($approved) {
                $link = '/in/' . $locationUrl . '/' . $urlName;
                return '<a href="' . $link .'">Live</a>';                
            } else {
                return 'Awaiting Approval ' . $approved;
            }
        }
        
        
        return '<a href="'
               . $this->view->url ( array(
                    'action'      => 'index',
                    'controller'  => 'advertiser-continue',
                    'module'      => 'frontend',
                    'idProperty'  => $idProperty
                ))
              . '">[continue]</a>';
    }
}