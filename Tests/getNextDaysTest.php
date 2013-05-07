<?php

    //require_once 'PHPUnit/Autoload.php';
    //require_once '../lib_ical.php';

    class RepeatTest2 extends PHPUnit_Framework_TestCase
    {	       
        /*
         *  SECTION: GetNextDates()
         */
        public function test_SimpleDaily_FromDue()
        {
            $ical = "FREQ=DAILY;INTERVAL=1";

            $start = 0;
            $due = new DateTime("January 1, 2013");
            $comp = 0;          

            $newstart = 0;
            $newdue = new DateTime("January 2, 2013");

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        public function test_SimpleMonthly_FromDue()
        {
            $ical = "FREQ=MONTHLY;INTERVAL=2;BYMONTHDAY=1";

            $start = 0;
            $due = new DateTime("January 1, 2013");
            $comp = 0;          

            $newstart = 0;
            $newdue = new DateTime("March 1, 2013");

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        public function test_SimpleYearly_FromDue()
        {
            $ical = "FREQ=YEARLY;INTERVAL=2";

            $start = 0;
            $due = new DateTime("January 1, 2013");
            $comp = 0;          

            $newstart = 0;
            $newdue = new DateTime("January 1, 2015");

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        public function test_SimpleDaily_FromStart()
        {
            $ical = "FREQ=DAILY;INTERVAL=1";

            $start = new DateTime("January 1, 2013");;
            $due = 0;
            $comp = 0;          
            
            $newstart = new DateTime("January 2, 2013");
            $newdue = 0;

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        public function test_SimpleMonthly_FromStart()
        {
            $ical = "FREQ=MONTHLY;INTERVAL=2;BYMONTHDAY=1";
            
            $start = new DateTime("January 1, 2013");
            $due = 0;
            $comp = 0;          
            
            $newstart = new DateTime("March 1, 2013");
            $newdue = 0;

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        public function test_SimpleYearly_FromStart()
        {
            $ical = "FREQ=YEARLY;INTERVAL=2";

            $start = new DateTime("January 1, 2013");
            $due = 0;
            $comp = 0;

            $newstart = new DateTime("January 1, 2015");
            $newdue = 0;                

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        public function test_DailyUntil_NoNextOccurence()
        {
            // Repeat until Jan. 2, 2013
            $ical = "FREQ=DAILY;UNTIL=20130101T000000Z";

            $start = new DateTime("January 1, 2013");
            $due = new DateTime("January 2, 2013");
            $comp = 0;

            $newstart = -1;
            $newdue = -1;                
            $newical = '';

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }

        public function test_WeeklyUntil_NoNextOccurence()
        {
            // Repeat until Jan. 30, 2013
            $ical = "FREQ=WEEKLY;UNTIL=20130130T000000Z";

            $start = new DateTime("January 28, 2013");
            $due = new DateTime("January 29, 2013");
            $comp = 0;

            $newstart = -1;
            $newdue = -1;                
            $newical = '';

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }

        public function test_YearlyUntil_NoNextOccurence()
        {
            // Repeat until Jan. 1, 2014
            $ical = "FREQ=YEARLY;UNTIL=20140101T000000Z";

            $start = new DateTime("January 2, 2013");
            $due = new DateTime("January 3, 2013");
            $comp = 0;

            $newstart = -1;
            $newdue = -1;                
            $newical = '';

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }

        public function test_DailyUntil_LastOccurence()
        {
            // Repeat until Jan. 2, 2013
            $ical = "FREQ=DAILY;UNTIL=20130102T000000Z";

            $start = new DateTime("January 1, 2013");
            $due = new DateTime("January 2, 2013");
            $comp = 0;

            $newstart = new DateTime("January 2, 2013");
            $newdue = new DateTime("January 3, 2013");
            $newical = '';

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }

        public function test_WeeklyUntil_LastOccurence()
        {
            // Repeat until Jan. 30, 2013
            $ical = "FREQ=WEEKLY;UNTIL=20130130T000000Z";

            $start = new DateTime("January 21, 2013");
            $due = new DateTime("January 25, 2013");
            $comp = 0;

            $newstart = new DateTime("January 28, 2013");
            $newdue = new DateTime("February 1, 2013");
            $newical = '';

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }

        public function test_YearlyUntil_LastOccurence()
        {
            // Repeat until Jan. 1, 2014
            $ical = "FREQ=YEARLY;UNTIL=20140101T000000Z";

            $start = new DateTime("January 1, 2013");
            $due = new DateTime("January 2, 2013");
            $comp = 0;

            $newstart = new DateTime("January 1, 2014");
            $newdue = new DateTime("January 2, 2014");
            $newical = '';

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }
    }
?>