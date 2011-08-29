<?php
require_once 'Zend/Registry.php';

/** Internally used classes */
require_once 'Vfr/Availability/Calendar/Exception.php';
require_once 'Vfr/Availability/Calendar/Object.php';

class Vfr_Availability_Calendar_ImagePng
{   
	const RENDER_NORMAL = 1;
	const RENDER_MIDDLE = 2;
	const RENDER_END = 3;
	const RENDER_START = 4;
    const RENDER_END_START = 5;
	
	private $_logger=null;
	
	private $_settings=null;
	private $_availability=null;
	
	
	// unix timestap
	private $_timestamp=null;
	
	// unix timestamp
	private $_startTimestamp=null;
	private $_endTimestamp=null;

	private $_stateData=null;
	private $_idx=null;
	private $_idxLimit=null;
	
	private $_palette=null;
	private $_gdImg=null;
	
    private $_x=0;
    private $_y=0;
   
   	private $_calendarRows=null; 
    private $_tableWidth=null;
    private $_tableHeight=null;
    
    private $_dayNamesShort=null;
	
	public function __construct(Vfr_Availability_Calendar_Object $settings, $starttime, $endtime,
								Common_Resource_Availability_Rowset $availability)
	{
		$this->_logger = Zend_Registry::get('logger');
		//$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		
		if (!($settings instanceof Vfr_Availability_Calendar_Object))
			throw new Vfr_Availability_Calendar_Exception('Wrong parameter type for settings');
			
		if (!($availability instanceof Common_Resource_Availability_Rowset))
			throw new Vfr_Availability_Calendar_Exception('Wrong parameter type for availability');
		
		$this->_settings 	 = $settings;
		$this->_availability = $availability;
		
		$this->_startTimestamp = $starttime;
		$this->_endTimestamp   = $endtime;

		//exit;
		//$this->_setup(); // now a public function
        
        
        $this->_tableWidth = $this->_settings->getMonthMarginLeft()
						   + (7 * $this->_settings->getDayCellWidth())
						   + (6 * ($this->_settings->getDayCellMarginRight()))
						   + $this->_settings->getMonthMarginRight();
		
        $this->_tableHeight = $this->_settings->getMonthMarginTop()
							+ $this->_settings->getTitleHeight()
							+ $this->_settings->getNameCellMarginTop()
							+ $this->_settings->getDayCellHeight()
							+ $this->_settings->getNameCellMarginBottom()
							+ (5 * $this->_settings->getDayCellHeight())
							+ (5 * ($this->_settings->getDayCellMarginTop()))
							+ $this->_settings->getMonthMarginBottom();
		//var_dump($this->_settings);exit;
		
        // how many rows will there be in the calendar
		$this->_calendarRows = ceil($this->_settings->getDurationMonths() / $this->_settings->getColumns());
		//$this->_logger->log(__METHOD__ . ' There will be ' . $this->_calendarRows . ' calendar rows', Zend_Log::DEBUG);
       
	   	if ($this->_settings->getDurationMonths() < $this->_settings->getColumns()) {
			$this->_settings->setColumns($this->_settings->getDurationMonths());
		}
	    
        $this->_idx = 0;
        //$this->_logger->log(__METHOD__ . ' Initialising idx to ' . $this->_idx, Zend_Log::DEBUG);
		//$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
	}
    
	private function _renderStateAsString($state)
    {
        switch ($state) {
            case self::RENDER_NORMAL:
                return 'RENDER_NORMAL';
            break;
            
            case self::RENDER_MIDDLE;
                return 'RENDER_MIDDLE';
            break;
            
            case self::RENDER_END;
                return 'RENDER_END';
            break;
            
            case self::RENDER_START:
                return 'RENDER_START';
            break;
        
            case self::RENDER_END_START:
                return 'RENDER_END_START';
            break;
        
            default:
                return 'Unknown';
        }
    }
	
	public function setup()
	{
		//$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
        
        $this->_createStateData();
        
        //$this->_logger->log(__METHOD__ . ' Stata Data', Zend_Log::DEBUG);
        //foreach ($this->_stateData as $row) {
            //$this->_logger->log(__METHOD__ . ' Date change ' . strftime("%Y-%m-%d", $row) . ' (' . strtotime($row) . ')', Zend_Log::DEBUG);
        //}
		
        $this->_createDayNames();
        
		//$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
		return $this;
	}

	private function _dumpStateData()
	{
		foreach ($this->_stateData as $item) {
			echo "Date " . strftime("%Y-%m-%d", $item[0]) . ' ' . $this->_renderStateAsString($item[1]) . '<br />';
		}
	}

	private function _createStateData()
    {
        //$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);

        $this->_stateData = array();

		$size = sizeof($this->_availability);
		if ($size > 0) {
			$row = $this->_availability[0];
			$firstTimestamp = strtotime($row->startDate . ' 14:00:00');

			//var_dump($firstTimestamp);
			//var_dump($this->_startTimestamp)

			if ($firstTimestamp <= $this->_startTimestamp) {
				//var_dump("before");
				//exit;
				$this->_currentRenderState   = self::RENDER_MIDDLE;
			} else {
				$this->_stateData[] = array($firstTimestamp, self::RENDER_MIDDLE);
				$this->_currentRenderState   = self::RENDER_NORMAL;
			}

            //$this->_stateData[] = $row->startDate . ' 14:00:00';
            //$this->_stateData[] = $row->startDate . ' 14:00:00';
			//$this->_stateData[] = strtotime($row->endDate . ' 14:00:00');
			//$this->_stateData[] = $row->endDate . ' 14:00:00';
			//var_dump($this->_stateData
			$last = $row->endDate;
		
			for ($i=1; $i < $size; $i++) {
				$row = $this->_availability[$i];
	
				if ($row->startDate !== $last) {
					$this->_stateData[] = array(strtotime($last . ' 14:00:00'), self::RENDER_NORMAL);
					//$this->_stateData[] = $last . ' 14:00:00';
					$this->_stateData[] = array(strtotime($row->startDate . ' 14:00:00'), self::RENDER_MIDDLE);
					//$this->_stateData[] = $row->startDate . ' 14:00:00';
				}
	
				$last = $row->endDate;
			}
	
			$this->_stateData[] = array(strtotime($last . ' 14:00:00'), self::RENDER_NORMAL);
			//$this->_stateData[] = $last . ' 14:00:00';
	
			if (($this->_stateData[0][1] == self::RENDER_NORMAL) && ($this->_stateData[0][0] === $this->_startTimestamp)) {
				//var_dump("CHOP");
				$this->_currentRenderState = self::RENDER_NORMAL;
				$this->_idx++;
			}
			
			$this->_idxLimit = sizeof($this->_stateData);
		} else {
			$this->_currentRenderState = self::RENDER_NORMAL;
			$this->_idxLimit = 0;
		}
		
		
		//var_dump($this->_idxLimit);
		//var_dump($this->_stateData);exit;

		//$this->_dumpStateData();exit;

        //$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
    }
	
    private function _createDayNames()
    {
        //$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
        
		//$this->_logger->log(__METHOD__ . ' PHP Locale is ' . date_default_timezone_get(), Zend_Log::DEBUG);
        //$this->_logger->log(__METHOD__ . ' Language setting ' . $this->_settings->language, Zend_Log::DEBUG);
		// the purpose of doing this is the get the day names using the locale
		// for calendars that might be rendered in other languages
        // 2nd Jan 2000 is a Sunday
		$this->_dayNamesShort = array(substr(strftime("%a", strtotime("2000-01-02")), 0, 1),
									  substr(strftime("%a", strtotime("2000-01-03")), 0, 1),
									  substr(strftime("%a", strtotime("2000-01-04")), 0, 1),
									  substr(strftime("%a", strtotime("2000-01-05")), 0, 1),
									  substr(strftime("%a", strtotime("2000-01-06")), 0, 1),
									  substr(strftime("%a", strtotime("2000-01-07")), 0, 1),
									  substr(strftime("%a", strtotime("2000-01-08")), 0, 1));
        
        //$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
    }
    
	public function setStartTimestamp($unixtime)
	{
		//$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		//$this->_startTimestamp = $unixtime;
	
		//$this->_logger->log(__METHOD__ . ' Set startTimestamp to ' .
							//date('Y-m-d H:i:s', $this->_startTimestamp), Zend_Log::DEBUG);
		//$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
		return $this;
	}
	
	public function getStartTimestamp()
	{
		return $this->_startTimestamp;
	}
	
	public function setEndTimestamp($unixtime)
	{
		//$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		$this->_endTimestamp = $unixtime;
		
		//$this->_logger->log(__METHOD__ . ' Set endTimestamp to ' .
							//date('Y-m-d H:i:s', $this->_endTimestamp), Zend_Log::DEBUG);
		
		//$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
		return $this;
	}
	
	public function getEndTimestamp()
	{
		return $this->_endTimestamp;
	}


	/*
	private function _mergeAdjacentDateRanges()
	{
		$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		$finished = false;
		$i =0;
		
        $listsize = sizeof($this->_stateData);
        
		if ($listsize < 2) {
			$this->_logger->log(__METHOD__ . ' Count is less than 2', Zend_Log::INFO);
			$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
			return;
		}
		
		$size = $listsize - 2;
		while (!$finished) {
			$current = $this->_stateData[$i];
			$next    = $this->_stateData[$i+1];
			
			if ($current['endDate'] === $next['startDate']) {
				$this->_stateData[$i]['endDate'] = $next['endDate'];
                array_splice($this->_stateData, $i+1, 1);
				$i--;
				$size--;
			}
            
			if ($i >= $size) {
				$finished = true;
			} else {
				$i++;
			}
		}
        
		$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
	}
	*/
	
	private function _createImageBlankCanvas($extraYPixels)
	{
		//$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		//var_dump($this->_settings);
		
		$width = ($this->_tableWidth * $this->_settings->getColumns())		
			   + (($this->_settings->getMarginRight()) * ($this->_settings->getColumns() - 1) );
																		 // ;
		//$width += (($this->_settings->monthColumns - 1) * ($this->_settings->horizontalGap));
		//var_dump($width);

        $height = ($this->_calendarRows * $this->_tableHeight)
				+ ($this->_calendarRows-1) * ($this->_settings->getMarginBottom())
				+ $extraYPixels;

		$this->_gdImg = imagecreate($width, $height);
		$backgroundColor = imageColorAllocate($this->_gdImg, 255, 255, 255);
		//$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
	}
	
	private function _increaseCursor($d)
	{
		//$this->_logger->log(__METHOD__ . ' Cursor is ' .  date('Y-m-d H:i:s', $this->_timestamp) . ' (' . $this->_timestamp . ')', Zend_Log::DEBUG);
		//$this->_timestamp += ($d * 86400);
		$this->_timestamp = strtotime("+1 days", $this->_timestamp);
		//$this->_logger->log(__METHOD__ . ' Cursor changed to ' .  date('Y-m-d H:i:s', $this->_timestamp) . '(' . $this->_timestamp . ')', Zend_Log::DEBUG);
	}
	
	//private function _initCalendar()
	//{
		//$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		//$this->_increaseCalendarCursor(-1);
		//var_dump($this->_startCalendarDate->toString('YYYY-MM-dd'));
		//var_dump($this->_endCalendarDate->toString('YYYY-MM-dd'));

		//if (sizeof($this->_stateData) > 0) {
		//	$first = $this->_stateData[0];
		//	var_dump($first);
		//}

		//if ($first < $this->_startTimestamp) {
		//	var_dump($first);
		//	exit;
		//}


	/*	
		if ($this->_startCalendarDate === $this->_dateCursor) {
			$this->_logger->log(__METHOD__ . ' Date EQUALS', Zend_Log::DEBUG);
            
			$this->_currentRenderState = self::RENDER_START;
            $this->_logger->log(__METHOD__ . ' Render state set to ' . $this->_renderStateAsString(self::RENDER_START), Zend_Log::DEBUG);
            
            $this->_idx++;
            $this->_logger->log(__METHOD__ . ' Line ' . __LINE__ . ' Idx advanced to ' . $this->_idx, Zend_Log::DEBUG);
		} elseif (($this->_startCalendarDate < $this->_dateCursor) && ($this->_endCalendarDate > $this->_dateCursor)) {
			$this->_currentRenderState = self::RENDER_MIDDLE;
            $this->_logger->log(__METHOD__ . ' Render state set to ' . $this->_renderStateAsString(self::RENDER_MIDDLE), Zend_Log::DEBUG);
		
            $this->_idx++;
            $this->_logger->log(__METHOD__ . ' Line ' . __LINE__ . ' Idx advanced to ' . $this->_idx, Zend_Log::DEBUG);
        }
	*/	
		//$this->_increaseCalendarCursor(1);
		//$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
	//}
	
	private function _updateState()
	{
        //$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		if ($this->_idx === $this->_idxLimit) {
        	//$this->_logger->log(__METHOD__ . ' Idx hit limit, no need to check for more calendar state changes', Zend_Log::INFO);
			$this->_increaseCursor(1);
        	//$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
			return;
		}
       	
		list($nextTimestamp, $newstate) = $this->_stateData[$this->_idx];
		 
		// to see what to set the state to. we need to look ahead to the next day
		$this->_increaseCursor(1);
		//var_dump($this->_timestamp, $nextTimestamp);

		//if ($this->_currentRenderState === self::RENDER_NORMAL) {
			if ($this->_timestamp === $nextTimestamp) {
				//var_dump("changing state " . $this->_renderStateAsString($newstate));
				$this->_currentRenderState = $newstate;
				$this->_idx++;
			}
		//}
		//else {
		//	if ($this->_timestamp > $nextTimestamp) {
		//		//var_dump("changing state " . $this->_renderStateAsString($newstate));
		//		$this->_currentRenderState = $newstate;
		//		$this->_idx++;
		//	}
		//}
	
		/*
		switch ($this->_currentRenderState) {
			case self::RENDER_NORMAL:
                //$this->_logger->log(__METHOD__ . ' Line ' . __LINE__. ' Case RENDER_NORMAL (Idx=' . $this->_idx . ')', Zend_Log::DEBUG);
				// this means we're not in a 'state block'. If the start date of the next
				// state block is equal to the cursor date, change the rendering state to
				// RENDER_START and calendarState to the calendar state to CALENDAR_BOOKED
 				if ($this->_timestamp === $nextTimestamp) {
					//var_dump('match');exit;
					$this->_currentRenderState = self::RENDER_MIDDLE;
                    //$this->_logger->log(__METHOD__ . ' Line ' . __LINE__ . ' Render state set to ' . $this->_renderStateAsString(self::RENDER_MIDDLE), Zend_Log::DEBUG);
                            
                    $this->_idx++;
                    //$this->_logger->log(__METHOD__ . ' Line ' . __LINE__ . ' Idx advanced to ' . $this->_idx, Zend_Log::DEBUG);
				}
			break;
			
			case self::RENDER_START:
                $this->_logger->log(__METHOD__ . ' Case RENDER_START (Idx=' . $this->_idx . ')', Zend_Log::DEBUG);
				// this means we're going into a 'state block'. The next day could either
				// be a MIDDLE, END or END_START state. If it's not a boundary day,
				// renderState get set to MIDDLE. Otherwise, if the start date of the next state
				// block is on the next day, renderState gets set to END_START otherwise
				// renderState gets set to END    
				if ( (!$this->_timestamp === $this->_stateData[$this->_idx]) &&
					(!$this->_timestamp === $this->_stateData[$this->idx-1])) {
                    $this->_currentRenderState = self::RENDER_MIDDLE;
                    $this->_logger->log(__METHOD__ . ' Line ' . __LINE__ . ' Render state set to ' . $this->_renderStateAsString(self::RENDER_MIDDLE), Zend_Log::DEBUG);
                } else {
                    if ($this->_timestamp === $this->_stateData[$this->_idx]) {
                        $this->_currentRenderState = self::RENDER_END_START;
                        $this->_logger->log(__METHOD__ . ' Line ' . __LINE__ . ' Render state set to ' . $this->_renderStateAsString(self::RENDER_END_START), Zend_Log::DEBUG);
                        
                        $this->_idx++;
                        $this->_logger->log(__METHOD__ . ' Idx advanced to ' . $this->_idx, Zend_Log::DEBUG);
                    } else {
                        $this->_currentRenderState = self::RENDER_END;
                        $this->_logger->log(__METHOD__ . ' Line ' . __LINE__ . ' Render state set to ' . $this->_renderStateAsString(self::RENDER_END), Zend_Log::DEBUG);
                    }
                }
			break;

            case self::RENDER_MIDDLE:
                //$this->_logger->log(__METHOD__ . ' Case RENDER_MIDDLE (Idx=' . $this->_idx . ')', Zend_Log::DEBUG);
                // this means we're in a 'state block'. If the next day is a boundary day
                // and is also equal to the next blocks start date, set $renderState = 
                // END_START. If it's just a boundary day, set $renderState = END.
                if ($this->_timestamp === $nextTimestamp) {
                    $this->_currentRenderState = self::RENDER_NORMAL;
                    //$this->_logger->log(__METHOD__ . ' Render state set to ' . $this->_renderStateAsString(self::RENDER_NORMAL), Zend_Log::DEBUG);
                    $this->_idx++;
                    //$this->_logger->log(__METHOD__ . ' Idx advanced to ' . $this->_idx, Zend_Log::DEBUG);
                }
            break;a
	 
            case self::RENDER_END:
                $this->_logger->log(__METHOD__ . ' Case RENDER_END (Idx=' . $this->_idx . ')', Zend_Log::DEBUG);
                // this means we're going out of a 'state block'. The next possible
                // states are either NORMAL or START. If the next day is a boundary day
                // set $renderState = START otherwise set it to NORMAL.
                if ($this->_stateData[$this->_idx]['endDate']) ||
                    // timestamp is a boundary day
                    $this->_currentRenderState = self::RENDER_START;
                    $this->_logger->log(__METHOD__ . ' Line ' . __LINE__ . ' Render state set to ' . $this->_renderStateAsString(self::RENDER_START), Zend_Log::DEBUG);
                    
                    $this->_idx++;
                    $this->_logger->log(__METHOD__ . ' Idx advanced to ' . $this->_idx, Zend_Log::DEBUG);
                } else {
                    // not a boundary day
                    $this->_currentRenderState = self::RENDER_NORMAL;
                    $this->_logger->log(__METHOD__ . ' Line ' . __LINE__ . ' Render state set to ' . $this->_renderStateAsString(self::RENDER_NORMAL), Zend_Log::DEBUG);
                }
            break;
        
            case self::RENDER_END_START:
                $this->_logger->log(__METHOD__ . ' Case RENDER_END_START (Idx=' . $this->_idx . ')', Zend_Log::DEBUG);
                // this means we're entering into a 'state block'. The next possible
                // states are END, MIDDLE or END_START. If the next day is not a
                // boundary, set $renderState = MIDDLE. Otherwise, if the next day is
                // also equal to the next block start date, set it to END_START.
                // Otherwise set it to END.
                if (!$this->_timestamp === $this->_stateData[$this->_idx]['endDate']) {
                    // boundary day
                    $this->_currentRenderState = self::RENDER_MIDDLE;
                    $this->_logger->log(__METHOD__ . ' Line ' . __LINE__ . ' Render state set to ' . $this->_renderStateAsString(self::RENDER_MIDDLE), Zend_Log::DEBUG);
                } else {
                    // non boundary day
                    if ($this->_timestamp === $this->_stateData[$this->_idx]['startDate']) {
                        $this->_currentRenderState = self::RENDER_END_START;
                        $this->_logger->log(__METHOD__ . ' Line ' . __LINE__ . ' Render state set to ' . $this->_renderStateAsString(self::RENDER_END_START), Zend_Log::DEBUG);
						
                        $this->_idx++;
                        $this->_logger->log(__METHOD__ . ' Idx advanced to ' . $this->_idx, Zend_Log::DEBUG);
                    } else {
                        $this->_currentRenderState = self::RENDER_END;
                        $this->_logger->log(__METHOD__ . ' Line ' . __LINE__ . ' Render state set to ' . $this->_renderStateAsString(self::RENDER_END), Zend_Log::DEBUG);
                    }
                }
            break;
			
		}
        */
        //$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
	}
	
	private function _createPalette()
	{
		//$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		
		$this->_palette = array();

		$availableColour = $this->_settings->getAvailableColour();
		$this->_palette['available'] = imagecolorallocate($this->_gdImg,
						intval(substr($availableColour, 0, 2), 16),
						intval(substr($availableColour, 2, 2), 16),
						intval(substr($availableColour, 4, 2), 16));

		$bookedColour = $this->_settings->getBookedColour();
		$this->_palette['booked'] = imagecolorallocate($this->_gdImg,
						intval(substr($bookedColour, 0, 2), 16),
						intval(substr($bookedColour, 2, 2), 16),
						intval(substr($bookedColour, 4, 2), 16));

		$monthTableBorderColour = $this->_settings->getMonthTableBorderColour();
		$this->_palette['monthTableBorder'] = imagecolorallocate($this->_gdImg,
						intval(substr($monthTableBorderColour, 0, 2), 16),
						intval(substr($monthTableBorderColour, 2, 2), 16),
						intval(substr($monthTableBorderColour, 4, 2), 16));

		$dayNameColour = $this->_settings->getDayNameColour();
		$this->_palette['colourDayName'] = imagecolorallocate($this->_gdImg,
						intval(substr($dayNameColour, 0, 2), 16),
						intval(substr($dayNameColour, 2, 2), 16),
						intval(substr($dayNameColour, 4, 2), 16));


		$this->_palette['boundaryColour'] 	 = imageColorAllocate($this->_gdImg, 0, 0, 0);
		$this->_palette['black']		 	 = imageColorAllocate($this->_gdImg, 0, 0, 0);
		$this->_palette['numbers'] 			 = imageColorAllocate($this->_gdImg, 0x40, 0x44, 0x90);
		$this->_palette['titlebackground']	 = imageColorAllocate($this->_gdImg, 0xb3, 0xc3, 0xe3); 
		$this->_palette['titleforeground']	 = imageColorAllocate($this->_gdImg, 0x40, 0x44, 0x90);
		$this->_palette['weekdaybackground'] = imageColorAllocate($this->_gdImg, 0x84, 0x96, 0xc5); 
		$this->_palette['weekdayforeground'] = imageColorAllocate($this->_gdImg, 0xff, 0xff, 0xfc);
		$this->_palette['weekendbackground'] = imageColorAllocate($this->_gdImg, 0xb3, 0xc3, 0xe3);
		$this->_palette['weekendforeground'] = imageColorAllocate($this->_gdImg, 0xff, 0xff, 0xfc);
		$this->_palette['calendaredge']		 = imageColorAllocate($this->_gdImg, 0x84, 0x96, 0xc5);
		$this->_palette['white']			 = imageColorAllocate($this->_gdImg, 255, 255, 255);
		$this->_palette['red']				 = imageColorAllocate($this->_gdImg, 255, 0, 0);
		$this->_palette['green']			 = imageColorAllocate($this->_gdImg, 0, 255, 0);
		$this->_palette['blue']				 = imageColorAllocate($this->_gdImg, 0, 0, 255);
		
		//$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
	}
	
    private function _drawMonthTable($x, $y, $indent, $numDays, $extrarow)
    {  
        //$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
        
        //$startDay = intval($this->_settings->weekStartDay % 7);
        //$this->_logger->log(__METHOD__ . ' Week start day is ' . $this->_dayNamesShort[$startDay] . '(' . $startDay . ')', Zend_Log::DEBUG);
		
		//var_dump("S:" . $this->_dayNamesShort[$startDay]);
		//exit;
        
		//$daysInThisMonth = date('t', $this->_timestamp);
        //$this->_logger->log(__METHOD__ . ' Month ' . date('M', $this->_timestamp) . ' has ' . $daysInThisMonth . ' days', Zend_Log::DEBUG);
        
		//$indent = (7 + (date('w', $this->_timestamp) - $startDay)  ) %7;
		//$this->_logger->log(__METHOD__ . ' Indent is ' . $indent, Zend_Log::DEBUG);
        
        $this->_drawTitle($x, $y, $extrarow);
		$y += $this->_settings->getMonthMarginTop()
			+ $this->_settings->getTitleHeight()
			+ $this->_settings->getNameCellMarginTop();
		
        $this->_drawDayNames($x, $y);
		$y += $this->_settings->getDayCellHeight() + $this->_settings->getDayCellMarginRight();
		
        $this->_drawDays($x, $y, $indent, $numDays);
    
        //$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
    }
    
    private function _drawTitle($x, $y, $extrarow)
    {
        //$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
		
		// draw a border around the inner edge of the area
        imagerectangle($this->_gdImg,
		               $x,
                       $y,
                       $x + $this->_tableWidth - 1,
                       $y + $this->_tableHeight
					      + ($extrarow * ($this->_settings->getDayCellHeight() + $this->_settings->getDayCellMarginTop())) - 1,
                       $this->_palette['calendaredge']);
		
		// drop down from the very top
		$y += $this->_settings->getMarginTop();
		 
       	imagefilledrectangle($this->_gdImg,
							 $x + $this->_settings->getMonthMarginLeft(),
							 $y,
							 $x + $this->_tableWidth - $this->_settings->getMonthMarginRight() - 1,
							 $y + $this->_settings->getTitleHeight() - 1,
							 $this->_palette['titlebackground']);
	    
        imagestring($this->_gdImg,
                    $this->_settings->getFontCode(),
                    $x + ($this->_tableWidth / 2) - (imagefontwidth($this->_settings->getFontCode()) * 8 / 2), // 8 chars in 'Nov 2010'
                    $y + ($this->_settings->getTitleHeight() / 2) - imagefontheight($this->_settings->getFontCode()) / 2,
                    date('M Y', $this->_timestamp),
                    $this->_palette['titleforeground']);
        
        //$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
    }
    
    private function _drawDayNames($x, $y)
    {
        //$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
        
        $x += $this->_settings->getMonthMarginLeft();
        
        $startDay = intval($this->_settings->getWeekStartDay() % 7);
		
		$liney = $y + ($this->_settings->getDayCellHeight() / 2) - imagefontheight($this->_settings->getFontCode()) / 2;
        for ($i = $startDay; $i < (7 + $startDay); $i++) {
			$weekday = ($i % 7);
			if ((Vfr_Availability_Calendar_Object::DAY_SUNDAY === $weekday) ||
				(Vfr_Availability_Calendar_Object::DAY_SATURDAY === $weekday)) {
				$foreground = 'weekendforeground';
				$background = 'weekendbackground';
			} else {
				$foreground = 'weekdayforeground';
				$background = 'weekdaybackground';
			}
			
        	imagefilledrectangle($this->_gdImg,
								 $x,
								 $y, // + $this->_settings->dayCellVerticalGap,
								 $x + $this->_settings->getDayCellWidth() - 1,
								 $y + $this->_settings->getDayCellHeight() - 1,
								 $this->_palette[$background]);

            imagestring($this->_gdImg,
                        $this->_settings->getFontCode(),
                        $x + ($this->_settings->getDayCellWidth() / 2) - 3,
                        $liney,
                        $this->_dayNamesShort[$i%7],
                        $this->_palette[$foreground]);

            $x += $this->_settings->getDayCellWidth() + $this->_settings->getDayCellMarginRight();
        }

		//exit;
        
        //$this->_x -= (7 * $this->_settings->dayCellWidth) + $this->_settings->monthTableBorderGap;
        //$this->_y += $this->_settings->dayCellHeight;
		//$this->_y += $this->_settings->dayCellVerticalGap;
        
        //$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
    }
    
    public function _drawDays($x, $y, $indent, $numDays)
    {
        //$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
        //$this->_logger->log(__METHOD__ . ' Indent parameter is ' . $indent, Zend_Log::DEBUG);
        
        $x += $this->_settings->getMonthMarginLeft()
		   + $indent * ($this->_settings->getDayCellWidth() + $this->_settings->getDayCellMarginRight());
		
        
        // for each day in this month
		//$lastDayOfMonth = date("t", $this->_timestamp);
		//$this->_logger->log(__METHOD__ . ' Last day of month is ' . $lastDayOfMonth, Zend_Log::DEBUG);
		
		
		//if($indent + $lastDayOfMonth > 34) $sixthRow = true;
		//$this->_logger->log(__METHOD__ . ' Last day of month is ' . $lastDayOfMonth, Zend_Log::DEBUG);
		
		//if (isset($sixthRow)) $this->_logger->log(__METHOD__ . ' Sixthrow ' . $sixthRow, Zend_Log::DEBUG);
        for ($i=1; $i <= $numDays; $i++) {
			//var_dump($this->_currentRenderState);
            switch ($this->_currentRenderState) {
                case self::RENDER_NORMAL:
                case self::RENDER_MIDDLE:
                    $this->_drawWholeCell($x, $y);
					//var_dump($this->_settings->dayCellWidth + $this->_settings->dayCellHorizontalGap);
					//exit;
					$x += $this->_settings->getDayCellWidth() + $this->_settings->getDayCellMarginRight();
                break;
                
                default:
                    $this->_drawTwoHalfCells($x, $y);
            }
            
            //
			// can be optimised. just use counter instead
			//
			$day = date('j', $this->_timestamp);
            if ( ((($day + $indent) %7 == 0)) && ($day != $numDays)) {
                // back to beginning of row
				$x = $this->_x + $this->_settings->getMonthMarginLeft();
                
                //$this->_logger->log(__METHOD__ . ' Dropping down a row', Zend_Log::INFO);
                $y += $this->_settings->getDayCellHeight() + $this->_settings->getDayCellMarginTop();
            }
            
            $this->_updateState();     
            //$this->_increaseCalendarCursor(1);
        }
        
        //$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
    }
    
    private function _drawWholeCell($x, $y)
    {
	   	//$this->_x += $this->_settings->monthTableBorderGap + $this->_settings->dayCellHorizontalGap; 
        $borderWidth = $this->_settings->getMonthMarginLeft();
        
        $paleteString = (($this->_currentRenderState === self::RENDER_MIDDLE) ? 'booked' : 'available');
		
        //$this->_logger->log(__METHOD__ . ' Pallete string ' . $paleteString, Zend_Log::DEBUG);
        imagefilledrectangle($this->_gdImg,
                             $x,
                             $y,
                             $x + $this->_settings->getDayCellWidth() - 1,
                             $y + $this->_settings->getDayCellHeight() - 1,
                             $this->_palette[$paleteString]);
        
        //$this->_x += $this->_settings->dayCellHorizontalGap;
		$this->_drawDate($x, $y);
    }

	private function _drawDate($x, $y)
	{
        $day = date('j', $this->_timestamp);
        if ($day < 10) {
            $x+= 1 + intval($this->_settings->getDayCellWidth()  / 2) - imagefontwidth($this->_settings->getFontCode())  / 2;
            $y+= 1 + intval($this->_settings->getDayCellHeight() / 2) - imagefontheight($this->_settings->getFontCode()) / 2;
        } else {
            $x+= 1 + intval($this->_settings->getDayCellWidth()  / 2) - imagefontwidth($this->_settings->getFontCode());
            $y+= 1 + intval($this->_settings->getDayCellHeight() / 2) - (imagefontheight($this->_settings->getFontCode()) / 2);
        }
        imageString($this->_gdImg,
                    $this->_settings->getFontCode(),
                    $x,
                    $y,
                    $day,
                    $this->_palette['numbers']);
	}
    
    private function _drawTwoHalfCells($x, $y, $params)
    {
        $borderWidth = $this->_settings->getMonthMarginLeft();

		switch ($this->_currentRenderState) {
			case self::RENDER_START:
				$leftColour  = $this->_palette['available'];
				$rightColour = $this->_palette['booked'];
			break;

			case self::RENDER_END:
				$leftColour  = $this->_palette['booked'];
				$rightColour = $this->_palette['available'];
			break;
		}

		// left triangle
		$points = array($this->_x + $borderWidth,
						$this->_y + $borderWidth,
						$this->_x + $this->_settings->getDayCellWidth() - $borderWidth,
						$this->_y + $borderWidth,
						$this->_x + $borderWidth,
						$this->_y + $this->_settings->getDayCellHeight() - $borderWidth);

		imagefilledpolygon($this->_gdImg,
						   $points,
						   3, // number of points
		                   $leftColour);

		// right triangle 
		$points = array($this->_x + $this->_settings->getDayCellWidth() - $borderWidth,
						$this->_y + $this->_settings->getDayCellHeight() - $borderWidth,
						$this->_x + $this->_settings->getDayCellWidth() - $borderWidth,
						$this->_y + $borderWidth,
						$this->_x + $borderWidth,
						$this->_y + $this->_settings->getDayCellHeight() - $borderWidth);

		imagefilledpolygon($this->_gdImg,
		 	               $points,
						   3, // number of points
						   $rightColour);

		if (1 === $this->_settings->boundary) {
			imageline($this->_gdImg,
		    	      $this->_x + $this->_settings->getDayCellWidth() - $borderWidth,
		        	  $this->_y + $borderWidth,
		          	  $this->_x + $borderWidth,
		          	  $this->_y + $this->_settings->dayCellHeight() - $borderWidth,
		          	  $this->_palette['boundaryColour']);
		}

		$this->_drawDate($this->_x, $this->_y);
    }
    
	public function renderCalendarImage()
	{
		//$this->_logger->log(__METHOD__ . ' Start', Zend_Log::INFO);
        //$this->_logger->log(__METHOD__ . ' Render state initialised to ' . $this->_renderStateAsString(self::RENDER_NORMAL), Zend_Log::DEBUG);
        
		// set the calendar cursor to the 1st of the current month and year
		$this->_timestamp = strtotime($this->_settings->getStartYear() . '-' . $this->_settings->getStartMonth() . '-01 14:00:00');
		//$this->_logger->log(__METHOD__ . ' Timestamp initialised to ' .  strftime ("%d-%m-%Y", $this->_timestamp), Zend_Log::DEBUG);

		//$this->_initCalendar();		
        
		$startDay  = intval($this->_settings->getweekStartDay() % 7);
		$year      = $this->_settings->getStartYear();
		$month     = $this->_settings->getStartMonth();
		$current   = $this->_timestamp;
		$mn        = 0;
		$extrarows = 0;
		$trimArray = array();
		for ($i=0; $i < $this->_calendarRows; $i++) {
			$more = 0;
			for ($j=0; $j < $this->_settings->getColumns(); $j++) {
				$lastDayOfMonth = date("t", $current);
				$indent = (7 + (date('w', $current) - $startDay)  ) %7;
				//echo strftime("%Y-%m-%d", $current) . ' last day is ' . $lastDayOfMonth . ' and indent is '. $indent . ' and sum is ' . strval($indent + $lastDayOfMonth) . (($indent + $lastDayOfMonth > 35) ? ' YES' : '') . '<br />';
				$calnewrow = ( (($indent + $lastDayOfMonth) > 35) ? (int) 1 : (int) 0 );
				if (($calnewrow) && ($more === 0))
					$more = 1;
				
				$trimArray[$i][$j] = array('indent' => $indent,
										   'lastDayOfMonth' => $lastDayOfMonth,
										   'extrarow' => $calnewrow);
				
				$current = strtotime("+1 month", $current);
				$mn++;
				//var_dump($more);
				if ($mn === $this->_settings->getDurationMonths()) break;
			}

			$extrarows += $more;
			
			//var_dump("extrarows: " . $extrarows);
			if ($mn === $this->_settings->getDurationMonths()) break;
		}
		
		if ($extrarows > 0) {
			$extraYPixels = ($extrarows * $this->_settings->getDayCellHeight())
						  + (($extrarows - 0) * $this->_settings->getDayCellMarginTop());
		} else {
			$extraYPixels = 0;
		}
		
		//var_dump($trimArray);
		//var_dump($extraYPixels);
		//exit;
		
		$this->_createImageBlankCanvas($extraYPixels);
		$this->_createPalette();
		
		// initial y to top of canvas
		$this->_y = 0;

		// counter to measure number of months drawn so far
		$month = 0;

		// for each calendar row
		for ($i=0; $i < $this->_calendarRows; $i++) {
			// initial x to left of canvas
			$this->_x = 0;

			$sixthRow = false;
			$more = 0;
			for ($j=0; $j < $this->_settings->getColumns(); $j++) {
				$extrarow = $trimArray[$i][$j]['extrarow'];
                $this->_drawMonthTable($this->_x,
									   $this->_y,
									   $trimArray[$i][$j]['indent'],
									   $trimArray[$i][$j]['lastDayOfMonth'],
									   $extrarow);
				if (($extrarow) && ($more === 0))
					$more = 1;
					
				$month++;
				if ($month === $this->_settings->getDurationMonths()) break;
                $this->_x += $this->_tableWidth + $this->_settings->getMarginRight();
            }
			
			if ($month === $this->_settings->getDurationMonths()) break;
            $this->_y += $this->_tableHeight
			          + ($more * ($this->_settings->getDayCellHeight() + $this->_settings->getDayCellMarginTop()))
					  + $this->_settings->getMarginBottom();
		}
		
		//$this->_logger->log(__METHOD__ . ' End', Zend_Log::INFO);
		return $this;
	}
	
	public function getGdImage()
	{
		return $this->_gdImg;
	}	
}