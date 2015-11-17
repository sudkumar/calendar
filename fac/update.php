<?php 
    require "uploader.php";
    $userName = $_SESSION['userName'];
 ?>


<!DOCTYPE html>
<html xml:lang="en" lang="en">
    <head>
        <title>Courses Information</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
    </head>
    <body class="container">  


<div class="panel panel-default">
    <div class="panel-heading">
        <strong><span style="font: bold 30px sans-serif;">Course Details</span></strong>
        <?php 
          if(get_flag($userName) == 1)
            echo '<a href="." style="float:right;">Back to home</a>';
          else
            echo '<a href="logout.php" style="float:right;">Log out</a>';
          ?>
    </div>
    <div class="panel-body">
         <form action="#" method="POST" enctype="multipart/form-data" class="form-inline" role="form">
           <div class="form-group">
            <div class="input-group">
              <label class="sr-only" for="courseNo">CS252</label>
              <div class="input-group-addon">Course No.</div>
              <input type="text" class="form-control" id="courseNo" placeholder="CS252" name="course" required>
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <label class="sr-only" for="courseTitle">Computing Laboratory</label>
              <div class="input-group-addon">Course Title</div>
              <input type="text" class="form-control" id="courseTitle" placeholder="Computing Laboratory" name="course_title" required>
            </div>
          </div>
          <div class="form-group">
            <label class="sr-only" for="fileInput">File</label>
            <input type="file" class="form-control" id="fileInput" name="file1" required>
          </div>
          
          <button type="submit" name="submit" class="btn btn-primary pull-right">Submit</button>
        </form>   
    </div>
</div>


<div>
                <?php 
                    $query = "select teacherscourse.course from teacherscourse where userName = '$userName'";
                    $result = mysql_query($query);
                    $firstOne = true;
                    while($rs = mysql_fetch_array($result)){
                        if($firstOne){
                            echo <<<EOF
<div class="panel panel-default">
     <div class="panel-heading">
        <strong><span style="font: bold 30px sans-serif;">Remove course</span></strong>
    </div>
        <div class="panel-body">
            <form class="form-inline" role="form" action="deleteCourses.php" method="POST">
EOF;
                        $firstOne = false;
                        }
                        $course = $rs['course'];
                        echo <<<EOF


    <div class="input-group">
        <span class="input-group-addon">
            <input type="checkbox" class="checkbox"  id="$course" value="$course" name="check_list[]" >
        </span>
        <label class="form-control" for="$course">$course</labe>
    </div>
        
EOF;

                    }
                    if(!$firstOne)echo '<button type="submit" name="submit" class="btn btn-warning pull-right">Delete</button></form></div>';
                 ?>
            </div>  
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    </body>
</html>





