<?php

    require_once 'PHPUnit/Autoload.php';
    require_once '../lib_ical.php';

    class RepeatTest extends PHPUnit_Framework_TestCase
    {	
        /*
         *  SECTION: Convert to ical
         */
        public function testEveryNDay()
        {
            $repeat = "Every 2 days";
            $ical = "FREQ=DAILY;INTERVAL=2";

            $converted = convertToRRule($repeat,false);

            $this->assertEquals( $ical, $converted );
        }	

        public function testEveryNMonth()
        {
            $repeat = "Every 2 months";
            $ical = "FREQ=MONTHLY;INTERVAL=2";

            $converted = convertToRRule($repeat,false);

            $this->assertEquals( $ical, $converted );
        }	

        public function testEveryNYear()
        {
            $repeat = "Every 2 years";
            $ical = "FREQ=YEARLY;INTERVAL=2";

            $converted = convertToRRule($repeat,false);

            $this->assertEquals( $ical, $converted );
        }
        
        public function testDayOfMonth()
        {            
            $repeat = "On the 1 monday of each month";
            $ical = 'FREQ=MONTHLY;BYDAY=1MO';
            
            $converted = convertToRRule($repeat,false);
            $this->assertEquals( $ical, $converted );
            
            $repeat = "On the 2 tues of each month";
            $ical = 'FREQ=MONTHLY;BYDAY=2TU';
            
            $converted = convertToRRule($repeat,false);
            $this->assertEquals( $ical, $converted );
            
            $repeat = "On the 3 wed of each month";
            $ical = 'FREQ=MONTHLY;BYDAY=3WE';
            
            $converted = convertToRRule($repeat,false);
            $this->assertEquals( $ical, $converted );
            
            $repeat = "On the 4 thursday of each month";
            $ical = 'FREQ=MONTHLY;BYDAY=4TH';
            
            $converted = convertToRRule($repeat,false);
            $this->assertEquals( $ical, $converted );            
        }
        
        public function testEveryX()
        {            
            $repeat = "Every monday";
            $ical = 'FREQ=WEEKLY;BYDAY=MO';
            
            $converted = convertToRRule($repeat,false);
            $this->assertEquals( $ical, $converted );
            
            $repeat = "Every mon, wed, fri";
            $ical = 'FREQ=WEEKLY;BYDAY=MO,WE,FR';
            
            $converted = convertToRRule($repeat,false);
            $this->assertEquals( $ical, $converted );
            
            $repeat = "Every weekend";
            $ical = 'FREQ=WEEKLY;BYDAY=SU,SA';
            
            $converted = convertToRRule($repeat,false);
            $this->assertEquals( $ical, $converted );
            
            $repeat = "Every weekday";
            $ical = 'FREQ=WEEKLY;BYDAY=MO,TU,WE,TH,FR';
            
            $converted = convertToRRule($repeat,false);
            $this->assertEquals( $ical, $converted );
        }
    }
?>