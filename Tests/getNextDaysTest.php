<?php

    //require_once 'PHPUnit/Autoload.php';
    //require_once '../lib_ical.php';

    class RepeatTest2 extends PHPUnit_Framework_TestCase
    {
        /*
            TESTS FOR BAD INPUTS
        */

        public function test_BadParams()
        {
            $ical = "FREQ=DAILY;INTERVAL=1";

            $start = "asdf";
            $due = "efhg";
            $comp = 0;

            $newstart = 0;
            $newdue = 0;

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        public function test_BadParams2()
        {
            $ical = "FREQ=DAILY;INTERVAL=1";

            $start = new DateTime("January 1, 2012");
            $due = "efhg";
            $comp = 0;

            $newstart = new DateTime("January 2, 2012");
            $newdue = 0;

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        public function test_BadParams3()
        {
            $ical = "FREQ=DAILY;INTERVAL=1";

            $start = new DateTime("January 1, 2012");
            $due = new DateTime("January 10, 2012");;
            $comp = 4.5;
            
            $newstart = new DateTime("January 2, 2012");
            $newdue = new DateTime("January 11, 2012");

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        public function test_BadParams4()
        {
            $ical = "ASDF;FREQ=DAILY;INTERVAL=1";

            $start = new DateTime("January 1, 2012");
            $due = new DateTime("January 10, 2012");
            $comp = 0;

            //TODO: if the RRULE is invalid, then clear the rule, but keep the dates unchanged
            $newical = "";

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $start, $array[0] );
            $this->assertEquals( $due, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }


        /*
            TESTS FOR SIMPLE INTERVALS (EVERY X T)
        */


        public function test_AllOptionalParams()
        {
            $ical = "FREQ=DAILY;INTERVAL=1";

            $start = 0;
            $due = 0;
            $comp = 0;

            $newstart = 0;
            $newdue = new DateTime(date('Y-m-d H:i:s', strtotime('now + 24 hours')));            

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        public function test_UnixTimestamp_SimpleDaily()
        {
            $ical = "FREQ=DAILY;INTERVAL=1";

            $start = 0;
            $due = 1325404800;  // Jan 1, 2012 PDT
            $comp = 0;

            $newstart = 0;
            $newdue = new DateTime("January 2, 2012");

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );            
        }

        public function test_UnixTimestamp_SimpleDaily40()
        {
            $ical = "FREQ=DAILY;INTERVAL=40";

            $start = 0;
            $due = 1325404800;  // Jan 1, 2012 PDT
            $comp = 0;

            $newstart = 0;
            $newdue = new DateTime("February 10, 2012");

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );            
        }

         public function test_UnixTimestamp_SimpleWeekly()
        {
            $ical = "FREQ=WEEKLY;INTERVAL=2";

            $start = 0;
            $due = 1325404800;  // Jan 1, 2012 PDT
            $comp = 0;

            $newstart = 0;
            $newdue = new DateTime("January 15, 2012");

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );            
        }

        public function test_UnixTimestamp_SimpleYearly()
        {
            $ical = "FREQ=YEARLY;INTERVAL=1";

            $start = 0;
            $due = 1325404800;  // Jan 1, 2012 PDT
            $comp = 0;

            $newstart = 0;
            $newdue = new DateTime("January 1, 2013");

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

       
        public function test_SimpleYearly_Both()
        {
            $ical = "FREQ=YEARLY;INTERVAL=2";

            $start = new DateTime("January 1, 2013");
            $due = new DateTime("January 2, 2013");;
            $comp = 0;

            $newstart = new DateTime("January 1, 2015");
            $newdue = new DateTime("January 2, 2015");;                

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        /*
            TESTS FOR MONTHLY BY DAY (ON THE X D OF THE MONTH)
        */

         public function test_MonthlyByDay1()
        {
            // Every 2nd Tuesday
            $ical = 'FREQ=MONTHLY;BYDAY=2TU';

            $start = new DateTime("January 8, 2013");
            $due = new DateTime("January 12, 2013");
            $comp = 0;

            $newstart = new DateTime("February 8, 2013");
            $newdue = new DateTime("February 12, 2013");

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        public function test_MonthlyByDay2()
        {
            // Every 3nd Sunday
            $ical = 'FREQ=MONTHLY;BYDAY=3SU';

            $start = new DateTime("January 20, 2013");
            $due = new DateTime("January 21, 2013");
            $comp = 0;

            $newstart = new DateTime("February 16, 2013");
            $newdue = new DateTime("February 17, 2013");

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }
        
        public function test_MonthlyByDayLast()
        {
            // Every last Wed
            $ical = 'FREQ=MONTHLY;BYDAY=-1WE';

            $start = new DateTime("January 20, 2013");
            $due = new DateTime("January 21, 2013");
            $comp = 0;

            $newstart = new DateTime("January 29, 2013");
            $newdue = new DateTime("January 30, 2013");

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        /*
            TESTS FOR BY WEEK (EVERY W)
        */

        public function test_WeeklyByDay1()
        {
            // Every Tuesday
            $ical = 'FREQ=WEEKLY;BYDAY=TU';

            $start = new DateTime("January 1, 2013");
            $due = new DateTime("January 5, 2013");
            $comp = 0;

            //TODO: always reschedule the due-date
            $newstart = new DateTime("January 4, 2013");
            $newdue = new DateTime("January 8, 2013");

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        public function test_WeeklyByDay2()
        {
            // Every Friday
            $ical = 'FREQ=WEEKLY;BYDAY=FR';

            $start = new DateTime("January 4, 2013");
            $due = new DateTime("January 12, 2013");
            $comp = 0;

            //TODO: rescheduled from due
            $newstart = new DateTime("January 10, 2013");
            $newdue = new DateTime("January 18, 2013");

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        public function test_WeeklyByDayWeekend1()
        {
            // Every weekend
            $ical = 'FREQ=WEEKLY;BYDAY=SU,SA';

            $start = 0;
            $due = new DateTime("January 12, 2013");
            $comp = 0;

            //TODO: rescheduled from due
            $newstart = 0;
            $newdue = new DateTime("January 13, 2013");

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }
        
        public function test_WeeklyByDayWeekend2()
        {
            // Every weekend
            $ical = 'FREQ=WEEKLY;BYDAY=SU,SA';

            $start = 0;
            $due = new DateTime("January 13, 2013");
            $comp = 0;

            //TODO: rescheduled from due
            $newstart = 0;
            $newdue = new DateTime("January 19, 2013");

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }


        public function test_WeeklyByWeekday()
        {
            // Every weekday
            $ical = 'FREQ=WEEKLY;BYDAY=MO,TU,WE,TH,FR';

            $start = new DateTime("January 4, 2013");
            $due = new DateTime("January 12, 2013");
            $comp = 0;

            //TODO: rescheduled from due
            $newstart = new DateTime("January 6, 2013");
            $newdue = new DateTime("January 14, 2013");

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }



        /*

            TESTS FOR UNTIL

        */



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

            $newstart = -1;
            $newdue = -1;
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

            $newstart = -1;
            $newdue = -1;
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

            $newstart = -1;
            $newdue = -1;
            $newical = '';

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }

         public function test_YearlyUntil_LastOccurrenceNoStart()
        {
            // Repeat until Jan. 1, 2014
            $ical = "FREQ=YEARLY;UNTIL=20140101T000000Z";

            $start = 0;
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

         public function test_YearlyUntil_LastOccurrenceNoDue()
        {
            // Repeat until Jan. 1, 2014
            $ical = "FREQ=YEARLY;UNTIL=20140101T000000Z";

            $start = new DateTime("January 1, 2013");
            $due = 0;
            $comp = 0;

            $newstart = new DateTime("January 1, 2014");
            $newdue = 0;
            $newical = '';

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }


        /*
            TESTS FOR FROM COMP
        */

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

        // TODO: Fix when Monthly works
        // public function test_FromCompletion_Monthly()
        // {
        //     $ical = "FREQ=MONTHLY;INTERVAL=1;FROMCOMP";

        //     $start = new DateTime("January 1, 2013");
        //     $due = new DateTime("January 2, 2013");
        //     $comp = new DateTime("January 5, 2013");

        //     $newstart = new DateTime("February 4, 2013");
        //     $newdue = new DateTime("February 5, 2013");
        //     $newical = $ical;

        //     $array = getNextDates($start,$due,$comp,$ical);

        //     $this->assertEquals( $newstart, $array[0] );
        //     $this->assertEquals( $newdue, $array[1] );
        //     $this->assertEquals( $newical, $array[2] );
        // }

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
            $ical = "FREQ=YEARLY;INTERVAL=1;FROMCOMP";

            $start = new DateTime("January 1, 2013");
            $due = new DateTime("January 2, 2013");
            $comp = new DateTime("January 5, 2013");

            $newstart = new DateTime("January 4, 2014");
            $newdue = new DateTime("January 5, 2014");
            $newical = $ical;

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }

        public function test_FromCompletion_Yearly_WithoutStart()
        {
            $ical = "FREQ=YEARLY;INTERVAL=1;FROMCOMP";

            $start = 0;
            $due = new DateTime("January 2, 2011");
            $comp = new DateTime("January 5, 2013");

            $newstart = 0;
            $newdue = new DateTime("January 5, 2014");

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        public function test_FromCompletion_Yearly_WithoutDue()
        {
            $ical = "FREQ=YEARLY;INTERVAL=1;FROMCOMP";

            $start = new DateTime("January 2, 2011");
            $due = 0;
            $comp = new DateTime("January 5, 2013");

            $newstart = new DateTime("January 5, 2014");
            $newdue = 0;

            $array = getNextDates($start,$due,$comp,$ical);

            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        /*
            TESTS FOR FASTFORWARD
        */

        public function test_FastFoward_Daily()
        {
            $ical = "FREQ=DAILY;INTERVAL=1;FASTFORWARD";

            $today = new DateTime(date('Y-m-d'));
            $tomorow = new DateTime(date('Y-m-d', strtotime('today + 1 day')));

            $start = new DateTime("January 1, 2013");
            $due = new DateTime("January 2, 2013");
            $comp = new DateTime("January 5, 2013");

            $array = getNextDates($start,$due,$comp,$ical);
            
            $this->assertEquals( $today, $array[0] );
            $this->assertEquals( $tomorow, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        public function test_FastFoward_Weekly()
        {
            $ical = "FREQ=WEEKLY;INTERVAL=1;FASTFORWARD";

            $today = new DateTime(date('Y-m-d'));
            
            $start = new DateTime("January 1, 2013"); //tue
            $due = new DateTime("January 2, 2013"); //wed
            $comp = 0;

            // This takes Jan 2 and moves it foward a week at a time until it is not in the past
            $array = getNextDates($start,$due,$comp,$ical);
            
            //$this->assertGreaterThan( $today, $array[0] );
            $this->assertGreaterThan( $today, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        // TODO: Fix when Monthly works, remove BYMONTHDAY=1
        // public function test_FastFoward_Monthly()
        // {
        //     // Current, tests to make sure the due date is in the future
        //     // TODO: Does this need to test specific recurrence dates in the future?
        //     //       Or just make sure it's in the future?

        //     $ical = "FREQ=MONTHLY;INTERVAL=1;FASTFORWARD";

        //     $today = new DateTime(date('Y-m-d H:i:s'));

        //     $start = new DateTime("January 1, 2013");
        //     $due = new DateTime("January 2, 2013");
        //     $comp = new DateTime("January 5, 2013");

        //     $array = getNextDates($start,$due,$comp,$ical);
            
        //     $this->assertGreaterThan( $today, $array[1] );
        //     $this->assertEquals( $ical, $array[2] );
        // }

        public function test_FastFoward_Yearly()
        {
            // TODO: FINISH
            $ical = "FREQ=YEARLY;INTERVAL=1;FASTFORWARD";

            $todayPlusOneYear = new DateTime(date('Y-m-d', strtotime("today + 1 year")));

            $start = new DateTime("January 1, 2010");
            $due = new DateTime("January 2, 2010");
            $comp = 0;

            $array = getNextDates($start,$due,$comp,$ical);
        
            //TODO: it should skip 2011 and 2012 and 2013 because they are in the past and go to Jan 1/2 2014
            $this->assertEquals( , $array[1]->format("Y") );
            $this->assertEquals( $ical, $array[2] );
        }

        /*
            TESTS FOR COUNT
        */

        public function test_Count_FastFoward_Daily()
        {
            $ical = "FREQ=DAILY;INTERVAL=1;COUNT=5";


            $start = new DateTime("January 1, 2012");
            $due = new DateTime("January 2, 2012");
            $comp = 0;

            $newstart = new DateTime("January 2, 2012"); 
            $newdue = new DateTime("January 3, 2012");
            $newical = "FREQ=DAILY;INTERVAL=1;COUNT=4";

            $array = getNextDates($start,$due,$comp,$ical);
            
            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }

        public function test_Count_FastFoward_DailyLastTime()
        {
            $ical = "FREQ=WEEKLY;INTERVAL=1;COUNT=1";


            $start = new DateTime("January 1, 2012");
            $due = new DateTime("January 2, 2012");
            $comp = 0;

            $newstart = new DateTime("January 8, 2012"); 
            $newdue = new DateTime("January 9, 2012");
            $newical = "";

            $array = getNextDates($start,$due,$comp,$ical);
            
            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }

        public function test_Count_FastFoward_Yearly()
        {
            $ical = "FREQ=YEARLY;INTERVAL=1;FASTFORWARD;COUNT=5";

            $start = new DateTime("January 1, 1987");
            $due = new DateTime("January 2, 1987");
            $comp = new DateTime("January 5, 1987");

            $newstart = -1;
            $newdue = -1;
            $newical = "";

            $array = getNextDates($start,$due,$comp,$ical);
            
            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $newical, $array[2] );
        }


      /*

            TESTS FOR HOLIDAYS AND COMPLEXT BEHAVIOR

        */


        public function test_US_Holiday_MothersDay()
        {
            // --- Mothers Day ------------------------- //
            $ical = "FREQ=YEARLY;BYDAY=2SU;BYMONTH=5";

            $start = 0;
            $due = new DateTime("May 13, 2012");
            $comp = 0;

            $newstart = 0;
            $newdue = new DateTime("May 12, 2013");

            $array = getNextDates($start,$due,$comp,$ical);
            
            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        public function test_US_Holiday_FathersDay()
        {
            // --- Fathers Day ------------------------ //
            $ical = "FREQ=YEARLY;BYDAY=3SU;BYMONTH=6";

            $start = 0;
            $due = new DateTime("June 17, 2012");
            $comp = 0;

            $newstart = 0;
            $newdue = new DateTime("June 16, 2013");

            $array = getNextDates($start,$due,$comp,$ical);
            
            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        public function test_US_Holiday_IndependenceDay()
        {
            // --- Independence Day --------------------- //
            $ical = "FREQ=YEARLY;BYMONTHDAY=4;BYMONTH=7";

            $start = 0;
            $due = new DateTime("July 4, 2012");
            $comp = 0;

            $newstart = 0;
            $newdue = new DateTime("July 4, 2013");

            $array = getNextDates($start,$due,$comp,$ical);
            
            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }

        public function test_US_Holiday_Christmas()
        {
            // --- Christmas Day ------------------------ //
            $ical = "FREQ=YEARLY;BYMONTHDAY=25;BYMONTH=12";

            $start = 0;
            $due = new DateTime("Dec 25, 2012");
            $comp = 0;

            $newstart = 0;
            $newdue = new DateTime("Dec 25, 2013");

            $array = getNextDates($start,$due,$comp,$ical);
            
            $this->assertEquals( $newstart, $array[0] );
            $this->assertEquals( $newdue, $array[1] );
            $this->assertEquals( $ical, $array[2] );
        }
    }
?>