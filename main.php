<?php
    
    require_once 'lib_ical.php';    
    
    // Get rrule string
    $rr = convertToRRule("Every 2 months", false);	

    // Until Jan. 30, 2013
    $rr = "FREQ=WEEKLY;UNTIL=20130130T000000Z";

    echo $rr;
    echo "<br/>";

    $start = new DateTime( 'January 21, 2013' ); 
    $due = new DateTime('January 22, 2013');

    $comp = new DateTime('January 3, 2013');
    
    echo "start: ".$start->format("m/d/Y");
    echo "<br />";
    echo "due: ".$due->format("m/d/Y");
    echo "<br />";    
    
    $newDates = getNextDates( $start, $due, $comp, $rr );

    if( $newDates[0] === -1 )
    {
        echo "No next occurrence.";
    }
    else
    {
        echo "new start: ".$newDates[0]->format("m/d/Y");
        echo "<br />";
        echo "new due: ".$newDates[1]->format("m/d/Y");
        echo "<br />";       

        if( $newDates[2] == "" )
        {
            echo "No next occurrence.";
        }
        else
        {
            echo $newDates[2];
        }
    }

?>