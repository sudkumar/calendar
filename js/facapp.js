var cal = angular.module('CAL', []);
cal.controller('monthCtrl', ['$scope','$http', function($scope,$http){
	var weekDays = ['Sun','Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
	$scope.date = new Date();
	$scope.baseUrl = "../data/";
	var toDay = new Date();
	$scope.weekDay = weekDays;
	$scope.showDiv = false;
	$scope.eventDates = [];
	$scope.today = toDay.toLocaleString('en-US',{day:'numeric', month:'long', year:'numeric'});
	var firstDay = new Date($scope.date.getFullYear(),$scope.date.getMonth(),1);
	var lastDay = new Date($scope.date.getFullYear(),$scope.date.getMonth()+1,0);
	
	// functions for showing the calendar like previous month, next month 
	$scope.updateMonth = function(){
	 	var startWeekDay = firstDay.toLocaleString('en-US',{ weekday: 'short'});
	 	var lastDate = lastDay.toLocaleString('en-US',{ day: 'numeric' });
	 	var rows = Math.ceil((parseInt(lastDate)+ weekDays.indexOf(startWeekDay))/7);
	 	var newDays = new Array(rows);
	 	var  k = 1;
	 	for (var i = 0; i < rows; i++) {
			newDays[i] = new Array(7);
			for(var j=0;j < 7;j++){
				if(k == 1 && startWeekDay == weekDays[j]){
					newDays[i][j] = k; k++;
				}else{
					if(k > 1 && k <= lastDate){
						newDays[i][j] = k; k++;	
					}else{
						newDays[i][j] = '';
					}
				}	
			}
		}
		$scope.days = newDays;
	}
	$scope.updateMonth();

	$scope.preMonth = function(){
		$scope.date.setMonth($scope.date.getMonth() - 1);
		firstDay = new Date($scope.date.getFullYear(),$scope.date.getMonth(),1);
		lastDay = new Date($scope.date.getFullYear(),$scope.date.getMonth()+1,0);
		$scope.updateMonth();
	}
	$scope.nextMonth = function(){
		$scope.date.setMonth($scope.date.getMonth() + 1);
		firstDay = new Date($scope.date.getFullYear(),$scope.date.getMonth(),1);
		lastDay = new Date($scope.date.getFullYear(),$scope.date.getMonth()+1,0);
		$scope.updateMonth();
	}
	// taking events from database
	$scope.fetch = function(url){
		$http.get(url).
		success(function(data){
			$scope.storedEvents = data;
			$scope.eventDates = [];
			data.forEach(function(event){
				$scope.eventDates.push(event['date']);
			});
		}).
		error(function(data, status){
			$scope.storedEvents = {};
		})
	}	
	// functions for userinterface in calendar like highlight a date if it is an event or show event if it is an event 
	$scope.isEvent = function(day){
		var year = $scope.date.getFullYear();
		var month = $scope.date.toLocaleString('en-US',{ month: "short" });
		if($scope.eventDates.indexOf( day + " " + month + " " +year) != -1){
			return true;
		}else{
			return false;
		}	
	}

	// return whether or not we are after the today
	$scope.isAfterToday = function(day){
		var currYear = toDay.toLocaleString('en-US',{ year: 'numeric'});
		var currMonth = toDay.toLocaleString('en-US', {month: 'numeric'});
		var currDay = toDay.toLocaleString('en-US', {day: 'numeric'});
		var scopeYear = $scope.date.toLocaleString('en-US',{ year: 'numeric'});
		var scopeMonth = $scope.date.toLocaleString('en-US', {month: 'numeric'});
		if(currYear < scopeYear){
			return true;
		}else{
			if(currYear == scopeYear){
				if(currMonth < scopeMonth){
					return true;
				}else{
					if(currMonth == scopeMonth){
						if(currDay <= day){
							return true;
						}else{
							return false;
						}
					}else{
						return false;
					}
				}
			}else{
				return false;
			}
		}
	}
	$scope.showEvent = function(day){
		if(day > 0 && $scope.selectedCourse){
			$scope.showDiv = false;
			$scope.events = {};	
			$scope.showOkButton = false;
			$scope.showNewEventButton = false;
			var year = $scope.date.getFullYear();
			var month = $scope.date.toLocaleString('en-US',{ month: "short" });
			$scope.clickedDate = day + " " + month + " " + year ;
			if($scope.isAfterToday(day) || $scope.isEvent(day)){
				if(!$scope.showNewEventDiv){
					$scope.showNewEventButton = true;
				}
				if($scope.isEvent(day)){
					// show the events
					$scope.showDiv = true;
					$scope.showOkButton = true;
					$scope.showNewEventDiv = false;
					$scope.showNewEventButton = true;
					for(var i=0;i<$scope.eventDates.length;i++){
						if($scope.eventDates[i] == $scope.clickedDate){
							var dummy = {};
							dummy.id = $scope.storedEvents[i]['id'];
							dummy.title = $scope.storedEvents[i]['title'];
							dummy.venue = $scope.storedEvents[i]['venue'];
							dummy.course = $scope.storedEvents[i]['course'];
							dummy.date = $scope.storedEvents[i]['date'];
							dummy.time = $scope.storedEvents[i]['time'];
							dummy.canDelete = $scope.storedEvents[i]['canDelete'];
							$scope.events[i] = dummy;
						}
					}
				}
			}else{
				$scope.showNewEventDiv = false;
				$scope.showNewEventButton = false;
			}	
		}
		if(!$scope.selectedCourse){
			alert("Please select a course");
		}	
	}
	$scope.submitNewEvent = function(){
		$http({
		    method: 'POST',
		    url: $scope.baseUrl+'fac/addEvent.php',
		    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
		    transformRequest: function(obj) {
		        var str = [];
		        for(var p in obj)
		        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
		        return str.join("&");
		    },
		    data: {
		    	course: $scope.selectedCourse, 
		    	title: $scope.newEvent.title,
		    	date: $scope.clickedDate,
		    	venue: $scope.newEvent.venue,
		    	time: $scope.newEvent.time
		    }
		})
		.success(function (data){
			$scope.queryMessage = data;
			alert(data);
			$scope.fetch($scope.baseUrl+"fac/fetchEvent.php?requestedCourse=" + $scope.selectedCourse);
			$scope.cancelNewEvent();
			$scope.newEvent = {};
		})
		.error(function (data){
			$scope.queryMessage = "Error";
			alert("Internal error, Please try after some time");
		});

	}
	$scope.ok = function(){
		$scope.showDiv = false;
		$scope.showOkButton = false;
	}
	$scope.cancelNewEvent = function(){
		$scope.showNewEventDiv = false;
		$scope.showNewEventButton = false;
	}

	$scope.deleteEvent = function(deleteEvent){
		if(confirm('Are you sure you want to delete this event')){
			$http({
			    method: 'POST',
			    url: $scope.baseUrl+'fac/deleteEvent.php',
			    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			    transformRequest: function(obj) {
			        var str = [];
			        for(var p in obj)
			        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
			        return str.join("&");
			    },
			    data: {
			    	id: deleteEvent.id
			    }
			})
			.success(function (data){
				$scope.queryMessage = data;
				alert(data);
				$scope.fetch($scope.baseUrl+"fac/fetchEvent.php?requestedCourse="+$scope.selectedCourse);
				$scope.ok();
				$scope.cancelNewEvent();
			})
			.error(function (data, status, headers){
				$scope.queryMessage = "Error";
				alert("Internal error");
			});
		}
	}
	$scope.addNewEvent = function(){
		$scope.ok();
		$scope.showNewEventDiv = true;
		$scope.showNewEventButton = false;
	}

	$scope.grabEvents = function(selectedCourse){
		$scope.ok();
		$scope.cancelNewEvent();
		$scope.fetch($scope.baseUrl+"fac/fetchEvent.php?requestedCourse="+selectedCourse);

	}
	$scope.changePass = function(changepass){
		$http({
			    method: 'POST',
			    url: $scope.baseUrl+'fac/changepass.php',
			    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			    transformRequest: function(obj) {
			        var str = [];
			        for(var p in obj)
			        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
			        return str.join("&");
			    },
			    data: {
			    	currPass: changepass.currpass,
			    	newPass: changepass.newpass,
			    	renewPass: changepass.renewpass
			    }
			})
			.success(function (data){
				if(data == "Successfully Updated"){
					$scope.changepass = {};
				}
				alert(data);
			})	
			.error(function (data, status, headers){
				alert(data);
			});
	}

}])
