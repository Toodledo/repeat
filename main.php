<?php
    
    require_once 'lib_ical.php';    
    
    // Get rrule string
    $rr = convertToRRule("Every 2 months", false);	

    echo $rr;
    echo "<br/>";        

    $start = new DateTime( 'January 1, 2013' ); 
    $due = new DateTime('January 5, 2013');

    $comp = new DateTime('January 3, 2013');
    
    echo "start: ".$start->format("m/d/Y");
    echo "<br />";
    echo "due: ".$due->format("m/d/Y");
    echo "<br />";
    
    
    $newDates = getNextDates( $start, $due, $comp, $rr );
        
    echo "new start: ".$newDates[0]->format("m/d/Y");
    echo "<br />";
    echo "new due: ".$newDates[1]->format("m/d/Y");

?>