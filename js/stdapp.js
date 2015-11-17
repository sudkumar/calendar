var cal = angular.module('CAL', []);
cal.controller('monthCtrl', ['$scope','$http','$interval', function($scope, $http, $interval){
	var weekDays = ['Sun','Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
	$scope.date = new Date();
	var toDay = new Date();
	$scope.baseUrl = "../data/";
	$scope.weekDay = weekDays;
	$scope.showDiv = false;
	$scope.eventDates = [];
	$scope.showNewEventsNotification = false;
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

	// fetch the newly added events from storedEvents
	$scope.newlyAddedEvent = function(){
		$scope.storedNewlyAddedEvent = {};	
		for(var i=0, j=0;i<$scope.eventDates.length;i++){
			if($scope.storedEvents[i]['eventSeen'] == '0'){
				$scope.storedNewlyAddedEvent[j] = $scope.storedEvents[i];
				j++;
			}
		}
		if(j>0){
			$scope.showNewEventsNotification = true;
		}else{
			$scope.showNewEventsNotification = false;
		}
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
			$scope.newlyAddedEvent();
		}).
		error(function(data, status){
			$scope.storedEvents = {};
		})
	}
	$scope.fetch($scope.baseUrl+"std/myEvents.php");
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
	// show the function on click
	$scope.showEvent = function(day){
		if(day > 0){
			$scope.showDiv = false;
			$scope.showOkButton = false;
			$scope.events = {};
			var year = $scope.date.getFullYear();
			var month = $scope.date.toLocaleString('en-US',{ month: "short" });
			var clickedDate = day + " " + month + " " + year ;
			if($scope.isEvent(day)){
				$scope.showDiv = true;
				$scope.showOkButton = true;
				for(var i=0;i<$scope.eventDates.length;i++){
					if($scope.eventDates[i] == clickedDate){
						var dummy = {};
						dummy.title = $scope.storedEvents[i]['title'];
						dummy.venue = $scope.storedEvents[i]['venue'];
						dummy.course = $scope.storedEvents[i]['course'];
						dummy.date = $scope.storedEvents[i]['date'];
						dummy.time = $scope.storedEvents[i]['time'];
						$scope.events[i] = dummy;
						if($scope.storedEvents[i]['eventSeen'] == '0'){
							$scope.viewNotification($scope.storedEvents[i]['id']);
						}
					}
				}
			}	
		}	
	}
	
	$scope.viewNotification = function(id){
		$scope.events = $scope.storedNewlyAddedEvent;
		$scope.showDiv = true;
		$scope.showOkButton = true;
		$scope.showNewEventsNotification = false;
		$http.get($scope.baseUrl+"std/seenUpdate.php?id="+id).
		success(function(data){
				
		}).
		error(function(data, status){
			
		})
	}

	$scope.ok = function(){
		$scope.showDiv = false;
		$scope.showOkButton = false;
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

	$interval(function(){$scope.fetch($scope.baseUrl+"std/myEvents.php");}, 5000);	
	

}])
