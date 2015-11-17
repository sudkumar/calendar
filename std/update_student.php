<?php

require '../core/init.php';

if(!logged_in()){
  header('Location: ../');
}

$userName = $user_data['userName'];

if (!empty($_POST['submit'])) {
 
  if(!empty($_POST['password']) and !empty($_POST['firstname']) and !empty($_POST['firstname']) and !empty($_POST['email']) and !empty($_POST['department'])){ 
      $password    = $_POST['password'];
      $repassword = $_POST['repassword'];
      if($password = $repassword){
        $firstname   = $_POST['firstname'];
        $lastname    = $_POST['lastname'];
        $email     = $_POST['email'];
        $department  = $_POST['department'];
        update_student($userName, $firstname, $lastname, $email, $department, $password);
        update_flag($userName, "1");
        header('Location: .');
     }else{
      $errors[] = "Password don't match";
     }
  }else{
    $errors[] = "Fields marked with an asterick are required.";
  }
}

?>


<!DOCTYPE html>
<html lang="en" class="no-js"> 
    <head>
        <meta charset="UTF-8" />
        <title>User Details</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
        <script src="js/jquery.1.6.2.min.js"></script>
<script>
$(document).ready(function(){
    $("#join_us").click(function(){
        $(".login_user.active").removeClass("active");
        $(".register_user").addClass("active animate");
    });
    $("#login_user").click(function(){
        $(".register_user.active").removeClass("active");
        $(".login_user").addClass("active animaate");
    });

});
</script>
<style>
.header_inner{
    width:100%;
    height:100px;
    background: #2c3338;
    position: relative;
}
.user_name{
 color:#fff;
 font-family: 'Gotham Rounded Light';
 position:relative;
 top:30px;  
}
.wrapper_down{
    position: relative;
    top:0px;
    left:400px;
    width:500px;
}
.icon-inner-css{
    font-size: 25px;
    color:#fff;
    position: relative;
    top:5px;
    padding:0px 30px;
}

.mine_button{
    padding: 13px 25px;
    border-radius: 4px;
    height:35px;
    width:100px;
    color:#fff;
    font-size:22px;
    position:absolute;
    left:0px;
}

.mine_button:hover{
    cursor: pointer;
}
*{  
  margin:0;
  padding:0;
}


.wrapper_down a{
  display:block;
  color:#ad5482;  
  text-decoration:none;
  font-weight:bold;
  text-align:center;
}



.module{
  position:relative;
  top:60px;    
  height:458px;
  width:450px;
  margin-left:auto;
  margin-right:auto;
  margin-bottom: 15px;
  border-radius:5px;
  background:RGBA(255,255,255,1) !important;
    
  -webkit-box-shadow:  0px 0px 15px 0px rgba(0, 0, 0, .45);        
  box-shadow:  0px 0px 15px 0px rgba(0, 0, 0, .45);
  
}



.form{
  float:left;
  height:86%;
  width:100%;
  box-sizing:border-box;
  padding:40px;
}

.textbox{
  height:40px;
  width:100%;
  border-radius:3px;
  border:rgba(0,0,0,.3) 2px solid;
  box-sizing:border-box;
  padding:10px;
  margin-bottom:15px;
}

.textbox:focus{
  outline:none;
   border:rgba(24,149,215,1) 2px solid;
   color:rgba(24,149,215,1);
}

.button{
  height:50px;
  width:100%;
  border-radius:3px;
  border:rgba(0,0,0,.3) 0px solid;
  box-sizing:border-box;
  padding:10px;
  margin-bottom:30px;
  background:#90c843;
  color:#FFF;
  font-weight:bold;
  font-size: 12pt;
  transition:background .4s;
  cursor:pointer;
}

.button:hover{
  background:#80b438;
  
}
</style>
    </head>
<body style="background-color:#ddd !important;">


<!-- top nevigation bar -->
<nav class="navbar navbar-inverse nav-bar-fixed-top" role="navigation">
    <div class="container">
        <!-- this is to make that little icon when browser windows is to small to access the menu -->
        <button class="navbar-toggle" data-target=".navbar-responsive-collapse" data-toggle="collapse" type="button">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <!-- put the logo -->
        <a href="#" class="navbar-brand">Academic Calendar</a>
          
        <!-- put navigation menu  -->
        <div class="navbar-collapse collapse navbar-responsive-collapse">
            <ul class="nav navbar-nav pull-right">
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo $userName; ?> <strong class="caret"></strong> </a>
                  <ul class="dropdown-menu">
                      <li><a href="#"><span class="glyphicon glyphicon-lock"></span> Change Password </a></li>
                      <li class="divider"></li>
                      <li><a href="logout.php"><span class="glyphicon glyphicon-off"></span> Logout </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
        <div class="wrapper_inner">
            <div class="wrapper_down">
                <div class="module">
                  <div style="text-align: center; color: red;">
                  <?php
                        if(!empty($errors)){
                             echo output_errors($errors);
                             $errors[] = '';
                        }
                    ?>
                  </div>
                    <form class="form" method="POST" action="#" autocomplete="off">
                        <input type="text" name = "firstname" placeholder="*First Name" class="textbox" />
                        <input type="text" name = "lastname" placeholder="Last Name" class="textbox" />
                        <input type="text" name = "email" placeholder="*Email Address" class="textbox" />
                        <input type="text" name = "department" placeholder="*Department" class="textbox" />
                        <input type = "password" name = "password" placeholder="*Password" class="textbox" />
                        <input type = "password" name="repassword" placeholder="*Re-enter password" class="textbox" />
                        <input type="submit" name = "submit" value="Next" class="button" />
                    </form>
                </div>
            </div>

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
            <script src="../js/bootstrap.min.js"></script>

    </body>
</html>
