<?php

    //require_once 'PHPUnit/Autoload.php';
    //require_once '../lib_ical.php';

    class RepeatTest2 extends PHPUnit_Framework_TestCase
    {
        public function test_BadParams()
        {
            $ical = "FREQ=DAILY;INTERVAL=1";

            $start = "asdf";
            $due = "efhg";
            $comp = 0;

            $newstart = -1;
            $newdue = -1;
            $newical = "";

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }

        public function test_BadParams2()
        {
            $ical = "FREQ=DAILY;INTERVAL=1";

            $start = new DateTime("January 1, 2012");
            $due = "efhg";
            $comp = 0;

            $newstart = -1;
            $newdue = -1;
            $newical = "";

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }

        public function test_BadParams3()
        {
            $ical = "FREQ=DAILY;INTERVAL=1";

            $start = new DateTime("January 1, 2012");
            $due = new DateTime("January 10, 2012");;
            $comp = 4.5;

            $newstart = -1;
            $newdue = -1;
            $newical = "";

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }

        public function test_BadParams4()
        {
            $ical = "ASDF;FREQ=DAILY;INTERVAL=1";

            $start = new DateTime("January 1, 2012");
            $due = new DateTime("January 10, 2012");;
            $comp = 0;

            $newstart = -1;
            $newdue = -1;
            $newical = "";

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }

        public function test_AllOptional()
        {
            $ical = "FREQ=DAILY;INTERVAL=1";

            $start = 0;
            $due = 0;
            $comp = 0;

            $newstart = 0;
            $newdue = 0;

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

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

        public function test_SimpleDailyLeapYear_FromDue()
        {
            $ical = "FREQ=DAILY;INTERVAL=1";

            $start = 0;
            $due = new DateTime("February 28, 2013");
            $comp = 0;

            $newstart = 0;
            $newdue = new DateTime("March 1, 2013");

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        public function test_SimpleDailyLeapYear2_FromDue()
        {
            $ical = "FREQ=DAILY;INTERVAL=1";

            $start = 0;
            $due = new DateTime("February 28, 2016");
            $comp = 0;

            $newstart = 0;
            $newdue = new DateTime("February 29, 2016");

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        // TODO: Fix when Monthly works, remove BYMONTHDAY=1
        // public function test_SimpleMonthly_FromDue()
        // {
        //     $ical = "FREQ=MONTHLY;INTERVAL=2";

        //     $start = 0;
        //     $due = new DateTime("January 1, 2013");
        //     $comp = 0;          

        //     $newstart = 0;
        //     $newdue = new DateTime("March 1, 2013");

        //     $array = getNextDates($start,$due,$comp,$ical);

        //     $this->assertEquals( $newstart, $array[0] );
        //     $this->assertEquals( $newdue, $array[1] );
        //     $this->assertEquals( $ical, $array[2] );
        // }

        // // TODO: Fix when Monthly works, remove BYMONTHDAY=1
        // public function test_SingleMonthly_FromDue()
        // {
        //     $ical = "FREQ=MONTHLY";

        //     $start = 0;
        //     $due = new DateTime("January 31, 2013");
        //     $comp = 0;          

        //     //It should skip Februrary because there is no 31st of feb

        //     $newstart = 0;
        //     $newdue = new DateTime("March 31, 2013");

        //     $array = getNextDates($start,$due,$comp,$ical);

        //     $this->assertEquals( $newstart, $array[0] );
        //     $this->assertEquals( $newdue, $array[1] );
        //     $this->assertEquals( $ical, $array[2] );
        // }

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

        // TODO: Fix when Monthly works, remove BYMONTHDAY=1
        // public function test_SimpleMonthly_FromStart()
        // {
        //     $ical = "FREQ=MONTHLY;INTERVAL=2;BYMONTHDAY=1";
            
        //     $start = new DateTime("January 1, 2013");
        //     $due = 0;
        //     $comp = 0;          
            
        //     $newstart = new DateTime("March 1, 2013");
        //     $newdue = 0;

        //     $array = getNextDates($start,$due,$comp,$ical);
        // 
        //     $this->assertEquals( $newstart, $array[0] );
        //     $this->assertEquals( $newdue, $array[1] );
        //     $this->assertEquals( $ical, $array[2] );
        // }

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

        public function test_DailyUntil_NoNextOccurrence()
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

        public function test_WeeklyUntil_NoNextOccurrence()
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

        public function test_YearlyUntil_NoNextOccurrence()
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

        public function test_DailyUntil_LastOccurrence()
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

        public function test_WeeklyUntil_LastOccurrence()
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

        public function test_YearlyUntil_LastOccurrence()
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


        public function test_FromCompletion_Daily()
        {
            $ical = "FREQ=DAILY;INTERVAL=1;FROMCOMP";

            $start = new DateTime("January 1, 2013");
            $due = new DateTime("January 2, 2013");
            $comp = new DateTime("January 5, 2013");

            $newstart = new DateTime("January 5, 2013");
            $newdue = new DateTime("January 6, 2013");  
            $newical = $ical;

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }

        // TODO: Fix when Monthly works, remove BYMONTHDAY=5
        public function test_FromCompletion_Monthly()
        {
            $ical = "FREQ=MONTHLY;INTERVAL=1;BYMONTHDAY=5;FROMCOMP";

            $start = new DateTime("January 1, 2013");
            $due = new DateTime("January 2, 2013");
            $comp = new DateTime("January 5, 2013");

            $newstart = new DateTime("February 4, 2013");
            $newdue = new DateTime("February 5, 2013");
            $newical = $ical;

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }

        public function test_FromCompletion_Weekly()
        {
            $ical = "FREQ=WEEKLY;INTERVAL=1;FROMCOMP";

            $start = new DateTime("January 1, 2013");
            $due = new DateTime("January 2, 2013");
            $comp = new DateTime("January 5, 2013");

            $newstart = new DateTime("January 11, 2013");
            $newdue = new DateTime("January 12, 2013");
            $newical = $ical;

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }

        public function test_FromCompletion_Yearly()
        {
            $ical = "FREQ=WEEKLY;INTERVAL=1;FROMCOMP";

            $start = new DateTime("January 1, 2013");
            $due = new DateTime("January 2, 2013");
            $comp = new DateTime("January 5, 2013");

            $newstart = new DateTime("January 11, 2013");
            $newdue = new DateTime("January 12, 2013");
            $newical = $ical;

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }

        public function test_FromCompletion_Yearly_WithoutStart()
        {
            $ical = "FREQ=WEEKLY;INTERVAL=1;FROMCOMP";

            $start = 0;
            $due = new DateTime("January 2, 2013");
            $comp = new DateTime("January 5, 2013");

            $newstart = 0;
            $newdue = new DateTime("January 12, 2013");
            $newical = $ical;

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }

        public function test_FastFoward_Daily()
        {
            // $ical = "FREQ=DAILY;INTERVAL=1;FASTFORWARD";

            // TODO: Calculate tomorrow

            // $start = 0;
            // $due = new DateTime("January 2, 2013");
            // $comp = new DateTime("January 10, 2013");

            // $newstart = 0;   
            // $newdue = $tomorrow;
            // $newical = $ical;

            // $array = getNextDates($start,$due,$comp,$ical);

            // $this->assertEquals( $newstart, $array[0] );
            // $this->assertEquals( $newdue, $array[1] );
            // $this->assertEquals( $newical, $array[2] );
        }
    }
?>