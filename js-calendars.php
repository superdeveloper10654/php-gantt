window.padLeadingZero = function padLeadingZero(number) {
        return (number < 10) ? ("0" + number) : number;
      }
      window.getCalendar = function getCalendar(calendarID) {
        var calendar = null;
        $.each(window.ibex_gantt_config.calendars, function(index) {
          if (window.ibex_gantt_config.calendars[index].id == calendarID) {
            calendar = window.ibex_gantt_config.calendars[index];
            return calendar;
          }
        });
        return calendar;
      }
      
      window.getCalendarOverrides = function getCalendarOverrides(calendarID) {
        var calendarOverrides = [];
        $.each(window.ibex_gantt_config.calendarOverrides, function(index) {
          if (window.ibex_gantt_config.calendarOverrides[index].calendar_id == calendarID) {
            calendarOverrides.push(window.ibex_gantt_config.calendarOverrides[index]);
            return calendarOverrides;
          }
        });
        return calendarOverrides;
      }

		
      window.getMinutesInCalendarShift = function getMinutesInCalendarShift(calendarID) {
        var minutes;
        $.each(window.ibex_gantt_config.calendars, function(index) {
          if (window.ibex_gantt_config.calendars[index].id == calendarID) {
            var calendar = window.ibex_gantt_config.calendars[index];
            var startTime = moment(padLeadingZero(calendar.start_hour) + ":" + padLeadingZero(calendar.start_minute), "HH:mm");
            var endTime = moment(padLeadingZero(calendar.end_hour) + ":" + padLeadingZero(calendar.end_minute), "HH:mm");
            var duration = moment.duration(endTime.diff(startTime));
            minutes = Math.abs(duration.asMinutes());
          }
        });
        return minutes;
      }
      window.loadCalendarsToUI = function loadCalendarsToUI() {
        // Res
        $('.mdb-select').material_select('destroy');
        $('#resource_edit_calendar_id').empty();
        $('#resource_edit_calendar_id').append($('<option>', {
          value: "1",
          text: 'Select'
        }));
        $.each(window.ibex_gantt_config.calendars, function(index) {
          if (window.ibex_gantt_config.calendars[index].type == 2) {
            $('#resource_edit_calendar_id').append($('<option>', {
              value: window.ibex_gantt_config.calendars[index].id,
              text: window.ibex_gantt_config.calendars[index].name
            }));
          }
        });
        $('.mdb-select').material_select();
        $("#table_calendars > tbody").empty();
        $.each(window.ibex_gantt_config.calendars, function(index) {
          var calendar = window.ibex_gantt_config.calendars[index];
          var dayString = "";
          if (calendar.working_day_monday == 1) {
            dayString += "Mon, ";
          }
          if (calendar.working_day_tuesday == 1) {
            dayString += "Tue, ";
          }
          if (calendar.working_day_wednesday == 1) {
            dayString += "Wed, ";
          }
          if (calendar.working_day_thursday == 1) {
            dayString += "Thu, ";
          }
          if (calendar.working_day_friday == 1) {
            dayString += "Fri, ";
          }
          if (calendar.working_day_saturday == 1) {
            dayString += "Sat, ";
          }
          if (calendar.working_day_sunday == 1) {
            dayString += "Sun, ";
          }
          dayString = dayString.substring(0, dayString.length - 2);
          var calendarType = "Task";
          if (calendar.type == 2) {
            calendarType = "Resource";
          }
          $("#table_calendars > tbody").append('<tr><td style=""><a class="edit-calendar" data-index="' + calendar.id + '">' + calendar.name + '</a></td><td style=""">' + calendarType + '</td><td><span aria-hidden="true"><img src="img/svg/edit.svg" class="edit-calendar" data-index="' + calendar.id + '"></i></span></td></tr>');
        });
      }
		
// Edit both calendar types - task and resource
      $(document).on('click', '.edit-calendar', function(e) {
        $("#modal_edit_calendars").modal('hide');
        $("#modal_task_calendar_editor").modal('hide');
        $("#modal_resource_calendar_editor").modal('hide');
        $('.mdb-select').material_select('destroy');
        window.ibex_gantt_config.editingCalendarID = $(this).data('index');
        var calendar = getCalendar($(this).data('index'));
        var calendarOverrides = getCalendarOverrides($(this).data('index'));
        // TASK CALENDARS
        var calendarType = "";
        if (calendar.type == 1) {
		  
          if (calendar) {
			$("#task_editor_overrides_header").show();
			$("#resource_editor_overrides_header").show();
            $("#task_calendar_edit_id").val(calendar.id).trigger("change");
            $("#task_calendar_edit_type").val(calendar.type).trigger("change");
            $("#task_calendar_edit_name").val(calendar.name);
            $("#task_calendar_edit_start_time").val(padLeadingZero(calendar.start_hour) + ":" + padLeadingZero(calendar.start_minute));
            $("#task_calendar_edit_end_time").val(padLeadingZero(calendar.end_hour) + ":" + padLeadingZero(calendar.end_minute));
            $("#task_calendar_edit_start_minute").val(padLeadingZero(calendar.start_minute)).trigger("change");
            $("#task_calendar_edit_end_hour").val(padLeadingZero(calendar.end_hour)).trigger("change");
            $("#task_calendar_edit_end_minute").val(padLeadingZero(calendar.end_minute)).trigger("change");
            var is_default_task_calendar = 1;
            var is_default_resource_calendar = 0;
            if (calendar.is_default_task_calendar == 1) {
              $("#task_calendar_edit_default").attr('checked', true);
            } else {
              $("#task_calendar_edit_default").attr('checked', false);
            }
            if (calendar.working_day_monday == 1) {
              $("#task_calendar_edit_working_day_monday").attr("checked", true);
            } else {
              $("#task_calendar_edit_working_day_monday").attr("checked", false);
            }
            if (calendar.working_day_tuesday == 1) {
              $("#task_calendar_edit_working_day_tuesday").attr("checked", true);
            } else {
              $("#task_calendar_edit_working_day_tuesday").attr("checked", false);
            }
            if (calendar.working_day_wednesday == 1) {
              $("#task_calendar_edit_working_day_wednesday").attr("checked", true);
            } else {
              $("#task_calendar_edit_working_day_wednesday").attr("checked", false);
            }
            if (calendar.working_day_thursday == 1) {
              $("#task_calendar_edit_working_day_thursday").attr("checked", true);
            } else {
              $("#task_calendar_edit_working_day_thursday").attr("checked", false);
            }
            if (calendar.working_day_friday == 1) {
              $("#task_calendar_edit_working_day_friday").attr("checked", true);
            } else {
              $("#task_calendar_edit_working_day_friday").attr("checked", false);
            }
				
            if (calendar.working_day_saturday == 1) {
              $("#task_calendar_edit_working_day_saturday").attr("checked", true);
            } else {
              $("#task_calendar_edit_working_day_saturday").attr("checked", false);
            }
            if (calendar.working_day_sunday == 1) {
              $("#task_calendar_edit_working_day_sunday").attr("checked", true);
            } else {
              $("#task_calendar_edit_working_day_sunday").attr("checked", false);
            }
            $("#table_task_calendar_overrides > tbody").empty();
            $.each(calendarOverrides, function(index) {
              $("#table_task_calendar_overrides > tbody").append("<tr><td>" + moment(calendarOverrides[index].start_date).format("ddd Do MMM") + "&nbsp; - &nbsp;" + moment(calendarOverrides[index].end_date).format("ddd Do MMM") + "</td><td class='delete-calendar-override' data-index='" + calendarOverrides[index].id + "'><img src='img/svg/bin-1.svg'  style='height: 16px'></td></tr>");
            });
            $('.mdb-select').material_select();
            $("#modal_resource_calendar_editor").modal('hide');
            $("#modal_task_calendar_editor").modal('show');
            $("#delete-task-calendar").show();
          }
        }
        // RESOURCE CALENDARS
        if (calendar.type == 2) {
          if (calendar) {
            $("#resource_calendar_edit_id").val(calendar.id).trigger("change");
            $("resource_calendar_edit_type").val(calendar.type).trigger("change");
            $("#resource_calendar_edit_name").val(calendar.name);
            $("#resource_calendar_edit_start_time").val(padLeadingZero(calendar.start_hour) + ":" + padLeadingZero(calendar.start_minute));
            $("#resource_calendar_edit_end_time").val(padLeadingZero(calendar.end_hour) + ":" + padLeadingZero(calendar.end_minute));
            $("#resource_calendar_edit_start_minute").val(padLeadingZero(calendar.start_minute)).trigger("change");
            $("#resource_calendar_edit_end_hour").val(padLeadingZero(calendar.end_hour)).trigger("change");
            $("#resource_calendar_edit_end_minute").val(padLeadingZero(calendar.end_minute)).trigger("change");
            var is_default_task_calendar = 0;
            var is_default_resource_calendar = 1;
            if (calendar.is_default_resource_calendar == 1) {
              $("#resource_calendar_edit_default").attr('checked', true);
            } else {
              $("#resource_calendar_edit_default").attr('checked', false);
            }
            if (calendar.working_day_monday == 1) {
              $("#resource_calendar_edit_working_day_monday").attr("checked", true);
            } else {
              $("#resource_calendar_edit_working_day_monday").attr("checked", false);
            }
            if (calendar.working_day_tuesday == 1) {
              $("#resource_calendar_edit_working_day_tuesday").attr("checked", true);
            } else {
              $("#resource_calendar_edit_working_day_tuesday").attr("checked", false);
            }
            if (calendar.working_day_wednesday == 1) {
              $("#resource_calendar_edit_working_day_wednesday").attr("checked", true);
            } else {
              $("#resource_calendar_edit_working_day_wednesday").attr("checked", false);
            }
            if (calendar.working_day_thursday == 1) {
              $("#resource_calendar_edit_working_day_thursday").attr("checked", true);
            } else {
              $("#resource_calendar_edit_working_day_thursday").attr("checked", false);
            }
            if (calendar.working_day_friday == 1) {
              $("#resource_calendar_edit_working_day_friday").attr("checked", true);
            } else {
              $("#resource_calendar_edit_working_day_friday").attr("checked", false);
            }
            if (calendar.working_day_saturday == 1) {
              $("#resource_calendar_edit_working_day_saturday").attr("checked", true);
            } else {
              $("#resource_calendar_edit_working_day_saturday").attr("checked", false);
            }
            if (calendar.working_day_sunday == 1) {
              $("#resource_calendar_edit_working_day_sunday").attr("checked", true);
            } else {
              $("#resource_calendar_edit_working_day_sunday").attr("checked", false);
            }
            $("#table_resource_calendar_overrides > tbody").empty();
            $.each(calendarOverrides, function(index) {
              $("#table_resource_calendar_overrides > tbody").append("<tr><td>" + moment(calendarOverrides[index].start_date).format("DD/MM/YYYY") + " - " + moment(calendarOverrides[index].end_date).format("DD/MM/YYYY") + "</td><td class='delete-calendar-override' data-index='" + calendarOverrides[index].id + "'><img src='img/svg/bin-1.svg'  style='height: 16px'></td></tr>");
            });
            $('.mdb-select').material_select();
            $("#modal_task_calendar_editor").modal('hide');
            $("#modal_resource_calendar_editor").modal('show');
            $("#delete-resource-calendar").show();
          }
        }
      });


$("#save-task-calendar").click(function() {
            $(this).find('form').trigger('reset');                
        $("#modal_task_calendar_editor").modal('hide');
        $('.mdb-select').material_select('destroy');
        var enabled = true,
          isDefault = true,
          workingDayMonday = true,
          workingDayTuesday = true,
          workingDayWednesday = true,
          workingDayThursday = true,
          workingDayFriday = true,
          workingDaySaturday = true,
          workingDaySunday = true;
        if (!$('#task_calendar_edit_working_day_monday').is(':checked')) {
          workingDayMonday = false;
        }
        if (!$('#task_calendar_edit_working_day_tuesday').is(':checked')) {
          workingDayTuesday = false;
        }
        if (!$('#task_calendar_edit_working_day_wednesday').is(':checked')) {
          workingDayWednesday = false;
        }
        if (!$('#task_calendar_edit_working_day_thursday').is(':checked')) {
          workingDayThursday = false;
        }
        if (!$('#task_calendar_edit_working_day_friday').is(':checked')) {
          workingDayFriday = false;
        }
        if (!$('#task_calendar_edit_working_day_saturday').is(':checked')) {
          workingDaySaturday = false;
        }
        if (!$('#task_calendar_edit_working_day_sunday').is(':checked')) {
          workingDaySunday = false;
        }
        if (!$('#task_calendar_edit_enabled').is(':checked')) {
          enabled = false;
        }
        if (!$('#task_calendar_edit_default').is(':checked')) {
          isDefault = false;
        }
		  
        $.getJSON("beta.ajax.php?action=save_task_calendar&id=" + $("#task_calendar_edit_id").val() + "&name=" + $("#task_calendar_edit_name").val() + "&working_day_monday=" + workingDayMonday + "&working_day_tuesday=" + workingDayTuesday + "&working_day_wednesday=" + workingDayWednesday + "&working_day_thursday=" + workingDayThursday + "&working_day_friday=" + workingDayFriday + "&working_day_saturday=" + workingDaySaturday + "&working_day_sunday=" + workingDaySunday + "&start_time=" + $("#calendar_edit_start_time").val() + "&end_time=" + $("#calendar_edit_end_time").val() + "&enabled=" + enabled + "&default=" + isDefault + "&type=1" +"&overrides=" + JSON.stringify(window.ibex_gantt_config.activeCalendarOverrides), function(data) {
		  window.ibex_gantt_config.activeCalendarOverrides = [];
          reloadSettings();
        });
      });

      $("#save-resource-calendar").click(function() {
        $("#modal_resource_calendar_editor").modal('hide');
        $('.mdb-select').material_select('destroy');
        var enabled = true,
          isDefault = true,
          workingDayMonday = true,
          workingDayTuesday = true,
          workingDayWednesday = true,
          workingDayThursday = true,
          workingDayFriday = true,
          workingDaySaturday = true,
          workingDaySunday = true;
        if (!$('#resource_calendar_edit_working_day_monday').is(':checked')) {
          workingDayMonday = false;
        }
        if (!$('#resource_calendar_edit_working_day_tuesday').is(':checked')) {
          workingDayTuesday = false;
        }
        if (!$('#resource_calendar_edit_working_day_wednesday').is(':checked')) {
          workingDayWednesday = false;
        }
        if (!$('#resource_calendar_edit_working_day_thursday').is(':checked')) {
          workingDayThursday = false;
        }
        if (!$('#resource_calendar_edit_working_day_friday').is(':checked')) {
          workingDayFriday = false;
        }
        if (!$('#resource_calendar_working_day_saturday').is(':checked')) {
          workingDaySaturday = false;
        }
        if (!$('#resource_calendar_edit_working_day_sunday').is(':checked')) {
          workingDaySunday = false;
        }
        if (!$('#resource_calendar_edit_enabled').is(':checked')) {
          enabled = false;
        }
        if (!$('#resource_calendar_edit_default').is(':checked')) {
          isDefault = false;
        }
        $.getJSON("beta.ajax.php?action=save_resource_calendar&id=" + $("#resource_calendar_edit_id").val() + "&name=" + $("#resource_calendar_edit_name").val() + "&working_day_monday=" + workingDayMonday + "&working_day_tuesday=" + workingDayTuesday + "&working_day_wednesday=" + workingDayWednesday + "&working_day_thursday=" + workingDayThursday + "&working_day_friday=" + workingDayFriday + "&working_day_saturday=" + workingDaySaturday + "&working_day_sunday=" + workingDaySunday + "&start_time=" + $("#resource_calendar_edit_start_time").val() + "&end_time=" + $("#resource_calendar_edit_end_time").val() + "&enabled=" + enabled + "&default=" + isDefault + "&type=" + $("#resource_calendar_edit_type").val(), function(data) {
          reloadSettings();
        });
                           // window.location.href = "beta.php?id=" + $("#programme_id").val();
      });

  $(document).on('click', '.add-task-calendar-override', function(e) {
        var startDate = moment($("#task_calendar_edit_override_start_date").val(), 'dddd, DD MMM YYYY').format('YYYY-MM-DD');
        $.getJSON("beta.ajax.php?action=add_calendar_override&programme_id=" + $("#programme_id").val() + "&calendar_id=" + window.ibex_gantt_config.editingCalendarID + "&start_date=" + moment($("#task_calendar_edit_override_start_date").val(), 'dddd, DD MMM YYYY').format('YYYY-MM-DD') + "&end_date=" + moment($("#task_calendar_edit_override_end_date").val(), 'dddd, DD MMM YYYY').format('YYYY-MM-DD'), function(data) {
          $("#table_task_calendar_overrides > tbody").empty();
          $.each(data.calendar_overrides, function(index) {
            $("#table_task_calendar_overrides > tbody").append("<tr><td>" + moment(data.calendar_overrides[index].start_date).format("DD/MM/YYYY") + " - " + moment(data.calendar_overrides[index].end_date).format("DD/MM/YYYY") + "</td><td class='delete-calendar-override' data-index='" + data.calendar_overrides[index].id + "'><img src='img/svg/bin-1.svg'  style='height: 16px'></td></tr>");
          });
        });
      });

$(document).on('click', '.add-resource-calendar-override', function(e) {
        var startDate = moment($("#resource_calendar_edit_override_start_date").val(), 'dddd, DD MMM YYYY').format('YYYY-MM-DD');
        $.getJSON("beta.ajax.php?action=add_calendar_override&programme_id=" + $("#programme_id").val() + "&calendar_id=" + window.ibex_gantt_config.editingCalendarID + "&start_date=" + moment($("#resource_calendar_edit_override_start_date").val(), 'dddd, DD MMM YYYY').format('YYYY-MM-DD') + "&end_date=" + moment($("#resource_calendar_edit_override_end_date").val(), 'dddd, DD MMM YYYY').format('YYYY-MM-DD'), function(data) {
          $("#table_resource_calendar_overrides > tbody").empty();
          $.each(data.calendar_overrides, function(index) {
            $("#table_resource_calendar_overrides > tbody").append("<tr><td>" + moment(data.calendar_overrides[index].start_date).format("DD/MM/YYYY") + " - " + moment(data.calendar_overrides[index].end_date).format("DD/MM/YYYY") + "</td><td class='delete-calendar-override' data-index='" + data.calendar_overrides[index].id + "'><img src='img/svg/bin-1.svg'  style='height: 16px'></td></tr>");
          });
        });
      });

$(document).on('click', '#delete-resource-calendar', function(e) {
        $.getJSON("beta.ajax.php?action=delete_calendar&id=" + window.ibex_gantt_config.editingCalendarID, function(data) {
          $("#modal_resource_calendar_editor").modal('hide');
          reloadSettings();
        });
      });

$(document).on('click', '.delete-calendar-override', function(e) {
        $(this).data('index');
        $.getJSON("beta.ajax.php?action=delete_calendar_override&id=" + $(this).data('index') + "&calendar_id=" + window.ibex_gantt_config.editingCalendarID, function(data) {
          $("#table_task_calendar_overrides > tbody").empty();
          $.each(data.calendar_overrides, function(index) {
            $("#table_task_calendar_overrides > tbody").append("<tr><td>" + moment(data.calendar_overrides[index].start_date).format("DD/MM/YYYY") + " - " + moment(data.calendar_overrides[index].end_date).format("DD/MM/YYYY") + "</td><td class='delete-calendar-override' data-index='" + data.calendar_overrides[index].id + "'><img src='img/svg/bin-1.svg'  style='height: 16px'></td></tr>");
          });
        });
      });
		
		
		$(document).on('click', '.delete-resource-calendar-override', function(e) {
        $(this).data('index');
        $.getJSON("beta.ajax.php?action=delete_calendar_override&id=" + $(this).data('index') + "&calendar_id=" + window.ibex_gantt_config.editingCalendarID, function(data) {
          $("#table_resource_calendar_overrides > tbody").empty();
          $.each(data.calendar_overrides, function(index) {
            $("#table_resource_calendar_overrides > tbody").append("<tr><td>" + moment(data.calendar_overrides[index].start_date).format("DD/MM/YYYY") + " - " + moment(data.calendar_overrides[index].end_date).format("DD/MM/YYYY") + "</td><td class='delete-resource-calendar-override' data-index='" + data.calendar_overrides[index].id + "'><img src='img/svg/bin-1.svg'  style='height: 16px'></td></tr>");
          });
        });
	  });

$(document).on('click', '#delete-task-calendar', function(e) {
        $.getJSON("beta.ajax.php?action=delete_calendar&id=" + window.ibex_gantt_config.editingCalendarID, function(data) {
          $("#modal_task_calendar_editor").modal('hide');
          reloadSettings();
        });
      });

           $(".reopen-all-calendars").click(function() {
        $("#modal_edit_calendars").modal('show');
      });
                            
                            $("#settings-manage-calendars").click(function(e) {
        //$('#settings').removeClass("show active");
        //$('[data-toggle="collapse"]').show();
      });

      $(".close-calendars-modal").click(function(e) {
        $('#settings').addClass("show active");
        //$('[data-toggle="collapse"]').hide();
                            window.location.href = "beta.php?id=" + $("#programme_id").val();
      });
                            
                            $(".add-resource-calendar").click(function(e) {
        $("#modal_task_calendar_editor").modal('hide');
        $("#modal_edit_calendars").modal('hide');
        $("#modal_resource_calendar_editor").modal('show');
        $("#modal_resource_editor").modal('hide');
		  $("#resource_editor_overrides_header").hide();
      });

      $(".add-task-calendar").click(function(e) {
        $("#modal_resource_editor").modal('hide');
        $("#modal_edit_calendars").modal('hide');
        $("#modal_resource_calendar_editor").modal('hide');
        $("#modal_task_calendar_editor").modal('show');
		  $("#task_editor_overrides_header").hide();
      });
                            
                      $(document).on('change', '#resource_edit_calendar_id', function(e) {
        if ($(this).val() == "X") {
          $("#modal_calendar_editor").modal('show');
        }
      });      
		
		
		   $(".task-add-calendar-override").click(function(e) 
			{
				
				var startDate = $("#task_calendar_edit_override_start_y").val() + "-" + $("#task_calendar_edit_override_start_m").val() + "-" + $("#task_calendar_edit_override_start_d").val();
				var endDate = $("#task_calendar_edit_override_end_y").val() + "-" + $("#task_calendar_edit_override_end_m").val() + "-" + $("#task_calendar_edit_override_end_d").val();
	
	 $.getJSON("beta.ajax.php?action=add_task_calendar_override&programme_id=" + $("#programme_id").val() + "&calendar_id=" + $("#task_calendar_edit_id").val() + "&start_date=" + startDate + "&end_date=" + endDate, function(data) {
	  $("#table_task_calendar_overrides > tbody").empty();
            $.each(data.calendar_overrides, function(index) 
				{
              $("#table_task_calendar_overrides > tbody").append("<tr><td>" + moment(data.calendar_overrides[index].start_date).format("DD/MM/YYYY") + " - " + moment(data.calendar_overrides[index].end_date).format("DD/MM/YYYY") + "</td><td class='delete-calendar-override' data-index='" + data.calendar_overrides[index].id + "'><img src='img/svg/bin-1.svg'  style='height: 16px'></td></tr>");
			  });
			  
		 });
		 
		 
		 
		 
		 
				
	  });
	  
	  
	  
	  
		   $(".resource-add-calendar-override").click(function(e) 
			{
				var startDate = $("#resource_calendar_edit_override_start_y").val() + "-" + $("#resource_calendar_edit_override_start_m").val() + "-" + $("#resource_calendar_edit_override_start_d").val();
				var endDate = $("#resource_calendar_edit_override_end_y").val() + "-" + $("#resource_calendar_edit_override_end_m").val() + "-" + $("#resource_calendar_edit_override_end_d").val();
				 $.getJSON("beta.ajax.php?action=add_task_calendar_override&programme_id=" + $("#programme_id").val() + "&calendar_id=" + $("#resource_calendar_edit_id").val() + "&start_date=" + startDate + "&end_date=" + endDate, function(data) {
	  $("#table_resource_calendar_overrides > tbody").empty();
            $.each(data.calendar_overrides, function(index) 
				{
              $("#table_resource_calendar_overrides > tbody").append("<tr><td>" + moment(data.calendar_overrides[index].start_date).format("DD/MM/YYYY") + " - " + moment(data.calendar_overrides[index].end_date).format("DD/MM/YYYY") + "</td><td class='delete-resource-calendar-override' data-index='" + data.calendar_overrides[index].id + "'><img src='img/svg/bin-1.svg'  style='height: 16px'></td></tr>");
			  });
			  
		 });
		 
		 
				
				
	  });
	  
	  
	  
	  $('#modal_task_calendar_editor').on('shown.bs.modal', function (e) {
	 
$('.mdb-select').material_select('destroy');
  $("#task_calendar_edit_override_start_d").val(moment().format("DD")).trigger("change");
  $("#task_calendar_edit_override_start_m").val(moment().format("MM"));
  $("#task_calendar_edit_override_start_y").val(moment().format("YYYY"));
  
    $("#task_calendar_edit_override_end_d").val(moment().format("DD"));
  $("#task_calendar_edit_override_end_m").val(moment().format("MM"));
  $("#task_calendar_edit_override_end_y").val(moment().format("YYYY"));
   $('.mdb-select').material_select();
})
	  

 $('#modal_resource_calendar_editor').on('shown.bs.modal', function (e) {
	 
$('.mdb-select').material_select('destroy');
  $("#resource_calendar_edit_override_start_d").val(moment().format("DD")).trigger("change");
  $("#resource_calendar_edit_override_start_m").val(moment().format("MM"));
  $("#resource_calendar_edit_override_start_y").val(moment().format("YYYY"));
  
    $("#resource_calendar_edit_override_end_d").val(moment().format("DD"));
  $("#resource_calendar_edit_override_end_m").val(moment().format("MM"));
  $("#resource_calendar_edit_override_end_y").val(moment().format("YYYY"));
   $('.mdb-select').material_select();
})

	  
		
		
		
	
		
		
		
		