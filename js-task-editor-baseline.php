
var baselineStartDate = moment(task.baseline_start, 'YYYY-MM-DD HH:mm:ss', true);
var baselineEndDate = moment(task.baseline_end, 'YYYY-MM-DD HH:mm:ss', true);

if (baselineStartDate.isValid() == true) {
          $("#task_edit_baseline_start_date_d").val(baselineStartDate.format("DD")).trigger("change");
          $("#task_edit_baseline_start_date_m").val(baselineStartDate.format("MM")).trigger("change");
          $("#task_edit_baseline_start_date_y").val(baselineStartDate.format("YYYY")).trigger("change");
          $("#task_edit_baseline_start_time_h").val(baselineStartDate.format("HH")).trigger("change");
          $("#task_edit_baseline_start_time_m").val(baselineStartDate.format("mm")).trigger("change");
        } else {
          $("#task_edit_baseline_start_date_d").val('');
          $("#task_edit_baseline_start_date_m").val('');
          $("#task_edit_baseline_start_date_y").val('');
          $("#task_edit_baseline_start_time_h").val('');
          $("#task_edit_baseline_start_time_m").val('');
        }
        if (baselineEndDate.isValid() == true) {
          $("#task_edit_baseline_end_date_d").val(baselineEndDate.format("DD")).trigger("change");
          $("#task_edit_baseline_end_date_m").val(baselineEndDate.format("MM")).trigger("change");
          $("#task_edit_baseline_end_date_y").val(baselineEndDate.format("YYYY")).trigger("change");
          $("#task_edit_baseline_end_time_h").val(baselineEndDate.format("HH")).trigger("change");
          $("#task_edit_baseline_end_time_m").val(baselineEndDate.format("mm")).trigger("change");
        } else {
          $("#task_edit_baseline_end_date_d").val('');
          $("#task_edit_baseline_end_date_m").val('');
          $("#task_edit_baseline_end_date_y").val('');
          $("#task_edit_baseline_end_time_h").val('');
          $("#task_edit_baseline_end_time_m").val('');
        }

$(".set-baseline-to-task-dates").click(function(e) {
        $("#task_edit_baseline_start_date_d").material_select('destroy');
        $("#task_edit_baseline_start_date_m").material_select('destroy');
        $("#task_edit_baseline_start_date_y").material_select('destroy');
        $("#task_edit_baseline_start_time_h").material_select('destroy');
        $("#task_edit_baseline_start_time_m").material_select('destroy');
        $("#task_edit_baseline_end_date_d").material_select('destroy');
        $("#task_edit_baseline_end_date_m").material_select('destroy');
        $("#task_edit_baseline_end_date_y").material_select('destroy');
        $("#task_edit_baseline_end_time_h").material_select('destroy');
        $("#task_edit_baseline_end_time_m").material_select('destroy');
        $("#task_edit_baseline_start_date_d").val($("#task_edit_start_date_d").val());
        $("#task_edit_baseline_start_date_m").val($("#task_edit_start_date_m").val());
        $("#task_edit_baseline_start_date_y").val($("#task_edit_start_date_y").val());
        $("#task_edit_baseline_start_time_h").val($("#task_edit_start_time_h").val());
        $("#task_edit_baseline_start_time_m").val($("#task_edit_start_time_m").val());
        $("#task_edit_baseline_end_date_d").val(moment($("#task_edit_end_date").val(), "ddd D MMM (HH:mm)").format("DD"));
        $("#task_edit_baseline_end_date_m").val(moment($("#task_edit_end_date").val(), "ddd D MMM (HH:mm)").format("MM"));
        $("#task_edit_baseline_end_date_y").val(moment($("#task_edit_end_date").val(), "ddd D MMM (HH:mm)").format("YYYY"));
        $("#task_edit_baseline_end_time_h").val(moment($("#task_edit_end_date").val(), "ddd D MMM (HH:mm)").format("HH"));
        $("#task_edit_baseline_end_time_m").val(moment($("#task_edit_end_date").val(), "ddd D MMM (HH:mm)").format("mm"));
        $("#task_edit_baseline_start_date_d").material_select();
        $("#task_edit_baseline_start_date_m").material_select();
        $("#task_edit_baseline_start_date_y").material_select();
        $("#task_edit_baseline_start_time_h").material_select();
        $("#task_edit_baseline_start_time_m").material_select();
        $("#task_edit_baseline_end_date_d").material_select();
        $("#task_edit_baseline_end_date_m").material_select();
        $("#task_edit_baseline_end_date_y").material_select();
        $("#task_edit_baseline_end_time_h").material_select();
        $("#task_edit_baseline_end_time_m").material_select();
        calculateTaskBudgetFinances();
      });

 $(document).on('click', '.delete-link', function(e) {
        gantt.deleteLink($(this).data("index"));
        var task = gantt.getTask(window.ibex_gantt_config.activeTaskID);
        var predecessors = task.$target;
        var successors = task.$source;

        $(".list-group-predecessors").html('yy');
        $.each(predecessors, function(i) {
          $(".list-group-predecessors").append('<li class="list-group-item d-flex justify-content-between align-items-center">' + gantt.getTask(gantt.getLink(predecessors[i]).source).text + '<span class="delete-link" data-index="' + predecessors[i] + '"><img src="img/svg/bin-1.svg"  style="height: 16px;" title="Remove this Dependency"></span></li>');
        });

        $(".list-group-successors").html('');
        $.each(successors, function(i) {
          $(".list-group-successors").append('<li class="list-group-item d-flex justify-content-between align-items-center">' + gantt.getTask(gantt.getLink(successors[i]).target).text + '<span class="delete-link" data-index="' + successors[i] + '"><img src="img/svg/bin-1.svg"  style="height: 16px;" title="Remove this Dependency"></span></li>');
        });



      });


 $(document).on('click', '.override-edit-dependency', function(e) {
        $("#modal_prevent_drag").modal('hide')
        var linkID = $(this).data("id");

        //  if (window.ibex_gantt_config.selfCanEditLinks == true) {
        $('.mdb-select').material_select('destroy');
        $(".delete-link").show();
        $("#link_edit_type").val('1');
        $("#link_edit_offset_type").val('1');
        $("#link_edit_duration_hours").val('0');
        $("#link_edit_duration_mins").val('0');
        $("#link_edit_offset").val('0');

        var link = gantt.getLink(linkID);
        var task = gantt.getTask(link.source);

        $("#link_edit_id").val(linkID);
        $("#link_edit_new").val('false');
        $("#link_edit_source_task_id").val(gantt.getTask(link.source).id);
        $("#link_edit_target_task_id").val(gantt.getTask(link.target).id);

        $("#link_edit_source_task_guid").val(gantt.getTask(link.source).guid);
        $("#link_edit_target_task_guid").val(gantt.getTask(link.target).guid);
        $("#link_edit_type").val(link.type);
        $("#link_edit_offset_type").val(link.offset_type);
        $("#link_edit_offset").val(link.offset_minutes);
        $("#link_edit_duration_hours").val(Math.floor(link.offset_minutes / 60));
        $("#link_edit_duration_mins").val(Math.floor(link.offset_minutes % 60));

        // Custom
        $("#link_edit_duration_custom").val(convertMinutesToPeriod(link.offset_minutes, task.id));
        $(".link-edit-intro").html("<strong>" + gantt.getTask(link.source).text + "</strong> <> <strong>" + gantt.getTask(link.target).text + "</strong>");

        if (link.color == "red") {
          $('#link_edit_type').attr('disabled', 'disabled');
          $('#link_edit_offset_type').attr('disabled', 'disabled');
          $('#link_edit_duration_hours').attr('disabled', 'disabled');
          $('#link_edit_duration_mins').attr('disabled', 'disabled');
          $('#link_edit_duration_custom').attr('disabled', 'disabled');
          $('#link_edit_offset').attr('disabled', 'disabled');
        } else {
          $('#link_edit_type').removeAttr('disabled', 'disabled');
          $('#link_edit_offset_type').removeAttr('disabled', 'disabled');
          $('#link_edit_duration_hours').removeAttr('disabled', 'disabled');
          $('#link_edit_duration_mins').removeAttr('disabled', 'disabled');
          $('#link_edit_duration_custom').removeAttr('disabled', 'disabled');
          $('#link_edit_offset').removeAttr('disabled', 'disabled');
        }

        var endSource = moment(gantt.getTask($("#link_edit_source_task_id").val()).end_date);
        var startSource = moment(gantt.getTask($("#link_edit_source_task_id").val()).start_date).format("YYYY-MM-DD HH:mm");
        var existingDurationSource = gantt.getTask($("#link_edit_source_task_id").val()).duration_worked;
        var offsetDuration = Number($("#link_edit_offset").val());
        var newSumDuration = Number(offsetDuration) + Number(existingDurationSource);
        var proposedStartDate = getTaskEndDate(startSource, newSumDuration, gantt.getTask($("#link_edit_source_task_id").val()).calendar_id);
        proposedStartDate = getEncasedStartDateTime(proposedStartDate, gantt.getTask($("#link_edit_source_task_id").val()).calendar_id);

        $(".link-edit-timings").html("<strong>" + gantt.getTask($("#link_edit_source_task_id").val()).text + "</strong><br> finishes on " + endSource.format("DD/MM/YYYY") + " at " + endSource.format("HH:mm") + "<br><br><strong>" + gantt.getTask($("#link_edit_target_task_id").val()).text + "</strong><br> will start on " + moment(proposedStartDate).format("DD/MM/YYYY") + " at " + moment(proposedStartDate).format("HH:mm"));
        $('.mdb-select').material_select();

        // Set up timings
        if (window.ibex_gantt_config.periodDescriptor == "1") {
          // Hours and mins
          $(".link-edit-hours").show();
          $(".link-edit-mins").show();
          $(".link-edit-custom").hide();
        } else {
          if (window.ibex_gantt_config.periodDescriptor == "2") {
            window.ibex_gantt_config.periodDescriptorTextPlural = "days";
          }
          if (window.ibex_gantt_config.periodDescriptor == "3") {
            window.ibex_gantt_config.periodDescriptorTextPlural = "nights";
          }
          if (window.ibex_gantt_config.periodDescriptor == "4") {
            window.ibex_gantt_config.periodDescriptorTextPlural = "shifts";
          }
          // Custom
          $(".link-edit-hours").hide();
          $(".link-edit-mins").hide();
          $(".link-edit-custom").show();

          $('#link_edit_duration_custom_label').text('Offset Duration (' + window.ibex_gantt_config.periodDescriptorTextPlural + ')');
        }

        $("#modal_link_editor").modal('show');
        /*
                                } else {

                                }
        */






      });
