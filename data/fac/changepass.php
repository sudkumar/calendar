<?php 
	require "../../core/init.php";
	if(logged_in() and connect() and isset($_POST) and !empty($_POST)){
		if(isset($_POST['currPass']) and isset($_POST['newPass']) and isset($_POST['renewPass'])){
			if(!empty($_POST['currPass']) and !empty($_POST['newPass']) and !empty($_POST['renewPass'])){
				$currPass = sanitize($_POST['currPass']);
				$newPass = sanitize($_POST['newPass']);
				$renewPass = sanitize($_POST['renewPass']);
				$userName = $_SESSION['userName'];
				$query = "SELECT `password` FROM `usersdetail` WHERE `userName` = '$userName'";
				$result = mysql_query($query);
				if($rs = mysql_fetch_array($result)){
					if($rs['password'] == md5($currPass)){
						if($newPass == $renewPass){
							$password = md5($newPass);
							if(mysql_query("UPDATE `usersdetail` SET `password` = '$password' WHERE userName='$userName'")){
								echo "Successfully Updated";
							}else{
								echo "Server error encountered";
							}
						}else{
							echo "New password don't match";
						}
					}else{
						echo "Current password don't match";
					}
				}
			}
		}
	}
 ?>