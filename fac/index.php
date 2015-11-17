<?php 
	require "../core/init.php";
	if(!logged_in()){
		header('Location: ../');
	}else{
		$type = $_SESSION['type'];
		$userName = $_SESSION['userName'];
		if($type == 'std'){
			header('Location: ../std/');
		}
		$flag = get_flag($userName);
		if ($flag == 0) 
           header('Location: update.php');
	}
 ?>
<!DOCTYPE html>
<html lang="en" ng-app="CAL">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Academic Calendar</title>
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="../css/custom.css">
</head>
<body>
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
          <a href="." class="navbar-brand">Academic Calendar</a>
          
          <!-- put navigation menu  -->
          <div class="navbar-collapse collapse navbar-responsive-collapse">
            <ul class="nav navbar-nav pull-right">
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo $userName; ?> <strong class="caret"></strong> </a>
                  <ul class="dropdown-menu">
                    <li><a href="update.php"><span class="glyphicon glyphicon-refresh"></span> Update Courses </a></li>
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
		<div ng-controller="monthCtrl" ng-cloak >
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
		<h3 class="panel-title">
			Select a course to view events.
		</h3>
	</div> 
	<div class="panel-body">
		<form class="form-inline" role="form">
EOF;
						$firstOne = false;
						}
						$course = $rs['course'];
						echo <<<EOF


    <div class="input-group">
    	<span class="input-group-addon">
			<input type="radio" class="radio" ng-model="selectedCourse" id="$course" value="$course" ng-change="grabEvents(selectedCourse)">
		</span>
		<label class="form-control" for="$course">$course</labe>
	</div>
		
EOF;

					}
					if(!$firstOne) echo "</form></div></div>";	
				 ?>
			</div>	
			<div class="row">
				<div class="table-responsive col-md-6" id="monthDivision" >
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
					<div style="text-align: center;">
						<button type="button" class="btn btn-primary" ng-click="addNewEvent()" ng-show="showNewEventButton" data-toggle="modal" data-target=".add-event-model" data-backdrop="static">Add Event</button>
					</div>
				</div>
				<div class="col-md-6">
					<div ng-show="showDiv" class="showEventDiv" id="eventsDiv">
						<!-- this table contains all the events on that day-->
						<table ng-repeat="event in events" class="table table-bordered table-condensed">
							<tr>
								<th>Course:</th> <td> {{event.course}} </td>
							</tr>
							<tr>
								<th>Title:</th> <td> {{event.title}}</td>
							</tr>
							<tr>
								<th>Date:</th> <td> {{event.date}}</td>
							</tr>
							<tr>
								<th>Venue:</th> <td> {{event.venue}}</td>
							</tr>
							<tr>
								<th>Time:</th> <td> {{event.time}}</td>
							</tr>
							<tr ng-show="event.canDelete">
								<th colspan="2"><button type="button" class="btn btn-danger" ng-click="deleteEvent(event)">Delete</button></td>
							</tr>
						</table>
					</div>
					<div style="text-align: center;"> 
						<button type="button" class="btn btn-default" ng-click="ok()" ng-show="showOkButton">Ok</button>
					</div>
					<!-- model for adding events -->
					<div class="modal fade add-event-model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-sm">
						    <div class="modal-content">
							    <div class="modal-header">
							    	<h4 class="modal-title">Add Event</h4>
							    </div>
						      	<div class="modal-body">
									<form  name="myForm" class="form-horizontal" novalidate>	
										<div class="form-group">
											<div class="input-group">
												<span  class="input-group-addon" style="width: 80px;">Course:</span>
										    	<span class="form-control" style="width: 200px;"  id="course" > {{selectedCourse}} </span>
										  	</div>
										</div> 
										<div class="form-group">	
										  	<div class="input-group">
										    	<label for="title"class="input-group-addon" style="width: 80px;">Title:</label>
										    	<input type="text" style="width: 200px;" class="form-control" id="title" ng-model="newEvent.title" required >
										  	</div>
										</div>
										<div class="form-group">
										  	<div class="input-group">
										    	<span  class="input-group-addon" style="width: 80px;">Date:</span>
										    	<span class="form-control" style="width: 200px;" id="course" > {{clickedDate}}  </span>
										  	</div>
										</div>
										<div class="form-group">  	
										  	<div class="input-group">
										    	<label for="venue"class="input-group-addon" style="width: 80px;">Venue:</label>
										    	<input type="text" style="width: 200px;" class="form-control" id="venue" ng-model="newEvent.venue" required  >
										  	</div>
										</div> 
										<div class="form-group">  	
										  	<div class="input-group">
										    	<label for="venue"class="input-group-addon" style="width: 80px;">Time:</label>
										    	<input type="text" style="width: 200px;" class="form-control" id="venue" ng-model="newEvent.time" required  >
										  	</div>
										</div>  	
									</form>
							    </div>
						      	<div class="modal-footer">
						        	<button type="button" class="btn btn-default" data-dismiss="modal" ng-click="cancelNewEvent()">Cancel</button>
						        	<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="submitNewEvent()" ng-disabled="newEvent.title == NULL">Submit</button>
						      	</div>
						    </div>
						</div>
					</div>


					<!-- change password model -->
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
						        	<button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="changePass(changepass)" ng-disabled="changepass.currpass == NULL || changepass.newpass == NULL || changepass.renewpass == NULL || changepass.newpass.length < 6 || changepass.newpass != changepass.renewpass ">Submit</button>
						      	</div>
						    </div>
						</div>
					</div>
				</div>	
			</div>
		</div>
	</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
	<script src="../js/angular-min.js"></script>
	<script src="../js/facapp.js"></script>
</body>
</html>