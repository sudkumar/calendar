<?php 
require '../../core/init.php';
if(logged_in()){
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	if(connect() and isset($_POST) and !empty($_POST)){
		$owner = $_SESSION['userName'];
		$course = $_POST['course'];
		$title =  $_POST['title'];
		$date =  $_POST['date'];
		$venue =  $_POST['venue'];
		$time = $_POST['time'];
		if(mysql_query("INSERT INTO `events` (`course`, `title`,`date`, `venue`, `owner`, `time`) VALUES('$course', '$title', '$date', '$venue', '$owner', '$time')")){
			$courseIdQuery = mysql_insert_id();
			$students = "SELECT `userName` FROM `studentscourse` WHERE `course` = '$course'";
			$result = mysql_query($students);
			$value = "";
			while($rs = mysql_fetch_array($result)){
				$username = $rs['userName'];
				$value .= "('$username', '$course', '$courseIdQuery'),";
			}
			$value = rtrim($value, ",");
			$updateStudentEventQuery = "INSERT INTO `studentscourseeventseen` (`userName`, `course`, `eventId`) VALUES $value";
			if(mysql_query($updateStudentEventQuery)){
				echo "Successfully added $title";
			}else{
				echo "Error in query";
			}
		}else{
			echo "Error while adding!!!";
		}	
	}
}				
 ?>