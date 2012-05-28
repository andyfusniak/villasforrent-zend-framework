<?php
class Vfr_View_Helper_MinStayDuration extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';

    public function minStayDuration($d)
    {
        $d = (int) $d;

        switch ($d) {
            case 0:
                return '-';
                break;

            case 1:
                return '1 night';
                break;

            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
                return (string) $d . ' nights';
                break;
            case 7:
                return '1 week';
                break;
            case 14:
                return '2 weeks';
                break;
            case 21:
                return '3 weeks';
                break;
            case 30:
                return '1 month';
                break;
            case 60:
                return '2 months';
                break;
            case 90:
                return '3 months';
                break;
            case 365:
                return '1 year';
                break;
            default:
                return (string) $d . ' nights';
        }
    }
}
