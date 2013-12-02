<?php

require_once 'When.php';	// Include When library

/*
	Given a start date, duedate, completion date and rrule string, finds and returns the new values.

	$start = unix timestamp or DateTime object
	$due =  unix timestamp or DateTime object
	$comp =  unix timestamp or DateTime object
	$rrule = string

	returns array(newStart, newDue, newiCal)
*/
function rep_getNextDates($start,$due,$comp,$rrule)		
{
	$noNextOccur = array(-1,-1,"");
	$today = new DateTime(date('Y-m-d H:i:s'));

	if($start === 0 && $due === 0) {
		$due = $today;
	}

	// If start and due are timestamps, convert them to dates
	if($start !== 0) {
		if(is_int($start)) {
			$s = new DateTime();
			$s->setTimestamp($start);
			$s->setTimezone(new DateTimeZone(date_default_timezone_get()));
			$start = $s;
		}

		if(!($start instanceof DateTime)) $start = 0;
	}

	if($due !== 0) {
		if(is_int($due)) {
			$d = new DateTime();
			$d->setTimestamp($due);
			$d->setTimezone(new DateTimeZone(date_default_timezone_get()));
			$due = $d;
		}

		if(!($due instanceof DateTime)) $due = 0;
	}

	if($comp !== 0) {
		if(is_int($comp)) {
			$d = new DateTime();
			$d->setTimestamp($comp);
			$d->setTimezone(new DateTimeZone(date_default_timezone_get()));
			$comp = $d;
		}

		if(!($comp instanceof DateTime)) $comp = 0;
	}	
	
	$rs = new When();
	$rd = new When();
	$rc = new When();    
			
	$newstart = null;
	$newdue = null;
	$newrrule = $rrule;    

	// Optional Rules
	$fromComp = false;
	$fastFoward = false;
	$count = false;
	$subtractCount = 1;

	$match = array();
	preg_match("/(FROMCOMP[;]?)/i",$rrule,$match);            
	$fromComp = !empty($match[1]) && !empty($comp) && $comp !== 0;   

	$match = array();
	preg_match("/(FASTFORWARD[;]?)/i",$rrule,$match);            
	$fastFoward = !empty($match[1]);    

	$countMatch = array();
	preg_match("/(COUNT=([0-9]*)[;]?)/i",$rrule,$countMatch);
	$count = !empty($countMatch[1]);

	if( $fromComp ) $rrule = preg_replace("/FROMCOMP[;]?/i", "", $rrule);
	if( $fastFoward ) $rrule = preg_replace("/FASTFORWARD[;]?/i", "", $rrule);

	// Calculate DUE date
	if( $due === 0) {
	 	$newdue = 0;
	} else {
		try {
			if($fromComp) { //repeat from completion
				$rd->recur($comp,'')->rrule($rrule);
				$d = $rd->next();
				if($d == $comp) {
					if($count) {
						// Work around for When() lib behavior
						$carray = explode("=", $countMatch[1]);
						$newcount = ((int)($carray[1])) + 1;

						$newcountstr = "COUNT=".$newcount;
						$temprrule = preg_replace("/(COUNT=[0-9]*)/i", $newcountstr, $rrule);

						$rd = new When();
						$rd->recur($comp,'')->rrule($temprrule);
						
						$d = $rd->next();
					}

					$d = $rd->next();	
				}
			} else { //repeat from due-date
				$rd->recur($due,'')->rrule($rrule);
				$d = $rd->next();
				if($d == $due) {
					if($count) {
						// Work around for When() lib behavior
						$carray = explode("=", $countMatch[1]);
						$newcount = ((int)($carray[1])) + 1;

						$newcountstr = "COUNT=".$newcount;
						$temprrule = preg_replace("/(COUNT=[0-9]*)/i", $newcountstr, $rrule);

						$rd = new When();
						$rd->recur($due,'')->rrule($temprrule);
						
						$d = $rd->next();
					}
					$d = $rd->next();
				}				
			}
		} catch(Exception $e) {
			//failed to parse iCal rule
			return array($start->getTimestamp(),$due->getTimestamp(),"");
		}

		// No next occuraence
		if( !( $d instanceof DateTime) ) {
			return $noNextOccur;
		}

		//fast forward to the next future date
		if($fastFoward) {
		  	while( true ) {
				$d = $rd->next();
				if(!($d instanceof DateTime)) {
					 return $noNextOccur;
				} else if ($d > $today) {
					if($fromComp) {
					  $subtractCount = $comp->diff($d)->days;
					} else {
						$subtractCount = $due->diff($d)->days;	
					}
					break;
				}	            
		  	}	       
		}

		// No next occurence after returned one
		if( !( $rd->next() instanceof DateTime ) ) {
			$newrrule = "";
		}		

		$newdue = $d->getTimestamp();

		if($start !== 0) {
			$newstart = clone $d;
			$newstart->sub($start->diff($due));
			$newstart = $newstart->getTimestamp();
		} else {
			$newstart = 0;
		}
	}

	// Calculate START date if not already done
	if($newstart === null) {
		if( $start === 0) {
			 $newstart = 0;
		} else {
			try {
				if($fromComp) {
					$rc->recur($comp,'')->rrule($rrule);
					$d = $rc->next();
					if($d == $comp) {
						if($count) {
							// Work around for When() lib behavior
							$carray = explode("=", $countMatch[1]);
							$newcount = ((int)($carray[1])) + 1;

							$newcountstr = "COUNT=".$newcount;
							$temprrule = preg_replace("/(COUNT=[0-9]*)/i", $newcountstr, $rrule);

							$rc = new When();
							$rc->recur($comp,'')->rrule($temprrule);
							
							$d = $rc->next();
						}

						$d = $rc->next();
					}
				} else {
					$rc->recur($start,'')->rrule($rrule);
					$d = $rc->next();
					if(  $d == $start) {
						if($count) {
							// Work around for When() lib behavior
							$carray = explode("=", $countMatch[1]);
							$newcount = ((int)($carray[1])) + 1;

							$newcountstr = "COUNT=".$newcount;
							$temprrule = preg_replace("/(COUNT=[0-9]*)/i", $newcountstr, $rrule);

							$rc = new When();
							$rc->recur($start,'')->rrule($temprrule);
							
							$d = $rc->next();
						}

						$d = $rc->next();
					}
				}
			} catch(Exception $e) {
				echo "excepto";
				if($due!==0) $due = $due->getTimestamp();
				if($start!==0) $start = $start->getTimestamp();
				return array($start,$due,"");
			}

			// No next occuraence
			if( !( $d instanceof DateTime) ) {
				return $noNextOccur;
			}

			if($fastFoward) {
			  	while( true ) {
					$d = $rs->next();
					if(!($d instanceof DateTime)) {
						return $noNextOccur;
					} else if ($d > $today) {
						if($fromComp) {
						  $subtractCount = $comp->diff($d)->days;
						} else {
							$subtractCount = $due->diff($d)->days;	
						}
						break;
					}
			  	}
			}

			 // No next occurence after returned one
			if( !( $rc->next() instanceof DateTime ) ) {
				$newrrule = "";
			}

			$newstart = $d->getTimestamp();
		}        
	}   

	// Update COUNT flag if exists and new rrule being returned
	if($count && $newrrule != "") {
		$carray = explode("=", $countMatch[1]);
		$newcount = ((int)($carray[1])) - $subtractCount;		

		$newcountstr = "COUNT=".$newcount;
		$newrrule = preg_replace("/(COUNT=[0-9]*)/i", $newcountstr, $newrrule);    	
	}

	return array($newstart,$newdue,$newrrule);
} 

/*
	Converts Toodledo's old repeat strings into iCal RRULEs
	$text = the "repeatA" field from the db.  Old "repeat" number will need to be pre-conveted into repeatA
	$fromComp = boolean if we should repeat from the completion date. false means repeat from due-date (default)

	No external dependencies
*/
function rep_convertToRRule($text, $fromcomp) 
{
	$repeat = '';
	$text = trim(strtolower($text));
	
	$every = strpos($text,'every');
	$each = strpos($text,'each');
	$parent = strpos($text,'parent');
	
	if($parent !== FALSE) {
		$repeat = "PARENT";
	} else if(($every!==FALSE && $every<5) || ($each!==FALSE && $each<5)){ // Every|Each X T
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
			
			if(empty($match[0])) return rep_convertToRRule("Every ".$text, $fromcomp);
			if(empty($match[1])) return rep_convertToRRule("Every ".$match[2], $fromcomp);
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

function rep_translateShortToLongDayNames($shorts) {
	$all = explode(",", $shorts);
	$longs = "";
	foreach($all as $short) {
	 	if($short=="MO") $longs .= ", Mon";
		else if($short=="TU") $longs .= ", Tue";
		else if($short=="WE") $longs .= ", Wed";
		else if($short=="TH") $longs .= ", Thu";
		else if($short=="FR") $longs .= ", Fri";
		else if($short=="SA") $longs .= ", Sat";
		else if($short=="SU") $longs .= ", Sun";
	}
	return substr($longs,2);
}

//converts iCal RRULEs back into old Toodledo repeatA strings
function rep_convertToToodledo($rrule) {
	$ret = "";	
	
	if(strpos($rrule,'PARENT')!==FALSE) return "With Parent";

	$parts = explode(";", $rrule);
	foreach($parts as $p) {
		if(stristr($p, "FREQ=")!==false) $freq = str_replace("FREQ=", "", $p);
		else if(stristr($p, "INTERVAL=")!==false) $interval = str_replace("INTERVAL=", "", $p);
		else if(stristr($p, "BYDAY=")!==false) $byday = str_replace("BYDAY=", "", $p);
	}

	if($freq=="DAILY") {
		if(empty($interval)) {
			$ret = "Every 1 day";
		} else { 
			$ret = "Every ".intval($interval)." days";
		}
	} else if($freq=="WEEKLY") {
	
		if(!empty($interval)) {
			$ret = "Every ".intval($interval)." weeks";
		} else if(!empty($byday)) {
			
			$byday = rep_translateShortToLongDayNames($byday);

			$ret = "Every ".$byday;
		} else { 
			$ret = "Every 1 week";
		}
		
	} else if($freq=="MONTHLY") {
		if(!empty($interval)) {
			$ret = "Every ".intval($interval)." months";
		} else if(!empty($byday)) {
			$day = substr($byday,-2);
			$num = str_replace($day,"",$byday);

			$day = rep_translateShortToLongDayNames($day);
		
			if($num==1) $ret = "The 1st ".$day;
			else if($num==2) $ret = "The 2nd ".$day;
			else if($num==3) $ret = "The 3rd ".$day;
			else if($num==-1) $ret = "The last ".$day;
			else $ret = "The ".$num."th ".$day;
			$ret.=" of each month";

		} else { 
			$ret = "Every 1 month";
		}

	} else if($freq=="YEARLY") {
		if(empty($interval)) {
			$ret = "Every 1 year";
		} else { 
			$ret = "Every ".intval($interval)." years";
		}
	} 
	
	$ret = rep_formatAdvanced($ret);
	
	return $ret;
}

// Takes a user entered text string and normalizes it into a proper advanced repeat string
function rep_formatAdvanced($text) {
	$repeat = '';
	$text = trim(strtolower($text));
	
	$every = strpos($text,'every');
	$each = strpos($text,'each');
	$parent = strpos($text,'parent');
	
	if($text=='daily') return "Every 1 day";
	else if($text=='weekly') return "Every 1 week";
	else if($text=='biweekly') return "Every 2 weeks";
	else if($text=='monthly') return "Every 1 month";
	else if($text=='bimonthly') return "Every 2 months";
	else if($text=='quarterly') return "Every 3 months";
	else if($text=='semiannually') return "Every 6 months";
	else if($text=='yearly') return "Every 1 year";
	else if($parent!==FALSE) return "With Parent";

	if(($every!==FALSE && $every<5) || ($each!==FALSE && $each<5)) { //Every ...
		preg_match("/[a-z]* ([0-9]*)([a-z ,]*)/i",$text,$match);
		if(empty($match[1])) $match[1] = 1;
		if(empty($match[2])) $match[2] = '';
			
		$on = "Every ";
		$and = "";
		if(strpos($match[2],'mo')!==FALSE) { $repeat .= $on.$and."Mon"; $on=''; $and=", "; }
		if(strpos($match[2],'tu')!==FALSE && strpos($match[2],'tur')===FALSE) { $repeat .= $on.$and."Tue"; $on=''; $and=", "; } //tur prevents saTUrday
		if(strpos($match[2],'we')!==FALSE) { $repeat .= $on.$and."Wed"; $on=''; $and=", "; }
		if(strpos($match[2],'th')!==FALSE) { $repeat .= $on.$and."Thu"; $on=''; $and=", "; }
		if(strpos($match[2],'fr')!==FALSE) { $repeat .= $on.$and."Fri"; $on=''; $and=", "; }
		if(strpos($match[2],'sa')!==FALSE) { $repeat .= $on.$and."Sat"; $on=''; $and=", "; }
		if(strpos($match[2],'su')!==FALSE) { $repeat .= $on.$and."Sun"; $on=''; $and=", "; }
		
		$plural = 's';
		if($match[1]==1) $plural='';
		if(strpos($match[2],'day')!==FALSE && empty($and)) $repeat="Every ".$match[1]." day".$plural;
		else if(strpos($match[2],'week')!==FALSE) $repeat="Every ".$match[1]." week".$plural;
		else if(strpos($match[2],'month')!==FALSE) $repeat="Every ".$match[1]." month".$plural;
		else if(strpos($match[2],'year')!==FALSE) $repeat="Every ".$match[1]." year".$plural;
		
		if(strpos($match[2],'weekday')!==FALSE) $repeat = "Every weekday";
		else if(strpos($match[2],'weekend')!==FALSE) $repeat = "Every weekend";
		
	} else { //on the ...
		
		if(strpos($text,'first')!==FALSE) $num = 1;
		else if(strpos($text,'second')!==FALSE) $num = 2;
		else if(strpos($text,'third')!==FALSE) $num = 3;
		else if(strpos($text,'fourth')!==FALSE) $num = 4;
		else if(strpos($text,'fifth')!==FALSE) $num = 5;
		else if(strpos($text,'last')!==FALSE) $num = -1;
		else {
			preg_match("/[a-z ]* ([0-9]*)([a-z ]*)/i",$text,$match);
			
			if(empty($match[0])) return rep_formatAdvanced("Every ".$text);
			if(empty($match[1])) return rep_formatAdvanced("Every ".$match[2]);
			$num = $match[1];
		}
		
		if(strpos($text,' mo')!==FALSE) $day = "Mon";
		if(strpos($text,' tu')!==FALSE) $day = "Tue";
		else if(strpos($text,' we')!==FALSE) $day = "Wed";
		else if(strpos($text,' th ')!==FALSE) $day = "Thu";
		else if(strpos($text,' thu')!==FALSE) $day = "Thu";
		else if(strpos($text,' fr')!==FALSE) $day = "Fri";
		else if(strpos($text,' sa')!==FALSE) $day = "Sat";
		else if(strpos($text,' su')!==FALSE) $day = "Sun";
			
		if($num>5) $num=-1;
		
		if($num==1) $repeat = "The 1st ".$day;
		else if($num==2) $repeat = "The 2nd ".$day;
		else if($num==3) $repeat = "The 3rd ".$day;
		else if($num==-1) $repeat = "The last ".$day;
		else $repeat = "The ".$num."th ".$day;
		$repeat.=" of each month";
	}
	return $repeat;
}

//converts advanced representations of simple repeats back to their simple counterpart
function rep_simplifyRepeat($rep,$adv) {
	if($rep!=50 && $rep!=150) return $rep; //already simple
	
	$fromCompletion=0;
	if($rep>=100) $fromCompletion=100;
	
	if(strtolower($adv)=="every 1 week") return $fromCompletion+1;
	else if(strtolower($adv)=="every 1 month") return $fromCompletion+2;
	else if(strtolower($adv)=="every 1 year") return $fromCompletion+3;
	else if(strtolower($adv)=="every 1 day") return $fromCompletion+4;
	else if(strtolower($adv)=="every 2 weeks") return $fromCompletion+5;
	else if(strtolower($adv)=="every 2 months") return $fromCompletion+6;
	else if(strtolower($adv)=="every 6 months") return $fromCompletion+7;
	else if(strtolower($adv)=="every 3 months") return $fromCompletion+8;
	else if(strtolower($adv)=="with parent") return $fromCompletion+9;
	
	return $rep; //no change
}

//takes the repeat number and string and returns the string
function rep_format($number, $adv) {
	if($number>=100) $number-=100;
	if($number==1) return "Weekly";
	else if($number==2) return "Monthly";
	else if($number==3) return "Yearly";
	else if($number==4) return "Daily";
	else if($number==5) return "Biweekly";
	else if($number==6) return "Bimonthly";
	else if($number==7) return "Semiannually";
	else if($number==8) return "Quarterly";
	else if($number==9) return "With Parent";
	else if($number==50 && empty($adv)) return "None";
	else if($number==60 && empty($adv)) return "None";
	else if($number==50 && $adv=="Every 1 week") return "Weekly";
	else if($number==50 && $adv=="Every 1 month") return "Monthly";
	else if($number==50 && $adv=="Every 1 year") return "Yearly";
	else if($number==50 && $adv=="Every 1 day") return "Daily";
	else if($number==50 && $adv=="Every 2 weeks") return "Biweekly";
	else if($number==50 && $adv=="Every 2 months") return "Bimonthly";
	else if($number==50 && $adv=="Every 3 months") return "Quarterly";
	else if($number==50 && $adv=="Every 6 months") return "Semiannually";
	else if($number==50) return $adv;
	else if($number==60) return $adv;
	return "None";
}

//Accepts any user input and turns it into iCal.
//Input can be simple(repeat only), advanced (repeatA + repeat) or iCal (repeatA)
function rep_normalize($repeat,$repeatA,$enhanced=true) {
	$fromcomp = "";
	if($repeat>=100) {
		if($enhanced) $fromcomp = ";FROMCOMP";
		$repeat-=100;
	}

	//parent is special
	if(strpos(strtolower($repeatA),'parent')!==FALSE || $repeat==9 || $repeat==109) {
		if($enhanced) return "PARENT".$fromcomp;
		return "";
	}
	//already in ical
	if(strpos($repeatA,'FREQ=')!==FALSE) {
		if(!$enhanced) {
			$repeatA = str_replace(";FROMCOMP",	"", $repeatA);
			$repeatA = str_replace("FROMCOMP;",	"", $repeatA);
		}
		return $repeatA;
	
	//old Toodledo format
	} else {
		$repeatA = strtolower($repeatA);
		$repeatA = rep_format($repeat,$repeatA);
		if(!empty($repeatA)) {
			$repeatA = rep_formatAdvanced($repeatA); //normalize the toodledo string
			$rrule = rep_convertToRRule($repeatA,$fromcomp);
			return $rrule;
		}
	}
	return "";
}

//Takes a repeat and repeatA(ical or old) and converts it to the human readable string
function rep_display($repeat, $repeatA) {
	$rrule = rep_normalize($repeat,$repeatA);
	$repeatA = rep_convertToToodledo($rrule); //convert to old format
	$repeatA = rep_format(50,$repeatA);
	return $repeatA;
}
?>