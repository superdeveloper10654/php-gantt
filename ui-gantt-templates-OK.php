<script>
  
  gantt.templates.resource_cell_class = function(start_date, end_date, resource, tasks) {
    var css = [];
    css.push("resource_marker");
    if (tasks.length <= 1) {
      css.push("workday_ok");
    } else {
      css.push("workday_over");
    }
    return css.join(" ");
  };

  gantt.templates.resource_cell_value = function(start_date, end_date, resource, tasks) 
  {
	  var shiftHours = 0;
	 // if (window.ibex_gantt_config.currentZoomLevel == "day")
		//{
      
    var validDay;
    for (var task of tasks) {
      var calendarID = task.calendar_id;
      validDay = isDateWorkingDate(moment(start_date).format("YYYY-MM-DD"), calendarID);
      if (validDay == true) {
        for (var calendar of window.ibex_gantt_config.calendars) {
          var taskStartDate = moment(task.start_date);
          var taskEndDate = moment(task.end_date);
          if (calendar['id'] == calendarID) 
			 {
            var endTime = calendar['end_time'];
            var startDate = moment(start_date);
            var endDate = moment(end_date);
            var checkStartDate = taskStartDate.isBetween(startDate, endDate, null, []);
            var checkEndDate = taskEndDate.isBetween(startDate, endDate, null, []);
            var taskStartDateTime = moment(task.start_date).format("HH:mm");
                var taskStartTime = moment(taskStartDateTime, "HH:mm");
                var taskEndTime = moment(padLeadingZero(calendar['end_hour']) + ":" + padLeadingZero(calendar['end_minute']), "HH:mm");
var hours_worked = 0;
         
         var spansMidnight = false;
         
            if (checkStartDate == true || checkEndDate == true) 
				{
              if (checkStartDate == true) 
				  {     
            
            
        
        $.each(window.ibex_gantt_config.calendars, function(index) {
          if (window.ibex_gantt_config.calendars[index].id == task.calendar_id) {
            endTime = padLeadingZero(window.ibex_gantt_config.calendars[index].end_hour) + ":" + padLeadingZero(window.ibex_gantt_config.calendars[index].end_minute);
            if (window.ibex_gantt_config.calendars[index].start_hour > window.ibex_gantt_config.calendars[index].end_hour) {
              spansMidnight = false;
              //shiftHours = Math.abs(24 - +calendar.start_hour + +calendar.end_hour);
              shiftHours =555;
            } else {
              spansMidnight = true;
              //shiftHours = Math.abs(+calendar.end_hour - +calendar.start_hour);
              shiftHours =333;
            }
                 


          
            }
            
          }
               )
        }
       }
        }
      }
    }
    }
  
  
  
  var validDay;
    
    for (var task of tasks) {
      var calendarID = task.calendar_id;
      validDay = isDateWorkingDate(moment(start_date).format("YYYY-MM-DD"), calendarID);
      if (validDay == true) {
		
			
        for (var calendar of window.ibex_gantt_config.calendars) {
          var taskStartDate = moment(task.start_date);
          var taskEndDate = moment(task.end_date);
          if (calendar['id'] == calendarID) 
			 {
            var endTime = calendar['end_time'];
            var startDate = moment(start_date);
            var endDate = moment(end_date);
            var checkStartDate = taskStartDate.isBetween(startDate, endDate, null, []);
            var checkEndDate = taskEndDate.isBetween(startDate, endDate, null, []);
            var taskStartDateTime = moment(task.start_date).format("HH:mm");
                var taskStartTime = moment(taskStartDateTime, "HH:mm");
                var taskEndTime = moment(padLeadingZero(calendar['end_hour']) + ":" + padLeadingZero(calendar['end_minute']), "HH:mm");
               /* var duration = moment.duration(taskEndTime.diff(taskStartTime));
                var minutesInPeriod = parseInt(duration.asMinutes());
                var hoursInPeriod = Math.ceil(minutesInPeriod / 60);*/
            var duration_worked = task['duration_worked'];

            if (checkStartDate == true || checkEndDate == true) 
				{
              if (checkStartDate == true) 
				  {         
					 
					 
shiftHours = Math.abs(calendar['end_hour'] - calendar['start_hour']);
//shiftHours = 123;  

				  }
               
				  else 
				  {
					  
            
            
            
            
            
            
            
            
            
            
					  // End
					 var endDateOnly = moment(task.end_date).format("YYYY-MM-DD");
						
					  // Shift length in hours?
					   var a = moment(endDateOnly + " " + padLeadingZero(calendar['start_hour']) + ":" + padLeadingZero(calendar['start_minute']) + ":00");
					
var b = moment(endDateOnly + " " + padLeadingZero(calendar['end_hour']) + ":" + padLeadingZero(calendar['end_minute']) + ":00");

var shiftLength = Math.abs(b.diff(a, 'hours'));

					  

					  var a = moment(task.end_date);
					
//var b = moment(endDateOnly + " " + padLeadingZero(calendar['end_hour']) + ":" + padLeadingZero(calendar['end_minute']) + ":00");

var diff = Math.abs(b.diff(a, 'hours'));// 12


//shiftHours += Math.abs(shiftLength - diff);
//shiftHours =777;
shiftHours = Math.abs(calendar['end_hour'] - calendar['start_hour']);
//shiftHours = 123;





            
            
            
            
            
            
            
            
            
              }
            } else {
				
              shiftHours += Math.abs(calendar['end_hour'] - calendar['start_hour']);
              //shiftHours =999;
            }
            break;
          }
        }
      }
    }
  
  
  
  
  
  
  
  
  
  
  
    
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
   if (validDay) 
	 {
		
      var html = "<div>"
	
		if (window.ibex_gantt_config.currentZoomLevel == "day")
		{
        html += shiftHours;
		  // Add to localstorage
	
		  window.localStorage.setItem("resource_allocation_" + moment(start_date).format("YYYY-MM-DD"), shiftHours);
      } 

      html += "</div>";
		
      return html;
    }
  
  
  
  
  
  
  
  
  
  
  
  
  }
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  gantt.templates.link_class = function(link) {};

  var height = document.getElementById('container').clientHeight;
  $("#gantt_here").css("height", height - 95 + "px").css("color","<?=$_SESSION['user']['opacity_font']?>");

  gantt.templates.grid_row_class = function(start, end, task) {
    // console.log(task);
    if (task.manually_delayed == "1") {
      return "schedule-delayed";
    }
    if (task.progress == "100") {
      return "task-complete";
    }
    if (task.baseline_start != "") {
      return "schedule-delayed";
    }
  };

  gantt.templates.grid_row_class = function(start, end, task) {
    if (ibex_gantt_config.filterType == 2 && ibex_gantt_config.filterValue != "") {
      if (task.type == "task" && task.text.toLowerCase().indexOf(ibex_gantt_config.filterValue.toLowerCase()) >= 0) {
        return "task_highlighted";
      } else {
        return "";
      }
    } else if (task.type == "project") {
      return "row-project";
    } else if (task.type == "task") {
      if (task.is_summary == "1") {
        return "summary";
      }
    } else if (task.type == "button") {
      return "add-new-task-row";
    } else if (task.type == "milestone") {
      return "milestone";
    } else {
      return "";
    }
  };

  gantt.templates.task_row_class = function(start, end, task) {
    if (shouldHighlightTask(task)) {
      return "highlighted_resource";
    }
    return "";
  };
  
  
  
  
  var markerId = gantt.addMarker({
        start_date: new Date(),
        css: "today",
        text: null,
        title: null
      });
  
  gantt.addTaskLayer(function(task) {
          if (task.type == "task" && task.is_summary == "0") {
            var from = new Date(task.start_date);
            var to = new Date(task.end_date);
            var noWorkShifts = getNonWorkingPeriods(from, to, task.calendar_id, task.end_date);
            var shiftsDiv = document.createElement("div");
            for (var i = 0; i < noWorkShifts.length; i++) {
              var sizes = gantt.getTaskPosition(task, noWorkShifts[i].start_date, noWorkShifts[i].end_date);
              var el = document.createElement("div");
              el.className = "no-work-shift task-layer-" + task.guid;
              el.style.left = sizes.left + 'px';
              el.style.top = sizes.top + 3 + 'px';
              el.style.width = sizes.width + 'px';
              el.style.height = (sizes.height - 5) + 'px';
              shiftsDiv.appendChild(el);
            }
            return shiftsDiv;
          }
        });

  
</script>