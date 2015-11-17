<?php

include './core/init.php';
if(logged_in()){
    $type = $_SESSION['type'];
    header('Location: '.$type);
}
$p="0";

if (!empty($_POST['login_submit'])) {
    logged_in_redirect();
    if(isset($_POST) and !empty($_POST)){
        if(isset($_POST['userName']) and !empty($_POST['userName']) and isset($_POST['password']) and !empty($_POST['password'])){
            $userName = $_POST['userName'];
            $password = $_POST['password'];
            if(user_exists($userName) == false){
                $errors[] = 'We can\'t find the userName. Have you registered ?';
                $p = "10";
            }
            else{
                $login = login($userName, $password);
                if($login != false){
                    $_SESSION['userName'] = $userName;
                    $_SESSION['user_id'] = $login;
                    $designation = get_designation($userName);
                    if($designation == "student")
                    {
                        $flag = get_flag($userName);
                        $_SESSION['type'] = 'std';
                        if ($flag == 0) 
                            header('Location: std/update_student.php');
                        else header('Location: std/');
                        exit();
                    }

                    else
                    {
                        $flag = get_flag($userName);
                        $_SESSION['type'] = 'fac';
                        if ($flag == 0) 
                            header('Location: fac/update.php');
                        else header('Location: fac/');
                        exit();
                    }

                }
                else{
                    $errors[] = 'Incorrect password';
                    $p = "10";   
                }
            }
        }else{
            $errors[] = 'You need to enter a userName and password';
        }    
    }
}

if (!empty($_POST['signup_submit'])) {

    if(empty($_POST) === false){
            $required_fields = array('userName', 'password', 'password_again','firstname','email');
                foreach($_POST as $key => $value){
                    if(empty($value) && in_array($key, $required_fields) === true){
                        $errors[] = 'Fields maked with an asterik are required';
                        $p = "5";
                        break 1;
                    }
                }
            if(empty($errors) == true){
                $p = "5";
                if(user_exists($_POST['userName']) == true){
                    $errors[] = 'Sorry, the userName \' '.$_POST['userName'].' \' is already taken';
                    $p = "5";
                }
                if(preg_match("/\\s/", $_POST['userName']) == true){
                    $errors[] = 'Your userName must not contain any sapces';
                    $p = "5";
                }
                if(strlen($_POST['userName']) < 4){
                    $errors[] = 'Your userName must be at least 4 characters';
                }
                if(strlen($_POST['password']) < 6){
                    $errors[] = 'Your password must be at least 6 characters';
                }
                
                if($_POST['password'] !== $_POST['password_again']){
                    $errors[] = 'Your password do not match';
                }
                if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){
                    $errors[] = 'A valid email address is required';
                }
                if(email_exists($_POST['email']) == true){
                    $errors[] = 'Sorry, the email address \''.$_POST['email'].'\' is already in use';
                }
            }if(empty($errors) == true){    
                $register_data = array(
                'userName'      => $_POST['userName'],
                'password'      => $_POST['password'],
                'firstname'     => $_POST['firstname'],
                'lastname'      => $_POST['lastname'],
                'email'         => $_POST['email'],
                'department'    => $_POST['department'],
                'designation'   => "teacher",
                'flag'          => "0"
                );
                register_teacher($register_data);
                header('Location: index.php?registered');
                exit();
            }
    }
}
?>


<!DOCTYPE html>
<html lang="en" class="no-js"> 
    <head>
        <meta charset="UTF-8" />
        <title>Login and Registration Form </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="description" content="Login and Registration " />
        <link rel="shortcut icon" href="../favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/animate-custom.css" />
        <link rel="stylesheet" type="text/css" href="css/style_font.css" />
        <script src="js/jquery.1.6.2.min.js"></script>

            <script src="js/upload.js"></script>

<script>
$(document).ready(function(){
    $("#join_us").click(function(){
        $(".login_user.active").removeClass("active");
        $(".register_user").addClass("active animate");
        $("#errors").html("");
        $("#errors1").html("");
    });
    $("#login_user").click(function(){
        $(".register_user.active").removeClass("active");
        $(".login_user").addClass("active animate");
        $("#errors").html("");
        $("#errors").html("");
    });
    
        check_detail();
   
    

    
});
function check_detail(){
    var error= '<?php echo $p; ?>';;
    if (error== 5) {
        $(".login_user.active").removeClass("active");
        $(".register_user").addClass("active animate");
    } 
    if(error==10){
        $(".register_user.active").removeClass("active");
        $(".login_user").addClass("active animate");
    };

    
}

function check_password(){
    var string1=document.register.password.value;
    var string2=document.register.password_again.value;

    console.log(string1);
    if (string1!= string2){
        $(".error_box5").addClass("active");
        $(".error_show").addClass("add_margin")
    }
    else{
        $(".error_box5.active").removeClass("active");
        $(".error_show").removeClass("add_margin")
    }
}

</script>
<style>
.add_margin{
    margin-top:-30px !important;
}
#errors{
    color: red;
}
#errors1{
    color: red;
}
</style>
    </head>
    <body>
        <div class="container">
           
            <section>               
                <div id="container_demo" >
                    <div id="wrapper">
                        <div id="login"  class=" login_user active">
                            <form  method="POST" action="" autocomplete="off"> 
                                <h1>Log in</h1> 
                            <div id="errors">
                                    <?php
                                        if(!empty($errors)){
                                             echo output_errors($errors);
                                             $errors[] = '';
                                        }
                                    ?>
                                </div>
                                <p> 
                                    <label for="userName" class="uname" data-icon="u" > Your email or userName </label>
                                    <input id="userName"  name="userName" required="required" type="text" placeholder="userId" onblur="user_name_login()" />
                                    
                                </p>
                                <p> 
                                    <label for="password" class="youpasswd" data-icon="p"> Your password </label>
                                    <input id="password" name="password" required="required" type="password" placeholder="Password" class="input_text"/> 
                                </p>
                                <p class="keeplogin"> 
                                    <input type="checkbox" name="loginkeeping" id="loginkeeping" value="loginkeeping" /> 
                                    <label for="loginkeeping">Keep me logged in</label>
                                </p>
                                <p  class="login button"> 
                                    <input type="submit" name="login_submit" value="Login" /> 
                                </p>
                                <p class="change_link">
                                    Not a member yet ?
                                    <a  id="join_us" class="">Join us <span style="color:red;font-size:13px">(*only for teacher)</span></a>
                                </p>
                            </form>
                        </div>

                       <div id="register" class=" register_user" >
                            <form  action="" name="register" method = "POST" autocomplete="off">
                             
                                <h1><span style="padding-right:120px;"> Sign up</span> </h1>
                                <p class="change_link" style="width: 140px;margin: 0px;padding: 0px !important;position: relative;top: -60px;left: 215px;">
                                    <span style="font-size:11px;">Already a Member ?</span>
                                    <a  id="login_user"  > Go and log in </a>
                                </p>
                                <div id="errors1">
                                    <?php
                                        if(!empty($errors)){
                                             echo output_errors($errors);
                                             $errors[] = '';
                                        }
                                    ?>
                                </div>
                            <div>
                                <div style="float:left;width:150px;margin">
                                    <p> 
                                    <label for="usernamesignup" class="uname" data-icon="u" >Firstname</label>
                                    <input id="usernamesignup" name="firstname" required="required" type="text" placeholder="Firstname" value="<?php if(!empty($_POST['firstname'])){echo $_POST['firstname'];}?>" />
                                    </p>
                                </div>
                                <div style="float:right;width:150px;margin-right:18px">
                                    <p> 
                                    <label for="usernamesignup" class="uname" data-icon="u" >Lastname</label>
                                    <input id="usernamesignup" name="lastname"  type="text" placeholder="Lastname" value="<?php if(!empty($_POST['firstname'])){echo $_POST['lastname'];}?>" />
                                    </p>
                                </div> 
                            </div>
                            <div style="float:left;margin-top:10px;">
                                <p style="margin-bottom:10px"> 
                                    <label for="usernamesignup" class="uname" data-icon="u">Your userName</label>
                                    <input id="usernamesignup" name="userName" required="required" type="text" placeholder="userName" value="<?php if(!empty($_POST['firstname'])){echo $_POST['userName'];}?>" />
                                </p>
                                 <p> 
                                    <label for="emailsignup" class="youmail" data-icon="e" > Your email</label>
                                    <input id="emailsignup" name="email" required="required" type="email" placeholder="Email Id" value="<?php if(!empty($_POST['firstname'])){echo $_POST['email'];}?>"/> 
                                    
                                </p>
                                 <p>
                                    <label for="department"  style="padding-right:20px">Department</label>
                                        <select id="dept" name="department" style="background:#3b4148;color:rgb(198, 188, 188);border:0px;padding:7px 51px;margin-right:-8px;letter-spacing: 2px;" >
                                          <option value='CSE'>Computer Science Engineering</option>
                                          <option value='EE'>Electrical Engineering</option>
                                          <option value='ME'>Mechanical Engineering</option>
                                          <option value='AERO'>Aerospace Engineering</option>
                                          <option value='CIVIL'>Civil Engineering</option>
                                          <option value='CHEM'>Chemical Engineering</option>
                                          <option value='MSE'>Material Science & Engineering</option>
                                          <option value='BSBE'>Biological Sci. & BioEngineering</option>
                                        </select>               
                                </p>
                                
                                <p> 
                                    <label for="passwordsignup" class=" youpasswd" data-icon="p">Your password </label>
                                    <input id="passwordsignup" name="password" required="required" type="password" placeholder="Password"/>
                                </p>
                                <p> 
                                    <label for="passwordsignup_confirm" class="youpasswd" data-icon="p">Please confirm your password </label>
                                    <input id="passwordsignup_confirm" name="password_again" required="required" type="password" placeholder="Re Enter Password" onblur="check_password()"/>
                                    <!--p class="error_box error_box5">
                                        *password doesn't Match !
                                    </p--> 
                                </p>
                                <p  class="signin button" style="margin-bottom:0px"> 
                                    <input type="submit" name="signup_submit" onclick="check_detail()"  value="Sign up"/> 
                                </p>
                                
                            </div>
                            </form>
                        </div> 
                    </div>
                </div>  
            </section>
        </div>
    </body>
</html>