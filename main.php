<?php
    
    require_once 'lib_ical.php';    
    
    // Get rrule string
    $rr = icalRepeatAdvanced("Every Fri");	

    echo $rr;
    echo "<br/>";        

    $start = new DateTime( 'January 1, 2013' ); 
    $due = new DateTime('January 5, 2013');

    $comp = new DateTime('January 3, 2013');
    
    echo $start->format("m/d/Y");
    echo "<br />";
    echo $due->format("m/d/Y");
    echo "<br />";
    
    
    $newDates = getNextDates( $start, $due, $comp, $rr.=";UNTIL=20130131T090000Z" );
        
    echo $newDates[0]->format("m/d/Y");
    echo "<br />";
    echo $newDates[1]->format("m/d/Y");

?>