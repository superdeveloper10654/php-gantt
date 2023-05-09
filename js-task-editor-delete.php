
gantt.attachEvent("onBeforeTaskDelete", function(id, item) {
        $.getJSON("beta.ajax.php?action=delete_task&task_id=" + id, function(data) {
          window.ibex_gantt_config.activityPrimaryGUID = item.guid;
          window.ibex_gantt_config.activitySecondaryGUID = '';
          window.ibex_gantt_config.activityInfo = '';
          window.ibex_gantt_config.activityAction = "deleted"
          window.ibex_gantt_config.activityType = "task";
			 
          setTimeout(function() {
            reloadActivityFeed();
				
				gantt.clearAll();
				gantt.load('data.php');
				var dp = new gantt.dataProcessor("data.php");
        dp.setTransactionMode("GET", true);
        dp.enableDebug(true);
        dp.init(gantt);
				
		
   function shouldHighlightResource(resource) {

        var selectedTaskId = gantt.getState().selected_task;
        if (gantt.isTaskExists(selectedTaskId)) {
          var selectedTask = gantt.getTask(selectedTaskId),
            selectedResource = selectedTask[gantt.config.resource_property];

          if (resource.id == selectedResource) {
            return true;
          } else
          if (gantt.$resourcesStore.isChildOf(selectedResource, resource.id)) {
            return true;
          }
        }
        return false;
      }

		
      var resourceTemplates = {
        grid_row_class: function(start, end, resource) {
          var css = [];
          if (gantt.$resourcesStore.hasChild(resource.id)) {
            css.push("folder_row");
            css.push("group_row");
          }
          if (shouldHighlightResource(resource)) {
            css.push("highlighted_resource");
          }
          return css.join(" ");
        },
        task_row_class: function(start, end, resource) {
          var css = [];
          if (shouldHighlightResource(resource)) {
            css.push("highlighted_resource");
          }
          if (gantt.$resourcesStore.hasChild(resource.id)) {
            css.push("group_row");
          }

          return css.join(" ");

        }
      };

		//console.log("LAYOUT");
		//console.log(window.ibex_gantt_config.resourceConfig);
  
  gantt.config.layout = {
          css: "gantt_container",
          rows: [{
              gravity: 2,
              id: "main-gantt", // Added by RB 02.12.18
              cols: [{
                  view: "grid",
                  group: "grids",
                  scrollY: "scrollVer",
                },
                {
                  resizer: true,
                  width: 1
                },
                {
                  view: "timeline",
                  scrollX: "scrollHor",
                  scrollY: "scrollVer"
                },
                {
                  view: "scrollbar",
                  id: "scrollVer",
                  group: "vertical"
                }
              ]
            },
            {
              resizer: true,
              width: 1,
              next: "resources-gantt"
            },
            {
              gravity: 1,
              id: "resources-gantt",
              config: window.ibex_gantt_config.resourceConfig,
              templates: resourceTemplates,
              cols: [{
                  view: "resourceGrid",
                  group: "grids",
                  scrollY: "resourceVScroll"
                },
                {
                  resizer: true,
                  width: 1
                },
                {
                  view: "resourceTimeline",
                  scrollX: "scrollHor",
                  scrollY: "resourceVScroll"
                },
                {
                  view: "scrollbar",
                  id: "resourceVScroll",
                  group: "vertical"
                }
              ]
            },
            {
              view: "scrollbar",
              id: "scrollHor"
            }
          ]
			 };
			 
			 
			 
			 
			 
				
				
				 gantt.config.lightbox.sections = [{
          name: "description",
          height: 38,
          map_to: "text",
          type: "textarea",
          focus: true
        },
        {
          name: "owner",
          height: 22,
          map_to: "owner_id",
          type: "select",
          options: gantt.serverList("people")
        },
        {
          name: "time",
          type: "duration",
          map_to: "auto"
        }
		  ];
		  
		  
		     var resourceGroups = window.ibex_gantt_config.resource_groups;
		console.log('groups are');
		console.log(resourceGroups);
        var parsedGroups = [];
        // Add resources to UI and datastores
 
        $.each(resourceGroups, function(index) 
		  {
          var uiObject = {
            id: resourceGroups[index].id,
            text: resourceGroups[index].name,
            is_group: resourceGroups[index].is_group,
            parent: 0
				
          };
          parsedGroups.push(uiObject);
			 console.log(uiObject);
        
        });
		  
		  console.log('finished groups are');
		  console.log(parsedGroups);
        gantt.$resourcesStore.parse(parsedGroups);
		  
		  
		  
		  
		   var resources = window.ibex_gantt_config.resources;
	  $.each(resources, function(index) {
          gantt.$resourcesStore.parse([{
            id: parseInt(resources[index].id),
            text: resources[index].name,
            calendar_id: resources[index].calendar_id,
            cost_rate: resources[index].cost_rate,
            notes: resources[index].notes,
            unit_of_measure: resources[index].unit_of_measure,
            company: resources[index].company,
            is_group: resources[index].is_group,
            parent: resources[index].group_id,
            outputs_unit: resources[index].outputs_unit,
          }]);
		 });
		 
		 

		 
		  
		  
		  
      gantt.$resourcesStore = gantt.createDatastore({
        name: gantt.config.resource_store,
        type: "treeDatastore",
        initItem: function(item) {
			  
          item.parent = item.parent || gantt.config.root_id;
          item[gantt.config.resource_property] = item.parent;
          item.open = true;
			 console.log("ITEM IS ");
			 console.log(item);
          return item;
        }
      });

      gantt.$resourcesStore.attachEvent("onAfterSelect", function(id) {
        gantt.refreshData();
	  });
	  
	  
	  
	  
	  
	  
	  
      function toggleGroups(input) {
        gantt.$groupMode = !gantt.$groupMode;
        if (gantt.$groupMode) {
          input.value = "show gantt view";
          var groups = gantt.$resourcesStore.getItems().map(function(item) {
            var group = gantt.copy(item);
            group.group_id = group.id;
            group.id = gantt.uid();
            return group;
          });
          gantt.groupBy({
            groups: groups,
            relation_property: gantt.config.resource_property,
            group_id: "group_id",
            group_text: "text"
          });
        } else {
          input.value = "show resource view";
          gantt.groupBy(false);
        }
      }

      gantt.$resourcesStore.attachEvent("onParse", function() {
        var people = [];
        gantt.$resourcesStore.eachItem(function(res) {
		  console.log('workong on ');
		  console.log(res);
			  
          if (!gantt.$resourcesStore.hasChild(res.id)) {
            var copy = gantt.copy(res);
            copy.key = parseInt(res.id);
            copy.label = res.text;
            copy.resource_id = parseInt(res.id);
            people.push(copy);
          }
        });
		  
		  console.log('people are');
		  console.log(people);
        gantt.updateCollection("people", people);
	  });
	  
	  
	  
	  
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
	  
	  // Split out per view
	  var shiftHours = 0;
	  if (window.ibex_gantt_config.currentZoomLevel == "day")
		{
		
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
            var hours_worked = duration_worked / 60;
                var cal1 = 0;
                var cal2 = 0;
                var cal3 = 0;
                var cal4 = 0;
                var cal5 = 0;
                var cal6 = 0;
                var cal7 = 0;
                if (calendar['working_day_monday'] == '1') {
                cal1 = 1;
                }
                if (calendar['working_day_tuesday'] == '1') {
                cal2 = 1;
                }
                if (calendar['working_day_wednesday'] == '1') {
                cal3 = 1;
                }
                if (calendar['working_day_thursday'] == '1') {
                cal4 = 1;
                }
                if (calendar['working_day_friday'] == '1') {
                cal5 = 1;
                }
                if (calendar['working_day_saturday'] == '1') {
                cal6 = 1;
                }
                if (calendar['working_day_sunday'] == '1') {
                cal7 = 1;
                }
					 
                var cal_sum = cal1 + cal2 + cal3 + cal4 + cal5 + cal6 + cal7;
            var hours_worked = duration_worked / 60;
            var max_hours_worked_in_week = hours_worked  // to be expanded out to each month
            
            if (checkStartDate == true || checkEndDate == true) 
				{
              if (checkStartDate == true) 
				  {         
					 
					 if (window.ibex_gantt_config.currentZoomLevel == "day")
		{
			// Days
					   var startDateOnly = moment(task.start_date).format("YYYY-MM-DD");
						
						
					  // Shift length in hours?
					   var a = moment(startDateOnly + " " + padLeadingZero(calendar['start_hour']) + ":" + padLeadingZero(calendar['start_minute']) + ":00");
					
var b = moment(startDateOnly + " " + padLeadingZero(calendar['end_hour']) + ":" + padLeadingZero(calendar['end_minute']) + ":00");

var shiftLength = Math.abs(b.diff(a, 'hours'));

					  

					  var a = moment(task.start_date);
					
var b = moment(startDateOnly + " " + padLeadingZero(calendar['start_hour']) + ":" + padLeadingZero(calendar['start_minute']) + ":00");

var diff = Math.abs(b.diff(a, 'hours'));// 12


					  shiftHours += Math.abs(shiftLength - diff);
					  
				  }
				  
				  
              } 
				  else 
				  {
					  
					  // End
					 var startDateOnly = moment(task.end_date).format("YYYY-MM-DD");
						
						
					  // Shift length in hours?
					   var a = moment(startDateOnly + " " + padLeadingZero(calendar['start_hour']) + ":" + padLeadingZero(calendar['start_minute']) + ":00");
					
var b = moment(startDateOnly + " " + padLeadingZero(calendar['end_hour']) + ":" + padLeadingZero(calendar['end_minute']) + ":00");

var shiftLength = Math.abs(b.diff(a, 'hours'));

					  

					  var a = moment(task.end_date);
					
var b = moment(startDateOnly + " " + padLeadingZero(calendar['end_hour']) + ":" + padLeadingZero(calendar['end_minute']) + ":00");

var diff = Math.abs(b.diff(a, 'hours'));// 12


shiftHours += Math.abs(shiftLength - diff);






              }
            } else {
				
              shiftHours += Math.abs(calendar['end_hour'] - calendar['start_hour']);
            }
            break;
          }
        }
      }
    }
	 
	 
	 
 }
 
 
 	// Week 
	if (window.ibex_gantt_config.currentZoomLevel == "month")
		{
			
			
		  var defaultTaskCalendarID;
		   $.each(window.ibex_gantt_config.calendars, function(index) {
        if (window.ibex_gantt_config.calendars[index].is_default_task_calendar == 1) {
          defaultTaskCalendarID = window.ibex_gantt_config.calendars[index].id;
			
        }
	  });
	  
		  var datesWithin = enumerateDaysBetweenDates(start_date, end_date, defaultTaskCalendarID);
		  datesWithin.pop();
		       for (var dateWithin of datesWithin) 
				 {
					
					 // Get local storage value
				
					 if (window.localStorage.getItem("resource_allocation_" + dateWithin))
					{
						validDay = true;
						shiftHours += parseInt(window.localStorage.getItem("resource_allocation_" + dateWithin));
					}
					 
				 }
 	}
	
	
	
	
	
 	// Week 
	if (window.ibex_gantt_config.currentZoomLevel == "quarter")
		{
			
			
		  
		  var defaultTaskCalendarID;
		   $.each(window.ibex_gantt_config.calendars, function(index) {
        if (window.ibex_gantt_config.calendars[index].is_default_task_calendar == 1) {
          defaultTaskCalendarID = window.ibex_gantt_config.calendars[index].id;
			
        }
	  });
	  
		  var datesWithin = enumerateDaysBetweenDates(start_date, end_date, defaultTaskCalendarID);
		  datesWithin.pop();
		       for (var dateWithin of datesWithin) 
				 {
				
					 // Get local storage value
				
					 if (window.localStorage.getItem("resource_allocation_" + dateWithin))
					{
						validDay = true;
						shiftHours += parseInt(window.localStorage.getItem("resource_allocation_" + dateWithin));
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
		if (window.ibex_gantt_config.currentZoomLevel == "month")
		{
        html += shiftHours;
	  } 
	  if (window.ibex_gantt_config.currentZoomLevel == "quarter")
		{
        html += shiftHours;
	  } 
      html += "</div>";
		
      return html;
    }
	 
	 
	 
	 
	 
	 
	 };
	 
		    
				 
				 console.log('gantt obj 2');
				 console.log(gantt);
		 
	  
          }, 1000);
          $.ajax({
            url: 'beta.ajax.php?action=snapshot_gantt',
            type: 'POST',
            dataType: 'json',
            success: function(data) {
              var lastSaveTime = data.save_time;
              window.ibex_gantt_config.lastSaveTime = Number(lastSaveTime) + 1;
            },
            data: {
              primary_guid: window.ibex_gantt_config.activityPrimaryGUID,
              secondary_guid: window.ibex_gantt_config.activitySecondaryGUID,
              action_type: window.ibex_gantt_config.activityAction,
              type: window.ibex_gantt_config.activityType,
              info: window.ibex_gantt_config.activityInfo,
              gantt_data: window.ibex_gantt_config.snapshotData
            }
          });
          window.ibex_gantt_config.lastSnapshotTime = moment().unix();
			 
				var resourceTemplates = {
        grid_row_class: function(start, end, resource) {
          var css = [];
          if (gantt.$resourcesStore.hasChild(resource.id)) {
            css.push("folder_row");
            css.push("group_row");
          }
          if (shouldHighlightResource(resource)) {
            css.push("highlighted_resource");
          }
          return css.join(" ");
        },
        task_row_class: function(start, end, resource) {
          var css = [];
          if (shouldHighlightResource(resource)) {
            css.push("highlighted_resource");
          }
          if (gantt.$resourcesStore.hasChild(resource.id)) {
            css.push("group_row");
          }

          return css.join(" ");

        }
	  };
	  
	  
			 gantt.config.layout = {
          css: "gantt_container",
          rows: [{
              gravity: 2,
              id: "main-gantt", // Added by RB 02.12.18
              cols: [{
                  view: "grid",
                  group: "grids",
                  scrollY: "scrollVer",
                },
                {
                  resizer: true,
                  width: 1
                },
                {
                  view: "timeline",
                  scrollX: "scrollHor",
                  scrollY: "scrollVer"
                },
                {
                  view: "scrollbar",
                  id: "scrollVer",
                  group: "vertical"
                }
              ]
            },
            {
              resizer: true,
              width: 1,
              next: "resources-gantt"
            },
            {
              gravity: 1,
              id: "resources-gantt",
              config: window.ibex_gantt_config.resourceConfig,
              templates: resourceTemplates,
              cols: [{
                  view: "resourceGrid",
                  group: "grids",
                  scrollY: "resourceVScroll"
                },
                {
                  resizer: true,
                  width: 1
                },
                {
                  view: "resourceTimeline",
                  scrollX: "scrollHor",
                  scrollY: "resourceVScroll"
                },
                {
                  view: "scrollbar",
                  id: "resourceVScroll",
                  group: "vertical"
                }
              ]
            },
            {
              view: "scrollbar",
              id: "scrollHor"
            }
          ]
        };
		  var resourceMode = "hours";
		  var radios = [].slice.call(gantt.$container.querySelectorAll("input[type='radio']"));
        radios.forEach(function(r) {
          gantt.event(r, "change", function(e) {
            var radios = [].slice.call(gantt.$container.querySelectorAll("input[type='radio']"));
            radios.forEach(function(r) {
              r.parentNode.className = r.parentNode.className.replace("active", "");
            });

            if (this.checked) {
              resourceMode = this.value;
              this.parentNode.className += " active";
				  console.log("DS ID");
				  console.log(gantt.getDatastore(gantt.config.resource_store));
              gantt.getDatastore(gantt.config.resource_store).refresh();
				
            }
          });
		 });
		  
			 
		
        });
        return false;
      });

 gantt.attachEvent("onAfterTaskDelete", function(id, item) {
        // Update parent?
        if (item.parent != 0) {
          var dates = getSummaryTaskDates(item.parent);
          var parent = gantt.getTask(item.parent);
          parent.start_date = moment.unix(dates.start_date).toDate();
          parent.end_date = moment.unix(dates.end_date).toDate();
          window.ibex_gantt_config.suppressParentUpdateID = parent.id;
          var minutesToNow = getMinutesBetweenDates(moment.unix(dates.start_date), moment.unix(dates.end_date), parent.calendar_id);
          //console.log('x2');
          //console.log('item is');
          //console.log(item);
          var periodsWorking = getWorkingPeriods(moment.unix(dates.start_date).toDate(), moment.unix(dates.end_date).toDate(), item.calendar_id, moment.unix(dates.end_date).toDate());
          var totalMins = 0;
          for (i = 0; i < periodsWorking.length; ++i) {
            var a = moment(periodsWorking[i].start_date);
            var b = moment(periodsWorking[i].end_date);
            var diff = (b.diff(a, 'minutes'));
            totalMins = totalMins + diff;
          }
          parent.duration_worked = totalMins;
          gantt.updateTask(parent.id);
        }
        refreshUIOrder();
        var task = item;
        gantt.addTaskLayer(function(task) 
		  {
          if (task.type == "task") {
            if (task.is_summary == "1") {
              return "";
            } else {
              var from = new Date(task.start_date);
              var to = new Date(task.end_date);
              var noWorkShifts = getNonWorkingPeriods(from, to, task.calendar_id, task.end_date);
              var shiftsDiv = document.createElement("div");
              for (var i = 0; i < noWorkShifts.length; i++) {
                var sizes = gantt.getTaskPosition(task, noWorkShifts[i].start_date, noWorkShifts[i].end_date);
                var el = document.createElement("div");
                el.className = "no-work-shift task-layer-" + task.guid;
                el.style.left = sizes.left + 'px';
                el.style.top = sizes.top + 4 + 'px';
                el.style.width = sizes.width + 'px';
                el.style.height = (sizes.height - 10) + 'px';
                shiftsDiv.appendChild(el);
              }
              return shiftsDiv;
            }
          }
        });
      });

