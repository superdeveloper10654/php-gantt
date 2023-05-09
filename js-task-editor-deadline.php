
$('#task_edit_deadline_time').clockpicker({
        autoclose: true,
        'default': 'now',
        'twelveHour': false,
        donetext: ''
      });

  if (moment(task.deadline, "YYYY-MM-DD HH:mm:ss", true).isValid()) {
    var date1 = moment(task.deadline).toDate();
    $("#task_edit_deadline_date_d").val(moment(task.deadline).format("DD")).trigger("change");
    $("#task_edit_deadline_date_m").val(moment(task.deadline).format("MM")).trigger("change");
    $("#task_edit_deadline_date_y").val(moment(task.deadline).format("YYYY")).trigger("change");
    $("#task_edit_deadline_time_h").val(moment(task.deadline).format("HH")).trigger("change");
    $("#task_edit_deadline_time_m").val(moment(task.deadline).format("mm")).trigger("change");
  } else {
    $("#task_edit_deadline_date_d").val('');
    $("#task_edit_deadline_date_m").val('');
    $("#task_edit_deadline_date_y").val('');
    $("#task_edit_deadline_time_h").val('');
    $("#task_edit_deadline_time_m").val('');
  }

  $(".set-deadline-to-task-dates").click(function() {
    $('#task_edit_deadline_date_d').material_select('destroy');
    $('#task_edit_deadline_date_m').material_select('destroy');
    $('#task_edit_deadline_date_y').material_select('destroy');
    $('#task_edit_deadline_time_h').material_select('destroy');
    $('#task_edit_deadline_time_m').material_select('destroy');
    $("#task_edit_deadline_date_d").val(moment($("#task_edit_end_date").val(), "ddd D MMM (HH:mm)").format("DD")).trigger("change");
    $("#task_edit_deadline_date_m").val(moment($("#task_edit_end_date").val(), "ddd D MMM (HH:mm)").format("MM")).trigger("change");
    $("#task_edit_deadline_date_y").val(moment($("#task_edit_end_date").val(), "ddd D MMM (HH:mm)").format("YYYY")).trigger("change");
    $("#task_edit_deadline_time_h").val(moment($("#task_edit_end_date").val(), "ddd D MMM (HH:mm)").format("HH")).trigger("change");
    $("#task_edit_deadline_time_m").val(moment($("#task_edit_end_date").val(), "ddd D MMM (HH:mm)").format("mm")).trigger("change");
    $('#task_edit_deadline_date_d').material_select();
    $('#task_edit_deadline_date_m').material_select();
    $('#task_edit_deadline_date_y').material_select();
    $('#task_edit_deadline_time_h').material_select();
    $('#task_edit_deadline_time_m').material_select();
  });
