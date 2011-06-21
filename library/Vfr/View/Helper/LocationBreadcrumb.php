<?php
class Vfr_View_Helper_LocationBreadcrumb extends Zend_View_Helper_Abstract
{
    public function locationBreadcrumb($fastLookupRow, $options=null)
    {
        if (!$fastLookupRow)
            return '';
        
        // default whitespace is on
        $whitespace = ' ';
        
        if (($options) && (is_array($options))) {
            if (isset($options['whitespace'])) {
                if ($options['whitespace'] == true) {
                    $whitespace = ' ';
                } else {
                    $whitespace = '';
                }
            }
        }
       
        return $this->view->escape($fastLookupRow->countryName) . $whitespace . '-&gt;' . $whitespace
             . $this->view->escape($fastLookupRow->regionName) . $whitespace . '-&gt;' . $whitespace
             . $this->view->escape($fastLookupRow->destinationName);
    }
}