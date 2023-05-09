<script>
$('.toggle-deadline-visibility').click(function() {
        $(".deadlines-hidden").html('<img class="header-icon" src="img/svg/deadline.svg"><span class="small">Hide deadlines</span>');
        $(".deadlines-shown").html('<img class="header-icon" src="img/svg/deadline.svg"><span class="small">Show deadlines</span>');
        if (window.ibex_gantt_config.deadlinesVisible == false) 
		  {
          $(this).addClass('deadlines-shown').removeClass('deadlines-hidden');
          $(".deadline").show();
          window.ibex_gantt_config.deadlinesVisible = true;
        } else {
          $(this).addClass('deadlines-hidden').removeClass('deadlines-shown');
          $(".deadline").hide();
          window.ibex_gantt_config.deadlinesVisible = false;
        }
      });

gantt.addTaskLayer(function draw_deadline(task) {
          var deadlineDate = moment(task.deadline, 'YYYY-MM-DD HH:mm:ss', true);
          if (deadlineDate.isValid() == true) {
            var sizes = gantt.getTaskPosition(task, moment(task.deadline).toDate());
            var el = document.createElement('div');
            el.className = 'deadline';
            el.style.left = sizes.left + 'px';
            el.style.width = sizes.width + '24px';
            el.style.top = sizes.top + 'px';
            return el;
          }
          return false;
        });
  
		  
  window.updateWorkingDaysUIDeadline = function updateWorkingDaysUIDeadline() {
	  
     /*     $('#task_edit_deadline_date_d').material_select('destroy');
          $start_date = 1;
          $end_date = 31;
          for (var n = 1; n < 32; ++n) 
			 {
            if (n < 10) {
              n = "0" + n;
            }
            var dateCheck = $("#task_edit_deadline_date_y").val() + "-" + $("#task_edit_deadline_date_m").val() + "-" + n;
				alert(dateCheck);
            var dateFormatted = moment(dateCheck).format('ddd Do');
            var result = isDateWorkingDate(dateCheck, $("#task_edit_calendar_id").val());
            if (result == false) {
              $("#task_edit_deadline_date_d option[value=" + n + "]").attr('disabled', 'disabled');
            } else {
              $("#task_edit_deadline_date_d option[value=" + n + "]").removeAttr('disabled');
              $("#task_edit_deadline_date_d option[value=" + n + "]").text(dateFormatted);
            }
          }
          $('#task_edit_deadline_date_d').material_select();*/
        }
  
  $('#task_edit_deadline_date_m').on('change', function() {
          updateWorkingDaysUIDeadline();
        });
        $('#task_edit_deadline_date_y').on('change', function() {
          updateWorkingDaysUIDeadline();
        });
</script>