<?php
class Common_Model_System extends Vfr_Model_Abstract
{
    
    public function getAllInformationSchemaTriggers()
    {
        return $this->getResource('Trigger')->getAll();
    }
    
    public function getInformationSchemaTriggerByName($name)
    {
        $triggerResource = $this->getResource('Trigger');
        
        return $triggerResource->getByName($name);
    }
}
