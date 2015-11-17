<?php 
	session_start();
	if(isset($_SESSION['userName'])){
		unset($_SESSION['userName']);
		unset($_SESSION['type']);
		session_destroy();
	}
	header('Location: ../');

 ?>