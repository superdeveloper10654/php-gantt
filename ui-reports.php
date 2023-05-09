<script>
  
  
  $("#task_reports_select").select2({
    closeOnSelect: true,
    placeholder: "Select a task",
    multiple: false
  });


  $('#task_reports_select').on("select2:select", function(e) {
    
    
    $('#table-resources-names tbody > tr').remove();
    $('#table-files-names tbody > tr').remove();
    $('#bar1').empty();
    $('#bar2').empty();
    $('#bar-legend-budget').empty();
    $('#bar-legend-baseline-value').empty();
    $('#bar-legend-earned-value').empty();
    $('#bar-legend-actual-cost').empty();
    
    
    
    $.getJSON("beta.ajax.php?action=reported_task&id=" + e.params.data.id, function(data) {

      window.ibex_gantt_config.reported_task = data.task;

      var task = data.task;
      var calendar = data.task.calendar_object;
      var overrides = data.task.calendar_override_objects;
      
      

var calendarName = calendar.name;
$('#calendar-name').html(calendarName);
     
      var calendarWorkingDays = 'Sporadic';
      
      if (calendar.working_day_monday == 1 && calendar.working_day_tuesday == 1 && calendar.working_day_wednesday == 1 && calendar.working_day_thursday == 1 && calendar.working_day_friday == 1 && calendar.working_day_saturday == 1 && calendar.working_day_sunday == 1) {
      calendarWorkingDays = 'Mon to Sun';
      }
      if (calendar.working_day_monday == 1 && calendar.working_day_tuesday == 1 && calendar.working_day_wednesday == 1 && calendar.working_day_thursday == 1 && calendar.working_day_friday == 1 && calendar.working_day_saturday == 1 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Mon to Sat';
      }
      if (calendar.working_day_monday == 1 && calendar.working_day_tuesday == 1 && calendar.working_day_wednesday == 1 && calendar.working_day_thursday == 1 && calendar.working_day_friday == 1 && calendar.working_day_saturday == 0 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Mon to Fri';
      }
      if (calendar.working_day_monday == 1 && calendar.working_day_tuesday == 1 && calendar.working_day_wednesday == 1 && calendar.working_day_thursday == 1 && calendar.working_day_friday == 0 && calendar.working_day_saturday == 0 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Mon to Thu';
      }
      if (calendar.working_day_monday == 1 && calendar.working_day_tuesday == 1 && calendar.working_day_wednesday == 1 && calendar.working_day_thursday == 0 && calendar.working_day_friday == 0 && calendar.working_day_saturday == 0 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Mon to Wed';
      }
      if (calendar.working_day_monday == 1 && calendar.working_day_tuesday == 1 && calendar.working_day_wednesday == 0 && calendar.working_day_thursday == 0 && calendar.working_day_friday == 0 && calendar.working_day_saturday == 0 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Mon & Tue';
      }
      if (calendar.working_day_monday == 1 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 0 && calendar.working_day_thursday == 0 && calendar.working_day_friday == 0 && calendar.working_day_saturday == 0 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Mondays';
      }
      
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 1 && calendar.working_day_wednesday == 1 && calendar.working_day_thursday == 1 && calendar.working_day_friday == 1 && calendar.working_day_saturday == 1 && calendar.working_day_sunday == 1) {
      calendarWorkingDays = 'Tue to Sun';
      }
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 1 && calendar.working_day_wednesday == 1 && calendar.working_day_thursday == 1 && calendar.working_day_friday == 1 && calendar.working_day_saturday == 1 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Tue to Sat';
      }
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 1 && calendar.working_day_wednesday == 1 && calendar.working_day_thursday == 1 && calendar.working_day_friday == 1 && calendar.working_day_saturday == 0 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Tue to Fri';
      }
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 1 && calendar.working_day_wednesday == 1 && calendar.working_day_thursday == 1 && calendar.working_day_friday == 0 && calendar.working_day_saturday == 0 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Tue to Thu';
      }
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 1 && calendar.working_day_wednesday == 1 && calendar.working_day_thursday == 0 && calendar.working_day_friday == 0 && calendar.working_day_saturday == 0 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Tue & Wed';
      }
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 0 && calendar.working_day_thursday == 0 && calendar.working_day_friday == 0 && calendar.working_day_saturday == 0 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Tuesdays';
      }
      
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 1 && calendar.working_day_thursday == 1 && calendar.working_day_friday == 1 && calendar.working_day_saturday == 1 && calendar.working_day_sunday == 1) {
      calendarWorkingDays = 'Wed to Sun';
      }
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 1 && calendar.working_day_thursday == 1 && calendar.working_day_friday == 1 && calendar.working_day_saturday == 1 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Wed to Sat';
      }
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 1 && calendar.working_day_thursday == 1 && calendar.working_day_friday == 1 && calendar.working_day_saturday == 0 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Wed to Fri';
      }
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 1 && calendar.working_day_thursday == 1 && calendar.working_day_friday == 0 && calendar.working_day_saturday == 0 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Wed & Thu';
      }
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 1 && calendar.working_day_thursday == 0 && calendar.working_day_friday == 0 && calendar.working_day_saturday == 0 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Wednesdays';
      }
      
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 1 && calendar.working_day_thursday == 1 && calendar.working_day_friday == 1 && calendar.working_day_saturday == 1 && calendar.working_day_sunday == 1) {
      calendarWorkingDays = 'Wed to Sun';
      }
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 1 && calendar.working_day_thursday == 1 && calendar.working_day_friday == 1 && calendar.working_day_saturday == 1 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Wed to Sat';
      }
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 1 && calendar.working_day_thursday == 1 && calendar.working_day_friday == 1 && calendar.working_day_saturday == 0 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Wed to Fri';
      }
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 1 && calendar.working_day_thursday == 1 && calendar.working_day_friday == 0 && calendar.working_day_saturday == 0 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Wed & Thu';
      }
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 1 && calendar.working_day_thursday == 0 && calendar.working_day_friday == 0 && calendar.working_day_saturday == 0 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Wednesdays';
      }
      
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 0 && calendar.working_day_thursday == 1 && calendar.working_day_friday == 1 && calendar.working_day_saturday == 1 && calendar.working_day_sunday == 1) {
      calendarWorkingDays = 'Thu to Sun';
      }
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 0 && calendar.working_day_thursday == 1 && calendar.working_day_friday == 1 && calendar.working_day_saturday == 1 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Thu to Sat';
      }
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 0 && calendar.working_day_thursday == 1 && calendar.working_day_friday == 1 && calendar.working_day_saturday == 0 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Thu & Fri';
      }
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 0 && calendar.working_day_thursday == 1 && calendar.working_day_friday == 0 && calendar.working_day_saturday == 0 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Thursdays';
      }
      
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 0 && calendar.working_day_thursday == 0 && calendar.working_day_friday == 1 && calendar.working_day_saturday == 1 && calendar.working_day_sunday == 1) {
      calendarWorkingDays = 'Fri to Sun';
      }
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 0 && calendar.working_day_thursday == 0 && calendar.working_day_friday == 1 && calendar.working_day_saturday == 1 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Fri & Sat';
      }
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 0 && calendar.working_day_thursday == 0 && calendar.working_day_friday == 1 && calendar.working_day_saturday == 0 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Fridays';
      }
      
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 0 && calendar.working_day_thursday == 0 && calendar.working_day_friday == 0 && calendar.working_day_saturday == 1 && calendar.working_day_sunday == 1) {
      calendarWorkingDays = 'Sat & Sun';
      }
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 0 && calendar.working_day_thursday == 0 && calendar.working_day_friday == 0 && calendar.working_day_saturday == 1 && calendar.working_day_sunday == 0) {
      calendarWorkingDays = 'Saturdays';
      }
      
      if (calendar.working_day_monday == 0 && calendar.working_day_tuesday == 0 && calendar.working_day_wednesday == 0 && calendar.working_day_thursday == 0 && calendar.working_day_friday == 0 && calendar.working_day_saturday == 0 && calendar.working_day_sunday == 1) {
      calendarWorkingDays = 'Sundays';
      }
      
      var calendarWorkingTimes = calendar.start_time + ' to ' + calendar.end_time;

        $('#calendar-details').html('(' + calendarWorkingDays + ',&nbsp;' + calendarWorkingTimes + ')');
        
        
        
      var normalStartTime = calendar.start_time;
      var normalFinishTime = calendar.end_time;

      var normalWorkingHoursPerDay = calendar.end_hour - calendar.start_hour;
      var normalNonWorkingHoursPerDay = 24 - normalWorkingHoursPerDay;

      var baselineDurationIncludingNonworking = moment(task.baseline_end).diff(moment(task.baseline_start), 'days') + 1;
      var dayNameBaselineStart = moment(task.baseline_start).format('dddd');
      
      $.each(window.ibex_gantt_config.calendarOverrides, function(index) {
        var overrides = getCalendarOverrides(calendar.id);
        for (i = 0; i < overrides.length; i++) {
          var startDateOverride = overrides[i].start_date;
          var endDateOverride = overrides[i].end_date;
        }
        var overrideDuration = moment(endDateOverride) - moment(startDateOverride) + 1;
      });

      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      

      var progress = (task.progress * 1).toFixed(2);
      $("#GaugeMeter_progress").gaugeMeter({
        percent: progress
      });
      
      new Morris.Line({
  element: 'line1',
  data: [
    { day: 'Mon', baseline: 20, actual: 10 },
    { day: 'Tue', baseline: 40, actual: 45 },
    { day: 'Wed', baseline: 60, actual: 50 },
    { day: 'Thu', baseline: 80, actual: 65 },
    { day: 'Fri', baseline: 100, actual: 90 }
  ],
  xkey: 'day',
  parseTime: false,
  ykeys: ['baseline','actual'],
  labels: ['',''],
  lineColors: ['#ddd','#1ea69a'],
            resize: true,

            grid: true,
            axis: true,
});
      
      
      
      
      
      
      

var comments = [];
  if (task.comments != "" && task.comments != undefined) {
    comments = JSON.parse(task.comments);
  }
  $('#task_report_comments > tbody').empty();
  for (i = 0; i < comments.length; i++) {
    $('#task_report_comments >tbody').append('<tr><td>"' + comments[i].text + '"<br><span class="text-muted" style="font-size: 0.9em">' + comments[i].added + '</span></td></tr>');
  }


















      var resources = data.task.resources_objects;
      var taskResources = [];
      var taskResourceGroupID = task.resource_group_id;

      if (taskResourceGroupID == "0" || taskResourceGroupID == null || taskResourceGroupID == "") {} else {
        // We need to get all items in this resource group too and append to string above
        $.each(window.ibex_gantt_config.resources, function(i) {
          if (window.ibex_gantt_config.resources[i].group_id == taskResourceGroupID) {
            taskResources.push(window.ibex_gantt_config.resources[i].name);
            taskResources.push(window.ibex_gantt_config.resources[i].cost_rate);
            taskResources.push(window.ibex_gantt_config.resources[i].resource_edit_image_url);
            
            var resource_item_actual_cost = ((task.workload_quantity * progress / 100).toFixed(2) * window.ibex_gantt_config.resources[i].cost_rate).toFixed(2);
            var resource_item_effort = (task.workload_quantity * progress / 100).toFixed(2) + ' ' + task.workload_quantity_unit + ' @ £' + window.ibex_gantt_config.resources[i].cost_rate;
//$('#table-resources-names > tbody').append('<tr><td><img style="height: 60px; float: left;" src="' + window.ibex_gantt_config.resources[i].resource_image_url + '"</td><td>' + window.ibex_gantt_config.resources[i].name + '</td><td><div>Actual cost £' + resource_item_actual_cost + '</div><div>' + resource_item_effort + '</div></td></tr>') ;
$('#table-resources-names > tbody').append('<tr><td><img style="width: 100px;" src="' + window.ibex_gantt_config.resources[i].resource_image_url + '"</td><td><div>' + window.ibex_gantt_config.resources[i].name + '</div><div>Actual cost £' + resource_item_actual_cost + '</div><div>' + resource_item_effort + '</div></td></tr>') ;
          }
      });
      }
      

var files = data.task.files_objects;
      var taskFiles = [];
$.each(window.ibex_gantt_config.files, function(i) {            
            var fileName = window.ibex_gantt_config.files[i].name;
  var fileImage = window.ibex_gantt_config.files[i].url;
  var extension = window.ibex_gantt_config.files[i].extension;
  if (extension == "xls" || extension == "xlsx" || extension == "xlsm" || extension == "csv") {
            var fileImage = "img/svg/file-excel.svg";
          }
  if (extension == "doc" || extension == "docx" || extension == "docm" || extension == "dotx" || extension == "dot" || extension == "dotm" || extension == "odt")  {
            var fileImage = "img/svg/file-word.svg";
          }
  if (extension == "pdf")  {
            var fileImage = "img/svg/file-pdf.svg";
          }
            
$('#table-files-names > tbody').html('<tr><td><div class="reports-files-container"><img class="reports-file-image" src="' + fileImage + '"><div class="report-file-name">' + window.ibex_gantt_config.files[i].name + '</div></div></td></tr>') ;
        
      });
      





      var task_text = task.text;
      $('#task-text').html(task_text);

      var task_parent = data.task.parent_object;
      $('#task-parent-text').html(task_parent.text);

      var task_bar_color = task.color;


































































      // TASK DURATION
      // 'duration_worked' is the number of hours worked per day, e.g: 60 mins x 10 hrs x 12 days
      //var duration = Math.abs(Number(task.duration));
      var taskTotalHours = task.duration_worked / 60; 

      var daysWorked = (taskTotalHours * progress / 100).toFixed(2);
      var durationProgressed = 0;

      if (daysWorked = 1) {
        durationProgressed = daysWorked + ' day worked';
      }
      if (daysWorked > 1) {
        durationProgressed = daysWorked + ' days worked';
      }
      $('#duration-progressed').html(durationProgressed);

      var durationExplanation = 'Duration is&nbsp;';
      if (progress == 100) {
        var durationExplanation = 'Duration was&nbsp;';
      };
      $('#duration-explanation').html(durationExplanation);
      var durationDaysNumber = task.duration;
      $('#duration-days-number').html(durationDaysNumber + ' shifts');
      var durationHoursNumber = '(' + taskTotalHours + '&nbsp;working hours)';
      $('#duration-hours-number').html(durationHoursNumber);




































     




      var baselineProgress = (1 * task.baseline_progress).toFixed(2);
      $("#GaugeMeter_baseline_progress").gaugeMeter({
        percent: baselineProgress
      });







































      // TASK SCHEDULE STATUS

      if (progress == "100" && task.actual_cost_completion > task.budget_at_completion) {
        $('#block-start-status').html('Delayed').css({'display': 'block'});
        $('#block-finish-status').html('Delayed').css({'display': 'block'});
        $('#block-actual-cost-status').html('High').css({'display': 'block'});
      }
      if (moment(task.start_date).isAfter(moment(task.baseline_start)) && moment(task.end_date).isAfter(moment(task.baseline_end)) && progress == "0") {
        $('#block-start-status').html('Delayed').css({'display': 'block'});
        $('#block-finish-status').html('Delayed').css({'display': 'block'});
      }
      if (moment(task.start_date).isAfter(moment(task.baseline_start)) && moment().isAfter(moment(task.start_date)) && moment(task.end_date).isAfter(moment(task.baseline_end)) && progress != "0") {
        $('#block-start-status').html('Delayed').css({'display': 'block'});
        $('#block-finish-status').html('Delayed').css({'display': 'block'});
      }
      if (moment(task.start_date).isBefore(moment(task.baseline_start)) && moment(task.end_date).isBefore(moment(task.baseline_end)) && progress == "0") {
        $('#block-start-status').html('Accelerated').css({'display': 'block'});
        $('#block-finish-status').html('Accelerated').css({'display': 'block'});
      }
      if (moment(task.start_date).isBefore(moment(task.baseline_start)) && moment(task.end_date).isBefore(moment(task.baseline_end)) && progress != "0") {
        $('#block-start-status').html('Accelerated').css({'display': 'block'});
        $('#block-finish-status').html('Accelerated').css({'display': 'block'});
      }
      if (moment(task.start_date).isBefore(moment(task.baseline_start)) && moment(task.end_date).isAfter(moment(task.baseline_end)) && progress != "0") {
        $('#block-start-status').html('Accelerated').css({'display': 'block'});
        $('#block-finish-status').html('Accelerated').css({'display': 'block'});
        $('#block-duration-status').html('Prolonged').css({'display': 'block'});
      }
      if (moment(task.start_date).isSame(moment(task.baseline_start)) && moment(task.end_date).isAfter(moment(task.baseline_end)) && progress != "0") {
        $('#block-duration-status').html('Prolonged').css({'display': 'block'});
      }
      if (moment(task.start_date).isBefore(moment(task.baseline_start)) && moment(task.end_date).isAfter(moment(task.baseline_end)) && progress != "0") {
        $('#block-duration-status').html('Prolonged').css({'display': 'block'});
      }
      
      if (task.manually_delayed == "0" && progress == "0") {
      }
      if (moment(task.start_date).isSame(moment(task.baseline_start)) && moment(task.start_date).isBefore(moment()) && moment(task.baseline_end).isSame(moment(task.end_date)) && task.manually_delayed == "0" && progress != "0" && progress != "100") {
      }

      if (progress == "100") {
      }
      
      if (moment(task.end_date).isAfter(moment(task.deadline))) {
        $('#block-deadline-status').html('Missed').css({'display': 'block'});
      }
      
      if (progress < baselineProgress) {
        $('#block-progress-status').html('Behind').css({'display': 'block'});
      }
      if (progress == baselineProgress) {
        $('#block-progress-status').html('Steady').css({'display': 'block', 'background': '#1ea69a' });
      }
      if (progress > baselineProgress) {
        $('#block-progress-status').html('Ahead').css({'display': 'block'});
      }

      
      
      
      
      
      
      // BASELINE DURATION (RB HACK - AB TO CALCULATE PROPERLY)

     
if (baselineDurationIncludingNonworking == durationDaysNumber && progress != "0") {

      }



      
      
      
      
      
      
      
      
      
      
      
      
      
      

// TASK EARNED VALUE
      var earnedValue = (task.budget_cost_completion * progress / 100).toFixed(2);
      $('#earned-value').html('£' + earnedValue);
      var earnedValueExplanation = 'Budget (£' + task.budget_at_completion + ') x actual progress (' + progress + '%)';
      $('#earned-value-explanation').html(earnedValueExplanation);


 // TASK COST PERFORMANCE INDICATOR
      var cpi = ((earnedValue / task.actual_cost_completion) * 100).toFixed(2);
      $("#GaugeMeter_cpi").gaugeMeter({
        percent: cpi
      });
var cpiDecimal = cpi / 100;
      var costEfficiencyExplanation = 'Also known as CPI (' + cpiDecimal + ').<br>Earned value (£' + earnedValue + ') divided by actual cost (£' + task.actual_cost_completion + ')';
      $('#cost-efficiency-explanation').html(costEfficiencyExplanation);


      
      

     









     

      










      // TASK START
      var startExplanation = "Starting on&nbsp;";
      if (progress > 0) {
        startExplanation = "Started on&nbsp;";
      };
      $('#start-explanation').html(startExplanation);
      var startDate = '' + moment(task.start_date, "YYYY-MM-DD HH:mm:ss").format("ddd Do MMM YYYY") + '';
      $('#start-date').html(startDate);
      var startTime = '&nbsp;@&nbsp;' + moment(task.start_date, "YYYY-MM-DD HH:mm:ss").format("HH:mm") + '';
      $('#start-time').html(startTime);


      // TASK FINISH (END)
      var finishExplanation = "Finishing on&nbsp;";
      if (progress == 100) {
        finishExplanation = "Finished on&nbsp;";
      };
      $('#finish-explanation').html(finishExplanation);
      var finishDate = '' + moment(task.end_date, "YYYY-MM-DD HH:mm:ss").format("ddd Do MMM YYYY") + '';
      $('#finish-date').html(finishDate);
      var finishTime = '&nbsp;@&nbsp;' + moment(task.end_date, "YYYY-MM-DD HH:mm:ss").format("HH:mm") + '';
      $('#finish-time').html(finishTime);








      // TASK BASELINE START
      var baselineStartExplanation = "Starting on&nbsp;";
      if (progress > 0) {
        baselineStartExplanation = "Started on&nbsp;";
      };
      $('#baseline-start-explanation').html(baselineStartExplanation);
      var baselineStartDate = '' + moment(task.baseline_start, "YYYY-MM-DD HH:mm:ss").format("ddd Do MMM YYYY") + '';
      $('#baseline-start-date').html(baselineStartDate);
      var baselineStartTime = '&nbsp;@&nbsp;' + moment(task.baseline_start, "YYYY-MM-DD HH:mm:ss").format("HH:mm") + '';
      $('#baseline-start-time').html(baselineStartTime);


      // TASK BASELINE FINISH (END)
      var finishExplanation = "Finishing on&nbsp;";
      if (progress == 100) {
        finishExplanation = "Finished on&nbsp;";
      };
      $('#baseline-finish-explanation').html(finishExplanation);
      var baselineFinishDate = '' + moment(task.baseline_end, "YYYY-MM-DD HH:mm:ss").format("ddd Do MMM YYYY") + '';
      $('#baseline-finish-date').html(finishDate);
      var baselineFinishTime = '&nbsp;@&nbsp;' + moment(task.baseline_end, "YYYY-MM-DD HH:mm:ss").format("HH:mm") + '';
      $('#baseline-finish-time').html(baselineFinishTime);





























      // TASK DEADLINE
      if (task.deadline == 'Null') {
        var deadlineExplanation = "not set";
        var deadlineDate = "";
        var deadlineTime = "";
      };
      if (task.deadline != '' && progress < 100) {
        deadlineExplanation = 'Deadline is&nbsp;';
        $('#deadline-explanation').html(deadlineExplanation);
        deadlineDate = '' + moment(task.deadline, "YYYY-MM-DD HH:mm:ss").format("ddd Do MMM YYYY");
        $('#deadline-date').html(deadlineDate);
        deadlineTime = '&nbsp;@&nbsp;' + moment(task.deadline, "YYYY-MM-DD HH:mm:ss").format("HH:mm");
        $('#deadline-time').html(deadlineTime);
      }
      if (task.deadline != '' && progress == 100) {
        deadlineExplanation = 'Deadline was&nbsp;';
        $('#deadline-explanation').html(deadlineExplanation);
        deadlineDate = '' + moment(task.deadline, "YYYY-MM-DD HH:mm:ss").format("ddd Do MMM YYYY");
        $('#deadline-date').html(deadlineDate);
        deadlineTime = '&nbsp;@&nbsp;' + moment(task.deadline, "YYYY-MM-DD HH:mm:ss").format("HH:mm");
        $('#deadline-time').html(deadlineTime);
      }


      // TASK WORKLOAD
      var workloadProgressedExplanation = '&nbsp;workload';
      $('#workload-progressed-explanation').html(workloadProgressedExplanation);
      var workloadProgressedQuantity = '' + (task.workload_quantity * progress / 100).toFixed(2);
      $('#workload-progressed-quantity').html(workloadProgressedQuantity);
      var workloadTotalQuantity = task.workload_quantity;
      $('#workload-total-quantity').html(workloadTotalQuantity);
      var workloadUnit = '&nbsp;' + task.workload_quantity_unit;
      $('#workload-unit').html(workloadUnit);
      
var workloadExplanation = 'Workload quantity (' + workloadTotalQuantity + workloadUnit + ') x actual progress (' + progress + '%)';
      $('#workload-explanation').html(workloadExplanation);


// TASK ACTUAL COSTS
      // Calculated in 'js-task-editor-finances.php': totalCost = ((resourceLocated.cost_rate * task.workload_quantity) * (progress / 100));
      var actualCost = (task.actual_cost_completion * 1).toFixed(2);
      $('#actual-cost').html('£' + actualCost);
      var actualCostExplanation = 'Resource costs x workload quantity (' + workloadTotalQuantity + workloadUnit + ') upon setting the task\'s progress';
      $('#actual-cost-explanation').html(actualCostExplanation);

 // TASK FINAL COST (ESTIMATE AT COMPLETION)
      var finalCost = (task.budget_cost_completion / cpi * 100).toFixed(2);
      $('#final-cost').html('£' + finalCost);
      var finalCostExplanation = 'Budget (£' + task.budget_at_completion + ') divided by cost effeciency (' + cpi + '%), also known as CPI (' + cpiDecimal + ')';
      $('#final-cost-explanation').html(finalCostExplanation);
      
      
       // TASK COST (ESTIMATE) TO COMPLETE
      var costToComplete = (finalCost - actualCost).toFixed(2);
      $('#cost-to-complete').html('£' + costToComplete);
      var costToCompleteExplanation = 'Final cost (£' + finalCost + ') minus actual cost (£' + actualCost + ')';
      $('#cost-to-complete-explanation').html(costToCompleteExplanation);

      // TASK BASELINE VALUE
      var baselineValue = (task.budget_cost_completion * baselineProgress / 100).toFixed(2);
      $('#baseline-value').html('£' + baselineValue);
      var baselineValueExplanation = 'Budget (£' + task.budget_at_completion + ') x baseline progress (' + baselineProgress + '%)';
      $('#baseline-value-explanation').html(baselineValueExplanation);

// TASK COST VARIANCE
      var costVariance = 999;
      var cV_suffix = '£';
      if (earnedValue > actualCost) {
        costVariance = (earnedValue - actualCost).toFixed(2);
      }
      if (earnedValue < actualCost) {
        costVariance = (earnedValue - actualCost) * -1;
        cV_suffix = '-£';
      }
      if (earnedValue == actualCost) {
        costVariance = 0
      }
      $('#cost-variance').html(cV_suffix + costVariance);
      
      var costVarianceExplanation = 'The difference between earned value (£' + earnedValue + ') and actual cost (£' + actualCost + ')';
      $('#cost-variance-explanation').html(costVarianceExplanation);

      // TASK SCHEDULE PERFORMANCE INDICATOR
      var spi = (0 * 1).toFixed(2);
      spi = (earnedValue / baselineValue * 100).toFixed(2);
      $("#GaugeMeter_spi").gaugeMeter({
        percent: spi
      });
      var spiDecimal = spi / 100;
var scheduleEfficiencyExplanation = 'Also known as SPI (' + spiDecimal + ').<br>Earned value (£' + earnedValue + ') divided by baseline value (£' + baselineValue + ')';
      $('#schedule-efficiency-explanation').html(scheduleEfficiencyExplanation);

      
      
      console.log('cpi = ' + cpi);
      if ( cpi < 100) {
        $('#block-cpi-status').html('Poor').css({'display': 'block', 'background': '#f50' });
      }
      
      
      
      // TASK SCHEDULE VARIANCE
      var scheduleVariance = 999;
      var sV_suffix = '£';
      if (earnedValue > baselineValue) {
        scheduleVariance = (earnedValue - baselineValue).toFixed(2);
      }
      if (earnedValue < baselineValue) {
        scheduleVariance = (earnedValue - baselineValue) * -1;
        sV_suffix = '-£';
      }
      if (earnedValue == baselineValue) {
        scheduleVariance = 0
      }
      $('#schedule-variance').html(sV_suffix + scheduleVariance);
      
      var scheduleVarianceExplanation = 'The difference between earned value (£' + earnedValue + ') and baseline value (£' + baselineValue + ')';
      $('#schedule-variance-explanation').html(scheduleVarianceExplanation);
      
      
      if (progress == baselineProgress) {
        $('#block-spi-status').html('Good').css({'display': 'block', 'background': '#1ea69a' });
      }
      
      

      // TASK RISK SCORE
      if (task.budget_at_completion >= finalCost) {
        var riskScore = 0;
        var riskExplanation = 'No specific/ quantifiable risks';
      }
      if ( task.budget_at_completion > 1) {
        var riskScore = (2 / task.budget_at_completion).toFixed(2);
        
        var riskExplanation = 'alpha';
      }

      $("#GaugeMeter_risk_score").gaugeMeter({
        percent: riskScore
      });
      $('#risk-explanation').html(riskExplanation);




      // TASK PRODUCTIVITY
      var productivity = (workloadTotalQuantity / task.duration).toFixed(2);
$('#productivity').html(productivity + workloadUnit + ' / day');
      var productivityExplanation = 'Workload quantity (' + workloadTotalQuantity + workloadUnit + ') over the duration (' + task.duration + ' days)';
      $('#productivity-explanation').html(productivityExplanation);
      

var budgetAtCompletion = task.budget_at_completion;
      var budgetFigure = (task.budget_at_completion * 1).toFixed(2);
      $('#budget').html('£' + budgetFigure);
      var budgetExplanation = 'Resource costs x workload quantity (' + workloadTotalQuantity + workloadUnit + ') upon baselining the task';
      $('#budget-explanation').html(budgetExplanation);
      
      jQuery(function() {
        var data = [{

            a: budgetAtCompletion,

            b: actualCost,

          }, ],
          config = {
            data: data,
            xkey: '',
            ykeys: ['a', 'b'],
            fillOpacity: 0.6,
            hideHover: 'auto',
            resize: true,
            pointFillColors: ['#ffffff'],
            pointStrokeColors: ['black'],
            barColors: ['#eee', '#a97afb'],
            grid: false,
            axis: false,
            xLabels: 'day',


          };
        config.element = 'bar1';
        Morris.Bar(config);

      });
      
      
      
      
      
      
      
      
      
      var workloadRemaining = workloadTotalQuantity - workloadProgressedQuantity;
      
      Morris.Bar({
      element: 'bar-workload',
      data: [
        {
         b1: workloadProgressedQuantity, 
         b2: workloadRemaining,
        },
      ],
      horizontal: true,
        stacked: true,
        grid: false,
        axes: false,
        ymax: workloadTotalQuantity,
      xkey: '',
      ykeys: ['b1','b2'],
     barColors: ["#1ea69a","#ddd"],
    });
      
      
      Morris.Bar({
      element: 'bar-actual-cost',
      data: [
        {
         b3: actualCost, 
         b4: costToComplete,
        },
      ],
      horizontal: true,
        stacked: true,
        grid: false,
        axes: false,
        ymax: finalCost,
      xkey: '',
      ykeys: ['b3','b4'],
     barColors: ["#1ea69a","#ddd"],
    });
 
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      

      var barLegendBudgetAtCompletion = ('<div style="height: 15px; width: 15px; float: left; margin: 3px 10px 0 0; background: #eee;"></div> Budget £' + task.budget_at_completion);
      $('#bar-legend-budget-at-completion').html(barLegendBudgetAtCompletion);
      
      var barLegendfinalCost = ('<div style="height: 15px; width: 15px; float: left; margin: 3px 10px 0 0; background: #a97afb"></div> Final cost £' + finalCost);
      $('#bar-legend-final-cost').html(barLegendfinalCost);
      
      var barLegendBaselineValue = ('<div style="height: 15px; width: 15px; float: left; margin: 3px 10px 0 0; background: #999;"></div> Baseline value £' + baselineValue);
      $('#bar-legend-baseline-value').html(barLegendBaselineValue);
      
      var barLegendEarnedValue = ('<div style="height: 15px; width: 15px; float: left; margin: 3px 10px 0 0; background: #f8ac59;"></div> Earned value £' + earnedValue);
      $('#bar-legend-earned-value').html(barLegendEarnedValue);
      
      var barLegendActualCost = ('<div style="height: 15px; width: 15px; float: left; margin: 3px 10px 0 0; background: #f7347a;"></div> Actual cost £' + actualCost);
      $('#bar-legend-actual-cost').html(barLegendActualCost);

      var barLegendCostToComplete = ('<div style="height: 15px; width: 15px; float: left; margin: 3px 10px 0 0; background: #ac2455;"></div> Cost to complete £' + costToComplete);
      $('#bar-legend-cost-to-complete').html(barLegendCostToComplete);
      
      var barLegendExplanation = '';
      if (earnedValue < actualCost && actualCost == baselineValue) {
        barLegendExplanation = "Earned value (£" + earnedValue + ") is less than actual cost (£" + actualCost + ").<br><br>Actual cost is the same as baseline value (£" + baselineValue + ")";
      }
      if (earnedValue < actualCost && actualCost < baselineValue) {
        barLegendExplanation = "Earned value (£" + earnedValue + ") is less than actual cost (£" + actualCost + ").<br><br>Actual cost is less than baseline value (£" + baselineValue + ")";
      }
      if (earnedValue < actualCost && actualCost > baselineValue) {
        barLegendExplanation = "Earned value (£" + earnedValue + ") is less than actual cost (£" + actualCost + ").<br><br>Actual cost is greater than baseline value (£" + baselineValue + ")";
      }
      if (earnedValue == actualCost && actualCost > baselineValue) {
        barLegendExplanation = "Earned value (£" + earnedValue + ") is the same as actual cost (£" + actualCost + ").<br><br>Actual cost is greater than baseline value (£" + baselineValue + ")";
      }
      if (earnedValue == actualCost && actualCost < baselineValue) {
        barLegendExplanation = "Earned value (£" + earnedValue + ") is the same as actual cost (£" + actualCost + ").<br><br>Actual cost is less than baseline value (£" + baselineValue + ")";
      }
      if (earnedValue > actualCost && earnedValue < baselineValue) {
        barLegendExplanation = "Earned value (£" + earnedValue + ") is greater than actual cost (£" + actualCost + ") but less than baseline value (£" + baselineValue + ")";
      }
 $('#bar-legend-explanation').html(barLegendExplanation);
      
      
      
            jQuery(function() {
        var data = [{
            y: 'Cost to complete',
            a: actualCost,
            b: costToComplete
          }, ],
          config = {
            data: data,
            xkey: 'y',
            ykeys: ['a', 'b', 'c'],
            fillOpacity: 0.6,
            hideHover: 'auto',
            resize: true,
            pointFillColors: ['#ffffff'],
            pointStrokeColors: ['black'],
            barColors: ['#f7347a', '#ac2455'],
            grid: false,
            axes: false,
            stacked: true,
            ymax: finalCost,
          };
        config.element = 'bar2';
        Morris.Bar(config);
      });

      
      
      
      
      
      
      
      
      
      
      
      
      


var startNarrative = "starting on&nbsp;";
      if (progress > 0) {
        startNarrative = "started on&nbsp;";
      };
      var finishNarrative = "should finish on&nbsp;";
      if (progress == 100) {
        finishNarrative = "finished on&nbsp;";
      };
      var progressNarrative = "";
      if (progress > 0 && progress < 100 && progress == baselineProgress) {
        progressNarrative = "Actual progress is at " + progress + "%, which is the same as the baseline progress, therefore this task is on schedule";
      };
      if (progress == 100 && progress == baselineProgress) {
        progressNarrative = "Actual progress is at 100% (completed), which is the same as the baseline progress, therefore this task has finished on schedule";
      };
      if (progress == 100 && progress > baselineProgress) {
        progressNarrative = "Actual progress is at 100% (completed), which is greater than baseline progress (" + baselineProgress + "%), therefore this task has finished ahead of schedule";
      };
      if (progress == 100 && progress < baselineProgress) {
        progressNarrative = "Actual progress is at 100% (completed), which is lower than baseline progress (" + baselineProgress + "%), therefore this task has finished behind schedule";
      };
      if (progress > 0 && progress < 100 && progress == baselineProgress) {
        progressNarrative = "Actual progress is at&nbsp;" + progress + "%, which is the same as the baseline progress, therefore this task is on schedule";
      };
      if (progress > 0 && progress < 100 && progress > baselineProgress) {
        progressNarrative = "Actual progress is at&nbsp;" + progress + "%, which is greater than baseline progress (" + baselineProgress + "%), therefore this task is ahead of schedule";
      };
      if (progress > 0 && progress < 100 && progress < baselineProgress) {
        progressNarrative = "Actual progress is at&nbsp;" + progress + "%, which is lower than baseline progress (" + baselineProgress + "%), therefore this task is behind schedule";
      };

      var baselineNarrative = "It is baselined from&nbsp" + baselineStartDate + baselineStartTime + "&nbsp;to&nbsp;" + baselineFinishDate + baselineFinishTime + ".&nbsp;";
var deadlineNarrative = "It has a deadline of&nbsp" + deadlineDate + deadlineTime;
if (progress == 100) {
        deadlineNarrative = "It had a deadline of&nbsp" + deadlineDate + deadlineTime;
      };
      
      
var taskName = task.text;
      var narrativeLineOne = '<tr><td>Task name&nbsp;"' + taskName + '"&nbsp;' + startNarrative + startDate + startTime + '&nbsp;and&nbsp;' + finishNarrative + finishDate + finishTime + '.</td></tr>';
      var narrativeLineTwo = '<tr><td>' + baselineNarrative + deadlineNarrative + ".</td></tr>";
      var narrativeLineThree = '<tr><td>' + progressNarrative + '.</td></tr>';
      var narrativeLineFour = '<tr><td>The workload of&nbsp' + workloadTotalQuantity + workloadUnit + '&nbsp;is scheduled over&nbsp;' + durationDaysNumber + '&nbsp;days,&nbsp;' + calendarWorkingDays + '&nbsp;between&nbsp;' + calendarWorkingTimes + '.</td></tr>';

$('#table-report-narrative > tbody').html(narrativeLineOne + narrativeLineTwo + narrativeLineThree + narrativeLineFour) ;












    });
  });











$("#toggle-reports-blocks").click(function(e) {
$("#modal_toggle_reports_blocks").modal('show');
});
  
  $("#toggle-design-modes").click(function(e) {
$("#modal_toggle_reports_design_modes").modal('show');
});


function toggleBlocksStartFunction() {
  var checkBox = document.getElementById("block-sta-det");  
var blockStartDetails = document.getElementById("block-start-details");
  if (checkBox.checked == true){
    blockStartDetails.style.display = "block";
  } else {
     blockStartDetails.style.display = "none";
  }
}
  
 function toggleBlocksFinishFunction() {
  var checkBox = document.getElementById("block-fin-det");  
var blockFinishDetails = document.getElementById("block-finish-details");
  if (checkBox.checked == true){
    blockFinishDetails.style.display = "block";
  } else {
     blockFinishDetails.style.display = "none";
  }
}

  function toggleBlocksDurationFunction() {
  var checkBox = document.getElementById("block-dur-det");  
var blockDurationDetails = document.getElementById("block-duration-details");
  if (checkBox.checked == true){
    blockDurationDetails.style.display = "block";
  } else {
     blockDurationDetails.style.display = "none";
  }
}

  function toggleBlocksCalendarFunction() {
  var checkBox = document.getElementById("block-cal");  
var blockCalendar = document.getElementById("block-calendar");
  if (checkBox.checked == true){
    blockCalendar.style.display = "block";
  } else {
     blockCalendar.style.display = "none";
  }
}

function toggleBlocksDeadlineFunction() {
  var checkBox = document.getElementById("block-dea");  
var blockDeadline = document.getElementById("block-deadline");
  if (checkBox.checked == true){
    blockDeadline.style.display = "block";
  } else {
     blockDeadline.style.display = "none";
  }
}
  
  function toggleBlocksWorkloadFunction() {
  var checkBox = document.getElementById("block-wor");  
var blockWorkload = document.getElementById("block-workload");
  if (checkBox.checked == true){
    blockWorkload.style.display = "block";
  } else {
     blockWorkload.style.display = "none";
  }
}

  function toggleBlocksActualProgressFunction() {
  var checkBox = document.getElementById("block-act-prog");  
var blockActualProgress = document.getElementById("block-actual-progress");
  if (checkBox.checked == true){
    blockActualProgress.style.display = "block";
  } else {
     blockActualProgress.style.display = "none";
  }
}
  
  function toggleBlocksBaselineProgressFunction() {
  var checkBox = document.getElementById("block-bas-prog");  
var blockBaselineProgress = document.getElementById("block-baseline-progress");
  if (checkBox.checked == true){
    blockBaselineProgress.style.display = "block";
  } else {
     blockBaselineProgress.style.display = "none";
  }
}
  
  function toggleBlocksActualCostFunction() {
  var checkBox = document.getElementById("block-act-cost");  
var blockActualCost = document.getElementById("block-actual-cost");
  if (checkBox.checked == true){
    blockActualCost.style.display = "block";
  } else {
     blockActualCost.style.display = "none";
  }
}
  
  function toggleBlocksBaselineValueFunction() {
  var checkBox = document.getElementById("block-bas-val");  
var blockBaselineValue = document.getElementById("block-baseline-value");
  if (checkBox.checked == true){
    blockBaselineValue.style.display = "block";
  } else {
     blockBaselineValue.style.display = "none";
  }
}
  
  function toggleBlocksEarnedValueFunction() {
  var checkBox = document.getElementById("block-ear-val");  
var blockEarnedValue = document.getElementById("block-earned-value");
  if (checkBox.checked == true){
    blockEarnedValue.style.display = "block";
  } else {
     blockEarnedValue.style.display = "none";
  }
}
  
  function toggleBlocksProductivityFunction() {
  var checkBox = document.getElementById("block-prod");  
var blockProductivity = document.getElementById("block-productivity");
  if (checkBox.checked == true){
    blockProductivity.style.display = "block";
  } else {
     blockProductivity.style.display = "none";
  }
}
  
  function toggleBlocksfinalCostFunction() {
  var checkBox = document.getElementById("block-eac");  
var blockfinalCost = document.getElementById("block-final-cost");
  if (checkBox.checked == true){
    blockfinalCost.style.display = "block";
  } else {
     blockfinalCost.style.display = "none";
  }
}
  
  function toggleBlockscostToCompleteFunction() {
  var checkBox = document.getElementById("block-etc");  
var blockcostToComplete = document.getElementById("block-cost-to-complete");
  if (checkBox.checked == true){
    blockcostToComplete.style.display = "block";
  } else {
     blockcostToComplete.style.display = "none";
  }
}
  
  function toggleBlocksCostVarianceFunction() {
  var checkBox = document.getElementById("block-cost-var");  
var blockCostVariance = document.getElementById("block-cost-variance");
  if (checkBox.checked == true){
    blockCostVariance.style.display = "block";
  } else {
     blockCostVariance.style.display = "none";
  }
}
  
function toggleBlocksScheduleVarianceFunction() {
  var checkBox = document.getElementById("block-sch-var");  
var blockScheduleVariance = document.getElementById("block-schedule-variance");
  if (checkBox.checked == true){
    blockScheduleVariance.style.display = "block";
  } else {
     blockScheduleVariance.style.display = "none";
  }
}
  
  function toggleBlocksCostEfficiencyFunction() {
  var checkBox = document.getElementById("block-cost-eff");  
var blockCostEfficiency = document.getElementById("block-cost-efficiency");
  if (checkBox.checked == true){
    blockCostEfficiency.style.display = "block";
  } else {
     blockCostEfficiency.style.display = "none";
  }
}
  
   function toggleBlocksScheduleEfficiencyFunction() {
  var checkBox = document.getElementById("block-sch-eff");  
var blockScheduleEfficiency = document.getElementById("block-schedule-efficiency");
  if (checkBox.checked == true){
    blockScheduleEfficiency.style.display = "block";
  } else {
     blockScheduleEfficiency.style.display = "none";
  }
}
  
  function toggleWorkloadExplanationFunction() {
  var checkBox = document.getElementById("workload-exp");  
var divWorkloadExplanation = document.getElementById("workload-explanation");
  if (checkBox.checked == true){
    divWorkloadExplanation.style.display = "block";
  } else {
     divWorkloadExplanation.style.display = "none";
  }
}
  
  function toggleBaselineValueExplanationFunction() {
  var checkBox = document.getElementById("baseline-value-exp");  
var divBaselineValueExplanation = document.getElementById("baseline-value-explanation");
  if (checkBox.checked == true){
    divBaselineValueExplanation.style.display = "block";
  } else {
     divBaselineValueExplanation.style.display = "none";
  }
}
  
  function toggleEarnedValueExplanationFunction() {
  var checkBox = document.getElementById("earned-value-exp");  
var divEarnedValueExplanation = document.getElementById("earned-value-explanation");
  if (checkBox.checked == true){
    divEarnedValueExplanation.style.display = "block";
  } else {
     divEarnedValueExplanation.style.display = "none";
  }
}
  
  function toggleActualCostExplanationFunction() {
  var checkBox = document.getElementById("actual-cost-exp");  
var divActualCostExplanation = document.getElementById("actual-cost-explanation");
  if (checkBox.checked == true){
    divActualCostExplanation.style.display = "block";
  } else {
     divActualCostExplanation.style.display = "none";
  }
}
  
  
  function toggleRiskExplanationFunction() {
  var checkBox = document.getElementById("risk-exp");  
var divRiskExplanation = document.getElementById("risk-explanation");
  if (checkBox.checked == true){
    divRiskExplanation.style.display = "block";
  } else {
     divRiskExplanation.style.display = "none";
  }
}
  
  
  
  function toggleScheduleVarianceExplanationFunction() {
  var checkBox = document.getElementById("schedule-variance-exp");  
var divScheduleVarianceExplanation = document.getElementById("schedule-variance-explanation");
  if (checkBox.checked == true){
    divScheduleVarianceExplanation.style.display = "block";
  } else {
     divScheduleVarianceExplanation.style.display = "none";
  }
}
  
  function toggleCostVarianceExplanationFunction() {
  var checkBox = document.getElementById("cost-variance-exp");  
var divCostVarianceExplanation = document.getElementById("cost-variance-explanation");
  if (checkBox.checked == true){
    divCostVarianceExplanation.style.display = "block";
  } else {
     divCostVarianceExplanation.style.display = "none";
  }
}
  
   function toggleProductivityExplanationFunction() {
  var checkBox = document.getElementById("productivity-exp");  
var divProductivityExplanation = document.getElementById("productivity-explanation");
  if (checkBox.checked == true){
    divProductivityExplanation.style.display = "block";
  } else {
     divProductivityExplanation.style.display = "none";
  }
}
  
  
    function togglefinalCostExplanationFunction() {
  var checkBox = document.getElementById("final-cost-exp");  
var divfinalCostExplanation = document.getElementById("final-cost-explanation");
  if (checkBox.checked == true){
    divfinalCostExplanation.style.display = "block";
  } else {
     divfinalCostExplanation.style.display = "none";
  }
}
  
  
  function togglecostToCompleteExplanationFunction() {
  var checkBox = document.getElementById("cost-to-complete-exp");  
var divcostToCompleteExplanation = document.getElementById("cost-to-complete-explanation");
  if (checkBox.checked == true){
    divcostToCompleteExplanation.style.display = "block";
  } else {
     divcostToCompleteExplanation.style.display = "none";
  }
}
  
  
   function toggleScheduleEfficiencyExplanationFunction() {
  var checkBox = document.getElementById("schedule-efficiency-exp");  
var divScheduleEfficiencyExplanation = document.getElementById("schedule-efficiency-explanation");
  if (checkBox.checked == true){
    divScheduleEfficiencyExplanation.style.display = "block";
  } else {
     divScheduleEfficiencyExplanation.style.display = "none";
  }
}
  
  function toggleCostEfficiencyExplanationFunction() {
  var checkBox = document.getElementById("cost-efficiency-exp");  
var divCostEfficiencyExplanation = document.getElementById("cost-efficiency-explanation");
  if (checkBox.checked == true){
    divCostEfficiencyExplanation.style.display = "block";
  } else {
     divCostEfficiencyExplanation.style.display = "none";
  }
}
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  

$(document).ready(function() {
    $("#darkmode").click(function() {
        if (this.checked) {
            $('.inner').css({ 'background': 'rgba(39, 62, 73, 0.8)', 'color': '#fafafa' });
        }
      else {
        $('.inner').css({ 'background': '#fff', 'color': '#3c3c3c' });
        $('.reports-files-container').css({ 'background': '#f9f9f9' });
      }
    });
});
  
  $(document).ready(function() {
    $("#blocktitles").click(function() {
        if (this.checked) {
            $('.block-title').show();
          $('.inner').css({ 'padding-top': '0' });
        }
      else {
        $('.block-title').hide();
        $('.inner'); //.css({ 'padding-top': '20px' });
      }
    });
});
  
  $(document).ready(function() {
    $("#masonrylayout").click(function() {
        if (this.checked) {
            $('.block-container').css({'display': 'flex'});
        }
      else {
        $('.block-container').css({'display': 'block'});
        $('.inner div:not(:first-child)').css({'display': 'block'});
      }
    });
});
  
  
  
  
  
  
  /*
  $(".block-remove").on("click",function(){
        $(this).parents(".block").fadeOut(400,function(){
            $(this).remove();
        });
        return false;
    });
  */


  
  
</script>
<script src="js/raphael.min.js"></script>
<script src="js/morris.js"></script>

<script src="js/GaugeMeter.js"></script>
<script>
  $(document).ready(function() {
    $(".GaugeMeter").gaugeMeter();
  });
</script>