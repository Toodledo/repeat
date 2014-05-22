<?php

    //require_once 'PHPUnit/Autoload.php';
    //require_once '../lib_ical.php';

    class ConvertToToodledoTests extends PHPUnit_Framework_TestCase
    {	
        /*
         *  SECTION: Convert to ical
         */
        public function testEveryNDay()
        {
            $repeat = "Every 2 days";
            $ical = "FREQ=DAILY;INTERVAL=2";

            $converted = rep_convertToToodledo($ical,false);

            $this->assertEquals( $repeat, $converted );

            $repeat = "Every 25 days";
            $ical = "FREQ=DAILY;INTERVAL=25";

            $converted = rep_convertToToodledo($ical,false);

            $this->assertEquals( $repeat, $converted );
        }	

		public function testEveryNWeek()
        {
            $repeat = "Every 2 weeks";
            $ical = "FREQ=WEEKLY;INTERVAL=2";

            $converted = rep_convertToToodledo($ical,false);

            $this->assertEquals( $repeat, $converted );

            $repeat = "Every 52 weeks";
            $ical = "FREQ=WEEKLY;INTERVAL=52";

            $converted = rep_convertToToodledo($ical,false);

            $this->assertEquals( $repeat, $converted );
        }	

        public function testEveryNMonth()
        {
            $repeat = "Every 2 months";
            $ical = "FREQ=MONTHLY;INTERVAL=2";

            $converted = rep_convertToToodledo($ical,false);

            $this->assertEquals( $repeat, $converted );

            $repeat = "Every 12 months";
            $ical = "FREQ=MONTHLY;INTERVAL=12";

            $converted = rep_convertToToodledo($ical,false);

            $this->assertEquals( $repeat, $converted );
        }	

        public function testEveryNYear()
        {
            $repeat = "Every 2 years";
            $ical = "FREQ=YEARLY;INTERVAL=2";

            $converted = rep_convertToToodledo($ical,false);

            $this->assertEquals( $repeat, $converted );

            $repeat = "Every 12 years";
            $ical = "FREQ=YEARLY;INTERVAL=12";

            $converted = rep_convertToToodledo($ical,false);

            $this->assertEquals( $repeat, $converted );
        }

        public function testSingularIntervals()
        {
            $converted = rep_convertToToodledo("FREQ=DAILY",false);
            $this->assertEquals( "Every 1 day", $converted );

            $converted = rep_convertToToodledo("FREQ=WEEKLY",false);
            $this->assertEquals( "Every 1 week", $converted );

            $converted = rep_convertToToodledo("FREQ=MONTHLY",false);
            $this->assertEquals( "Every 1 month", $converted );

            $converted = rep_convertToToodledo("FREQ=YEARLY",false);
            $this->assertEquals( "Every 1 year", $converted );
        }
        
        public function testDayOfMonth()
        {            
            $repeat = "The 1st Mon of each month";
            $ical = 'FREQ=MONTHLY;BYDAY=1MO';
            
            $converted = rep_convertToToodledo($ical,false);
            $this->assertEquals( $repeat, $converted );
            
            $repeat = "The 2nd Tue of each month";
            $ical = 'FREQ=MONTHLY;BYDAY=2TU';
            
            $converted = rep_convertToToodledo($ical,false);
            $this->assertEquals( $repeat, $converted );
            
            $repeat = "The 3rd Wed of each month";
            $ical = 'FREQ=MONTHLY;BYDAY=3WE';
            
            $converted = rep_convertToToodledo($ical,false);
            $this->assertEquals( $repeat, $converted );
            
            $repeat = "The 4th Thu of each month";
            $ical = 'FREQ=MONTHLY;BYDAY=4TH';
            
            $converted = rep_convertToToodledo($ical,false);
            $this->assertEquals( $repeat, $converted );

            $repeat = "The last Fri of each month";
            $ical = 'FREQ=MONTHLY;BYDAY=-1FR';
            
            $converted = rep_convertToToodledo($ical,false);
            $this->assertEquals( $repeat, $converted );
        }
        
        public function testEveryX()
        {            
            $repeat = "Every Mon";
            $ical = 'FREQ=WEEKLY;BYDAY=MO';
            
            $converted = rep_convertToToodledo($ical,false);
            $this->assertEquals( $repeat, $converted );
            
            $repeat = "Every Mon, Wed, Fri";
            $ical = 'FREQ=WEEKLY;BYDAY=MO,WE,FR';
            
            $converted = rep_convertToToodledo($ical,false);
            $this->assertEquals( $repeat, $converted );
            
            $repeat = "Every Sat, Sun";
            $ical = 'FREQ=WEEKLY;BYDAY=SU,SA';
            
            $converted = rep_convertToToodledo($ical,false);
            $this->assertEquals( $repeat, $converted );
        }
        
        public function testParent()
        {            
            $repeat = "With Parent";
            $ical = 'PARENT';
            
            $converted = rep_convertToToodledo($ical,false);
            $this->assertEquals( $repeat, $converted );

            $ical = 'PARENT;FROMCOMP';
            
            $converted = rep_convertToToodledo($ical,false);
            $this->assertEquals( $repeat, $converted );

        }
        
        public function testComp()
        {   
            $repeat = "Every 2 days";
            $ical = "FREQ=DAILY;INTERVAL=2;FROMCOMP";

            $converted = rep_convertToToodledo($ical,true);
            $this->assertEquals( $repeat, $converted );

            $repeat = "Every Mon";
            $ical = 'FREQ=WEEKLY;BYDAY=MO;FROMCOMP';
            
            $converted = rep_convertToToodledo($ical,true);
            $this->assertEquals( $repeat, $converted );
        }
        
        public function testFormat() {
            $this->assertEquals("Every 1 day",rep_formatAdvanced("daily"));
            $this->assertEquals("Every 1 week",rep_formatAdvanced("weekly"));
            $this->assertEquals("Every 2 weeks",rep_formatAdvanced("biweekly"));
            $this->assertEquals("Every 1 month",rep_formatAdvanced("monthly"));
            $this->assertEquals("Every 2 months",rep_formatAdvanced("bimonthly"));
            $this->assertEquals("Every 3 months",rep_formatAdvanced("quarterly"));
            $this->assertEquals("Every 6 months",rep_formatAdvanced("semiannually"));
            $this->assertEquals("Every 1 year",rep_formatAdvanced("yearly"));
            $this->assertEquals("With Parent",rep_formatAdvanced("parent"));
            $this->assertEquals("Every Mon",rep_formatAdvanced("every monday"));
            $this->assertEquals("Every Tue",rep_formatAdvanced("every Tu"));
            $this->assertEquals("Every Wed",rep_formatAdvanced("every WED"));
            $this->assertEquals("Every Fri, Sat",rep_formatAdvanced("Every Fri and Sat"));
            $this->assertEquals("Every 5 days",rep_formatAdvanced("EVery 5 days"));
            $this->assertEquals("Every 2 weeks",rep_formatAdvanced("Every 2 weeks"));
            $this->assertEquals("Every 7 months",rep_formatAdvanced("every 7 months"));
            $this->assertEquals("Every 10 years",rep_formatAdvanced("every 10 years"));
            $this->assertEquals("Every weekday",rep_formatAdvanced("weekday"));
            $this->assertEquals("Every weekend",rep_formatAdvanced("weekends"));
            $this->assertEquals("Every Fri",rep_formatAdvanced("each friday"));
            $this->assertEquals("The 1st Mon of each month",rep_formatAdvanced("on the first monday"));
            $this->assertEquals("The 2nd Sat of each month",rep_formatAdvanced("the second sa"));
            $this->assertEquals("The last Fri of each month",rep_formatAdvanced("last fri"));
            $this->assertEquals("The 1st Mon of each month",rep_formatAdvanced("the 1st monay"));
            $this->assertEquals("The 2nd Thu of each month",rep_formatAdvanced("The 2nd thur"));
            $this->assertEquals("The 1st Tue of each month",rep_formatAdvanced("The first tuesday of each month"));
        }

        public function testNormalize() {
            $this->assertEquals("PARENT",rep_normalize(9,""));
            $this->assertEquals("PARENT;FROMCOMP",rep_normalize(109,""));
            $this->assertEquals("PARENT",rep_normalize(0,"With Parent"));
 
            $this->assertEquals("FREQ=DAILY",rep_normalize(4,""));
            $this->assertEquals("FREQ=DAILY;FROMCOMP",rep_normalize(104,""));
            $this->assertEquals("FREQ=DAILY",rep_normalize(4,"Every 1 Week"));
            $this->assertEquals("FREQ=MONTHLY;INTERVAL=2",rep_normalize(6,""));
 
            $this->assertEquals("FREQ=DAILY",rep_normalize(50,"Every 1 Day"));
            $this->assertEquals("FREQ=DAILY;INTERVAL=2",rep_normalize(50,"Every 2 Day"));
            $this->assertEquals("FREQ=MONTHLY;BYDAY=1TU",rep_normalize(50,"The first tuesday of each month"));
            $this->assertEquals("FREQ=WEEKLY;BYDAY=TU",rep_normalize(50,"every Tu"));
            $this->assertEquals("FREQ=WEEKLY;BYDAY=TU;FROMCOMP",rep_normalize(150,"every Tu"));

            $this->assertEquals("FREQ=DAILY",rep_normalize(50,"FREQ=DAILY"));
            $this->assertEquals("FREQ=DAILY;FROMCOMP",rep_normalize(150,"FREQ=DAILY;FROMCOMP"));
            $this->assertEquals("FREQ=WEEKLY;BYDAY=TU;FROMCOMP",rep_normalize(104,"FREQ=WEEKLY;BYDAY=TU;FROMCOMP"));

            $this->assertEquals("FREQ=DAILY",rep_normalize(60,"FREQ=DAILY"));
            $this->assertEquals("FREQ=DAILY;FROMCOMP",rep_normalize(160,"FREQ=DAILY;FROMCOMP"));

            $this->assertEquals("FREQ=WEEKLY;BYDAY=TU",rep_normalize(150,"every Tu",false));
            $this->assertEquals("FREQ=DAILY",rep_normalize(160,"FREQ=DAILY;FROMCOMP",false));
            $this->assertEquals("",rep_normalize(9,"",false));

            $this->assertEquals("FREQ=MONTHLY;BYDAY=-1FR",rep_normalize(60,"FREQ=MONTHLY;BYDAY=-1FR"));

            $this->assertEquals("FREQ=MONTHLY;INTERVAL=3;FROMCOMP",rep_normalize(50,"FREQ=MONTHLY;INTERVAL=3;FROMCOMP"));

        }

        public function testDisplay() {
            $this->assertEquals("With Parent",rep_display(9,""));
            $this->assertEquals("With Parent",rep_display(109,""));
            $this->assertEquals("With Parent",rep_display(0,"With Parent"));
 
            $this->assertEquals("Daily",rep_display(4,""));
            $this->assertEquals("Daily",rep_display(104,""));
            $this->assertEquals("Daily",rep_display(4,"Every 1 Week"));
            $this->assertEquals("Bimonthly",rep_display(6,""));
 
            $this->assertEquals("Daily",rep_display(50,"Every 1 Day"));
            $this->assertEquals("Every 2 days",rep_display(50,"Every 2 Day"));
            $this->assertEquals("The 1st Tue of each month",rep_display(50,"The first tuesday of each month"));
            $this->assertEquals("Every Tue",rep_display(50,"every Tu"));
            $this->assertEquals("Every Tue",rep_display(150,"every Tu"));

            $this->assertEquals("Daily",rep_display(50,"FREQ=DAILY"));
            $this->assertEquals("Daily",rep_display(50,"FREQ=DAILY;FROMCOMP"));
            $this->assertEquals("Every Tue",rep_display(4,"FREQ=WEEKLY;BYDAY=TU;FROMCOMP"));

            $this->assertEquals("Daily",rep_display(60,"FREQ=DAILY"));
            $this->assertEquals("Daily",rep_display(160,"FREQ=DAILY;FROMCOMP"));

            $this->assertEquals("None",rep_display(0,""));
            $this->assertEquals("None",rep_display(50,""));
            $this->assertEquals("None",rep_display(60,""));
            $this->assertEquals("None",rep_display(60,"FREQ="));
        }
    }
?>