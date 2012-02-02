<?php
class Vfr_View_Helper_RentalBasis extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';
    
    public function rentalBasis($rentalBasis)
    {
        switch ($rentalBasis)
        {
            case 'PR':
                return 'Per Property';
            break;
        
            case 'SN':
                return 'Per Person';
            break;
        
            case 'RM':
                return 'Per Room';
            break;
        
            default:
                return 'Unknown';
        }
    }
}
