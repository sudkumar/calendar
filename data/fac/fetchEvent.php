<?php 
	require '../../core/init.php';
	if(!logged_in()){
		die("Permission denied");
	}
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	if(connect() and isset($_GET['requestedCourse']) and !empty($_GET['requestedCourse'])){
		$userName = $_SESSION['userName'];	
		$requestedCourse = $_GET['requestedCourse'];
/*
what we have to do here that 
given professor's username, we have to find all his courses from `teacherscourse` table
then find all the student in all that courses from `studentscourse` table
then find all the courses for that students from `studentscourse` table
then find all the events for these course and display them  from `events` table
*/
$query = <<<EOF
select distinct events.* 
	from events 
	inner join 
		(select studentscourse.* 
			from studentscourse 
			inner join 
				(select studentscourse.* 
					from studentscourse 
					inner join teacherscourse 
					on studentscourse.course = '$requestedCourse' 
					where teacherscourse.userName = '$userName' 
				) allStudents 
			on allStudents.userName = studentscourse.userName 
		) allCourses 
	on events.course = allCourses.course 
EOF;

		$result = mysql_query($query);
		$outp = "[";
		while($rs = mysql_fetch_array($result)) {
		    if ($outp != "[") {$outp .= ",";}
		    $outp .= '{"id":"'  . $rs["id"] . '",';
		    $outp .= '"course":"'  . $rs["course"] . '",';
		    $outp .= '"title":"'   . $rs["title"] . '",';
		    $outp .= '"date":"'. $rs["date"]     . '",';
		    $outp .= '"time":"'.  $rs["time"]     .'",';
		    $outp .= '"owner":"'. $rs["owner"]     . '",';
		    if($rs["owner"] == $userName ){
		    	$outp .= '"canDelete":"true",';
		    }else{
		    	$outp .= '"canDelete":"false",';
		    }
		    $outp .= '"venue":"'   . $rs["venue"] . '"}';
		}
		$outp .="]";
		echo($outp);
	}
	
 ?>