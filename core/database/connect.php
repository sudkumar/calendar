<?php
	


$connection_error = "Sorry, we\'re expering connection problems."; 

// Connect to server and select databse.
function connect(){
	$host="localhost"; // Host name
	$username="root"; // Mysql username
	$password=""; // Mysql password
	$db_name="cal"; // Database name
	if(mysql_connect($host, $username, $password) and mysql_select_db($db_name)){
		return true;
	}else{
		return false;
	}
}
connect();
?>