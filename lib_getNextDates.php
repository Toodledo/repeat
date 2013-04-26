<?php

	require_once '/lib_ical.php';	// Include iCal converter
	require_once '../When/When.php';	// Include When library	
	
	// Get rrule string
	$rr = icalRepeatAdvanced("every 1 week");	
	
	$start = new DateTime();
	$due = new DateTime();
	$comp = new DateTime();
	
	//echo $rr;
	
	echo getNextDates( $start, $due, $comp, $rr );
	
	function getNextDates($start,$due,$comp,$rrule)		
	{	
		$when = new When();
		$count = ";COUNT=2";
		
		$rrule .= $count;
		
		echo $rrule;
	
		$when->recur($start)->rrule($rrule);
		
		echo "<br/>";
		echo $when->next()->format("m/d/Y");
		echo "<br/>";
		echo $when->next()->format("m/d/Y");
		echo "<br/>";
		echo $when->next()->format("m/d/Y");
	
		// TODO
		//return array($newstart,$newdue,$rrule);
	}
?>
