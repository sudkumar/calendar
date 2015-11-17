<?php 
	require '../../core/init.php';
	if(!logged_in()){
		die("Permission denied");
	}
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	if(connect()){
		$userName = $_SESSION['userName'];
		$query = "select events.*, studentscourseeventseen.seen from events inner join studentscourseeventseen on events.id = studentscourseeventseen.eventId and studentscourseeventseen.userName = '$userName'";
		$result = mysql_query($query);
		$outp = "[";
		while($rs = mysql_fetch_array($result)) {
		    if ($outp != "[") {$outp .= ",";}
		    $outp .= '{"course":"'  . $rs["course"] . '",';
		    $outp .= '"id":"'   . $rs["id"] . '",';
		    $outp .= '"title":"'   . $rs["title"] . '",';
		    $outp .= '"date":"'. $rs["date"]     . '",';
		     $outp .= '"time":"'. $rs["time"]     . '",';
		    $outp .= '"eventSeen":"'. $rs["seen"]     . '",';
		    $outp .= '"venue":"'   . $rs["venue"] . '"}';
		}
		$outp .="]";
		echo($outp);
	}
 ?>