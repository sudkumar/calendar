<?php 
	require '../../core/init.php';
	if(!logged_in()){
		die("Permission denied");
	}
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	if(connect() and isset($_GET['id']) and !empty($_GET['id']) ){
		$userName = $_SESSION['userName'];
		$id = $_GET['id'];
		if($id == '-1'){
			mysql_query("UPDATE `studentscourseeventseen` SET `seen` = 1 WHERE userName = '$userName'");
		}else{
			mysql_query("UPDATE `studentscourseeventseen` SET `seen` = 1 WHERE userName = '$userName' and eventId = '$id'");
		}
	}
 ?>