<?php
class Vfr_Availability_Calendar_Object
{
	const DAY_SUNDAY = 0;
	const DAY_MONDAY = 1;
	const DAY_TUESDAY = 2;
	const DAY_WEDNESSDAY = 3;
	const DAY_THURSDAY = 4;
	const DAY_FRIDAY = 5;
	const DAY_SATURDAY = 6;

	protected $durationMonths=null;
	protected $startYear=null;
	protected $startMonth=null;
	
	protected $monthColumns=null;
	
	protected $slope=null;
	protected $boundary=null;
	
	protected $weekStartDay=null;
	protected $key=null;
	protected $keylabels=null;
	
	// presentation
	protected $language=null;
	protected $bookedColour=null;
	protected $availableColour=null;
	protected $dayNameColour=null;
	protected $monthTableBorderColour=null;
	
	protected $titleHeight=null;  // e.g. November 2010
	protected $titleWidth=null;
	protected $titleMarginBottom=null;
	
	protected $dayCellWidth=null;  // e.g. |<->|   | 2 |   | 3 |   | 4 | etc
	protected $dayCellHeight=null;
	
	protected $dayCellMarginTop=null;
	protected $dayCellMarginBottom=null;
	protected $dayCellMarginLeft=null;
	protected $dayCellMarginRight=null;
	
	protected $nameCellMarginTop=null;
	protected $nameCellMarginBottom=null;
	protected $nameCellMarginLeft=null;
	protected $nameCellMarginRight=null;
	
	protected $monthMarginTop=null;
	protected $monthMarginRight=null;
	protected $monthMarginBottom=null;
	protected $monthMarginLeft=null;
	
	protected $marginTop=null;
	protected $marginBottom=null;
	protected $marginLeft=null;
	protected $marginRight=null;

	public function __construct()
	{
		// font
		$this->fontCode = 2;
		
		// styling
		$this->weekStartDay = (int) self::DAY_MONDAY;
		$this->durationMonths = 12;
		$this->startYear = date('Y');
		$this->startMonth = date('m');
		$this->monthColumns = 4;
		$this->language = 'en_EN';
		$this->boundary = 0;
		$this->slope = 1;
		$this->colourMonthTableBorder = '8496c5';
		$this->availableColour = 'e7eff7';
		$this->bookedColour    = 'e7eff7';
		$this->dayNameColour   = 'e7eff7';
		$this->monthTableBorderColour = '000000';
		$this->key=1;
		$this->keylabels = array('a' => 'Available', 'b' => 'Booked');
	
		// geometry
		$this->titleHeight = 24;
		$this->titleWidth  = null;
	
		$this->dayCellWidth  = 26;
		$this->dayCellHeight = 21;
	
		$this->dayCellMarginTop     = 2;
		$this->dayCellMarginBottom  = 0;
		$this->dayCellMarginLeft	= 0;
		$this->dayCellMarginRight	= 2;
	
		$this->nameCellMarginTop	= 10;
		$this->nameCellMarginBottom = 0;
		$this->nameCellMarginLeft   = 3;
		$this->nameCellMarginRight  = 3;
	
		$this->monthMarginTop    = 4;
		$this->monthMarginRight  = 4;
		$this->monthMarginBottom = 4;
		$this->monthMarginLeft   = 4;
	
		$this->marginTop    = 4;
		$this->marginBottom = 4;
		$this->marginLeft   = 3;
		$this->marginRight  = 4;
	}
	
	public function setFontCode($font)
	{
		$this->fontCode = $font;
		
		return $this;
	}
	
	public function getFontCode()
	{
		return $this->fontCode;
	}
	
	public function setCalendarDuration($d)
	{
		$this->durationMonths = (int) $d;

		return $this;
	}
	
	public function setDayNamesHorizontalGap($g)
	{
		$this->dayNamesHorizontalGap = (int) $g;
	}
	
	public function setStartMonth($value)
	{
		$value = (int) $value;
		
		if ($value < 10) {
			$this->startMonth = '0' . strval($value);
		} elseif (99 === $value) {
			$this->startMonth = date('m');
		} else {
			$this->startMonth = strval($value);
		}
		
		return $this;
	}
	
	public function setStartYear($value)
	{
		if (99 === $value) {
			$this->startYear = (int) date('Y');
		} else {
			$this->startYear = (int) $value;
		}
		
		return $this;
	}

	public function setColumns($value)
	{
		$this->monthColumns = (int) $value;

		return $this;
	}

	public function getColumns()
	{
		return $this->monthColumns;
	}
	
	public function setColourAvailable($value)
	{
		$value = (string) $value;
		
		$this->availableColour = $value;
		
		return $this;
	}

	public function setLanguage($value)
	{
		$this->language = (string) $value;
		
		return $this;
	}
	
	public function setBookedColour($value)
	{
		$this->bookedColour = (string) $value;
		
		return $this;
	}

	public function getBookedColour()
	{
		return $this->bookedColour;
	}

	public function setAvailableColour($value)
	{
		$this->availableColour = $value;
		
		return $this;
	}

	public function getAvailableColour()
	{
		return $this->availableColour;
	}
	
	public function setDayNameColour($value)
	{
		$this->dayNameColour = (string) $value;
		
		return $this;
	}

	public function getDayNameColour()
	{
		return $this->dayNameColour;
	}
	
	public function setMonthTableBorderColour($value)
	{
		$this->monthTableBorderColour = (string) $value;
		
		return $this;
	}

	public function getMonthTableBorderColour()
	{
		return $this->monthTableBorderColour;
	}

	public function setTitleWidth($pixels)
	{
		$this->titleWidth = (int) $pixels;

		return $this;
	}

	public function getTitleWidth()
	{
		return $this->titleWidth;
	}

	public function setDayCellWidth($pixels)
	{
		$this->dayCellWidth = (int) $pixels;

		return $this;
	}
	
	public function setDayCellHeight($value)
	{
		$this->dayCellHeight = (int) $value;
		
		return $this;
	}
	
	public function setTitleHeight($pixels)
	{
		$this->titleHeight = (int) $pixels;
		
		return $this;
	}

	public function getTitleHeight()
	{
		return $this->titleHeight;
	}

	public function setMarginTop($top)
	{
		$this->marginTop = $top;
		
		return $this;
	}
	
	public function getMarginTop()
	{
		return $this->marginTop;
	}

	public function setMarginBottom($bottom)
	{
		$this->marginBottom = $bottom;
		
		return $this;
	}

	public function getMarginBottom()
	{
		return $this->marginBottom;
	}

	public function setMarginLeft($left)
	{
		$this->marginLeft = $left;
		
		return $this;
	}
	
	public function getMarginLeft()
	{
		return $this->marginLeft;
	}

	public function setMarginRight($right)
	{
		$this->marginRight = $right;
		
		return $this;
	}

	public function getMarginRight()
	{
		return $this->marginRight;
	}
	

	public function getWeekStartDay()
	{
		return $this->weekStartDay;
	}
	
	public function getDurationMonths()
	{
		return $this->durationMonths;
	}
	
	public function getStartMonth()
	{
		return $this->startMonth;
	}
	
	public function getStartYear()
	{
		return $this->startYear;
	}

	
	
	public function getDayCellWidth()
	{
		return $this->dayCellWidth;
	}

	public function getDayCellHeight()
	{
		return $this->dayCellHeight;
	}
	
	public function getDayCellMarginTop()
	{
		return $this->dayCellMarginTop;
	}
	
	public function getDayCellMarginBottom()
	{
		return $this->dayCellMarginBottom;
	}
	
	public function getDayCellMarginRight()
	{
		return $this->dayCellMarginRight;	
	}
	
	public function getDayCellMarginLeft()
	{
		return $this->dayCellMarginLeft;
	}
	
	public function getNameCellMarginTop()
	{
		return $this->nameCellMarginTop;
	}
	
	public function getNameCellMarginBottom()
	{
		return $this->nameCellMarginBottom;
	}
		
	public function getNameCellMarginLeft()
	{
		return $this->nameCellMarginLeft;
	}
	
	public function getNameCellMarginRight()
	{
		return $this->nameCellMarginRight;
	}
	
	public function getMonthMarginTop()
	{
		return $this->monthMarginTop;
	}
	
	public function getMonthMarginRight()
	{
		return $this->monthMarginRight;
	}
	
	public function getMonthMarginBottom()
	{
		return $this->monthMarginBottom;
	}
	
	public function getMonthMarginLeft()
	{
		return $this->monthMarginLeft;
	}
}
