<?php
class AvailabilityImageController extends Zend_Controller_Action
{
    public function init()
    {
        $this->getHelper('viewRenderer')->setNoRender();
        $this->_helper->layout->disableLayout();
    }

    public function renderAction()
    {
        $idProperty = $this->_getParam('idProperty', null);
        if (!isset($idProperty)) {
            $idProperty = $this->_getParam('idProperties', null);
        }

        if (null === $idProperty) {
            throw new Exception(__METHOD__ . ' Called without passing property id');
        }

        $propertyModel = new Common_Model_Property();
        $idCalendar = $propertyModel->getCalendarIdByPropertyId($idProperty);
        $calendar = $propertyModel->getCalendarById($idCalendar);

        $settings = new Vfr_Availability_Calendar_Object();

        $rates = $this->_getParam('rates', 0);

        //var_dump($calendar);
        //exit;
        $settings->setCalendarDuration($this->_getParam('du', $calendar->vDuration));
        $settings->setStartYear($this->_getParam('yr', $calendar->vStartYear));
        $settings->setStartMonth($this->_getParam('mo', $calendar->vStartMonth));

        // presentation
        $settings->setLanguage($this->_getParam('lg', $calendar->vLanguage));
        $settings->setBookedColour($this->_getParam('bc', $calendar->vBookedColour));
        $settings->setAvailableColour($this->_getParam('ac', $calendar->vAvailableColour));
        $settings->setDayNameColour($this->_getParam('dc', $calendar->vDayNameColour));
        $settings->setMonthTableBorderColour($this->_getParam('tb', $calendar->vTableBorderColour));
        $settings->setColumns($this->_getParam('cc', (($calendar->vColumns === 0) ? 3 : $calendar->vColumns)));
        $settings->setDayCellWidth($this->_getParam('cw', 19));
        $settings->setDayCellHeight($this->_getParam('ch', 19));
        $settings->setHorizontalGap   = $this->_getParam('hg', $calendar->vHorizontalGapPixels);
        $settings->verticalGap     = $this->_getPAram('vg', $calendar->vVerticalGapPixels);


        // Calculate the number of years this calendar spans
        // Example: if we start in December and span 3 months then we'll have spanned
        // a whole year
        $yearSpan = intval(($settings->getDurationMonths() + $settings->getStartMonth() ) / 12);

        $starttime = strtotime($settings->getStartYear() . '-' . $settings->getStartMonth() . '-01 14:00:00');

        // calculate the month the calendar will finish in
        $endMonth = intval(($settings->getDurationMonths() + $settings->getStartMonth() - 1) % 12);
        $endMonth = ($endMonth == 0) ? intval(12) : intval($endMonth);
        $endMonth = (($endMonth < 10) ? '0' . $endMonth : $endMonth);

        $daysInLastMonth = cal_days_in_month(CAL_GREGORIAN, $endMonth, $settings->getStartYear() + $yearSpan);
        $endtime = strtotime($settings->getStartYear() + $yearSpan . '-' . $endMonth . '-' . $daysInLastMonth . ' 14:00:00');

        $availabilityRowset = $propertyModel->getAvailabilityRangeByCalendarId(
            $idCalendar,
            strftime('%Y-%m-%d', $starttime),
            strftime('%Y-%m-%d', $endtime)
        );
        //var_dump($availabilityRowset);
        //exit;
        //var_dump($settings);
        //var_dump(strftime('%Y-%m-%d', $starttime));
        //exit;

        $mysettings = new Vfr_Availability_Calendar_Object();
        $mysettings->setFontCode($this->_getParam('fc', 2))
                   ->setCalendarDuration($this->_getParam('du', $calendar->vDuration))
                   ->setStartYear($this->_getParam('yr', $calendar->vStartYear))
                   ->setStartMonth($this->_getParam('mo', $calendar->vStartMonth))
                   ->setColumns($this->_getParam('cc', ($calendar->vColumns === 0) ? 5 : $calendar->vColumns))
                   ->setMonthMarginLeft($this->_getParam('mml', 4))
                   ->setMonthMarginRight($this->_getParam('mmr', 4))
                   ->setDayCellWidth($this->_getParam('cw', 19))
                   ->setDayCellHeight($this->_getParam('ch', 19))
                   ->setDayCellMarginRight($this->_getParam('dr', 2))
                   ->setMarginTop($this->_getParam('mt', 4))
                   ->setMarginRight($this->_getParam('mr', 4))
                   ->setMarginBottom($this->_getParam('mb', 4))
                   ->setMarginLeft($this->_getParam('ml', 4))
                   ->setWeekStartDay($this->_getParam('wd', Vfr_Availability_Calendar_Object::DAY_MONDAY))
                   ->setColourAvailable('99CCFF')
                   ->setBookedColour($this->_getParam('bc', 'FF6666'));

        $img = new Vfr_Availability_Calendar_ImagePng($mysettings, $starttime, $endtime, $availabilityRowset);
        $img->setStartTimestamp($starttime)
            ->setEndTimestamp($endtime)
            ->setup()
            ->renderCalendarImage();

        $this->getResponse()->setHeader('Content-Type', 'image/png');
        imagepng($img->getGdImage());
        //var_dump($availabilityRowset);
    }
}
