
  gantt.addTaskLayer(function draw_baseline(task) 
  {
          var baselineStartDate = moment(task.baseline_start, 'YYYY-MM-DD HH:mm:ss', true);
          var baselineEndDate = moment(task.baseline_end, 'YYYY-MM-DD HH:mm:ss', true);
          if (baselineStartDate.isValid() == true && baselineEndDate.isValid() == true) {
            var sizes = gantt.getTaskPosition(task, moment(task.baseline_start).toDate(), moment(task.baseline_end).toDate());
            var el = document.createElement('div');
            el.className = 'baseline';
            el.style.left = sizes.left + 'px';
            el.style.width = sizes.width + 'px';
            el.style.top = sizes.top + 35 + 'px';
            return el;
          }
          return false;
        });
  
  $(".toggle-baseline-visibility").click(function(e) 
  {
	 
    if (window.ibex_gantt_config.baselinesVisible == false) 
	 {
      $(".baseline").show();
      window.ibex_gantt_config.baselinesVisible = true;
      $(".toggle-baseline-visibility .small").html('Hide Baselines');
    } else {
      $(".baseline").hide();
      window.ibex_gantt_config.baselinesVisible = false;
      $(".toggle-baseline-visibility .small").html('Show Baselines');
    }
  });

  $('a.header-toolbar-button.toggle-baseline-visibility').click(function() {
  alert('222');
    $(".baselines-hidden").html('<img class="header-icon" src="img/svg/baselines.svg"><span class="small">Show baselines</span>');
    $(".baselines-shown").html('<img class="header-icon" src="img/svg/baselines.svg"><span class="small">Hide baselines</span>');
    if ($(this).hasClass('baselines-hidden')) {
      $(this).addClass('baselines-shown').removeClass('baselines-hidden');
      $(".baseline").show();
    } else {
      $(this).addClass('baselines-hidden').removeClass('baselines-shown');
      $(".baseline").hide();
    }
  });

  // UIs
  $(".toggle-baselines").click(function(e) {
  alert('111');
    if (window.ibex_gantt_config.baselinesShown) {
      window.ibex_gantt_config.baselinesShown = false;
      $(".baseline").hide();
    } else {
      window.ibex_gantt_config.baselinesShown = true;
      $(".baseline").show();
    }
  });

  window.updateWorkingDaysUIBaseline = function updateWorkingDaysUIBaseline() 
  {
  /*
    $('#task_edit_baseline_end_date_d').material_select('destroy');
    $('#task_edit_baseline_start_date_d').material_select('destroy');
    $start_date = 1;
    $end_date = 31;
    for (var n = 1; n < 32; ++n) {
      if (n < 10) {
        n = "0" + n;
      }
      var dateCheck = $("#task_edit_baseline_start_date_y").val() + "-" + $("#task_edit_baseline_start_date_m").val() + "-" + n;
      var dateFormatted = moment(dateCheck).format('ddd Do');
      var result = isDateWorkingDate(dateCheck, $("#task_edit_calendar_id").val());
      if (result == false) {
        $("#task_edit_baseline_start_date_d option[value=" + n + "]").attr('disabled', 'disabled');
        $("#task_edit_baseline_end_date_d option[value=" + n + "]").attr('disabled', 'disabled');
      } else {
        $("#task_edit_baseline_start_date_d option[value=" + n + "]").removeAttr('disabled');
        $("#task_edit_baseline_start_date_d option[value=" + n + "]").text(dateFormatted);
        $("#task_edit_baseline_end_date_d option[value=" + n + "]").removeAttr('disabled');
        $("#task_edit_baseline_end_date_d option[value=" + n + "]").text(dateFormatted);
      }
    }
    $('#task_edit_baseline_start_date_d').material_select();
    $('#task_edit_baseline_end_date_d').material_select();*/
  }

  $('#task_edit_baseline_start_date_m').on('change', function() {
    updateWorkingDaysUIBaseline();
  });
  $('#task_edit_baseline_start_date_y').on('change', function() {
    updateWorkingDaysUIBaseline();
  });
  $('#task_edit_baseline_end_date_m').on('change', function() {
    updateWorkingDaysUIBaseline();
  });
  $('#task_edit_baseline_end_date_y').on('change', function() {
    updateWorkingDaysUIBaseline();
  });

  $('#reset_baselines_from').datepicker({
    format: 'D d M yyyy',
    calendarWeeks: true,
    weekStart: 1,
    autoclose: true
  });

  $('#reset_baselines_to').datepicker({
    format: 'D d M yyyy',
    calendarWeeks: true,
    weekStart: 1,
    autoclose: true
  });

  $(".btn-reset-baselines").click(function(e) {
    $("#reset_baselines_from").val(moment(gantt.config.start_date).format("ddd D MMM YYYY")); // RB added 12.04.19
    $("#reset_baselines_to").val(moment(gantt.config.end_date).format("ddd D MMM YYYY")); // RB added 12.04.19
    $("#modal_reset_baselines").modal('show');
  });

  $(".reset-all-baselines").click(function(e) {
    window.ibex_gantt_config.preventSnapshot = true;
    var tasks = gantt.getTaskByTime();
    for (var i = 0; i < tasks.length; i++) {
      var task = gantt.getTask(tasks[i].id);
      if (task.type != "project") {
        task.baseline_start = task.start_date;
        task.baseline_end = task.end_date;
        task.deadline = task.end_date;
        gantt.updateTask(task.id);
      }
    }
    window.ibex_gantt_config.preventSnapshot = false;
    $("#modal_reset_baselines").modal('hide');
    location.reload();
  });
  
   $(".reset-baselines").click(function(e) {
        window.ibex_gantt_config.preventSnapshot = true;
        var dateFrom = moment($("#reset_baselines_from").val(), "DD/MM/YYYY").toDate();
        var dateTo = moment($("#reset_baselines_to").val(), "DD/MM/YYYY").toDate();
        var tasks = gantt.getTaskByTime(new Date(dateFrom), new Date(dateTo));
        for (var i = 0; i < tasks.length; i++) {
          var task = gantt.getTask(tasks[i].id);
          if (task.type != "project") {
            task.baseline_start = task.start_date;
            task.baseline_end = task.end_date;
            task.deadline = task.end_date;
            gantt.updateTask(task.id);
          }
        }
        window.ibex_gantt_config.preventSnapshot = false;
        $("#modal_reset_baselines").modal('hide');
        location.reload();
      });
  
