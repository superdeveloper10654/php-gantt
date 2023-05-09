

task.progress = $("#task_edit_progress").val();
          if (task.progress == "") {
            task.progress = "0";
          }
			 

			 
			 	// Baseline progress calculations
		  var baselineProgress = 0;
		  
			 var momentStart = moment(task.start_date).format("X");
			 var momentBaselineStart = moment(task.baseline_start).format("X")
			  var momentEnd = moment(task.end_date).format("X");
			  var momentBaselineEnd = moment(task.baseline_end).format("X")
			  
			if (momentStart == momentBaselineStart && momentEnd == momentBaselineEnd)
			 {
				 
				baselineProgress = task.progress;
				
			 }
			 else 
			 {
				 // Get difference between start and now based on progress calc-progress function (MAY NEED WORK!)
				 
				 if (moment(task.baseline_start).isAfter(moment())) {
          // Not started
          baselineProgress = 0;

        } else if (moment(task.baseline_end).isBefore(moment())) {
          // Finished
          baselineProgress = 100;
			 
        } else 
		  {
          // Mid task
          // Are we in a non-working period?
          var periods = getNonWorkingPeriods(task.baseline_start, task.baseline_end, task.calendar_id, task.baseline_end);
          var totalNonMins = 0;
          for (i = 0; i < periods.length; ++i) {
            var start = periods[i].start_date;
            var a = moment(periods[i].start_date);
            var b = moment(periods[i].end_date);
            var diff = (b.diff(a, 'minutes'));
            totalNonMins = totalNonMins + diff;
          }
          // Get total task mins
          var a = moment(task.baseline_start);
          var b = moment(task.baseline_end);
          var diff = (b.diff(a, 'minutes'));
          var workingTaskMins = diff - totalNonMins;
          var minutesToNow = getMinutesBetweenDates(moment(task.baseline_start), moment(task.baseline_end), task.calendar_id);

          var periodsWorking = getWorkingPeriods(task.baseline_start, moment().toDate(), task.calendar_id, task.baseline_end);
          var totalMins = 0;
          for (i = 0; i < periodsWorking.length; ++i) {
            var a = moment(periodsWorking[i].start_date);
            var b = moment(periodsWorking[i].end_date);
            var diff = (b.diff(a, 'minutes'));
            totalMins = totalMins + diff;
          }
          // Do we need to add extra mins to totalMins to compensate for today?
          var minutesToNow = getMinutesBetweenDates(moment(task.baseline_start), moment(), task.calendar_id);

          baselineProgress = (Number(totalMins) / Number(workingTaskMins)) * 100;
			 
		 }       
		 
		 
		 
			
			 	
		 	}
		 
		 
			 task.baseline_progress = baselineProgress;
		  
		  
		  
		  
			 // End of baseline progress calculations
			 
			 

$('#ex1').bootstrapSlider();
      $('#ex2').bootstrapSlider();
      var progressSlider = $("#ex2").on("slideStop", function(slideEvt) {
        $("#task_edit_progress").val(slideEvt.value);
        $(".task-edit-progress-ui").html("Progress: " + slideEvt.value + "%");
      });

$("#task_edit_progress").val(task.progress);
        if (task.progress) {
          progressSlider.bootstrapSlider('setValue', (task.progress));
          $(".task-edit-progress-ui").html('Progress: ' + (task.progress) + '%');
        }

        if (task.progress == "100") {
          $("#task-editor-status-ok").show();
        }


/*
$(document).on('click', '.mark-task-on-track', function(e) {
        var task = gantt.getTask(window.ibex_gantt_config.activeTaskID);
        task.manually_delayed = "0";
        gantt.updateTask(task.id);
        if (moment(task.start_date).isAfter(moment())) {
          // Not started
          progressSlider.bootstrapSlider('setValue', 0);
          $(".task-edit-progress-ui").html('Progress: 0% (not started yet)');
          $("#task_edit_progress").val('0');
        } else if (moment(task.end_date).isBefore(moment())) {
          // Finished
          progressSlider.bootstrapSlider('setValue', 100);
          $(".task-edit-progress-ui").html('Progress: 100%');
          $("#task_edit_progress").val('100');
          $(".set-previous-tasks-complete-notification").show();
        } else {
          // Mid task
          // Are we in a non-working period?
          var periods = getNonWorkingPeriods(task.start_date, task.end_date, task.calendar_id, task.end_date);
          var totalNonMins = 0;
          for (i = 0; i < periods.length; ++i) {
            var start = periods[i].start_date;
            var a = moment(periods[i].start_date);
            var b = moment(periods[i].end_date);
            var diff = (b.diff(a, 'minutes'));
            totalNonMins = totalNonMins + diff;
          }
          // Get total task mins
          var a = moment(task.start_date);
          var b = moment(task.end_date);
          var diff = (b.diff(a, 'minutes'));
          var workingTaskMins = diff - totalNonMins;
          var minutesToNow = getMinutesBetweenDates(moment(task.start_date), moment(task.end_date), task.calendar_id);

          var periodsWorking = getWorkingPeriods(task.start_date, moment().toDate(), task.calendar_id, task.end_date);
          var totalMins = 0;
          for (i = 0; i < periodsWorking.length; ++i) {
            var a = moment(periodsWorking[i].start_date);
            var b = moment(periodsWorking[i].end_date);
            var diff = (b.diff(a, 'minutes'));
            totalMins = totalMins + diff;
          }
          // Do we need to add extra mins to totalMins to compensate for today?
          var minutesToNow = getMinutesBetweenDates(moment(task.start_date), moment(), task.calendar_id);
          $("#task_edit_progress").val((Number(totalMins) / Number(workingTaskMins)));
          var percentComplete = (Number(totalMins) / Number(workingTaskMins));
          $('#collapseTaskEditorComments').removeClass('collapse');
          $("#new_comment_text").val('Task on schedule at ' + percentComplete.toFixed(2) + "%");
          $("#new_comment_text").focus();
        }
      });
*/

      $(document).on('click', '.mark-task-delayed', function(e) {
        var task = gantt.getTask(window.ibex_gantt_config.activeTaskID);
        task.manually_delayed = "1";
        gantt.updateTask(task.id);
        $('#collapseTaskEditorComments').removeClass('collapse');
        $("#new_comment_text").val('Task delayed due to ');
        $("#new_comment_text").focus();
      });

$(".set-previous-tasks-complete").click(function(e) {
        var taskRef = gantt.getTask(window.ibex_gantt_config.activeTaskID);
        var refEndDate = moment(taskRef.end_date);
        var tasks = gantt.getTaskByTime();
        for (var i = 0; i < tasks.length; i++) {
          var task = gantt.getTask(tasks[i].id)
          if (task.type == "task" && moment(task.start_date).isBefore(refEndDate) && moment(task.end_date).isBefore(refEndDate)) {
            task.progress == "100";
            gantt.updateTask(task.id);
          }
        }
      });
                                         
                                         
                                         
                                         
                                        
                                         
                 $(document).on('click', '.calc-progress', function(e) {
        var task = gantt.getTask(window.ibex_gantt_config.activeTaskID);
        task.manually_delayed = "0";
		
        gantt.updateTask(task.id);
        if (moment(task.start_date).isAfter(moment())) {
          // Not started
          progressSlider.bootstrapSlider('setValue', 0);
          $(".task-edit-progress-ui").html('Progress: 0% (not started yet)');
          $("#task_edit_progress").val('0');
        } else if (moment(task.end_date).isBefore(moment())) {
          // Finished
          progressSlider.bootstrapSlider('setValue', 100);
          $(".task-edit-progress-ui").html('Progress: 100%');
          $("#task_edit_progress").val('100');
          $(".set-previous-tasks-complete-notification").show();
        } else {
          // Mid task
          // Are we in a non-working period?
          var periods = getNonWorkingPeriods(task.start_date, task.end_date, task.calendar_id, task.end_date);
          var totalNonMins = 0;
          for (i = 0; i < periods.length; ++i) {
            var start = periods[i].start_date;
            var a = moment(periods[i].start_date);
            var b = moment(periods[i].end_date);
            var diff = (b.diff(a, 'minutes'));
            totalNonMins = totalNonMins + diff;
          }
          // Get total task mins
          var a = moment(task.start_date);
          var b = moment(task.end_date);
          var diff = (b.diff(a, 'minutes'));
          var workingTaskMins = diff - totalNonMins;
          var minutesToNow = getMinutesBetweenDates(moment(task.start_date), moment(task.end_date), task.calendar_id);

          var periodsWorking = getWorkingPeriods(task.start_date, moment().toDate(), task.calendar_id, task.end_date);
          var totalMins = 0;
          for (i = 0; i < periodsWorking.length; ++i) {
            var a = moment(periodsWorking[i].start_date);
            var b = moment(periodsWorking[i].end_date);
            var diff = (b.diff(a, 'minutes'));
            totalMins = totalMins + diff;
          }
          // Do we need to add extra mins to totalMins to compensate for today?
          var minutesToNow = getMinutesBetweenDates(moment(task.start_date), moment(), task.calendar_id);
          $("#task_edit_progress").val((Number(totalMins) / Number(workingTaskMins))) * 100;
          var percentComplete = (Number(totalMins) / Number(workingTaskMins)) * 100;
          $("#task_edit_progress").val(percentComplete.toFixed(2)); 
          progressSlider.bootstrapSlider('setValue', percentComplete.toFixed(2));
          $(".task-edit-progress-ui").html('Progress: ' + percentComplete.toFixed(2) +'%');                                    
          $('#collapseTaskEditorComments').removeClass('collapse');
          $("#new_comment_text").val('Task on track at ' + percentComplete.toFixed(2) + "%");
          $("#new_comment_text").focus();
        }                                
      });
                     $("#task_edit_progress").keyup(function() {
                                                
progressSlider.bootstrapSlider('setValue', $("#task_edit_progress").val());
          $(".task-edit-progress-ui").html('Progress: ' + $("#task_edit_progress").val() + '%');                
});                           
