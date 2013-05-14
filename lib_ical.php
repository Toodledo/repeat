<?php

require_once 'When.php';	// Include When library	

function getNextDates($start,$due,$comp,$rrule)		
{
	$noNextOccur = array(-1,-1,"");

	// If start and due are timestamps, convert them to dates
	if($start !== 0)
	{
		if(is_int($start)) 
		{
			$s = new DateTime();
			$s->setTimestamp($start);
			$start = $s;
		}

		if(!($start instanceof DateTime)) return $noNextOccur;
	}

	if($due !== 0)
	{
		if(is_int($due)) 
		{
			$d = new DateTime();
			$d->setTimestamp($due);
			$due = $d;
		}

		if(!($due instanceof DateTime)) return $noNextOccur;
	}	

	if($comp !== 0)
	{
		if(is_int($comp)) 
		{
			$d = new DateTime();
			$d->setTimestamp($comp);
			$comp = $d;
		}

		if(!($comp instanceof DateTime)) return $noNextOccur;
	}	

    $rs = new When();
    $rd = new When();
    $rc = new When();    
            
    $newstart = null;
    $newdue = null;
    $newrrule = $rrule;

    $today = new DateTime(date('Y-m-d H:i:s'));

    // Optional Rules
    $fromComp = false;
    $fastFoward = false;    

    $match = array();
    preg_match("/(FROMCOMP[;]?)/i",$rrule,$match);            
    $fromComp = !empty($match[1]) && !empty($comp);    

    $match = array();
    preg_match("/(FASTFORWARD[;]?)/i",$rrule,$match);            
    $fastFoward = !empty($match[1]);    

    if( $fromComp ) $rrule = preg_replace("/FROMCOMP[;]?/i", "", $newrrule);
    if( $fastFoward ) $rrule = preg_replace("/FASTFORWARD[;]?/i", "", $newrrule);
    
    // Calculate START date
    if( $start === 0)
    {
        $newstart = 0;
    }
    else   
    {
        if($fromComp)  // FLAG: FROMCOMP
        {
        	if( $due === 0)
        	{
        		try
        		{
	        		$rc->recur($comp)->rrule($rrule);
        		}
        		catch(Exception $e)
        		{
        			return $noNextOccur;
        		}

				$d = $rc->next();
				$d = $rc->next();

		        // No next occuraence
		        if( !( $d instanceof DateTime) )
		        {
		        	return $noNextOccur;
		        }

		        // No next occurence after returned one
	            if( !( $rc->next() instanceof DateTime ) )
	            {
	            	$newrrule = "";
	            }

				$newstart = $d;
        	}

        	// ELSE:
        	// Lazy calculate based on new due date calculation below
        }
        elseif ($fastFoward)	// FLAG: FASTFOWARD
        {
        	if( $due === 0)
        	{        		
        		try
        		{
	        		$rs->recur($start)->rrule($rrule);
        		}
        		catch(Exception $e)
        		{
        			return $noNextOccur;
        		}

        		$d = null;
        		while( true )
		        {
		        	$d = $rs->next();
		            if(!($d instanceof DateTime))
		            {
		                return $noNextOccur;
		            }
		            elseif ($d > $today)
		            {
		            	break;
		            }
		        }

		        // No next occurence after returned one
	            if( !( $rs->next() instanceof DateTime ) )
	            {
	            	$newrrule = "";
	            }

		        $newstart = $d;
        	}
        	else
        	{
        		try
        		{
        			$rs->recur($start)->rrule($rrule);
        		}
        		catch(Exception $e)
        		{
        			return $noNextOccur;
        		}

        		$diff = $start->diff($due);
        		$d = null;
        		$nd = null;
        		while( true )
		        {
		        	$d = $rs->next();

		            if(!($d instanceof DateTime))
		            {
		                return $noNextOccur;
		            }

		            $nd = clone $d;
		        	$nd->add($start->diff($due));

		            if ($nd > $today)
		            {
		            	break;
		            }
		        }

		        // No next occurence after returned one
	            if( !( $rs->next() instanceof DateTime ) )
	            {
	            	$newrrule = "";
	            }

		        $newstart = $d;
		        $newdue = $nd;
        	}
        }
        else
        {            
    		try
    		{
    			$rs->recur($start)->rrule($rrule);
    		}
    		catch(Exception $e)
    		{
    			return $noNextOccur;
    		}

            $d = $rs->next();
            $d = $rs->next();

            // No next occuraence
            if( !( $d instanceof DateTime) )
            {
            	return $noNextOccur;
            }

            // No next occurence after returned one
            if( !( $rs->next() instanceof DateTime ) )
            {
            	$newrrule = "";
            }

            $newstart = clone $d;
        }
    }
        
    // Calculate DUE date
    if( $due === 0)
    {
        $newdue = 0;
    }
    elseif($fromComp)	// FLAG: FROMCOMP
    {
    	try
		{
			$rc->recur($comp)->rrule($rrule);
		}
		catch(Exception $e)
		{
			return $noNextOccur;
		}

		$d = $rc->next();
		$d = $rc->next();

        // No next occuraence
        if( !( $d instanceof DateTime) )
        {
        	return $noNextOccur;
        }

        // No next occurence after returned one
        if( !( $rc->next() instanceof DateTime ) )
        {
        	$newrrule = "";
        }		

        $newdue = $d;

        // Lazy calculation of new start if not 0
		if($start !== 0)
		{
			$newstart = clone $newdue;
			$newstart->sub($start->diff($due));
		}		
    }
    elseif($fastFoward)	// FLAG: FASTFOWARD
    {
    	if($start === 0)
    	{
    		try
			{
				$rd->recur($due)->rrule($rrule);
			}
			catch(Exception $e)
			{
				return $noNextOccur;
			}

	    	// Loop until future date if it exists
	    	$d = null;
			while( true )
	        {
	        	$d = $rd->next();
	            if(!($d instanceof DateTime))
	            {
	                return $noNextOccur;
	            }
	            elseif ($d > $today)
	            {
	            	break;
	            }            
	        }      

	        $newdue = $d;
    	}
    }
    else
    {
        if($start === 0)
        {
        	try
			{
				$rd->recur($due)->rrule($rrule);
			}
			catch(Exception $e)
			{
				return $noNextOccur;
			}

			$d = $rd->next();
            $d = $rd->next();

    		// No next occuraence
            if( !( $d instanceof DateTime) )
            {
            	return $noNextOccur;
            }

            // No next occurence after returned one
            if( !( $rd->next() instanceof DateTime ) )
            {
            	$newrrule = "";
            }
            
            $newdue = clone $d;
        }
        else
        {
        	$newdue = clone $newstart;
        	$newdue->add($start->diff($due));
        }
    }   

    return array($newstart,$newdue,$newrrule);
} 

//converts our repeat strings into iCal RRULEs
function convertToRRule($text, $fromcomp) 
{
	$repeat = '';
	$text = trim(strtolower($text));
	
	$every = strpos($text,'every');
	$each = strpos($text,'each');
	
	if(($every!==FALSE && $every<5) || ($each!==FALSE && $each<5)){ // Every|Each X T
	
		preg_match("/[a-z]* ([0-9]*)([a-z ,]*)/i",$text,$match);		
		if(empty($match[1])) $match[1] = 1;	// X
		if(empty($match[2])) return "";		// T
			
		$repeat = "WEEKLY;BYDAY=";
		$and = '';

		if(strpos($match[2],'sun')!==FALSE) { $repeat .= $and."SU"; $and=","; }
		if(strpos($match[2],'mon')!==FALSE) { $repeat .= $and."MO"; $and=","; }
		if(strpos($match[2],'tue')!==FALSE) { $repeat .= $and."TU"; $and=","; }
		if(strpos($match[2],'wed')!==FALSE) { $repeat .= $and."WE"; $and=","; }
		if(strpos($match[2],'thu')!==FALSE) { $repeat .= $and."TH"; $and=","; }
		if(strpos($match[2],'fri')!==FALSE) { $repeat .= $and."FR"; $and=","; }
		if(strpos($match[2],'sat')!==FALSE) { $repeat .= $and."SA"; $and=","; }
		
		if(strpos($match[2],'day')!==FALSE && empty($and)) $repeat="DAILY";
		else if(strpos($match[2],'week')!==FALSE) $repeat="WEEKLY";
		else if(strpos($match[2],'month')!==FALSE) $repeat="MONTHLY";
		else if(strpos($match[2],'year')!==FALSE) $repeat="YEARLY";
		
		if($match[1]>1) $repeat.=";INTERVAL=".$match[1];
		
		if(strpos($match[2],'weekday')!==FALSE) $repeat = "WEEKLY;BYDAY=MO,TU,WE,TH,FR";
		else if(strpos($match[2],'weekend')!==FALSE) $repeat = "WEEKLY;BYDAY=SU,SA";
		
		$repeat = "FREQ=" . $repeat;
		
	} else { //on the ...
		
		if(strpos($text,'first')!==FALSE) $num = 1;
		else if(strpos($text,'second')!==FALSE) $num = 2;
		else if(strpos($text,'third')!==FALSE) $num = 3;
		else if(strpos($text,'fourth')!==FALSE) $num = 4;
		else if(strpos($text,'fifth')!==FALSE) $num = 5;
		else if(strpos($text,'last')!==FALSE) $num = -1;
		else {
			preg_match("/[a-z ]* ([0-9]*)([a-z ]*)/i",$text,$match);
			
			if(empty($match[0])) return convertToRRule("Every ".$text);
			if(empty($match[1])) return convertToRRule("Every ".$match[2]);
			$num = $match[1];
		}
		
		if(strpos($text,'mon')!==FALSE) $day = "MO";
		if(strpos($text,'tue')!==FALSE) $day = "TU";
		else if(strpos($text,'wed')!==FALSE) $day = "WE";
		else if(strpos($text,'thu')!==FALSE) $day = "TH";
		else if(strpos($text,'fri')!==FALSE) $day = "FR";
		else if(strpos($text,'sat')!==FALSE) $day = "SA";
		else if(strpos($text,'sun')!==FALSE) $day = "SU";
			
		if($num>5) $num=-1;
		
		$repeat = "FREQ=MONTHLY;BYDAY=".$num.$day;
	}
	
   if($fromcomp) $repeat.=";FROMCOMP";
        
	return $repeat;
}

//converts iCal RRULEs back into our repeat strings
function iCalReverseRepeat($freq, $interval, $byday) {
	$ret = "";	
	
	if($freq=="DAILY") {
		if(empty($interval)) {
			$ret = "every day";
		} else { 
			$ret = "every ".makeDecimal($interval)." days";
		}
	} else if($freq=="WEEKLY") {
	
		if(!empty($interval)) {
			$ret = "every ".makeDecimal($interval)." weeks";
		} else if(!empty($byday)) {
			$ret = "every ".$byday;
		} else { 
			$ret = "every week";
		}
		
	} else if($freq=="MONTHLY") {
		if(!empty($interval)) {
			$ret = "every ".makeDecimal($interval)." months";
		} else if(!empty($byday)) {
			$day = substr($byday,-2);
			$num = str_replace($day,"",$byday);
			$ret = "on the ".$num." ".$day;
		} else { 
			$ret = "every month";
		}

	} else if($freq=="YEARLY") {
		if(empty($interval)) {
			$ret = "every year";
		} else { 
			$ret = "every ".makeDecimal($interval)." year";
		}
	} 
	
	$ret = formatRepeat($ret);
	
	return $ret;
}

?>