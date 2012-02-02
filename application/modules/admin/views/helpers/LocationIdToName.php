<?php
class Admin_View_Helper_LocationIdToName extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';
    
    protected $_locationModel = null;
    protected $_locationLookup = array ();
    
    public function LocationIdToName($location)
    {
        if (null == $location)
            return;
            
        // if an array is passed, it is used to preload the view helper
        if (is_array($location)) {
            if (null == $this->_locationModel)
                $this->_locationModel = new Common_Model_Location();
                
            $locationRowset = $this->_locationModel->getLocationList(
                $location
            );
            
            foreach ($locationRowset as $locationRow) {
                $idLocation = $locationRow->idLocation;
                $name       = $locationRow->name;
                
                $this->_locationLookup[$idLocation] = $name;
            }
            
            return '';
        } else if (is_int($location)) {
            // check to see if the location is cached in the view helper
            if (isset($this->_locationLookup[$location])) {
                return $this->view->escape(
                    $this->_locationLookup[$location]  
                );
            } else {
                // not cached, so use the model to get it
                // and then add it to the cache in case it's referenced again
                if (null == $this->_locationModel)
                    $this->_locationModel = new Common_Model_Location();
                    
                try {
                    $locationRow = $this->_locationModel->getLocationByPk(
                        $location  
                    );
                    
                    if ($locationRow) {
                        $idLocation = $locationRow->idLocation;
                        $name       = $locationRow->name;
                        
                        // add to the cache
                        $this->_locationLookup[$idLocation] = $name;
                        
                        return $this->view->escape(
                            $name  
                        );
                    } else {
                        return 'bad loookup';
                    }
                } catch (Exception $e) {
                    throw $e;
                }
            }
        }
        
        return 'bad lookup';
    }
}