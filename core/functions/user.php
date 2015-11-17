<?php


function register_teacher($register_data){
	array_walk($register_data, 'array_sanitize');
	$register_data['password'] = md5($register_data['password']);
	
	$fields = '`'.implode('`, `', array_keys($register_data)).'`';
	
	$data = '\''. implode('\', \'', $register_data). '\'';
	
	mysql_query("INSERT INTO `usersdetail` ($fields) VALUES ($data) ");
	
}	

function enter_course($data){
	array_walk($data, 'array_sanitize');
	$fields = '`'.implode('`, `', array_keys($data)).'`';
	
	$data = '\''. implode('\', \'', $data). '\'';
	mysql_query("INSERT INTO `teacherscourse` ($fields) VALUES ($data) ");
	}

function user_data($user_id){
	$data  = array();
	$user_id = (int)$user_id;
	
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	
	if($func_num_args > 1){
		unset($func_get_args[0]);
		
		$fields = '`'. implode('`, `', $func_get_args) . '`';
		$data = mysql_fetch_assoc(mysql_query("SELECT $fields FROM `usersdetail` WHERE `user_id` = $user_id "));
		
		return $data;
	}
}

function enter_student($register_data){
	array_walk($register_data, 'array_sanitize');
	$password  = $register_data['password'];
	$register_data['password'] = md5($register_data['password']);
	
	$fields = '`'.implode('`, `', array_keys($register_data)).'`';
	
	$data = '\''. implode('\', \'', $register_data). '\'';
	$avar = $register_data['userName'];
	$query = mysql_query("SELECT COUNT(*) FROM `usersdetail` WHERE `userName` = '$avar'");
	$var = mysql_result($query, 0);
	if($var == 0)
		// no student is registered with this username
		// mail($register_data['email'], "Login details", "You user name is roll number and OTP is $password.", "admin@cal.com")
		mysql_query("INSERT INTO `usersdetail` ($fields) VALUES ($data) ");	
}

function enter_student_into_students_course($register_data){
	array_walk($register_data, 'array_sanitize');
	$fields = '`'.implode('`, `', array_keys($register_data)).'`';
	
	$data = '\''. implode('\', \'', $register_data). '\'';
	$avar = $register_data['userName'];
	$bvar = $register_data['course'];
	$query = mysql_query("SELECT COUNT(*) FROM `studentscourse` WHERE (`userName` = '$avar') AND (`course` = '$bvar') ");
	$var = mysql_result($query, 0);
	if($var == 0)
		mysql_query("INSERT INTO `studentscourse` ($fields) VALUES ($data) ");	
}


function logged_in_redirect(){
	if(isset($_SESSION['user_id'])) {
		header('Location: ./');
	}
}
function logged_in(){
	return (isset($_SESSION['user_id'])) ? true : false; 
}		


function user_exists($userName){
	$userName = sanitize($userName);
	$query = mysql_query("SELECT COUNT(`user_id`) FROM `usersdetail` WHERE `userName` = '$userName'");
	return (mysql_result($query, 0) == 1) ? true : false;
}


function email_exists($email){
	$email = sanitize($email);
	$query = mysql_query("SELECT COUNT(`user_id`) FROM `usersdetail` WHERE `email` = '$email'");
	return (mysql_result($query, 0) == 1) ? true : false;
}

function user_id_from_username($userName){
	$userName = sanitize($userName);

	return mysql_result(mysql_query("SELECT `user_id` FROM `usersdetail` WHERE `userName` = '$userName'"), 0, 'user_id');	
}
function user_id_from_email($email){
	$email = sanitize($email);

	return mysql_result(mysql_query("SELECT `user_id` FROM `usersdetail` WHERE `email` = '$email'"), 0, 'user_id');	
}

function login($userName, $password){
	$user_id = user_id_from_username($userName);
	
	$userName = sanitize($userName);
	$password = md5($password);
	
	return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `usersdetail` WHERE `userName` = '$userName' AND password = '$password'"), 0) == 1) ? $user_id: false;
}

function update_student($userName, $firstname, $lastname, $email, $department, $password){
	$userName = sanitize($userName);
	$firstname = sanitize($firstname);
	$lastname = sanitize($lastname);
	$email = sanitize($email);
	$department = sanitize($department);
	$password = md5($password);
	mysql_query("UPDATE `usersdetail` SET `firstname` = '$firstname', `lastname` = '$lastname', `email` = '$email', `department` = '$department', `password` = '$password' WHERE `userName` = '$userName' ");
}

function get_designation($userName){
	$userName = sanitize($userName);
	$query = mysql_query("SELECT `designation` FROM `usersdetail` WHERE `userName` = '$userName'");
	$var = mysql_result($query, 0);
	return $var;
}

function get_flag($userName){
	$userName = sanitize($userName);
	$query = mysql_query("SELECT `flag` FROM `usersdetail` WHERE `userName` = '$userName'");
	$var = mysql_result($query, 0);
	return $var;
}

function update_flag($userName,$flag){
	$userName = sanitize($userName);
	mysql_query("UPDATE `usersdetail` SET `flag` = '$flag' WHERE `userName` = '$userName'");
	mysql_result($query, 0);
}

function rollno_exists($userName, $course)
{
	$userName = sanitize($userName);
	$course = sanitize($course);
	$query = mysql_query("SELECT COUNT(*) FROM `studentscourse` WHERE `userName` = '$userName' AND `course` = '$course'");
	return (mysql_result($query, 0) == 1) ? true : false;
}

function remove_student($userName, $course)
{
	$userName = sanitize($userName);
	$course = sanitize($course);
	$query = mysql_query("DELETE * FROM `studentscourse` WHERE `userName` = '$userName' AND `course` = '$course'");
}
?>