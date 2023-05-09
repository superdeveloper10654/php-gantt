

$(document).on('click', '#distribute-workload-quantity', function(e) {
    var quantity = parseInt($("#task_edit_workload_total_quantity").val());




  //  var days = parseInt($('.workload-day-root').length);
var days = $("#task_edit_duration_custom").val();


    $('.workload-day-root').each(function(i, obj) {
      $(this).find("input").val(Number(quantity / days).toFixed(2));
    });
    updateWorkloadValues();
  });

  $(document).on("click", ".calculate-duration-from-resource-workload", function() {
    var task = gantt.getTask(window.ibex_gantt_config.activeTaskID);
    var resourceGroupID = $("#task_edit_resource_group_id").val();
    var group;
    var canSchedule = true;
    $.each(window.ibex_gantt_config.resource_groups, function(index) {
      if (window.ibex_gantt_config.resource_groups[index].id == resourceGroupID) {
        group = window.ibex_gantt_config.resource_groups[index];
        if (window.ibex_gantt_config.resource_groups[index].outputs_unit != $("#task_edit_workload_unit").val()) {
          canSchedule = false;
        }
        if ($("#task_edit_workload_total_quantity").val() == "") {
          canSchedule = false;
        }
        if ($("#task_edit_resource_group_id").val() == "") {
          canSchedule = false;
        }
      }
    });

    if (canSchedule == true) {
      var workloadQuantity = Number($("#task_edit_workload_total_quantity").val()) / Number(group.output_per_minute);
      createSnapshot();
      var task = gantt.getTask(window.ibex_gantt_config.activeTaskID);
      task.programme_id = $("#programme_id").val();
      task.timing_overriden = $("#task_edit_timings_overriden").val();
      task.type = $("#task_edit_type").val();
      task.text = $(".task_edit_name").val();
      task.budget_cost_completion = $("#task_edit_budget_at_completion").val();
      task.actual_cost_completion = $("#task_edit_actual_costs").val();
      task.costs_incurred = $("#costs_incurred").val();
      task.calendar_id = $("#task_edit_calendar_id").val();
      task.duration_unit = window.ibex_gantt_config.taskDurationUnit;
      task.workload_quantity = $("#task_edit_workload_total_quantity").val();
      task.workload_quantity_unit = $("#task_edit_workload_unit").val();
      if (task.custom_permission_groups == "undefined") {
        task.custom_permission_groups = NULL;
      }
      if (window.ibex_gantt_config.periodDescriptor == "1" || $("#task_edit_timings_overriden").val() == "1") {
        var durationHours = 0,
          durationMins = 0;
        if ($("#task_edit_duration_hours").val() != "") {
          durationHours = parseInt($("#task_edit_duration_hours").val());
        }
        if ($("#task_edit_duration_mins").val() != "") {
          durationMins = parseInt($("#task_edit_duration_mins").val());
        }
        task.duration_worked = durationHours * 60 + durationMins;
      } else {
        $.each(window.ibex_gantt_config.calendars, function(index) {
          if (window.ibex_gantt_config.calendars[index].id == task.calendar_id) {
            var calendar = window.ibex_gantt_config.calendars[index];
            var startTime = moment(padLeadingZero(calendar.start_hour) + ":" + padLeadingZero(calendar.start_minute), "HH:mm");
            var endTime = moment(padLeadingZero(calendar.end_hour) + ":" + padLeadingZero(calendar.end_minute), "HH:mm");
            var duration = moment.duration(endTime.diff(startTime));
            minutes = parseInt(duration.asMinutes());
            task.duration_worked = parseInt($("#task_edit_duration_custom").val()) * minutes;
          }
        });
      }
      if (window.ibex_gantt_config.periodDescriptor != "1" && $("#task_edit_timings_overriden").val() == "0") {
        // Scale up
        var mins = convertPeriodToMinutes($("#task_edit_duration_custom").val(), $("#task_edit_calendar_id").val());
        task.duration_worked = mins;
      }
      task.start_date = moment($("#task_edit_start_date_d").val() + "/" + $("#task_edit_start_date_m").val() + "/" + $("#task_edit_start_date_y").val() + " " + $("#task_edit_start_time_h").val() + ":" + $("#task_edit_start_time_m").val(), "DD/MM/YYYY HH:mm").toDate();
      task.calendar_id = $("#task_edit_calendar_id").val();
      task.end_date = moment(getTaskEndDate(moment(task.start_date, "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm"), task.duration_worked, task.calendar_id)).toDate();
      if ($("#task_edit_type").val() == "task") {
        task.duration = parseInt($("#task_edit_duration_hours").val());
        //var duration = moment.duration(endTime.diff(startTime));
        task.color = $("#task_edit_bar_colour").val();
        task.constraint_type = $("#task_edit_constraint_type").val();
        task.constraint_date = moment($("#task_edit_constraint_date_d").val() + "/" + $("#task_edit_constraint_date_m").val() + "/" + $("#task_edit_constraint_date_y").val() + " " + $("#task_edit_constraint_time_h").val() + ":" + $("#task_edit_constraint_time_m").val(), "DD/MM/YYYY HH:mm").toDate();
        task.resource_group_id = $("#task_edit_resource_group_id").val();
        task.progress = $("#task_edit_progress").val();
      } else if ($("#task_edit_type").val() == "project") {
        task.calendar_id = $("#task_edit_calendar_id").val();
        var minutes;
        $.each(window.ibex_gantt_config.calendars, function(index) {
          if (window.ibex_gantt_config.calendars[index].id == task.calendar_id) {
            var calendar = window.ibex_gantt_config.calendars[index];
            var startTime = moment(padLeadingZero(calendar.start_hour) + ":" + padLeadingZero(calendar.start_minute), "HH:mm");
            var endTime = moment(padLeadingZero(calendar.end_hour) + ":" + padLeadingZero(calendar.end_minute), "HH:mm");
            var duration = moment.duration(endTime.diff(startTime));
            minutes = parseInt(duration.asMinutes());
          }
        });
        var nextWorkingDate = getNextWorkingDate($("#task_edit_calendar_id").val());
        task.start_date = moment(nextWorkingDate, "DD/MM/YYYY HH:mm").toDate();
        task.end_date = moment(getTaskEndDate(moment(task.start_date).format("YYYY-MM-DD HH:mm"), minutes - 1, task.calendar_id)).toDate();
      } else if ($("#task_edit_type").val() == "milestone") {
        task.start_date = moment($("#task_edit_start_date_d").val() + "/" + $("#task_edit_start_date_m").val() + "/" + $("#task_edit_start_date_y").val() + " " + $("#task_edit_start_time_h").val() + ":" + $("#task_edit_start_time_m").val(), "DD/MM/YYYY HH:mm").toDate();
        task.duration_worked = "0";
        task.end_date = task.start_date;
        task.color = $("#task_edit_bar_colour").val();
      }
      if (doesTaskViolateConstraint(task.id) && task.type != "project") {
        task.start_date = moment(getTaskConstrainedStartDate(task.id)).toDate();
        task.end_date = moment(getTaskEndDate(moment(task.start_date).format("YYYY-MM-DD HH:mm"), task.duration_worked, task.calendar_id)).toDate();
      }
      if (task.parent != 0) {
        gantt.getTask(task.parent).is_summary = "1";
        gantt.updateTask(task.parent);
        task.parent_guid = gantt.getTask(task.parent).guid;
      }
      // Bind resources
      task.resource_id = window.ibex_gantt_config.activeTaskResources;
      var afterForm = JSON.stringify($("#form_task_editor").serializeArray());
      var testBefore = JSON.parse(window.ibex_gantt_config.beforeTaskEditorForm);
      var testAfter = JSON.parse(afterForm);
      var changeString = "";
      for (var i = 0; i < testBefore.length; i++) {
        var name = testBefore[i].name;
        var value = testBefore[i].value;
        if (value != testAfter[i].value) {
          var val1 = value;
          var val2 = testAfter[i].value;
          if (val1 == '') {
            val1 = 'not set';
          }
          changeString += name + " was " + val1 + " and is now " + val2 + "<br>";
        }
      }
      var finalChangeString = changeString.substring(0, changeString.length - 4);
      if (task.$new) {
        window.ibex_gantt_config.activityPrimaryGUID = task.guid;
        window.ibex_gantt_config.activitySecondaryGUID = '';
        window.ibex_gantt_config.activityInfo = "Starts " + moment(task.start_date).format("ddd D MMM (HH:mm)");
        window.ibex_gantt_config.activityAction = "added"
        window.ibex_gantt_config.activityType = task.type;
      } else {
        window.ibex_gantt_config.activityPrimaryGUID = task.guid;
        window.ibex_gantt_config.activitySecondaryGUID = '';
        window.ibex_gantt_config.activityInfo = finalChangeString;
        window.ibex_gantt_config.activityAction = "edited"
        window.ibex_gantt_config.activityType = task.type;
      }
      setTimeout(function() {
        reloadActivityFeed();
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
      var durationNonAbs = task.duration_worked;
      task.duration_worked = Math.abs(durationNonAbs);
      task.duration_worked = workloadQuantity;
      if (task.$new == true) {
        gantt.addTask(task, task.parent);
      } else {
        gantt.updateTask(task.id);
      }

      // Compare this task with pre-object
      window.ibex_gantt_config.originalTaskEditorObject = task;

      // Check for changes
      if (window.ibex_gantt_config.linkTaskAfterInsert == true) {
        // Get previous task
        var prev_id = gantt.getPrevSibling(task.id);
        var prevTask = gantt.getTask(prev_id);
        if (prevTask.type != "project" && prevTask.is_summary != "1") {
          window.ibex_gantt_config.overrideLinkBeforeAddBehaviour = true;
          gantt.addLink({
            programme_id: $("#programme_id").val(),
            source: prevTask.id,
            source_guid: prevTask.guid,
            target: task.id,
            target_guid: task.guid,
            type: "0",
            offset_minutes: "0",
            offset_type: "1",
            created: moment().format("ddd D MMM YYYY"), //Added by RB 12.04.19
          });
          autoScheduleTasks();
        }
      }

      window.ibex_gantt_config.linkTaskAfterInsert = false;
      autoScheduleTasks();
      $("#modal_task_editor").modal('hide');
      gantt.render();
      gantt.showTask(task.id);
      window.location.href = "beta.php?id=" + $("#programme_id").val(); // Added by RB 28.02.19								
    } else {
      //alert('Unable to schedule - make sure 1) a resource group is assigned 2) workload unit matches resource group unit 3) values are not 0 / null etc');
      alert("<?=$_SESSION['user']['first_name']?>, we have a problem. We were unable to calculate this task's duration because the allocated Resource Group has a different unit of measure to the Workload unit of measure. Please ensure that both of these are the same, then try again. Thanks");
    }
  });

  window.getTaskWorkload = function getTaskWorkload() {
  setTimeout(function(){
    $.getJSON("beta.ajax.php?action=get_task_workload&guid=" + window.ibex_gantt_config.activeTaskGUID, function(data) {
     // $("#task_edit_workload_days > tbody").empty();
      $.each(data.workloads, function(index) {
        var quantity = "";
        var status = "";
        var dateTask;
        var dateNow;
        if (data.workloads[index].is_root == "1") {
          dateTask = data.workloads[index].work_date;
          dateNow = moment().startOf('day').format("YYYY-MM-DD");
          if (moment(data.workloads[index].work_date).isBefore(moment(dateNow))) {
            status = "<img src='img/svg/check-complete.svg' title='This workload has been done'>";
          }
          if (data.workloads[index].work_date == dateNow) {
            status = "<img src='img/svg/in-progress.svg' title='This workload is in progress'><small>In Progress</small>";

          } else if (moment(data.workloads[index].work_date).isAfter(moment(dateNow))) {
            status = "<img src='img/svg/pending-time.svg' title='This workload target is pending'><small>Pending</small>";
          }
          if (data.workloads[index].parent != "0") {
            status = "";
          }
        } else {
          dateTask = data.workloads[index].work_date;
          dateNow = moment().format("YYYY-MM-DD HH:mm");
          if (moment(data.workloads[index].work_date + " " + data.workloads[index].work_time).isBefore(moment(dateNow))) {
            status = "<img src='img/svg/check-complete-time.svg' title='This workload target has been done'><small>Done</small>";
          } else if (data.workloads[index].work_date + " " + data.workloads[index].work_time) {
            status = "<img src='img/svg/pending-time.svg' title='This workload target is pending'><small>Pending</small>";
          }
        }
        if (data.workloads[index].quantity) {
          quantity = data.workloads[index].quantity;
        }
        // Between dates?
        var target = moment(dateTask);
        var startDatePrime = moment($("#task_edit_start_date_d").val() + "/" + $("#task_edit_start_date_m").val() + "/" + $("#task_edit_start_date_y").val() + " " + $("#task_edit_start_time_h").val() + ":" + $("#task_edit_start_time_m").val(), "DD/MM/YYYY HH:mm").format("YYYY-MM-DD");
		  
		  var calendarSelected = $("#task_edit_calendar_id").val();
	 
	 var trimIteratedDates = false;
	 for(let calendar of window.ibex_gantt_config.calendars)
	 {
  		if (calendar.id == calendarSelected)
		{
			var startHour = parseInt(calendar.start_hour);
			var endHour = parseInt(calendar.end_hour);
			
			if (endHour < startHour)
			{
				trimIteratedDates = true;
				break;
			}
		}
	}
	var endDatePrime;
	
	if (trimIteratedDates == true)
	{
		endDatePrime = moment($("#task_edit_end_date").val(), "ddd D MMM (HH:mm)").subtract(1, "days").format("YYYY-MM-DD");
	}
	else
	{
		endDatePrime = moment($("#task_edit_end_date").val(), "ddd D MMM (HH:mm)").format("YYYY-MM-DD");
	}
	
	
	
		  
        var momentStart = moment(startDatePrime);
        var momentEnd = moment(endDatePrime);
		  //console.log('start is ' + startDatePrime + " and end is " + endDatePrime);
        var withinRangeCurrent = target.isBetween(momentStart, momentEnd, 'days', '[]');
        if (withinRangeCurrent == true) 
		  {
		  console.log('between range 1');
          if (data.workloads[index].is_root == "1") {
       //     $("#task_edit_workload_days > tbody").append("<tr data-id='" + data.workloads[index].id + "' class='workload-day-root'><td>" + moment(data.workloads[index].work_date).format('ddd Do MMM') + "</td><td><img data-id='" + data.workloads[index].id + "' class='add-to-workload-root' src='img/svg/crosshairs.svg' title='Add a Workload target' data-root='" + data.workloads[index].id + "'></img><input type='text' class='workload-quantity-level form-control' value='" + quantity + "'></td><td>" + status + "</td></tr>");
          } else {
       //     $("#task_edit_workload_days > tbody").append("<tr data-id='" + data.workloads[index].id + "' class='workload-day-root child'><td>@" + data.workloads[index].work_time + "</td><td><input type='text' class='workload-quantity-level form-control' value='" + quantity + "'><span class='remove-from-workload-root' data-id='" + data.workloads[index].id + "'><img src='img/svg/bin-1.svg' title='Remove this Workload target'></span></td><td>" + status + "</td></tr>");
          }
        }
      });
    });
 }, 1000);
  }

  $(document).on('change', '.workload-quantity-level', function(e) {
    updateWorkloadValues();
  });

  window.updateWorkloadValues = function updateWorkloadValues() {
    $('.workload-quantity-level').each(function(i, obj) {
      $.getJSON("beta.ajax.php?action=update_task_workload_quantity&id=" + $(this).closest('tr').data("id") + "&value=" + $(this).val(), function(data) {});
    });
  }

  
  
  
  window.updateWorkloadDays = function updateWorkloadDays() {
  
  
  setTimeout(function(){
  
  
    var parsedStart = moment($("#task_edit_start_date_d").val() + "/" + $("#task_edit_start_date_m").val() + "/" + $("#task_edit_start_date_y").val() + " " + $("#task_edit_start_time_h").val() + ":" + $("#task_edit_start_time_m").val(), "DD/MM/YYYY HH:mm").format("YYYY-MM-DD");
	 
	 // Check if we are operating on a calendar that spans midnight (we can lose the last instance of the iterated date)
	 var calendarSelected = $("#task_edit_calendar_id").val();
	 
	 var trimIteratedDates = false;
	 for(let calendar of window.ibex_gantt_config.calendars)
	 {
  		if (calendar.id == calendarSelected)
		{
			var startHour = parseInt(calendar.start_hour);
			var endHour = parseInt(calendar.end_hour);
			
			if (endHour < startHour)
			{
				trimIteratedDates = true;
				break;
			}
		}
	}
	var parsedEnd;
	if (trimIteratedDates == true)
	{
		parsedEnd = moment($("#task_edit_end_date").val(), "ddd D MMM (HH:mm)").subtract(1, "days").format("YYYY-MM-DD");
	}
	else
	{
		parsedEnd = moment($("#task_edit_end_date").val(), "ddd D MMM (HH:mm)").format("YYYY-MM-DD");
	}
	
	 var previousWorkloadCommit = window.localStorage.getItem("previous_workload_commit");
	 var previousWorkloadGUID = window.localStorage.getItem("previous_workload_guid");
	 
	 var dateRangeJSON = JSON.stringify(enumerateDaysBetweenDates(parsedStart, parsedEnd, $("#task_edit_calendar_id").val()));
	 
	 if (window.ibex_gantt_config.activeTaskGUID == previousWorkloadGUID && previousWorkloadCommit == dateRangeJSON)
		{
			// Ignore
		}
		else
		{
			
			window.localStorage.setItem("previous_workload_commit", dateRangeJSON);
			window.localStorage.setItem("previous_workload_guid", window.ibex_gantt_config.activeTaskGUID);
			
    $.getJSON("beta.ajax.php?action=update_task_workload_dates&guid=" + window.ibex_gantt_config.activeTaskGUID + "&range=" + JSON.stringify(enumerateDaysBetweenDates(parsedStart, parsedEnd, $("#task_edit_calendar_id").val())), function(data) {
      $("#task_edit_workload_days > tbody").empty();
      $.each(data.workloads, function(index) {
        var quantity = "";
        var status = "";
        var dateTask;
        var dateNow;
        if (data.workloads[index].is_root == "1") {
          dateTask = data.workloads[index].work_date;
          dateNow = moment().startOf('day').format("YYYY-MM-DD");
          if (moment(data.workloads[index].work_date).isBefore(moment(dateNow))) {
            status = "<img src='img/svg/check-complete.svg' title='This workload has been done'><small>Done</small>";
          }
          if (data.workloads[index].work_date == dateNow) {
            status = "<img src='img/svg/in-progress.svg' title='This workload is in progress'><small>In Progress</small>";
          } else if (moment(data.workloads[index].work_date).isAfter(moment(dateNow))) {
            status = "<img src='img/svg/pending.svg' title='This workload is pending'><small>Pending</small>";
          }
          if (data.workloads[index].parent != "0") {
            status = "";
          }
        } else {
          dateTask = data.workloads[index].work_date;
          dateNow = moment().format("YYYY-MM-DD HH:mm");
          if (moment(data.workloads[index].work_date + " " + data.workloads[index].work_time).isBefore(moment(dateNow))) {
            status = "<img src='img/svg/check-complete-time.svg' title='This workload has been done'>";
          } else if (data.workloads[index].work_date + " " + data.workloads[index].work_time) {
            status = "<img src='img/svg/pending-time.svg' title='This workload target is pending'><small>Pending</small>";
          }
        }
        if (data.workloads[index].quantity) {
          quantity = data.workloads[index].quantity;
        }
        // Between dates?
        var target = moment(dateTask);
        var startDatePrime = moment($("#task_edit_start_date_d").val() + "/" + $("#task_edit_start_date_m").val() + "/" + $("#task_edit_start_date_y").val() + " " + $("#task_edit_start_time_h").val() + ":" + $("#task_edit_start_time_m").val(), "DD/MM/YYYY HH:mm").format("YYYY-MM-DD");
		  
		  
		  
		  
      	  var calendarSelected = $("#task_edit_calendar_id").val();
	 
	 var trimIteratedDates = false;
	 for(let calendar of window.ibex_gantt_config.calendars)
	 {
  		if (calendar.id == calendarSelected)
		{
			var startHour = parseInt(calendar.start_hour);
			var endHour = parseInt(calendar.end_hour);
			
			if (endHour < startHour)
			{
				trimIteratedDates = true;
				break;
			}
		}
	}
	var endDatePrime;
	
	if (trimIteratedDates == true)
	{
		endDatePrime = moment($("#task_edit_end_date").val(), "ddd D MMM (HH:mm)").subtract(1, "days").format("YYYY-MM-DD");
	}
	else
	{
		endDatePrime = moment($("#task_edit_end_date").val(), "ddd D MMM (HH:mm)").format("YYYY-MM-DD");
	}
	
		  
		  
        var momentStart = moment(startDatePrime);
        var momentEnd = moment(endDatePrime);
        var withinRangeCurrent = target.isBetween(momentStart, momentEnd, 'days', '[]');
        if (withinRangeCurrent == true) {
          if (data.workloads[index].is_root == "1") {
            $("#task_edit_workload_days > tbody").append("<tr data-id='" + data.workloads[index].id + "' class='workload-day-root'><td>" + moment(data.workloads[index].work_date).format('ddd Do MMM') + "</td><td><img data-id='" + data.workloads[index].id + "' class='add-to-workload-root' src='img/svg/crosshairs.svg' title='Add a Workload target' data-root='" + data.workloads[index].id + "'></img><input type='text' class='workload-quantity-level form-control' value='" + quantity + "'></td><td>" + status + "</td></tr>");
          } else {
            $("#task_edit_workload_days > tbody").append("<tr data-id='" + data.workloads[index].id + "' class='workload-day-root child'><td>@" + data.workloads[index].work_time + "</td><td><input type='text' class='workload-quantity-level form-control' value='" + quantity + "'><span class='remove-from-workload-root' data-id='" + data.workloads[index].id + "'><img src='img/svg/bin-1.svg' title='Remove this Workload target'></span></td><td>" + status + "</td></tr>");
          }
        }
      });
    });
	 }
	 
	 }, 1000);
	 
	 
  }

  $(document).on('click', '#workload_root_submit', function(e) {
    if ($("#workload_child_target_hour").val() != "" && $("#workload_child_target_minute").val() != "" && $("#workload_child_quantity").val() != "") {
      $.getJSON("beta.ajax.php?action=add_to_workload_root&root=" + $(this).data("root") + "&target=" + $("#workload_child_target_hour").val() + ":" + $("#workload_child_target_minute").val() + "&quantity=" + $("#workload_child_quantity").val(), function(data) {
        getTaskWorkload();
        $("#modal_add_to_workload_root").modal('hide');
      });
    }
  });

  $(document).on('click', '.remove-from-workload-root', function(e) {
    $.getJSON("beta.ajax.php?action=remove_from_workload_root&id=" + $(this).data("id"), function(data) {
      getTaskWorkload();
    });
  });

  $(document).on('click', '.add-to-workload-root', function(e) {
    $('.mdb-select').material_select('destroy');
    // Get cal ID
    var calendarID = $("#task_edit_calendar_id").val();
    var startHour, endHour;
    if (calendarID) {
      $.each(window.ibex_gantt_config.calendars, function(index) {
        if (window.ibex_gantt_config.calendars[index].id == calendarID) {
          startHour = window.ibex_gantt_config.calendars[index].start_hour;
          endHour = window.ibex_gantt_config.calendars[index].end_hour;
        }
      });
    }
    $("#workload_child_target_hour option").each(function(i) {
      $(this).removeAttr('disabled');
    });
    $("#workload_child_target_hour option").each(function(i) {
      var hour = Number($(this).val());
      if (hour < Number(startHour) || hour > Number(endHour)) {
        $(this).attr('disabled', 'disabled');
      }
    });
	 
    var rootID = $(this).data("root");
    $.getJSON("beta.ajax.php?action=get_workload_root&id=" + rootID, function(data) {
      $("#workload_root_submit").attr('data-root', rootID);
      $("#workload_root_summary").html("You're adding a Workload target to " + moment(data.workload.work_date).format('dddd Do MMMM YYYY'));
      $('.mdb-select').material_select();
      $("#modal_add_to_workload_root").modal('show');
    });
  });
  
  $("#task_edit_workload_total_quantity").keyup(function() {
     updateTaskAttributes("1", window.ibex_gantt_config.activeTaskID);
    $("#adjusted-duration-prompt").show().delay(5000).fadeOut();
	  $("#workload-driven-duration-prompt").show().delay(5000).fadeOut();
  });
