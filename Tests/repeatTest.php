<?php

	require_once 'PHPUnit/Autoload.php';
	require_once '../lib_ical.php';
	
	class RepeatTest extends PHPUnit_Framework_TestCase
	{	
		// SECTION: Convert to ical
		public function testOne()
		{
			$repeat = "Every 2 Months";
			$ical = "FREQ=MONTHLY;INTERVAL=2";
			
			$converted = convertToRRule($repeat,false);
			
			$this->assertEquals( $ical, $converted );
		}	
		
		// SECTION: GetNextDates()
		public function testNextOne()
		{
			$ical = "FREQ=MONTHLY;INTERVAL=2";
		
			$start = 0;
			$due = new DateTime();
			$due->setTimestamp(1367358992);
			$comp = 0;			
			
			$newstart = 0;
			$newdue = new DateTime();
			$newdue->setTimestamp(1372629385);			
			
			$array = getNextDates($start,$due,$comp,$ical);
			
			$this->assertEquals( $newststart, $array[0] );
			$this->assertEquals( $newdue, $array[1] );
			$this->assertEquals( $ical, $array[2] );
			
			//getNextDates(0,1367358992,0,"MONTHLY;INTERVAL=2") and confirm that it
			//returns array(0,1372629385,"MONTHLY;INTERVAL=2")		
		}
	}
?>