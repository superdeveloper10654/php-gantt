
  
function removeA(arr) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
        what = a[--L];
        while ((ax= arr.indexOf(what)) !== -1) {
            arr.splice(ax, 1);
        }
    }
    return arr;
}



gantt.showLightbox = function(id) {
  var taskWorkingDates = [];
  window.ibex_gantt_config.activeTaskID = id;
  window.ibex_gantt_config.activeTaskWorkingDates = [];
  var task = gantt.getTask(id);
		
  window.ibex_gantt_config.originalTaskEditorObject = task;
  $("#task_edit_timings_overriden").val(task.timing_overriden);
  $("#modal_task_editor").modal('show');
// permissions-editor
  var canViewPermissions = false;
  for (i = 0; i < window.ibex_gantt_config.userGroups.length; i++) {
    var groupID = window.ibex_gantt_config.userGroups[i].id;
    for (j = 0; j < window.ibex_gantt_config.groups.length; j++) {
      if (window.ibex_gantt_config.groups[j].id == groupID) {
        if (window.ibex_gantt_config.groups[j].is_admin_group == "1") {
          canViewPermissions = true;
        }
      }
    }
  }
		  
  $("#task_edit_progress").val('0');
  $('.mdb-select').material_select('destroy');
  $('.mdb-select').material_select();
// RB 13.05.21
        
        if (task.$new) {
$('#task_edit_calendar_id').val(task.calendar_id).removeAttr('disabled');                            // RB 29.04.21
          task.guid = generateGUID();
          // Set date and time as today
          let currentDateValue = moment().format("DD");
          $("#task_edit_start_date_d option").each(function() {
            if (parseInt($(this).val()) >= parseInt(currentDateValue)) {
              if(!$(this).attr('disabled')) {
                currentDateValue = $(this).val();
                return false;
              }
            }
          });
          $("#task_edit_start_date_d").val(currentDateValue);
          $("#task_edit_start_date_m").val(moment().format("MM"));
          $("#task_edit_start_date_y").val(moment().format("YYYY"));
          // Default calendar start time?
          for (var calendar of window.ibex_gantt_config.calendars) {
            if (calendar['is_default_task_calendar'] == "1") {
              defaultTaskCalendarID = calendar['id'];
            }
            var startHour = calendar.start_hour;
            var startMinute = calendar.start_minute;
            if (startHour < 10) {
              startHour = "0" + startHour;
            }
            if (startMinute < 10) {
              startMinute = "0" + startMinute;
            }
            $("#task_edit_start_time_h").val(startHour);
            $("#task_edit_start_time_m").val(startMinute);
          }
			    $("#task_edit_name").val(task.text);
$('#task-editor-title').html('New Task');
        } else {
		   $('#task_edit_calendar_id').val(task.calendar_id).attr('disabled', 'disabled');                   
          // Set type
          window.ibex_gantt_config.suppressDynamicTaskMode = true;
          if (task.type == "task") {
            window.ibex_gantt_config.newObjectType = null; // 2
          }
          if (task.type == "project") {
            window.ibex_gantt_config.newObjectType = "2"; // null
          }
          if (task.type == "milestone") {
            window.ibex_gantt_config.newObjectType = "3";
          }
        }
        window.ibex_gantt_config.activeTaskGUID = task.guid;
        if (task.$new) {
        } else {
          getTaskWorkload();
        }
        // Check locks?
        if (task.$target.length > 0) {
          $("#start_dates_disabled").show();
          $("#task_edit_start_date_d").attr('disabled', 'disabled');
          $("#task_edit_start_date_m").attr('disabled', 'disabled');
          $("#task_edit_start_date_y").attr('disabled', 'disabled');
          $("#task_edit_start_time_h").attr('disabled', 'disabled');
          $("#task_edit_start_time_m").attr('disabled', 'disabled');
        } else {
          $("#start_dates_disabled").hide();
          $("#task_edit_start_date_d").removeAttr('disabled', 'disabled');
          $("#task_edit_start_date_m").removeAttr('disabled', 'disabled');
          $("#task_edit_start_date_y").removeAttr('disabled', 'disabled');
          $("#task_edit_start_time_h").removeAttr('disabled', 'disabled');
          $("#task_edit_start_time_m").removeAttr('disabled', 'disabled');
        }
        // Add all groups
        $(".container-permission-groups").html('');
        // Does this task has overrides?
        // No - just use defaults
        if (task.custom_permission_groups == "" || task.custom_permission_groups == null) {
          for (i = 0; i < window.ibex_gantt_config.groups.length; i++) {
            var groupID = window.ibex_gantt_config.groups[i].id;
            var groupName = window.ibex_gantt_config.groups[i].name;
            if (window.ibex_gantt_config.groups[i].is_admin_group == "1") {
              $(".container-permission-groups").append("<div class='md-form'><input type='checkbox' disabled checked class='form-check-input toggle-group-permission' data-task='" + id + "' data-group='" + groupID + "' id='permission_group_" + groupID + "'><label class='form-check-label' for='permission_group_" + groupID + "'>" + groupName + "</label></div>");
            } else {
              // Checked?
              var permissionSets = JSON.parse(window.ibex_gantt_config.settings.default_permission_sets);
              var checked = "";
              for (var k in permissionSets) {
                if (permissionSets.hasOwnProperty(k)) {
                  if (k == "group_" + groupID + "_set_1") {
                    if (permissionSets[k] == true) {
                      checked = "checked";
                    }
                  }
                }
              }
              $(".container-permission-groups").append("<div class='md-form'><input type='checkbox' " + checked + " class='form-check-input toggle-group-permission' data-task='" + id + "' data-group='" + groupID + "' id='permission_group_" + groupID + "'><label class='form-check-label editor-permission' for='permission_group_" + groupID + "'>" + groupName + "</label></div>");
            }
          }
        } else {
          // Yes - task has custom options set
          for (i = 0; i < window.ibex_gantt_config.groups.length; i++) {
            var groupID = window.ibex_gantt_config.groups[i].id;
            var groupName = window.ibex_gantt_config.groups[i].name;
            if (window.ibex_gantt_config.groups[i].is_admin_group == "1") {
              $(".container-permission-groups").append("<div class='md-form'><input type='checkbox' disabled checked class='form-check-input toggle-group-permission' data-task='" + id + "' data-group='" + groupID + "' id='permission_group_" + groupID + "'><label class='form-check-label editor-permission' for='permission_group_" + groupID + "'>" + groupName + "</label></div>");
            } else {
              // Checked?
              var permissionSets = JSON.parse(task.custom_permission_groups);
              var checked = "";
              for (var k in permissionSets) {
                if (permissionSets.hasOwnProperty(k)) {
                  if (k == groupID) {
                    if (permissionSets[k] == true) {
                      checked = "checked";
                    }
                  }
                }
              }
              $(".container-permission-groups").append("<div class='md-form'><input type='checkbox' " + checked + " class='form-check-input toggle-group-permission' data-task='" + id + "' data-group='" + groupID + "' id='permission_group_" + groupID + "'><label class='form-check-label editor-permission' for='permission_group_" + groupID + "'>" + groupName + "</label></div>");
            }
          }
        }
		  
		  // Progress calcs
		  $('#task_edit_progress').val(task.progress);
        // Task editor bindings
        $("#task_edit_type").val(task.type);
        $(".task_edit_name").val(task.text);
        var defaultTaskCalendarID = 0;
        $('#task_edit_calendar_id').empty();
        for (var calendar of window.ibex_gantt_config.calendars) {
          if (calendar.type == "1") {
            $('#task_edit_calendar_id').append($('<option>', {
              value: calendar['id'],
              text: calendar['name']
            }));
          }
          if (calendar['is_default_task_calendar'] == "1") {
            defaultTaskCalendarID = calendar['id'];
          }
        }
        var calendar = getCalendar($('#task_edit_calendar_id').val());
        if(parseInt(calendar.end_hour) - parseInt(calendar.start_hour)) {
          $("#task_edit_duration_hours").val(Math.abs(calendar.end_hour - calendar.start_hour)).trigger("change");
        }
        else {
          var hoursDuration = Math.abs(calendar.end_hour - calendar.start_hour)
          hoursDuration = 1440 - hoursDuration;
          $("#task_edit_duration_hours").val(hoursDuration).trigger("change");
        }

        $("#task_edit_duration_hours").val(Math.abs(calendar.end_hour - calendar.start_hour)).trigger("change");
        $("#task_edit_duration_mins").val('0').trigger("change");
        $("#task_edit_duration_custom").val('').trigger("change");
		  //alert(task.end_date);
       // $("#task_edit_end_date").val(moment(task.end_date).format("ddd D MMM (HH:mm)")).trigger("change");
        $("#task_edit_end_date").val(moment($("#task_edit_end_date").val(), "ddd D MMM HH:mm").format("YYYY-MM-DD")).trigger("change");
        if (task.$new) {
          $('#task_edit_calendar_id').val(task.calendar_id).removeAttr('disabled');                            // RB 29.04.21
          $('#task_edit_calendar_id').val(defaultTaskCalendarID).change();
          $(".delete-task").hide();
          $("#task_edit_bar_colour").val('#d6daff').trigger('change');
          $("#task_edit_bar_colour").css('color', '#d6daff');
          $("#cp2").attr('data-color', '#d6daff');
        } else {
          $("#task_edit_bar_colour").val(task.color);
          $("#task_edit_bar_colour").css('color', task.color);
          $("#cp2").attr('data-color', task.color);
          $('#task_edit_calendar_id').val(task.calendar_id).change();
          $('#task_edit_calendar_id').val(task.calendar_id).attr('disabled', 'disabled');                            // RB 29.04.21
          $('#task_edit_workload_total_quantity').val(task.workload_quantity).change();
          $('#task_edit_workload_unit').val(task.workload_quantity_unit).change();
			 //console.log('checking dur of ' + task.duration_worked + " and id of " + task.id);
          $("#task_edit_duration_custom").val(convertMinutesToPeriod(task.duration_worked, task.id)).trigger("change");
          $("#task_edit_start_date_d").val(moment(task.start_date).format("DD"));
          $("#task_edit_start_date_m").val(moment(task.start_date).format("MM"));
          $("#task_edit_start_date_y").val(moment(task.start_date).format("YYYY"));
          $("#task_edit_start_time_h").val(moment(task.start_date).format("HH"));
          $("#task_edit_start_time_m").val(moment(task.start_date).format("mm"));
        }
        // Set up date pickers based on calendar
        var nonWorkingDates = [];
        window.ibex_gantt_config.globalNonWorkingDays = [];
        if (calendar.working_day_monday == 0) {
          window.ibex_gantt_config.globalNonWorkingDays.push(1);
        }
        if (calendar.working_day_tuesday == 0) {
          window.ibex_gantt_config.globalNonWorkingDays.push(2);
        }
        if (calendar.working_day_wednesday == 0) {
          window.ibex_gantt_config.globalNonWorkingDays.push(3);
        }
        if (calendar.working_day_thursday == 0) {
          window.ibex_gantt_config.globalNonWorkingDays.push(4);
        }
        if (calendar.working_day_friday == 0) {
          window.ibex_gantt_config.globalNonWorkingDays.push(5);
        }
        if (calendar.working_day_saturday == 0) {
          window.ibex_gantt_config.globalNonWorkingDays.push(6);
        }
        if (calendar.working_day_sunday == 0) {
          window.ibex_gantt_config.globalNonWorkingDays.push(0);
        }
        var overrides = getCalendarOverrides(calendar.id);
        for (i = 0; i < overrides.length; i++) {
          var startDateOverride = overrides[i].start_date;
          var endDateOverride = overrides[i].end_date;
          var nonWorkingDatesOverride = enumerateDaysBetweenDates(startDateOverride, endDateOverride, calendar.id);
          for (j = 0; j < nonWorkingDatesOverride.length; j++) {
            window.ibex_gantt_config.globalNonWorkingDays.push(nonWorkingDatesOverride[j]);
          }
        }
        // Now go through dropdowns and remove non working days
        // Loop through all dates with loop / M / Y
        $start_date = 1;
        $end_date = 31;
        for (var n = 1; n < 32; ++n) {
          if (n < 10) {
            n = "0" + n;
          }
          var dateCheck = $("#task_edit_start_date_y").val() + "-" + $("#task_edit_start_date_m").val() + "-" + n;
          var dateFormatted = moment(dateCheck).format('ddd Do');
          var result = isDateWorkingDate(dateCheck, $("#task_edit_calendar_id").val());
          if (result == false) {
            $("#task_edit_start_date_d option[value=" + n + "]").attr('disabled', 'disabled');
          } else {
            $("#task_edit_start_date_d option[value=" + n + "]").removeAttr('disabled');
            $("#task_edit_start_date_d option[value=" + n + "]").text(dateFormatted);
          }
        }
        // Get days in this task
        var startFormatted = moment($("#task_edit_start_date").val(), "ddd D MMM (HH:mm)").format("YYYY-MM-DD");
        window.ibex_gantt_config.activeTaskWorkingDates = enumerateDaysBetweenDates(moment($("#task_edit_start_date_d").val() + "/" + $("#task_edit_start_date_m").val() + "/" + $("#task_edit_start_date_y").val() + " " + $("#task_edit_start_time_h").val() + ":" + $("#task_edit_start_time_m").val(), "DD/MM/YYYY HH:mm").format("YYYY-MM-DD"), moment($("#task_edit_end_date").val(), "ddd D MMM HH:mm").format("YYYY-MM-DD"), $('#task_edit_calendar_id').val());
        <?php include "js-task-editor-general.php"?>
        <?php include "js-task-editor-resources.php"?>
        <?php include "js-task-editor-status.php"?>
        <?php include "js-task-editor-comments-files.php"?>
        <?php include "js-task-editor-dependencies.php"?>
        <?php include "js-task-editor-deadline.php"?>
        <?php include "js-task-editor-baseline.php"?>
        <?php include "js-task-editor-finances.php"?>
        <?php 
          // include "js-task-editor-permissions.php";
          ?>
        // Resources
        var resArray;
        window.ibex_gantt_config.activeTaskResources = task.resource_id;

        if (window.ibex_gantt_config.activeTaskResources) {
          if (window.ibex_gantt_config.activeTaskResources.indexOf(",") != -1) {
            resArray = window.ibex_gantt_config.activeTaskResources.split(',');
          } else {
            resArray = window.ibex_gantt_config.activeTaskResources;
          }
        }
        $('#task_edit_resource_id').empty();
        $('#task_edit_resource_group_id').empty();
        var optionGroup = $("<option></option>");
        optionGroup.val("0");
        optionGroup.text("Select");
        $("#task_edit_resource_group_id").append(optionGroup);
        $.each(window.ibex_gantt_config.resource_groups, function(index) {
          var optionGroup = $("<option></option>");
          optionGroup.val(window.ibex_gantt_config.resource_groups[index].id);
          optionGroup.text(window.ibex_gantt_config.resource_groups[index].name);
          $("#task_edit_resource_group_id").append(optionGroup);
          var optgroup = $('<optgroup>');
          optgroup.attr('label', window.ibex_gantt_config.resource_groups[index].name);
          $.each(window.ibex_gantt_config.resources, function(i) {
            if (window.ibex_gantt_config.resources[i].group_id == window.ibex_gantt_config.resource_groups[index].id) {
              if (window.ibex_gantt_config.resources[i].calendar_id == null) {
                var option = $("<option></option>");
                option.val(window.ibex_gantt_config.resources[i].id);
                option.text(window.ibex_gantt_config.resources[i].name);
                if (resArray && resArray.includes(window.ibex_gantt_config.resources[i].id) == true) {
						 //console.log('here888');
                  option.attr('selected', 'selected');
                }
                //option.attr('disabled', 'disabled');
                // $("#task_edit_resource_id").append(option);
                optgroup.append(option);
              } else {
                var resourceEnabled = true;
                var resourceDisabledReason = 1;
                $.each(window.ibex_gantt_config.activeTaskWorkingDates, function(index) {
                  if (isDateWorkingDate(window.ibex_gantt_config.activeTaskWorkingDates[index], window.ibex_gantt_config.resources[i].calendar_id) == false) {
                    resourceEnabled = false;
                    resourceDisabledReason = 1;
                  }
                });
                var unavailableMessage = '';
                if (resourceEnabled == true) {
                  // Check clashes with other tasks
                  var resourceClashes = false;
                  var tasksAll = gantt.getTaskByTime();
                  for (var j = 0; j < tasksAll.length; j++) {
                    if (tasksAll[j].resource_id == window.ibex_gantt_config.resources[i].id && tasksAll[j].id != window.ibex_gantt_config.activeTaskID) {
                      // Same resource. Same date?
                      $.each(window.ibex_gantt_config.activeTaskWorkingDates, function(indexInner) {
                        var moment1 = moment(window.ibex_gantt_config.activeTaskWorkingDates[indexInner]);
                        var moment2 = moment(tasksAll[j].start_date);
                        var moment3 = moment(tasksAll[j].end_date);
                        if (moment1.isBetween(moment2, moment3, 'seconds', '[]')) {
                          var thisTask = gantt.getTask(window.ibex_gantt_config.activeTaskID);
                          if (moment3.isBefore(moment(thisTask.start_date))) {
                          } else if (moment3.isSame(moment(thisTask.start_date))) {
                          } else {
                            resourceClashes = true;
                          }
                        }
                      });
                    }
                  }
                  var setWorkloadClash = false;
                  if ($("#task_edit_workload_total_quantity").val() != "") {
                    // We have a workload set
                    if (window.ibex_gantt_config.resources[i].unit_of_measure == "1") {
                      if ($("#task_edit_workload_unit").val() != "no") {
                        resourceClashes = true;
                        setWorkloadClash = true;
                        unavailableMessage = ' has a conflicting workload unit ';
                      }
                    }
                    if (window.ibex_gantt_config.resources[i].unit_of_measure == "2") {
                      if ($("#task_edit_workload_unit").val() != "item") {
                        resourceClashes = true;
                        setWorkloadClash = true;
                        unavailableMessage = ' has a conflicting workload unit ';
                      }
                    }
                    if (window.ibex_gantt_config.resources[i].unit_of_measure == "8") {
                      if ($("#task_edit_workload_unit").val() != "mm") {
                        resourceClashes = true;
                        setWorkloadClash = true;
                        unavailableMessage = ' has a conflicting workload unit ';
                      }
                    }
                    if (window.ibex_gantt_config.resources[i].unit_of_measure == "9") {
                      if ($("#task_edit_workload_unit").val() != "m") {
                        resourceClashes = true;
                        setWorkloadClash = true;
                        unavailableMessage = ' has a conflicting workload unit ';
                      }
                    }
                    if (window.ibex_gantt_config.resources[i].unit_of_measure == "10") {
                      if ($("#task_edit_workload_unit").val() != "km") {
                        resourceClashes = true;
                        setWorkloadClash = true;
                        unavailableMessage = ' has a conflicting workload unit ';
                      }
                    }
                    if (window.ibex_gantt_config.resources[i].unit_of_measure == "11") {
                      if ($("#task_edit_workload_unit").val() != "m2") {
                        resourceClashes = true;
                        setWorkloadClash = true;
                        unavailableMessage = ' has a conflicting workload unit ';
                      }
                    }
                    if (window.ibex_gantt_config.resources[i].unit_of_measure == "12") {
                      if ($("#task_edit_workload_unit").val() != "km2") {
                        resourceClashes = true;
                        setWorkloadClash = true;
                        unavailableMessage = ' has a conflicting workload unit ';
                      }
                    }
                    if (window.ibex_gantt_config.resources[i].unit_of_measure == "13") {
                      if ($("#task_edit_workload_unit").val() != "kg") {
                        resourceClashes = true;
                        setWorkloadClash = true;
                        unavailableMessage = ' has a conflicting workload unit ';
                      }
                    }
                    if (window.ibex_gantt_config.resources[i].unit_of_measure == "14") {
                      if ($("#task_edit_workload_unit").val() != "t") {
                        resourceClashes = true;
                        setWorkloadClash = true;
                        unavailableMessage = ' has a conflicting workload unit ';
                      }
                    }
                    if (window.ibex_gantt_config.resources[i].unit_of_measure == "15") {
                      if ($("#task_edit_workload_unit").val() != "m3") {
                        resourceClashes = true;
                        setWorkloadClash = true;
                        unavailableMessage = ' has a conflicting workload unit ';
                      }
                    }
                    if (window.ibex_gantt_config.resources[i].unit_of_measure == "16") {
                      if ($("#task_edit_workload_unit").val() != "l") {
                        resourceClashes = true;
                        setWorkloadClash = true;
                        unavailableMessage = ' has a conflicting workload unit ';
                      }
                    }
                  }
                  if (resourceClashes == true) {
                    if (task.resource_id == window.ibex_gantt_config.resources[i].id) {
                      // Block to top of list as this item is no longer available
                      task.resource_id = 0;
                      $('#task_edit_resource_id').val(0);
                    }
                    if (setWorkloadClash == false) {
                      unavailableMessage = ' is assigned to another task ';
                    }
                    var option = $("<option></option>");
                    option.val(window.ibex_gantt_config.resources[i].id);
                    option.text(window.ibex_gantt_config.resources[i].name + unavailableMessage);
                    option.attr('disabled', 'disabled');
                    // $("#task_edit_resource_id").append(option);
                    optgroup.append(option);
                  } else {
                    var option = $("<option></option>");
                    option.val(window.ibex_gantt_config.resources[i].id);
                    option.text(window.ibex_gantt_config.resources[i].name);
                    // $("#task_edit_resource_id").append(option);
                    optgroup.append(option);
                  }
                } else {
                  if (task.resource_id == window.ibex_gantt_config.resources[i].id) {
                    // Block to top of list as this item is no longer available
                    task.resource_id = 0;
                    $('#task_edit_resource_id').val(0);
                  }
                  if (setWorkloadClash == false) {
                    unavailableMessage = ' is unavailable due to calendar settings ';
                  }
                  var option = $("<option></option>");
                  option.val(window.ibex_gantt_config.resources[i].id);
                  option.text(window.ibex_gantt_config.resources[i].name + unavailableMessage);
                  option.attr('disabled', 'disabled');
                  optgroup.append(option);
                }
              }
            }
          });
          $("#task_edit_resource_id").append(optgroup);
        });
		  
		  
		  for (var b = 0; b < window.ibex_gantt_config.shadowTaskResourceLinks.length; b++)
		  {
			  if (window.ibex_gantt_config.shadowTaskResourceLinks[b].task_id == window.ibex_gantt_config.activeTaskID)
				 {
					 removeA(resArray, window.ibex_gantt_config.shadowTaskResourceLinks[b].resource_id);
				 }
				
			}
			
		  
        $("#task_edit_resource_id").val(resArray).trigger("change");
        $('#task_edit_resource_group_id').val(task.resource_group_id).trigger("change");
        if (task.$new) {
          task.duration_unit = 1;
          $("#task_edit_title").text('');
          $("#task_edit_type").val('task');
          $(".task_edit_name").val('');
          $(".task_edit_name").focus();
          // Start date / time adjustments on settings
          var nextWorkingDate;
          if (window.ibex_gantt_config.taskInsertionMethod == 1) {
            nextWorkingDate = getNextWorkingDate($("#task_edit_calendar_id").val(), true);
          }
          if (window.ibex_gantt_config.taskInsertionMethod == 2) {
            nextWorkingDate = getNextWorkingDate($("#task_edit_calendar_id").val());
          }
          if (window.ibex_gantt_config.taskInsertionMethod == 3) {
            // Get last task date
            var endDateRef = moment().unix();
            gantt.eachTask(function(ch) {
              var endDate = moment(ch.end_date).format("X");
              if (endDate > endDateRef) {
                endDateRef = endDate;
              }
            });
            var endDatePeriod = moment.unix(endDateRef).format("YYYY-MM-DD");
            nextWorkingDate = getNextWorkingDate($("#task_edit_calendar_id").val(), false, endDatePeriod);
          }
          var date1 = moment(nextWorkingDate, "DD/MM/YYYY HH:mm").format('ddd D MMM YYYY');
          $("#task_edit_start_date").datepicker('update', date1);
          $("#task_edit_start_time").val(moment(nextWorkingDate, "DD/MM/YYYY HH:mm").format("HH:mm")).trigger("change");
          var duration_worked = 0;
          if (window.ibex_gantt_config.taskDurationUnit == 5) {
            var durationHours = 0,
              durationMins = 0;
            if ($("#task_edit_duration_hours").val() != "") {
              durationHours = parseInt($("#task_edit_duration_hours").val());
            }
            if ($("#task_edit_duration_mins").val() != "") {
              durationMins = parseInt($("#task_edit_duration_mins").val());
            }
            duration_worked = durationHours * 60 + durationMins;
          } else {
            $.each(window.ibex_gantt_config.calendars, function(index) {
              if (window.ibex_gantt_config.calendars[index].id == task.calendar_id) {
                var calendar = window.ibex_gantt_config.calendars[index];
                var startTime = moment(padLeadingZero(calendar.start_hour) + ":" + padLeadingZero(calendar.start_minute), "HH:mm");
                var endTime = moment(padLeadingZero(calendar.end_hour) + ":" + padLeadingZero(calendar.end_minute), "HH:mm");
                var duration = moment.duration(endTime.diff(startTime));
                var minutes = parseInt(duration.asMinutes());
                duration_worked = parseInt($("#task_edit_duration_hours").val()) * minutes;
              }
            });
          }
          var startDate = moment($("#task_edit_start_date_d").val() + "/" + $("#task_edit_start_date_m").val() + "/" + $("#task_edit_start_date_y").val() + " " + $("#task_edit_start_time_h").val() + ":" + $("#task_edit_start_time_m").val(), "DD/MM/YYYY HH:mm").toDate();
          var calendarID = $("#task_edit_calendar_id").val();
          //$("#task_edit_end_date").val(moment(getTaskEndDate(moment(startDate).format("YYYY-MM-DD HH:mm"), duration_worked, calendarID)).format("ddd D MMM (HH:mm)")).trigger("change");
          if (window.ibex_gantt_config.taskDurationUnit == 5) {}
        } else {
          $("#task_edit_duration_hours").val(Math.floor(task.duration_worked / 60)).trigger("change");
          $("#task_edit_duration_mins").val(task.duration_worked % 60).trigger("change");
          $("#task_edit_title").text(task.text);
          //$("#task_edit_type").attr('disabled', 'disabled');
        }
        // UI config
        $('#task_edit_start_date_d').material_select();
        $('#task_edit_start_date_m').material_select();
        $('#task_edit_start_date_y').material_select();
        $('#task_edit_start_time_h').material_select();
        $('#task_edit_start_time_m').material_select();
        $('.mdb-select').on("change", function() {
          $(".dropdown-content").hide();
          $(".select-dropdown").removeClass("active");
        });
        // Set select mdb stuff
        $("#task_edit_resource_id option").each(function(i) {
          if ($(this)[0].selected == true) {}
        });
		  
	
			    
  $("#modal_task_editor").modal('show');
  
        window.ibex_gantt_config.beforeTaskEditorForm = JSON.stringify($("#form_task_editor").serializeArray());
		  setTimeout(function(){
        $.getJSON("beta.ajax.php?action=get_task_locks&guid=" + window.ibex_gantt_config.activeTaskGUID, function(data) {
          if (data.locked == true) 
			 {
            $(".lock-author").html(data.user.first_name + " " + data.user.last_name + " is currently editing this task.");
            $(".lock-author-release").html("You may wait for " + data.user.first_name + " to finish, or simply close this message.<br><br> If there's 2 minutes of inactivity, then we'll release this to you.");
            $("#modal_task_locked").modal('show');
				$("#modal_task_editor").modal('hide');
          } else 
			 {
            $("#modal_task_locked").modal('hide');
			
            $("#modal_task_editor").modal('show');
            
          }
        });
	  }, 1000);
      }
      