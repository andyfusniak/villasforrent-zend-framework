<?php
class Admin_View_Helper_FeaturedStatusClass extends Zend_View_Helper_Abstract
{
    public function featuredStatusClass($startDate, $expiryDate)
    {
        $startTime  = strtotime($startDate . ' 00:00:00');
        $expiryTime = strtotime($expiryDate . ' 23:59:59');
        $nowTime    = time();

        if (($nowTime >= $startTime) && ($nowTime <= $expiryTime)) {
            return 'inprogress';
        } else if ($nowTime < $startTime) {
            return 'pending';
        } else if ($nowTime > $expiryTime) {
            return 'expired';
        }

        // should never happen
        return '';
    }
}
