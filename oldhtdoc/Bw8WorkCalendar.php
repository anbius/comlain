<?php
date_default_timezone_set('Asia/Shanghai');


////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Usage example:
////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	$Holidays = array(
//	 	array("2019-02-04", "2019-02-10"), //Spring Festival, begin/end days included
// 		array("2019-04-05", "2019-04-05") //QingMing Festival
// 	);
// 
// 	$ExtraWorkingDays = array(
// 		array("2019-02-02", "2019-02-03", "08:00:00", "17:00:00") //Shifted working days for Spring Festival
// 	);
// 
//	//ATTENTION: 0 stands for Sunday 
// 	$WeeklyWorkingSchedule = array(
// 			1 => array("08:00:00", "17:00:00"),
// 			2 => array("08:00:00", "17:00:00"),
// 			3 => array("08:00:00", "17:00:00"),
// 			4 => array("08:00:00", "17:00:00"),
// 			5 => array("08:00:00", "17:00:00")
// 		);
// 
// 	$objBw8WorkCalendar = new Bw8WorkCalendar($Holidays, $ExtraWorkingDays, $WeeklyWorkingSchedule);
//
//	/*********Scenario showcases:***********/
//	/*##1## Calculate working hours in seconds of one given time period*/
//	$BeginTimeStr = "2019-02-20 12:03:45";
//	$EndTimeStr = "2019-03-21 16:13:41";
//	$WorkHours = $objBw8WorkCalendar->CalcWorkHours($BeginTimeStr, $EndTimeStr);
//
//	/*##2## Calculate latest begin time from given end time and working hours*/
//	$EndTimeStr = "2019-03-21 16:13:41";
//	$WorkHours = 8 * 3600; //Working hours in seconds
//	$BeginTimeStr = $objBw8WorkCalendar->CalcBeginTime($EndTimeStr, $WorkHours); 
// 
//	/*##3## Calculate working hours for a group of tickets */
//	$EndTimeStr = "2019-03-21 16:13:41";
//	/*$TicketBeginTimeArray is an array of TicketID => StartTime*/
//	$TicketBeginTimeArray = array(
//		1 => "2019-02-20 12:03:45",
//		2 => "2019-01-12 11:23:14",
//		3 => "2017-11-08 08:11:39"
//	);
//	/*Returned $WorkingHoursArray is an array of TicketID => WorkingHours*/
//	$WorkingHoursArray = $objBw8WorkCalendar->CalcWorkHoursForTicketsFast($TicketBeginTimeArray, $EndTimeStr);
////////////////////////////////////////////////////////////////////////////////////////////////////////////

class Bw8WorkCalendar {
	private $HolidaysPlan = array();
	private $ExtraWorkingDaysPlan = array();
	private $NormalWorkingSchedule = array();

	public function __construct($Holidays, $ExtraWorkingDays, $WorkTimeSchedule) {
		//To enable fast processing, first transform input parameters to internal format 
		$this->InitHolidaysPlan($Holidays);
		$this->InitExtraWorkingDaysPlan($ExtraWorkingDays);
		$this->InitNormalWorkingSchedule($WorkTimeSchedule);
	}


	public function __destruct() {
		unset($this->HolidaysPlan);
		unset($this->ExtraWorkingDaysPlan);
		unset($this->NormalWorkingSchedule);
	}

	
	public function CalcWorkHours($BeginTimeStr, $EndTimeStr)
	{
		$BeginTimeArray = explode(" ", $BeginTimeStr);  
		$EndTimeArray = explode(" ", $EndTimeStr);  
	
		$BeginTime = strtotime($BeginTimeArray[0] . " 00:00:00");
		if($BeginTimeArray[0] == "" || $BeginTime === false)
			return 0;
		$EndTime = strtotime($EndTimeArray[0] . " 23:59:59");
		if($EndTimeArray[0] == "" || $EndTime === false)
			return 0;
	
		$TotalWorkingHours = 0;
		for ($CurrTime = $BeginTime; $CurrTime <= $EndTime; $CurrTime += 24 * 3600) {
			$CurrDateStr = date("Y-m-d", $CurrTime);
	
			$BeginTimeInDay = "00:00:00";
			if($BeginTimeArray[0] == $CurrDateStr)
				$BeginTimeInDay = $BeginTimeArray[1];
			$EndTimeInDay = "23:59:59";
			if($EndTimeArray[0] == $CurrDateStr)
				$EndTimeInDay = $EndTimeArray[1];
	
			$TotalWorkingHours += $this->GetWorkHoursOfGivingDay($CurrDateStr, $BeginTimeInDay, $EndTimeInDay);
		}
		return $TotalWorkingHours;
	}
	
	public function CalcBeginTime($EndTimeStr, $WorkingHoursInSec) {
		$EndTime = strtotime($EndTimeStr);
		
		$DecStep = $WorkingHoursInSec;
		$BeginTime = $EndTime;
		$BeginTimeStr = "";
		while(true) {
			$BeginTime -= $DecStep;
			$BeginTimeStr = date('Y-m-d H:i:s', $BeginTime);
			$CurrWorkHours = $this->CalcWorkHours($BeginTimeStr, $EndTimeStr);
			if($CurrWorkHours > $WorkingHoursInSec) {
				$BeginTime += $DecStep;
				$DecStep = $DecStep/2;
			}
			else if($CurrWorkHours == $WorkingHoursInSec) {
				break;
			}
		}
		return $BeginTimeStr;
	}

	public function CalcWorkHoursForTickets($TicketsBeginTimeArray, $EndTimeStr) {
		$WorkingHoursForTickets = array();

		if(! is_array($TicketsBeginTimeArray))
			return $WorkingHoursForTickets;

		foreach($TicketsBeginTimeArray as $TicketID => $BeginTimeStr) {
			$WorkingHoursForTickets[$TicketID] = $this->CalcWorkHours($BeginTimeStr, $EndTimeStr);
		}
		return $WorkingHoursForTickets;
	}
	
	
	public function CalcWorkHoursForTicketsFast($TicketsBeginTimeArray, $EndTimeStr) {
		$WorkingHoursForTickets = array();

		if(! is_array($TicketsBeginTimeArray))
			return $WorkingHoursForTickets;

		arsort($TicketsBeginTimeArray);
	
		$LastBeginTimeStr = "";
		$CurrentWorkingHours = 0;
		foreach($TicketsBeginTimeArray as $TicketID => $BeginTimeStr) {
			if($LastBeginTimeStr == "")
				$WorkHours = $this->CalcWorkHours($BeginTimeStr, $EndTimeStr);
			else 
				$WorkHours = $this->CalcWorkHours($BeginTimeStr, $LastBeginTimeStr);
	
			$CurrentWorkingHours += $WorkHours;
	
			$WorkingHoursForTickets[$TicketID] = $CurrentWorkingHours;
			
			$LastBeginTimeStr = $BeginTimeStr;
		}
		return $WorkingHoursForTickets;
	}

	private function GetWorkHoursOfGivingDay($DateStr, $BeginTimeStr, $EndTimeStr)
	{
		if($this->isHoliday($DateStr))
			return 0;
	
		$ExtraWorkingHours = $this->GetExtraWorkHours($DateStr, $BeginTimeStr, $EndTimeStr);
		if($ExtraWorkingHours > 0)
			return $ExtraWorkingHours;
	
		return $this->GetNormalWorkHours($DateStr, $BeginTimeStr, $EndTimeStr);
	}

	private function isHoliday($DateStr) {
		if(in_array($DateStr, $this->HolidaysPlan))
			return true;

		return false;
	}

	private function GetExtraWorkHours($DateStr, $BeginTimeStr, $EndTimeStr)
	{
		return $this->GetWorkingHoursOfGivenSchedule($this->ExtraWorkingDaysPlan, $DateStr, $BeginTimeStr, $EndTimeStr);
	}

	private function GetNormalWorkHours($DateStr, $BeginTimeStr, $EndTimeStr)
	{
		$DayOfWeek = date('w',strtotime($DateStr . " 00:00:00"));
		return $this->GetWorkingHoursOfGivenSchedule($this->NormalWorkingSchedule, $DayOfWeek, $BeginTimeStr, $EndTimeStr);
	}

	private function InitHolidaysPlan($HolidayArray)
	{
		$this->HolidaysPlan = array();

		if(! is_array($HolidayArray))
			return;

		foreach( $HolidayArray as $Holiday) {
			if(!is_array($Holiday) || count($Holiday) != 2)
				continue;

			$BeginTime = strtotime($Holiday[0]. " 00:00:00");
			if($Holiday[0] == "" || $BeginTime === false)
				continue;
			$EndTime = strtotime($Holiday[1] . " 23:59:59");
			if($Holiday[1] == "" || $EndTime === false)
				continue;
		
			for ($CurrTime = $BeginTime; $CurrTime <= $EndTime; $CurrTime += 24 * 3600) {
				$CurrDateStr = date("Y-m-d", $CurrTime);
				$this->HolidaysPlan[] = $CurrDateStr;
			}
		}
		return;
	}

	private function InitExtraWorkingDaysPlan($ExtraWorkDayArray) {
		$this->ExtraWorkingDaysPlan = array();
		$NewExtraWorkingDays = array();

		if(! is_array($ExtraWorkDayArray))
			return;

		foreach( $ExtraWorkDayArray as $ExtraWorkDay) {
			if(!is_array($ExtraWorkDay) || count($ExtraWorkDay) != 4)
				continue;

			$BeginTime = strtotime($ExtraWorkDay[0]. " 00:00:00");
			if($ExtraWorkDay[0] == "" || $BeginTime === false)
				continue;
			$EndTime = strtotime($ExtraWorkDay[1] . " 23:59:59");
			if($ExtraWorkDay[1] == "" || $EndTime === false)
				continue;
			$WorkSchedule = array($ExtraWorkDay[2], $ExtraWorkDay[3]);
		
			for ($CurrTime = $BeginTime; $CurrTime <= $EndTime; $CurrTime += 24 * 3600) {
				$CurrDateStr = date("Y-m-d", $CurrTime);
				$NewExtraWorkingDays[$CurrDateStr] = $WorkSchedule;
			}
		}
		$this->ExtraWorkingDaysPlan = $this->InitWorkingSchedule($NewExtraWorkingDays);
		return; 
	}

	private function InitNormalWorkingSchedule($WorkingSchedule) {
		$this->NormalWorkingSchedule = $this->InitWorkingSchedule($WorkingSchedule);
		return;
	}

	private function InitWorkingSchedule($WorkingSchedule)
	{
		$NewWorkingSchedule = array();
		if(! is_array($WorkingSchedule))
			return $NewWorkingSchedule;
	
		//Convert time string to timestamp to speed up in later calculation
		foreach( $WorkingSchedule as $Key => $WorkingHour) {
			$BeginTime = strtotime("1970-01-01 " . $WorkingHour[0]);
			if($WorkingHour[0] == "" || $BeginTime === false)
				continue;
			$EndTime = strtotime("1970-01-01 " . $WorkingHour[1]);
			if($WorkingHour[1] == "" || $EndTime === false)
				continue;
			
			$NewWorkingSchedule[$Key] = array($BeginTime, $EndTime);
		}
		return $NewWorkingSchedule;
	}
	
	
	private function GetWorkingHoursOfGivenSchedule($WorkingSchedule, $key, $BeginTimeStr, $EndTimeStr)
	{
		if(!array_key_exists($key, $WorkingSchedule))	
			return 0;
	
		$BeginTime = strtotime("1970-01-01 " . $BeginTimeStr);	
		$EndTime = strtotime("1970-01-01 " . $EndTimeStr);	
	
		if($WorkingSchedule[$key][0] > $BeginTime)
			$BeginTime = $WorkingSchedule[$key][0];
		if($WorkingSchedule[$key][1] < $EndTime)
			$EndTime = $WorkingSchedule[$key][1];
	
		$WorkingHoursOfTheDay = $EndTime - $BeginTime;
		if($WorkingHoursOfTheDay < 0)
			$WorkingHoursOfTheDay = 0;

		return $WorkingHoursOfTheDay;
	}

}
///////////////End of class definition///////////////////////////////

////////The followings are some test & verification functions////////

$g_BeginTime = 0;
function MeasureRunningTime($sDescriptionStr = "", $bMeasureBegin = true) {
	global $g_BeginTime;
	if($bMeasureBegin) {
		$g_BeginTime=microtime(true);
		return;
	}

	$CurrTime = microtime(true);
	$timediff = 1000 * ($CurrTime - $g_BeginTime);
	echo "Time spent for " . $sDescriptionStr . ": " . round($timediff, 4) . " ms\n";
	return;
}

function TestCalcWorkingHours(&$objBw8WorkCalendar) {
	$TicketBeginTimeArray = array();
	$MaxTimePeriod = 5 * 365 * 24 * 3600; //Let ticket begin time spans in a 5 years time period
	$nNumOfTickets = 100;

	echo "\n###################################################\n";
	echo "Now test working hours calculation for " . $nNumOfTickets . " tickets...\n";
	echo "###################################################\n";
	for($i = 0; $i < $nNumOfTickets; $i ++) {
		$back_seconds = mt_rand(100, $MaxTimePeriod);
		$TicketID = mt_rand(1, 99999999);
		$BeginTimeStr = date('Y-m-d H:i:s', strtotime('-' . $back_seconds . ' seconds'));

		$TicketBeginTimeArray[$TicketID] = $BeginTimeStr;
	}
	$EndTimeStr = date('Y-m-d H:i:s');


	//Get a ticket sample to verify whether the results of slow and fast versions are match
	$nVeryTicketSampleOffset = mt_rand(0, $nNumOfTickets - 1);
	$TicketIDToVerify = "";
	$count = 0;
	foreach($TicketBeginTimeArray as $TicketID => $TicketBeginTime) {
		if($count == $nVeryTicketSampleOffset) {
			$TicketIDToVerify = $TicketID;
			echo "Sample ticket to verify: TicketID = " . $TicketIDToVerify . ", " . $TicketBeginTime . " ==> " . $EndTimeStr . "\n";
			break;
		}
		$count++;
	}


	//Only run slow version when number of tickets is small, otherwise it will take too much time 
	if($nNumOfTickets < 200) {
		//Test slow version
		MeasureRunningTime();
		$WorkingHours_slow = $objBw8WorkCalendar->CalcWorkHoursForTickets($TicketBeginTimeArray, $EndTimeStr);
		echo "Working hours from slow version: " . $WorkingHours_slow[$TicketIDToVerify] . "(" . round($WorkingHours_slow[$TicketIDToVerify]/3600, 2) . " hours, "  . round($WorkingHours_slow[$TicketIDToVerify]/9/3600,2) . " working days)\n";
		MeasureRunningTime("slow version", false);
	}

	if(true) {
		//Test fast version
		MeasureRunningTime();
		$WorkingHours_fast = $objBw8WorkCalendar->CalcWorkHoursForTicketsFast($TicketBeginTimeArray, $EndTimeStr);
		echo "Working hours from fast version: " . $WorkingHours_fast[$TicketIDToVerify] . "(" . round($WorkingHours_fast[$TicketIDToVerify]/3600, 2) . " hours, "  . round($WorkingHours_fast[$TicketIDToVerify]/9/3600, 2) . " working days)\n";
		MeasureRunningTime("fast version", false);
	}

	return;
}

function TestCalcStartTimeFromWorkingHours(&$objBw8WorkCalendar) {
	echo "\n###################################################\n";
	echo "Now test start time calculation from given working hours...\n";
	echo "###################################################\n";

	$BeginTimeStr = "2019-02-20 12:03:45";
	$EndTimeStr = "2019-03-21 16:13:41";
	$WorkHours = $objBw8WorkCalendar->CalcWorkHours($BeginTimeStr, $EndTimeStr);

	echo "Working hours for: $BeginTimeStr => $EndTimeStr is $WorkHours(" . round($WorkHours/3600,2) . " hours, "  . round($WorkHours/9/3600,2) . " working days)\n";

	$NewBeginTimeStr = $objBw8WorkCalendar->CalcBeginTime($EndTimeStr, $WorkHours); 

	echo "Original begin time: $BeginTimeStr, new begin time: $NewBeginTimeStr!\n";
	if($NewBeginTimeStr != $BeginTimeStr)
		echo "Calculated begin time does not match!\n";
	
	return;
}

$Holidays = array(
	array("2019-02-04", "2019-02-10"), //Spring Festival, begin/end days included
	array("2019-04-05", "2019-04-05") //Qingming
);

$ExtraWorkingDays = array(
	array("2019-02-02", "2019-02-03", "08:00:00", "17:00:00") //Shifted working days for Spring Festival
);

$WeeklyWorkingSchedule = array(
		1 => array("08:00:00", "17:00:00"),
		2 => array("08:00:00", "17:00:00"),
		3 => array("08:00:00", "17:00:00"),
		4 => array("08:00:00", "17:00:00"),
		5 => array("08:00:00", "17:00:00")
	);

$objBw8WorkCalendar = new Bw8WorkCalendar($Holidays, $ExtraWorkingDays, $WeeklyWorkingSchedule);
	
TestCalcStartTimeFromWorkingHours($objBw8WorkCalendar); 
TestCalcWorkingHours($objBw8WorkCalendar);
?>
