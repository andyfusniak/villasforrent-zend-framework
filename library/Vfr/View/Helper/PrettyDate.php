<?php
class Vfr_View_Helper_PrettyDate extends Zend_View_Helper_Abstract
{
    public function prettyDate($mysqlDate)
    {
        $timestamp = strtotime($mysqlDate);
        
        return $this->_datePostFixString(strftime("%d", $timestamp)) . ' ' . strftime("%b %Y", $timestamp);
    }
    
    private function _datePostFixString($day) {
        $day = (int) $day;
        
        $postfix = '';
		switch ($day) {
			case 1:
			case 21:
			case 31:
				$postfix = 'st';
                break;
		
			case 2:
			case 22:
				$postfix = 'nd';
                break;
		
			case 3:
			case 23:
				$postfix = 'rd';
                break;
		
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 24:
			case 25:
			case 26:
			case 27:
			case 28:
			case 29:
			case 30:
				$postfix = 'th';
                break;
		}
        
        return strval($day) . strval($postfix);
    }
}