<?php 
	require '../core/init.php';
	if(!logged_in()){
		header('Location: ../');
	}else{
		$type = $_SESSION['type'];
		$userName = $_SESSION['userName'];
		if($type == 'fac'){
			header('Location: ../fac/');
		}
		$flag = get_flag($userName);
		if ($flag == 0) 
           header('Location: update_student.php');
	}
	
 ?>
<!DOCTYPE html>
<html xmlns:ng="http://angularjs.org" id="ng-app" ng-app="CAL">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Academic Calendar</title>
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="../css/custom.css">
</head>
<body ng-controller="monthCtrl">


<!-- top notificaiont bar-->
<div class="fluid-container" ng-show="showNewEventsNotification" ng-cloak>
 	<div class="panel panel-info" id="notification">	
 		<div class="panel-heading">
 			<div class="container">
 				<strong>There are newly added events in your courses.</strong><button class="btn btn-success btn-sm pull-right" ng-click="viewNotification(-1)">View</button>	
 			</div>
 		</div>
 	</div>		
</div>      	

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
            	        <li data-toggle="modal" data-target=".change-pass-model" data-backdrop="static" ><a href="#"><span class="glyphicon glyphicon-lock"></span> Change Password </a></li>
                	    <li class="divider"></li>
                    	<li><a href="logout.php"><span class="glyphicon glyphicon-off"></span> Logout </a></li>
                  	</ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
		<div  ng-cloak>
			<div class="row">
				<div class="table-responsive col-md-8" id="monthDivision" >
					<table class="table table-bordered">
						<thead>
							<tr>
								<th colspan="7">{{today}}</th>
							</tr>
							<tr>
								<th colspan="7" >{{date.getFullYear()}}</th>	
							</tr>
							<tr>
								<td type="button" class="btn btn default" ng-click="preMonth()"> 
									 <span class="glyphicon glyphicon-hand-left" aria-hidden="true"></span> 
								</td>
								<th colspan="5" id="month">{{date.toLocaleString('en-US',{ month: "long" })}}</th>
								<td type="button" class="btn btn default" ng-click="nextMonth()">   
									<span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span> 
								</td>
							</tr>
							<tr>
								<th ng-repeat="day in weekDay">{{day}}</th>
							</tr>	
						</thead>
						<tbody>
							<tr ng-repeat="line in days ">
								<td ng-repeat="day in line track by $index" ng-class="{'success': isEvent(day), 'hover': day>0}" ng-click="showEvent(day)">{{day}}</td>			
							</tr>
						</tbody>
					</table>
				</div>	
				<div class="col-md-4">
					<div ng-show="showDiv" class="showEventDiv" id="eventsDiv">
						<table ng-repeat="event in events" class="table table-bordered table-condensed">
							<tr>
								<th>Course:</th><td> {{event.course}}</td>
							</tr>
							<tr>
								<th>Title:</th>	<td> {{event.title}}</td>
							</tr>
							<tr>
								<th>Date:</th>	<td> {{event.date}}</td>
							</tr>
							<tr>
								<th>Venue:</th>	<td> {{event.venue}}</td>
							</tr>
							<tr>
								<th>Time:</th>	<td> {{event.time}}</td>
							</tr>
						</table>
					</div>
					<div style="text-align: center;"> 
						<button type="button" class="btn btn-default" ng-click="ok()" ng-show="showOkButton">Ok</button>
					</div>
				</div>
			</div>


			<!-- change pass model -->
			<div class="modal fade change-pass-model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg" style="max-width: 500px;">
				    <div class="modal-content">
					    <div class="modal-header">
					    	<h4 class="modal-title">Change Password</h4>
					    </div>
				      	<div class="modal-body">
							<form  name="myForm" class="form-horizontal" novalidate role="form">	 
								<div class="form-group">
								    <label for="pass1" class="col-sm-4 control-label">Current password:</label>
								    <div class="col-sm-8">
								      <input type="password" class="form-control" id="pass1" ng-model="changepass.currpass">
								    </div>
								  </div>
								<div class="form-group">
								    <label for="pass2" class="col-sm-4 control-label">New password:</label>
								    <div class="col-sm-8">
								      <input type="password" class="form-control" id="pass2" ng-model="changepass.newpass">
								    </div>
								  </div>
								<div class="form-group">
								    <label for="pass3" class="col-sm-4 control-label">Re-new password:</label>
								    <div class="col-sm-8">
								      <input type="password" class="form-control" id="pass3" ng-model="changepass.renewpass">
								    </div>
								  </div>  	
							</form>
					    </div>
				      	<div class="modal-footer">
				        	<button type="button" class="btn btn-default" data-dismiss="modal" >Cancel</button>
				        	<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="changePass(changepass)" ng-disabled="changepass.currpass == NULL || changepass.newpass == NULL || changepass.renewpass == NULL || changepass.newpass.length < 6 || changepass.newpass != changepass.renewpass">Submit</button>
				      	</div>
				    </div>
				</div>
			</div>		
		</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
	<script src="../js/angular-min.js"></script>
	<script src="../js/stdapp.js"></script>
</body>
</html>
