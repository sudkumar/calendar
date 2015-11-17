<?php
	require '../core/init.php'; 
	if(!logged_in()){
		header('Location: login.php');
	}
if(isset($_POST) and !empty($_POST) and isset($_POST['course']) and !empty($_POST['course'])){	
	$userName = $user_data['userName'];
	$course = $_POST['course'];

	if (!file_exists("upload/" . $userName))
	{
  		mkdir("upload/" . $userName, 0777, true);
	}
    if (empty($course) != true)
    {
		$data = array(
			'course' 		=> $course,
			'title'			=> $_POST['course_title'],
			'userName' 	=> $userName
			);
		enter_course($data);

		$temp = explode(".", $_FILES["file1"]["name"]);
		$extension = end($temp);				
		if (!file_exists("upload/" . $userName . "/" . $course))
		{
			mkdir("upload/" . $userName . "/" . $course, 0777, true);
		}
    	$file_name1 = $_FILES["file1"]["name"];
    	$file_type1 = $_FILES["file1"]["type"] . "<br>";
   		$file_size1 = ($_FILES["file1"]["size"] / 1024) . " KB";

          $timestamp = microtime();
	      $temp = explode(".",$_FILES["file1"]["name"]);

	      if($extension != "txt")
	      {
	      	echo "Invalid file format <br> only .txt is acceptable<br>";
	      	return;
	      }

		   else
		   {
		           $temp = explode(".", $file_name1);
		           $file_name1 = $course . "." . end($temp);
		   		   move_uploaded_file($_FILES["file1"]["tmp_name"], "upload/" . $userName . "/" . $course . "/" . $file_name1);
	  
			       echo "File uploaded successfully <br>";
			    
			       echo "File Name : " . $file_name1 . "<br>";
			       echo "File Size : " . $file_size1 . "<br>";
			       echo "File type : " . $file_type1 . "<br>";
			}

			$filename = "upload/" . $userName . "/" . $course . "/" . $file_name1;
			$handle = fopen($filename, "r");
			if ($handle) {
			    while (($line = fgets($handle)) !== false) 
			    {
			        $line = trim($line);
			        $data1 = array(
						'userName' 		=> $line,
						'firstname'		=> $line,
						'email' 		=> $line . "@iitk.ac.in",
						'password'		=> "sm" . $line,
						'designation'	=> "student"
					);
					enter_student($data1);
					$data2 = array(
						'userName'	=> $line,
						'course'	=> $course
						);
					enter_student_into_students_course($data2);
			    }
			    update_flag($userName, '1');
			    header('Location: #');
			} 
			else 
			{
			   echo "error...<br>";
			} 
			fclose($handle);
	}



} 

?>



