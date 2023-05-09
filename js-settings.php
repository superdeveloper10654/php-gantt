$(".auto-scheduler").click(function(e) {
        $(".auto-scheduler").removeClass("active-setting");
        $(this).addClass("active-setting");
        window.ibex_gantt_config.autoSchedulerActive = $(this).data("index");
        $.getJSON("beta.ajax.php?action=update_settings&aspect=automatic_scheduling&value=" + $(this).data("index"), function(data) {
        });
      });

      $("#settings_automatic_scheduling").change(function(e) {
        window.ibex_gantt_config.autoSchedulerActive = $("#settings_automatic_scheduling").val();
        $.getJSON("beta.ajax.php?action=update_settings&aspect=automatic_scheduling&value=" + $("#settings_automatic_scheduling").val(), function(data) {
          if ($("#settings_automatic_scheduling").val() == "1") {
            window.ibex_gantt_config.reloadPageAfterScheduling = 1;
            autoScheduleTasks();
          }
        });
      });

      $(".task-placement").click(function(e) {
        $(".task-placement").removeClass("active-setting");
        $(this).addClass("active-setting");
        window.ibex_gantt_config.taskInsertionMethod = $(this).data("index");
        $.getJSON("beta.ajax.php?action=update_settings&aspect=new_task&value=" + $(this).data("index"), function(data) {
        });
      });

      $("#settings_new_task_behaviour").change(function(e) {
        window.ibex_gantt_config.taskInsertionMethod = $("#settings_new_task_behaviour").val();
        $.getJSON("beta.ajax.php?action=update_settings&aspect=new_task&value=" + $("#settings_new_task_behaviour").val(), function(data) {
        });
      });

      $(".timing-unit").click(function(e) {
        $(".timing-unit").removeClass("active-setting");
        $(this).addClass("active-setting");
        window.ibex_gantt_config.timing_unit = $(this).data("index");
        $.getJSON("beta.ajax.php?action=update_settings&aspect=timing_unit&value=" + $(this).data("index"), function(data) {
        });
      });

      $("#settings_timing_unit").change(function(e) {
        window.ibex_gantt_config.timing_unit = $("#settings_timing_unit").val();
        $.getJSON("beta.ajax.php?action=update_settings&aspect=timing_unit&value=" + $("#settings_timing_unit").val(), function(data) {
        });
      });

      $(".settings-select").change(function(e) {
        $(this).parent().parent().notify("Saved", "success", {
          position: "right"
        });
      });

 $("#settings_period_descriptor").change(function(e) {
        window.ibex_gantt_config.periodDescriptor = $("#settings_period_descriptor").val();
        window.ibex_gantt_config.periodDescriptorTextSingular = $("#period_descriptor_singular").val();
        window.ibex_gantt_config.periodDescriptorTextPlural = $("#period_descriptor_plural").val();
        $.getJSON("beta.ajax.php?action=update_settings&aspect=period_descriptor&value=" + $("#settings_period_descriptor").val() + "&singular=" + $("#period_descriptor_singular").val() + "&plural=" + $("#period_descriptor_plural").val(), function(data) {});
      });

$('#period_descriptor_singular').focusout(function() {
        window.ibex_gantt_config.periodDescriptor = $("#settings_period_descriptor").val();
        window.ibex_gantt_config.periodDescriptorTextSingular = $("#period_descriptor_singular").val();
        window.ibex_gantt_config.periodDescriptorTextPlural = $("#period_descriptor_plural").val();
        $.getJSON("beta.ajax.php?action=update_settings&aspect=period_descriptor&value=" + $("#settings_period_descriptor").val() + "&singular=" + $("#period_descriptor_singular").val() + "&plural=" + $("#period_descriptor_plural").val(), function(data) {
        });
        $(this).notify("Saved", "success", {
          position: "right"
        });
      });

      $('#period_descriptor_plural').focusout(function() {
        window.ibex_gantt_config.periodDescriptor = $("#settings_period_descriptor").val();
        window.ibex_gantt_config.periodDescriptorTextSingular = $("#period_descriptor_singular").val();
        window.ibex_gantt_config.periodDescriptorTextPlural = $("#period_descriptor_plural").val();
        $.getJSON("beta.ajax.php?action=update_settings&aspect=period_descriptor&value=" + $("#settings_period_descriptor").val() + "&singular=" + $("#period_descriptor_singular").val() + "&plural=" + $("#period_descriptor_plural").val(), function(data) {
        });
        $(this).notify("Saved", "success", {
          position: "right"
        });
      });

 $(document).on("click", "#convert-task-timings", function() {
        var pluralsText = "";
        if (window.ibex_gantt_config.periodDescriptor == "2") {
          pluralsText = "days";
        }
        if (window.ibex_gantt_config.periodDescriptor == "3") {
          pluralsText = "nights";
        }
        if (window.ibex_gantt_config.periodDescriptor == "4") {
          pluralsText = "shifts";
        }
        if (window.ibex_gantt_config.periodDescriptor == "5") {
          pluralsText = window.ibex_gantt_config.periodDescriptorTextPlural;
        }
        $.getJSON("beta.ajax.php?action=override_task_timings&id=" + window.ibex_gantt_config.activeTaskID, function(data) {
          $("#task_edit_custom_duration_label").text('Duration');
          $(".row-task-editor-period-descriptors-custom").hide();
          $(".row-task-editor-period-descriptors-default").show();
          $("#label_task_edit_duration_hours").html('Duration (hrs & mins) <a style="text-decoration: underline; margin-left: 15px; position: relative; z-index: 99999;" class="revert-task-timings">' + pluralsText + '?</a> ');
          $("#task_edit_timings_overriden").val('1');
          var task = gantt.getTask(window.ibex_gantt_config.activeTaskID);
          task.timing_overriden = "1";
          gantt.updateTask(task.id);
        });
      });

$(document).on("click", ".revert-task-timings", function() {
        $.getJSON("beta.ajax.php?action=undo_override_task_timings&id=" + window.ibex_gantt_config.activeTaskID, function(data) {
          var task = gantt.getTask(window.ibex_gantt_config.activeTaskID);
          $("#task_edit_timings_overriden").val('0');
          task.timing_overriden = "0";
          gantt.updateTask(task.id);
          var pluralsText = "";
          if (window.ibex_gantt_config.periodDescriptor == "2") {
            pluralsText = "days";
          }
          if (window.ibex_gantt_config.periodDescriptor == "3") {
            pluralsText = "nights";
          }
          if (window.ibex_gantt_config.periodDescriptor == "4") {
            pluralsText = "shifts";
          }
          if (window.ibex_gantt_config.periodDescriptor == "5") {
            pluralsText = window.ibex_gantt_config.periodDescriptorTextPlural;
          }
          $("#task_edit_custom_duration_label").html('Duration (' + pluralsText + ") <a style='text-decoration: underline; margin-left: 15px; position: relative; z-index: 99999;' id='convert-task-timings'>hrs & mins?</a>");
          $(".row-task-editor-period-descriptors-custom").show();
          $(".row-task-editor-period-descriptors-default").hide();
        });
      });

$("#settings_period_descriptor").change(function() {
        if ($(this).val() == "5") // other
        {
          $("#period-descriptors-wrapper").show();
        } else $("#period-descriptors-wrapper").hide();
      });

$(".save-settings").click(function() {
        $.ajax({
          type: "POST",
          url: "beta.ajax.php?action=save_settings",
          data: $("#form_settings_edit").serialize(),
          success: null
        });
      });