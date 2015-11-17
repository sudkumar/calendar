<?php 
	require '../core/init.php';
	if(logged_in() and connect() and isset($_POST['submit'])){
		if(!empty($_POST['check_list'])){
			$myCourses = array();
			$userName = $_SESSION['userName'];
			$query = "select course from teacherscourse where userName = '$userName'";
            $result = mysql_query($query);
            while ($rs = mysql_fetch_array($result)) {
            	$myCourses[] = $rs['course'];
            }
			foreach($_POST['check_list'] as $selected){
				if(in_array($selected, $myCourses)){
					echo "This is my course";
					mysql_query("DELETE FROM `teacherscourse` WHERE course='$selected'");
					mysql_query("DELETE FROM `events` WHERE course='$selected'");
					mysql_query("DELETE FROM `studentscourse` WHERE course='$selected'");
					mysql_query("DELETE FROM `studentscourseeventseen` WHERE course='$selected'");
					header('Location: update.php');
				}else{
					//echo "You are not allowed to delete this course";
				}
			}
		}else{
			//echo "Please select a course";
		}
	}else{
		//echo "Connection error";
	}
	header('Location: update.php');
 ?>