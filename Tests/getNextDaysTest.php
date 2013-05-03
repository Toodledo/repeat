<?php


    class RepeatTest2 extends PHPUnit_Framework_TestCase
    {	       
        /*
         *  SECTION: GetNextDates()
         */
        public function testSimpleDaily()
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

	 	public function testSimpleWeekly()
        {
                $ical = "FREQ=WEEKLY;INTERVAL=2";

                $start = 0;
                $due = new DateTime("January 1, 2013");
                $comp = 0;			

                $newstart = 0;
                $newdue = new DateTime("January 15, 2013");

                $array = getNextDates($start,$due,$comp,$ical);

                $this->assertEquals( $newstart, $array[0] );
                $this->assertEquals( $newdue, $array[1] );
                $this->assertEquals( $ical, $array[2] );
        }
        
        public function testSimpleMonthly()
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

        public function testSimpleYearly()
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
    }
?>