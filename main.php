<?php
    
    require_once 'lib_ical.php';    
    
    // Get rrule string
    $rr = convertToRRule("Every Week", true);
    $rr = "FREQ=WEEKLY;INTERVAL=1;FROMCOMP";	

    echo $rr;
    echo "<br/>";
    $start = new DateTime( 'January 1, 2013' ); 
    $due = new DateTime('January 2, 2013');
    $comp = new DateTime('January 5, 2013');
    
    echo "start: ".$start->format("m/d/Y");
    echo "<br />";
    echo "due: ".$due->format("m/d/Y");
    echo "<br />";    
    echo "comp: ".$comp->format("m/d/Y");
    echo "<br />";    

    //$due = 0;
    
    $newDates = getNextDates( $start, $due, $comp, $rr );

    if( $newDates[0] === -1 )
    {
        echo "No next occurrence.";
    }
    else
    {
        //echo $newDates[0] === null ? "true" : "false";

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