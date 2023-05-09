<script>
  
gantt.templates.resource_cell_class = function(start_date, end_date, resource, tasks) {
    var css = [];
    css.push("resource_marker");
    if (tasks.length <= 1) {
      css.push("workday_ok");

    }
  else {
      css.push("workday_over");

    }
    return css.join(" ");
  };

  
  
  
  
  
  
    
  gantt.templates.resource_cell_value = function(start_date, end_date, resource, tasks) {
    var shiftHours = '<img src="img/svg/check-1.svg" style="height: 14px;">';

    for (var task of tasks) {
      var calendarID = task.calendar_id;
      if (window.ibex_gantt_config.currentZoomLevel == "day") {

          var html = "<div>"

          html += shiftHours;

          html += "</div>";
          return html;

      }

      if (window.ibex_gantt_config.currentZoomLevel == "hour") 
		{
			
			
        var shiftMins = '<img src="img/svg/close.svg" style="height: 14px;">';
        
        var from = new Date(task.start_date);
      var to = new Date(task.end_date);
      var noWorkShifts = getNonWorkingPeriods(from, to, task.calendar_id, task.end_date);
      for (var i = 0; i < noWorkShifts.length; i++) {
      
      
      var shiftMins = '<img src="img/svg/check-1.svg" style="height: 14px;">';
    }
        
	 
            var html = "<div>"

				// Check resource should be shown here based on res calendar limits and the current start / end
				
				// Load calendar for this res
			var calendar;
			$.each(window.ibex_gantt_config.calendars, function(index) 
			{
	        if (window.ibex_gantt_config.calendars[index].id == resource.calendar_id) 
			  {
	          calendar = window.ibex_gantt_config.calendars[index];
	        }
	 	 });
		 
		 // Parsing into HH:mm format only for moment
		 var calendarStartTime = moment(calendar.start_time, "HH:mm");
		 var calendarEndTime = moment(calendar.end_time, "HH:mm");
		 var shortStartDate = moment(start_date).format("HH:mm");
		 var shortEndDate = moment(end_date).format("HH:mm");
			var startTime = moment(shortStartDate, "HH:mm");
			var endTime = moment(shortEndDate, "HH:mm");
			
			if (startTime.isSameOrAfter(calendarStartTime) && endTime.isSameOrBefore(calendarEndTime) && endTime.format("HH:mm") != "00:00")
			{
				
				// Valid time - show icon
				 html += shiftMins;
				 html += "</div>";
			}	
			else 
			{
				// invalid time (outside resource calendar limits) - return blank
				 html += "</div>";
			}
			
         return html;
        
      }

    }

  }





































  gantt.templates.link_class = function(link) {};
  var height = document.getElementById('container').clientHeight;
  $("#gantt_here").addClass("animated fadeIn").css("height", height - 95 + "px").css("color", "<?=$_SESSION['user']['opacity_font']?>");
  gantt.templates.grid_row_class = function(start, end, task) {
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