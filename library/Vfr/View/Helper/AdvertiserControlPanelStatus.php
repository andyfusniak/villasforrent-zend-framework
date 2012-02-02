<?php
class Vfr_View_Helper_AdvertiserControlPanelStatus extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';

    public function advertiserControlPanelStatus($row)
    {
        $idProperty  = $row->idProperty;
        $urlName     = $row->urlName;
        $locationUrl = $row->locationUrl;
        $status      = $row->status;
        $approved    = $row->approved;
        
        if ($status == Common_Resource_Property::COMPLETE) {
            if ($approved) {
                $link = '/' . $locationUrl . '/' . $urlName;
                return '<a href="' . $link .'">Live</a>';                
            } else {
                return 'Awaiting Approval ' . $approved;
            }
        }
        
        return '<a href="'
               . $this->view->url( array(
                    'action'      => 'index',
                    'controller'  => 'continue',
                    'module'      => 'controlpanel',
                    'idProperty'  => $idProperty,
                    'digestKey'   => Vfr_DigestKey::generate(array($idProperty))),
                 null, true)
              . '">[continue]</a>';
    }
}
