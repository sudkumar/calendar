<?php
require '../../core/init.php';
if(logged_in()){
	$userName = $_SESSION['userName'];
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	if(connect() and isset($_POST) and !empty($_POST)){
		$id = $_POST['id'];
		$query1 = "DELETE FROM `events` WHERE id=$id AND owner='$userName'";
		$query2 = "DELETE FROM `studentscourseeventseen` WHERE `eventId`=$id";
		if(mysql_query($query1) and mysql_query($query2)){
			echo "Event successfully deleted";
		}else{
			echo "Server problem while deleting event";
		}
	}
}				
 ?>