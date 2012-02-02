<?php
class Vfr_View_Helper_Bytes extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';

    public function bytes($bytes, $precision=1)
    {
        if ($bytes == 0) {
            return '';
        }
        
        $units = array (
            'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB'
        );
        
        $i = floor(log($bytes, 1024));
        
        // for anything smaller than a KB don't use fractional values
        //if ((int) $i == 1) {
        //    $precision = 1;    
        //}
        return round($bytes / pow(1024, $i), $precision) . ' ' . $units[$i];
    }
}
