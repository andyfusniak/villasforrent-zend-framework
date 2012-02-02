<?php
class Vfr_View_Helper_LocationBreadcrumb extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';

    const TOTALS_NONE            = 1;
    const TOTALS_ALL_NODES       = 2;
    const TOTALS_LEAF_NODES_ONLY = 3;
 
    const DEFAULT_WHITESPACE = ' ';
    const DEFAULT_DELIMITER  = ':';
    const DEFAULT_TOTAL_MODE = self::TOTALS_NONE;
    const DEFAULT_MAKE_LINKS = true;
     
    private $_locationModel = null;
    
    public function locationBreadcrumb($idLocation, $options=null)
    {
        // default whitespace is on
        $whitespace = ' ';
        
        if (($options) && (is_array($options))) {
            // whitespace (default = ' ')
            if (isset($options['whitespace'])) {
                if ($options['whitespace'] == false)
                    $whitespace = '';
                else
                    $whitespace = self::DEFAULT_WHITESPACE;
            }
            
            // delimiter (default = ':')
            if (isset($options['delimiter']))
                $delimiter = $options['delimiter'];
            else
                $delimiter = self::DEFAULT_DELIMITER;
                
            // show totals mode
            if (isset($options['totalsMode']))
                $totalsMode = $options['totalsMode'];
            else
                $totalsMode = self::DEFAULT_TOTAL_MODE;
                
            // use href links (true or false)
            if (isset($options['makeLinks']))
                $makeLinks = $options['makeLinks'];
            else
                $makeLinks = self::DEFAULT_MAKE_LINKS;
            
        } else {
            $whitespace = self::DEFAULT_WHITESPACE;
            $delimiter  = self::DEFAULT_DELIMITER;
            $totalsMode = self::DEFAULT_TOTAL_MODE;
            $makeLinks  = self::DEFAULT_MAKE_LINKS;
        }
        
        // if the helper is called many times on the page
        // we only create a single locationModel instance
        if ($this->_locationModel == null)
            $this->_locationModel = new Common_Model_Location();
        
        //var_dump($locationRow);
        
        $locationRowset = $this->_locationModel->getPathFromRootNode($idLocation);
        
        $xhtml = '';
        $size = count($locationRowset);
        
        for ($i=0; $i < $size; $i++) {
            $locationRow = $locationRowset[$i];
            
            $name = $this->view->escape($locationRow->rowname);
            
            // if we want totals on all nodes
            if ($totalsMode == self::TOTALS_ALL_NODES)
                $name .= ' (' . $locationRow->totalVisible. ')';
            elseif (($totalsMode == self::TOTALS_LEAF_NODES_ONLY) && ($i == $size-1)) {
                $name .= ' (' . $locationRow->totalVisible. ')';
            }
            
            if ($makeLinks)
                $xhtml .= '<a class="breadcrumb" href="/' . $locationRow->url . '">' . $name . '</a>';
            else
                $xhtml .= $name;
                
            // if this isn't the last item
            if ($i < $size-1) {
                $xhtml .= $whitespace . $delimiter . $whitespace;
            }
        }
        
        //var_dump($xhtml);
        return $xhtml;
    }
}
