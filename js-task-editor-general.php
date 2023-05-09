// Get cal ID
var calendarID = $("#task_edit_calendar_id").val();
var startHour, endHour, calendar;
if (calendarID) {
  $.each(window.ibex_gantt_config.calendars, function(index) {
    if (window.ibex_gantt_config.calendars[index].id == calendarID) {
      startHour = window.ibex_gantt_config.calendars[index].start_hour;
      endHour = window.ibex_gantt_config.calendars[index].end_hour;
      calendar = window.ibex_gantt_config.calendars[index];
    }
  });
}




$("#task_edit_duration_custom").change(function() {
  if($('#task_edit_calendar_id').val() != null)
    var duration_worked = convertPeriodToMinutes($("#task_edit_duration_custom").val(), $('#task_edit_calendar_id').val());
  else
    var duration_worked = convertPeriodToMinutes($("#task_edit_duration_custom").val(), $('#task_edit_calendar_id_init').val());

  var duration_worked = convertPeriodToMinutes($("#task_edit_duration_custom").val(), $("#task_edit_calendar_id").val());

  if($('#task_edit_calendar_id').val() != null)
    var calendarID = $('#task_edit_calendar_id').val();
  else
    var calendarID = $('#task_edit_calendar_id_init').val();
  
  for (var calendar of window.ibex_gantt_config.calendars) {
    if (calendar.id == calendarID) {
      var startDate = moment($("#task_edit_start_date_d").val() + "/" + $("#task_edit_start_date_m").val() + "/" + $("#task_edit_start_date_y").val() + " " + calendar.start_time, "DD/MM/YYYY HH:mm").toDate();
      var test1 = getTaskEndDate(moment(startDate).format("YYYY-MM-DD HH:mm"), duration_worked, calendarID);
      $("#task_edit_end_date").val(moment(getTaskEndDate(moment(startDate).format("YYYY-MM-DD HH:mm"), duration_worked, calendarID)).format("ddd D MMM (HH:mm)")).trigger("change");
      updateWorkloadDays();
      break;
    }
  }

});

$("#task_edit_duration_hours").change(function() {
var duration_worked = 0;

if (window.ibex_gantt_config.periodDescriptor == "1") {


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

if (window.ibex_gantt_config.originalTaskEditorObject.timing_overriden == "1") {

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



var task = window.ibex_gantt_config.originalTaskEditorObject;

$.each(window.ibex_gantt_config.calendars, function(index)
{
if (window.ibex_gantt_config.calendars[index].id == task.calendar_id)
{
var calendar = window.ibex_gantt_config.calendars[index];

var startTime = moment(padLeadingZero(calendar.start_hour) + ":" + padLeadingZero(calendar.start_minute), "HH:mm");

var endTime = moment(padLeadingZero(calendar.end_hour) + ":" + padLeadingZero(calendar.end_minute), "HH:mm");

var duration = moment.duration(endTime.diff(startTime));

var minutes = parseInt(duration.asMinutes());

if (window.ibex_gantt_config.periodDescriptor == "1")
{

duration_worked = parseInt($("#task_edit_duration_hours").val()) * minutes;
} 
else 
{

duration_worked = parseInt($("#task_edit_duration_custom").val()) * minutes;
}

duration_worked = Math.abs(duration_worked);

}
});

}
}



// var startDate = moment($("#task_edit_start_date").val() + " " + $("#task_edit_start_time").val(), "ddd D MMM YYYY HH:mm").toDate();

var startDate = moment($("#task_edit_start_date_d").val() + "/" + $("#task_edit_start_date_m").val() + "/" + $("#task_edit_start_date_y").val() + " " + $("#task_edit_start_time_h").val() + ":" + $("#task_edit_start_time_m").val(), "DD/MM/YYYY HH:mm").toDate();


var test1 = $("#task_edit_start_date_d").val() + "/" + $("#task_edit_start_date_m").val() + "/" + $("#task_edit_start_date_y").val() + " " + $("#task_edit_start_time_h").val() + ":" + $("#task_edit_start_time_m").val();

var calendarID = $("#task_edit_calendar_id").val();


var test = moment(getTaskEndDate(moment(startDate, "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm"), duration_worked, calendarID)).format("DD/MM/YYYY HH:mm");


$("#task_edit_end_date").val(moment(getTaskEndDate(moment(startDate).format("YYYY-MM-DD HH:mm"), duration_worked, calendarID)).format("ddd D MMM (HH:mm)")).trigger("change");



updateTaskAttributes("2", window.ibex_gantt_config.activeTaskID);
updateWorkloadDays();

});

$("#task_edit_duration_mins").change(function() {
var duration_worked = 0;
if (window.ibex_gantt_config.taskDurationUnit == 1) {
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

//var startDate = moment($("#task_edit_start_date").val() + " " + $("#task_edit_start_time").val(), "ddd D MMM YYYY HH:mm").toDate();

var startDate = moment($("#task_edit_start_date_d").val() + "/" + $("#task_edit_start_date_m").val() + "/" + $("#task_edit_start_date_y").val() + " " + $("#task_edit_start_time_h").val() + ":" + $("#task_edit_start_time_m").val(), "DD/MM/YYYY HH:mm").toDate();

var calendarID = $("#task_edit_calendar_id").val();

$("#task_edit_end_date22").val(moment(getTaskEndDate(moment(startDate, "ddd D MMM (HH:mm)").format("YYYY-MM-DD HH:mm"), duration_worked, calendarID)).format("ddd D MMM (HH:mm)")).trigger("change");
updateTaskAttributes("2", window.ibex_gantt_config.activeTaskID);
updateWorkloadDays();
});

$('#cp2').colorpicker({
format: "hex"
}).on('change', function(e) {
$("#task_edit_bar_colour").css('border', '1px solid ' + task.color);
});