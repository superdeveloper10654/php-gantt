gantt.attachEvent("onAfterLinkAdd", function(id, item) {
        autoScheduleTasks();
      });

      gantt.attachEvent("onAfterLinkUpdate", function(id, item) {
        if (window.ibex_gantt_config.processAutoSchedulingAfterLinkChanges == true) {
          autoScheduleTasks();
        }
        autoScheduleTasks();
      });

      gantt.attachEvent("onBeforeLinkAdd", function(id, link) {
        if (window.ibex_gantt_config.rollbackActive == true) {
          return true;
        }
        var task = gantt.getTask(link.source);
        var taskTarget = gantt.getTask(link.target);
        if (task.is_summary == "1" || taskTarget.is_summary == "1") {
          return false;
        }
        if (window.ibex_gantt_config.overrideLinkBeforeAddBehaviour == true) {
          window.ibex_gantt_config.overrideLinkBeforeAddBehaviour = false;
          $("#modal_link_editor").modal('hide');
          return true;
        } else {
          window.ibex_gantt_config.overrideLinkBeforeAddBehaviour = false;
          $("#link_edit_id").val(id);
          $("#link_edit_new").val('true');
          $("#link_edit_source_task_id").val(gantt.getTask(link.source).id);
          $("#link_edit_target_task_id").val(gantt.getTask(link.target).id);
          // Check we can add this link
          if (gantt.getTask($("#link_edit_source_task_id").val()).calendar_id != gantt.getTask($("#link_edit_target_task_id").val()).calendar_id &&
            gantt.getTask($("#link_edit_source_task_id").val()).type == "task" && gantt.getTask($("#link_edit_target_task_id").val()).type == "task") {
            gantt.message({
              type: "error",
              text: gantt.getTask(link.source).text + " and " + gantt.getTask(link.target).text + " must use the same calendar to have a dependency between them.",
              expire: 50000
            });
            return false;
          }
          $("#link_edit_source_task_guid").val(gantt.getTask(link.source).guid);
          $("#link_edit_target_task_guid").val(gantt.getTask(link.target).guid);
          $(".link-edit-intro").html("<strong>" + gantt.getTask(link.source).text + "</strong> <> <strong>" + gantt.getTask(link.target).text + "</strong>");
          var endSource = moment(gantt.getTask($("#link_edit_source_task_id").val()).end_date);
          var startSource = moment(gantt.getTask($("#link_edit_source_task_id").val()).start_date).format("YYYY-MM-DD HH:mm");
          var existingDurationSource = gantt.getTask($("#link_edit_source_task_id").val()).duration_worked;
          if (gantt.getTask($("#link_edit_source_task_id").val()).type == "task" && gantt.getTask($("#link_edit_target_task_id").val()).type == "task") {
            // Task to task link
            var offsetDuration = 0;
            var newSumDuration = Number(offsetDuration) + Number(existingDurationSource);
            var proposedStartDate = getTaskEndDate(startSource, newSumDuration, gantt.getTask($("#link_edit_source_task_id").val()).calendar_id);
            proposedStartDate = getEncasedStartDateTime(proposedStartDate, gantt.getTask($("#link_edit_source_task_id").val()).calendar_id);
            $(".link-edit-timings").html("<strong>" + gantt.getTask($("#link_edit_source_task_id").val()).text + "</strong><br> finishes on " + endSource.format("ddd Do MMM YYYY") + " @ " + endSource.format("HH:mm") + "<br><br><strong>" + gantt.getTask($("#link_edit_target_task_id").val()).text + "</strong><br> will start on " + moment(proposedStartDate).format("ddd Do MMM YYYY") + " @ " + moment(proposedStartDate).format("HH:mm"));
            $('#link_edit_type').removeAttr('disabled', 'disabled');
            $('#link_edit_offset_type').removeAttr('disabled', 'disabled');
            $('#link_edit_duration_hours').removeAttr('disabled', 'disabled');
            $('#link_edit_duration_mins').removeAttr('disabled', 'disabled');
            $('#link_edit_duration_custom').removeAttr('disabled', 'disabled');
            $('#link_edit_offset').removeAttr('disabled', 'disabled');
          } else if (gantt.getTask($("#link_edit_source_task_id").val()).type == "milestone" && gantt.getTask($("#link_edit_target_task_id").val()).type == "task") {
            // Milestone to task
            var offsetDuration = 0;
            var newSumDuration = Number(offsetDuration) + Number(existingDurationSource);
            var proposedStartDate = getTaskEndDate(startSource, newSumDuration, gantt.getTask($("#link_edit_source_task_id").val()).calendar_id);
            proposedStartDate = getEncasedStartDateTime(proposedStartDate, gantt.getTask($("#link_edit_source_task_id").val()).calendar_id);
            $(".link-edit-timings").html("<strong>" + gantt.getTask($("#link_edit_source_task_id").val()).text + "</strong><br> finishes on " + endSource.format("ddd Do MMM YYYY") + " @ " + endSource.format("HH:mm") + "<br><br><strong>" + gantt.getTask($("#link_edit_target_task_id").val()).text + "</strong><br> will start on " + moment(proposedStartDate).format("ddd Do MMM YYYY") + " @ " + moment(proposedStartDate).format("HH:mm"));
            $('#link_edit_type').removeAttr('disabled', 'disabled');
            $('#link_edit_offset_type').removeAttr('disabled', 'disabled');
            $('#link_edit_duration_hours').removeAttr('disabled', 'disabled');
            $('#link_edit_duration_mins').removeAttr('disabled', 'disabled');
            $('#link_edit_duration_custom').removeAttr('disabled', 'disabled');
            $('#link_edit_offset').removeAttr('disabled', 'disabled');
          }
          // Toggle time units
          $("#modal_link_editor").modal('show');
          return false;
        }
      });

          // Displays the appropriate image in #modal_link_editor
          $("#link_edit_type").change(function() {
            if ($(this).val() == "0") {
              $("#F2S-image").show();
              $("#S2S-image").hide();
              $("#F2F-image").hide();
              $("#S2F-image").hide();
            }
            if ($(this).val() == "1") {
              $("#F2S-image").hide();
              $("#S2S-image").show();
              $("#F2F-image").hide();
              $("#S2F-image").hide();
            }
            if ($(this).val() == "2") {
              $("#F2S-image").hide();
              $("#S2S-image").hide();
              $("#F2F-image").show();
              $("#S2F-image").hide();
            }
            if ($(this).val() == "3") {
              $("#F2S-image").hide();
              $("#S2S-image").hide();
              $("#F2F-image").hide();
              $("#S2F-image").show();
            }
          });
 $(document).on('click', '.save-link', function(e) {
        window.ibex_gantt_config.processAutoSchedulingAfterLinkChanges = true;
        var test = $("#link_edit_type :selected").text();
        if ($("#link_edit_new").val() == 'true') {
          window.ibex_gantt_config.activityPrimaryGUID = $("#link_edit_source_task_guid").val();
          window.ibex_gantt_config.activitySecondaryGUID = $("#link_edit_target_task_guid").val();
          
          
          
if ($("#link_edit_offset_type").val() == "0") {
              window.ibex_gantt_config.activityInfo = $("#link_edit_type :selected").text() + " with no lead or lag";
            }
if ($("#link_edit_offset_type").val() == "1") {
              window.ibex_gantt_config.activityInfo = $("#link_edit_type :selected").text() + " with " + $("#link_edit_duration_custom").val() + " " + window.ibex_gantt_config.periodDescriptorTextSingular + " lag";
            }
          if ($("#link_edit_offset_type").val() == "2") {
              window.ibex_gantt_config.activityInfo = $("#link_edit_type :selected").text() + " with " + $("#link_edit_duration_custom").val() + " " + window.ibex_gantt_config.periodDescriptorTextSingular + " lead";
            }

          //window.ibex_gantt_config.activityInfo = $("#link_edit_type :selected").text() + " with " + $("#link_edit_offset_type :selected").text() + " of " + $("#link_edit_duration_hours").val() + " hours and " + $("#link_edit_duration_mins").val() + " minutes";
          window.ibex_gantt_config.activityAction = "added"
          window.ibex_gantt_config.activityType = "link";
          setTimeout(function() {
            reloadActivityFeed();
          }, 1000);
          $.ajax({
            url: 'beta.ajax.php?action=snapshot_gantt',
            type: 'POST',
            dataType: 'json',
            success: function(data) {
              var lastSaveTime = data.save_time;
              window.ibex_gantt_config.lastSaveTime = Number(lastSaveTime) + 1;
            },
            data: {
              primary_guid: window.ibex_gantt_config.activityPrimaryGUID,
              secondary_guid: window.ibex_gantt_config.activitySecondaryGUID,
              action_type: window.ibex_gantt_config.activityAction,
              type: window.ibex_gantt_config.activityType,
              info: window.ibex_gantt_config.activityInfo,
              gantt_data: window.ibex_gantt_config.snapshotData
            }
          });
          window.ibex_gantt_config.lastSnapshotTime = moment().unix();
          window.ibex_gantt_config.overrideLinkBeforeAddBehaviour = true;
          gantt.addLink({
            programme_id: $("#programme_id").val(),
            source: $("#link_edit_source_task_id").val(),
            source_guid: $("#link_edit_source_task_guid").val(),
            target: $("#link_edit_target_task_id").val(),
            target_guid: $("#link_edit_target_task_guid").val(),
            type: $("#link_edit_type").val(),
            offset_minutes: $("#link_edit_offset").val(),
            offset_type: $("#link_edit_offset_type").val(),
            created: moment().format("ddd D MMM YYYY"),
          });
        } else {
          var link = gantt.getLink($("#link_edit_id").val());
          link.source = $("#link_edit_source_task_id").val();
          link.source_guid = $("#link_edit_source_task_guid").val();
          link.target = $("#link_edit_target_task_id").val();
          link.target_guid = $("#link_edit_target_task_guid").val();
          link.type = $("#link_edit_type").val();
          link.offset_minutes = $("#link_edit_offset").val();
          link.offset_type = $("#link_edit_offset_type").val();
          window.ibex_gantt_config.activityPrimaryGUID = $("#link_edit_source_task_guid").val();
          window.ibex_gantt_config.activitySecondaryGUID = $("#link_edit_target_task_guid").val();
if ($("#link_edit_offset_type").val() == "0") {
              window.ibex_gantt_config.activityInfo = $("#link_edit_type :selected").text() + " with no lead or lag";
            }
if ($("#link_edit_offset_type").val() == "1") {
              window.ibex_gantt_config.activityInfo = $("#link_edit_type :selected").text() + " with " + $("#link_edit_duration_custom").val() + " " + window.ibex_gantt_config.periodDescriptorTextSingular + " lag";
            }
          if ($("#link_edit_offset_type").val() == "2") {
              window.ibex_gantt_config.activityInfo = $("#link_edit_type :selected").text() + " with " + $("#link_edit_duration_custom").val() + " " + window.ibex_gantt_config.periodDescriptorTextSingular + " lead";
            }
          //window.ibex_gantt_config.activityInfo = $("#link_edit_type :selected").text() + " dependency with " + $("#link_edit_offset_type :selected").text() + " of " + $("#link_edit_duration_hours").val() + " hours and " + $("#link_edit_duration_mins").val() + " minutes";
          window.ibex_gantt_config.activityAction = "edited"
          window.ibex_gantt_config.activityType = "link";
          setTimeout(function() {
            reloadActivityFeed();
          }, 1000);
          $.ajax({
            url: 'beta.ajax.php?action=snapshot_gantt',
            type: 'POST',
            dataType: 'json',
            success: function(data) {
              var lastSaveTime = data.save_time;
              window.ibex_gantt_config.lastSaveTime = Number(lastSaveTime) + 1;
            },
            data: {
              primary_guid: window.ibex_gantt_config.activityPrimaryGUID,
              secondary_guid: window.ibex_gantt_config.activitySecondaryGUID,
              action_type: window.ibex_gantt_config.activityAction,
              type: window.ibex_gantt_config.activityType,
              info: window.ibex_gantt_config.activityInfo,
              gantt_data: window.ibex_gantt_config.snapshotData
            }
          });
          window.ibex_gantt_config.lastSnapshotTime = moment().unix();
          gantt.updateLink(link.id);
        }
        $("#modal_link_editor").modal('hide');
      });
      
      
      $("#link_edit_duration_custom").change(function() {
        var task = gantt.getTask($("#link_edit_source_task_id").val());
        var minutes;
        var offsetDuration = 0;
        $.each(window.ibex_gantt_config.calendars, function(index) {
          if (window.ibex_gantt_config.calendars[index].id == task.calendar_id) {
            var calendar = window.ibex_gantt_config.calendars[index];
            var startTime = moment(padLeadingZero(calendar.start_hour) + ":" + padLeadingZero(calendar.start_minute), "HH:mm");
            var endTime = moment(padLeadingZero(calendar.end_hour) + ":" + padLeadingZero(calendar.end_minute), "HH:mm");
            var duration = moment.duration(endTime.diff(startTime));
            minutes = parseInt(duration.asMinutes());
            offsetDuration = parseInt($("#link_edit_duration_custom").val()) * minutes;
          }
        });










        var endSource = moment(gantt.getTask($("#link_edit_source_task_id").val()).end_date);
        var startSource = moment(gantt.getTask($("#link_edit_source_task_id").val()).start_date).format("YYYY-MM-DD HH:mm");
        var existingDurationSource = gantt.getTask($("#link_edit_source_task_id").val()).duration_worked;

        if (gantt.getTask($("#link_edit_source_task_id").val()).type == "task" && gantt.getTask($("#link_edit_target_task_id").val()).type == "task") {

          // Task to task link
          if (gantt.getTask($("#link_edit_source_task_id").val()).calendar_id == gantt.getTask($("#link_edit_target_task_id").val()).calendar_id) {


            $("#link_edit_offset").val(offsetDuration);
$(document).on('change', '#link_edit_offset_type', function(e) {
if ($("#link_edit_offset_type").val() == "0") {
              $('#link_edit_duration_custom').val('0');
            }
});
            var newSumDuration;
            if ($("#link_edit_offset_type").val() == "1") {
              newSumDuration = Number(offsetDuration) + Number(existingDurationSource);
            } else {
              newSumDuration = Number(existingDurationSource) - Number(offsetDuration);
            }

            var proposedStartDate = getTaskEndDate(startSource, newSumDuration, gantt.getTask($("#link_edit_source_task_id").val()).calendar_id);
            proposedStartDate = getEncasedStartDateTime(proposedStartDate, gantt.getTask($("#link_edit_source_task_id").val()).calendar_id);

            $("#proposed_target_start_date").val(proposedStartDate);
            $(".link-edit-timings").html("<strong>" + gantt.getTask($("#link_edit_source_task_id").val()).text + "</strong><br> finishes on " + endSource.format("ddd Do MMM YYYY") + " @ " + endSource.format("HH:mm") + "<br><br><strong>" + gantt.getTask($("#link_edit_target_task_id").val()).text + "</strong><br> will start on " + moment(proposedStartDate).format("ddd Do MMM YYYY") + " @ " + moment(proposedStartDate).format("HH:mm"));
            $('#link_edit_type').removeAttr('disabled', 'disabled');
            $('#link_edit_offset_type').removeAttr('disabled', 'disabled');
            $('#link_edit_duration_hours').removeAttr('disabled', 'disabled');
            $('#link_edit_duration_mins').removeAttr('disabled', 'disabled');
            $('#link_edit_duration_custom').removeAttr('disabled', 'disabled');
            $('#link_edit_offset').removeAttr('disabled', 'disabled');
          } else {
            $('#link_edit_type').attr('disabled', 'disabled');
            $('#link_edit_offset_type').attr('disabled', 'disabled');
            $('#link_edit_duration_hours').attr('disabled', 'disabled');
            $('#link_edit_duration_mins').attr('disabled', 'disabled');
            $('#link_edit_duration_custom').attr('disabled', 'disabled');
            $('#link_edit_offset').attr('disabled', 'disabled');
          }
        } else if (gantt.getTask($("#link_edit_source_task_id").val()).type == "milestone" && gantt.getTask($("#link_edit_target_task_id").val()).type == "task") {

          // Milestone into task
          existingDurationSource = 0;

          $("#link_edit_offset").val(offsetDuration);
          var newSumDuration;
          if ($("#link_edit_offset_type").val() == "1") {
            newSumDuration = Number(offsetDuration) + Number(existingDurationSource);
          } else {
            newSumDuration = Number(existingDurationSource) - Number(offsetDuration);
          }

          var proposedStartDate = getTaskEndDate(startSource, newSumDuration, gantt.getTask($("#link_edit_source_task_id").val()).calendar_id);
          proposedStartDate = getEncasedStartDateTime(proposedStartDate, gantt.getTask($("#link_edit_source_task_id").val()).calendar_id);

          $("#proposed_target_start_date").val(proposedStartDate);
          $(".link-edit-timings").html("<strong>" + gantt.getTask($("#link_edit_source_task_id").val()).text + "</strong><br> finishes on " + endSource.format("ddd Do MMM YYYY") + " @ " + endSource.format("HH:mm") + "<br><br><strong>" + gantt.getTask($("#link_edit_target_task_id").val()).text + "</strong><br> will start on " + moment(proposedStartDate).format("ddd Do MMM YYYY") + " @ " + moment(proposedStartDate).format("HH:mm"));
          $('#link_edit_type').removeAttr('disabled', 'disabled');
          $('#link_edit_offset_type').removeAttr('disabled', 'disabled');
          $('#link_edit_duration_hours').removeAttr('disabled', 'disabled');
          $('#link_edit_duration_mins').removeAttr('disabled', 'disabled');
          $('#link_edit_duration_custom').removeAttr('disabled', 'disabled');
          $('#link_edit_offset').removeAttr('disabled', 'disabled');
        } else if (gantt.getTask($("#link_edit_source_task_id").val()).type == "task" && gantt.getTask($("#link_edit_target_task_id").val()).type == "milestone") {

          // Milestone into task

          $("#link_edit_offset").val(offsetDuration);
          var newSumDuration;
          if ($("#link_edit_offset_type").val() == "1") {
            newSumDuration = Number(offsetDuration) + Number(existingDurationSource);
          } else {
            newSumDuration = Number(existingDurationSource) - Number(offsetDuration);
          }

          var proposedStartDate = getTaskEndDate(startSource, newSumDuration, gantt.getTask($("#link_edit_source_task_id").val()).calendar_id);
          proposedStartDate = getEncasedStartDateTime(proposedStartDate, gantt.getTask($("#link_edit_source_task_id").val()).calendar_id);

          $("#proposed_target_start_date").val(proposedStartDate);
          $(".link-edit-timings").html("<strong>" + gantt.getTask($("#link_edit_source_task_id").val()).text + "</strong><br> finishes on " + endSource.format("ddd Do MMM YYYY") + " @ " + endSource.format("HH:mm") + "<br><br><strong>" + gantt.getTask($("#link_edit_target_task_id").val()).text + "</strong><br> will start on " + moment(proposedStartDate).format("ddd Do MMM YYYY") + " @ " + moment(proposedStartDate).format("HH:mm"));
          $('#link_edit_type').removeAttr('disabled', 'disabled');
          $('#link_edit_offset_type').removeAttr('disabled', 'disabled');
          $('#link_edit_duration_hours').removeAttr('disabled', 'disabled');
          $('#link_edit_duration_mins').removeAttr('disabled', 'disabled');
          $('#link_edit_duration_custom').removeAttr('disabled', 'disabled');
          $('#link_edit_offset').removeAttr('disabled', 'disabled');
        }
      });

      $("#link_edit_duration_hours").change(function() {
        var endSource = moment(gantt.getTask($("#link_edit_source_task_id").val()).end_date);
        var startSource = moment(gantt.getTask($("#link_edit_source_task_id").val()).start_date).format("YYYY-MM-DD HH:mm");
        var existingDurationSource = gantt.getTask($("#link_edit_source_task_id").val()).duration_worked;
        if (gantt.getTask($("#link_edit_source_task_id").val()).type == "task" && gantt.getTask($("#link_edit_target_task_id").val()).type == "task") {
          // Task to task link
          if (gantt.getTask($("#link_edit_source_task_id").val()).calendar_id == gantt.getTask($("#link_edit_target_task_id").val()).calendar_id) {
            var offsetDuration = Number(($("#link_edit_duration_hours").val() * 60)) + Number($("#link_edit_duration_mins").val());
            $("#link_edit_offset").val(offsetDuration);
            var newSumDuration;
            if ($("#link_edit_offset_type").val() == "1") {
              newSumDuration = Number(offsetDuration) + Number(existingDurationSource);
            } else {
              newSumDuration = Number(existingDurationSource) - Number(offsetDuration);
            }
            var proposedStartDate = getTaskEndDate(startSource, newSumDuration, gantt.getTask($("#link_edit_source_task_id").val()).calendar_id);
            proposedStartDate = getEncasedStartDateTime(proposedStartDate, gantt.getTask($("#link_edit_source_task_id").val()).calendar_id);
            $("#proposed_target_start_date").val(proposedStartDate);
            $(".link-edit-timings").html("<strong>" + gantt.getTask($("#link_edit_source_task_id").val()).text + "</strong><br> finishes on " + endSource.format("ddd Do MMM YYYY") + " @ " + endSource.format("HH:mm") + "<br><br><strong>" + gantt.getTask($("#link_edit_target_task_id").val()).text + "</strong><br> will start on " + moment(proposedStartDate).format("ddd Do MMM YYYY") + " @ " + moment(proposedStartDate).format("HH:mm"));
            $('#link_edit_type').removeAttr('disabled', 'disabled');
            $('#link_edit_offset_type').removeAttr('disabled', 'disabled');
            $('#link_edit_duration_hours').removeAttr('disabled', 'disabled');
            $('#link_edit_duration_mins').removeAttr('disabled', 'disabled');
            $('#link_edit_duration_custom').removeAttr('disabled', 'disabled');
            $('#link_edit_offset').removeAttr('disabled', 'disabled');
          } else {
            $('#link_edit_type').attr('disabled', 'disabled');
            $('#link_edit_offset_type').attr('disabled', 'disabled');
            $('#link_edit_duration_hours').attr('disabled', 'disabled');
            $('#link_edit_duration_mins').attr('disabled', 'disabled');
            $('#link_edit_duration_custom').attr('disabled', 'disabled');
            $('#link_edit_offset').attr('disabled', 'disabled');
          }
        } else if (gantt.getTask($("#link_edit_source_task_id").val()).type == "milestone" && gantt.getTask($("#link_edit_target_task_id").val()).type == "task") {
          // Milestone into task
          existingDurationSource = 0;
          var offsetDuration = Number(($("#link_edit_duration_hours").val() * 60)) + Number($("#link_edit_duration_mins").val());
          $("#link_edit_offset").val(offsetDuration);
          var newSumDuration;
          if ($("#link_edit_offset_type").val() == "1") {
            newSumDuration = Number(offsetDuration) + Number(existingDurationSource);
          } else {
            newSumDuration = Number(existingDurationSource) - Number(offsetDuration);
          }
          var proposedStartDate = getTaskEndDate(startSource, newSumDuration, gantt.getTask($("#link_edit_source_task_id").val()).calendar_id);
          proposedStartDate = getEncasedStartDateTime(proposedStartDate, gantt.getTask($("#link_edit_source_task_id").val()).calendar_id);
          $("#proposed_target_start_date").val(proposedStartDate);
          $(".link-edit-timings").html("<strong>" + gantt.getTask($("#link_edit_source_task_id").val()).text + "</strong><br> finishes on " + endSource.format("ddd Do MMM YYYY") + " @ " + endSource.format("HH:mm") + "<br><br><strong>" + gantt.getTask($("#link_edit_target_task_id").val()).text + "</strong><br> will start on " + moment(proposedStartDate).format("ddd Do MMM YYYY") + " @ " + moment(proposedStartDate).format("HH:mm"));
          $('#link_edit_type').removeAttr('disabled', 'disabled');
          $('#link_edit_offset_type').removeAttr('disabled', 'disabled');
          $('#link_edit_duration_hours').removeAttr('disabled', 'disabled');
          $('#link_edit_duration_mins').removeAttr('disabled', 'disabled');
          $('#link_edit_duration_custom').removeAttr('disabled', 'disabled');
          $('#link_edit_offset').removeAttr('disabled', 'disabled');
        } else if (gantt.getTask($("#link_edit_source_task_id").val()).type == "task" && gantt.getTask($("#link_edit_target_task_id").val()).type == "milestone") {
          // Milestone into task
          var offsetDuration = Number(($("#link_edit_duration_hours").val() * 60)) + Number($("#link_edit_duration_mins").val());
          $("#link_edit_offset").val(offsetDuration);
          var newSumDuration;
          if ($("#link_edit_offset_type").val() == "1") {
            newSumDuration = Number(offsetDuration) + Number(existingDurationSource);
          } else {
            newSumDuration = Number(existingDurationSource) - Number(offsetDuration);
          }
          var proposedStartDate = getTaskEndDate(startSource, newSumDuration, gantt.getTask($("#link_edit_source_task_id").val()).calendar_id);
          proposedStartDate = getEncasedStartDateTime(proposedStartDate, gantt.getTask($("#link_edit_source_task_id").val()).calendar_id);
          $("#proposed_target_start_date").val(proposedStartDate);
          $(".link-edit-timings").html("<strong>" + gantt.getTask($("#link_edit_source_task_id").val()).text + "</strong><br> finishes on " + endSource.format("ddd Do MMM YYYY") + " @ " + endSource.format("HH:mm") + "<br><br><strong>" + gantt.getTask($("#link_edit_target_task_id").val()).text + "</strong><br> will start on " + moment(proposedStartDate).format("ddd Do MMM YYYY") + " @ " + moment(proposedStartDate).format("HH:mm"));
          $('#link_edit_type').removeAttr('disabled', 'disabled');
          $('#link_edit_offset_type').removeAttr('disabled', 'disabled');
          $('#link_edit_duration_hours').removeAttr('disabled', 'disabled');
          $('#link_edit_duration_mins').removeAttr('disabled', 'disabled');
          $('#link_edit_duration_custom').removeAttr('disabled', 'disabled');
          $('#link_edit_offset').removeAttr('disabled', 'disabled');
        }
      });
      
      $("#link_edit_duration_mins").change(function() {
        var endSource = moment(gantt.getTask($("#link_edit_source_task_id").val()).end_date);
        var startSource = moment(gantt.getTask($("#link_edit_source_task_id").val()).start_date).format("YYYY-MM-DD HH:mm");
        var existingDurationSource = gantt.getTask($("#link_edit_source_task_id").val()).duration_worked;
        if (gantt.getTask($("#link_edit_source_task_id").val()).calendar_id == gantt.getTask($("#link_edit_target_task_id").val()).calendar_id) {
          var offsetDuration = Number(($("#link_edit_duration_hours").val() * 60)) + Number($("#link_edit_duration_mins").val());
          $("#link_edit_offset").val(offsetDuration);
          var newSumDuration;
          if ($("#link_edit_offset_type").val() == "1") {
            newSumDuration = Number(offsetDuration) + Number(existingDurationSource);
          } else {
            newSumDuration = Number(existingDurationSource) - Number(offsetDuration);
          }
          var proposedStartDate = getTaskEndDate(startSource, newSumDuration, gantt.getTask($("#link_edit_source_task_id").val()).calendar_id);
          proposedStartDate = getEncasedStartDateTime(proposedStartDate, gantt.getTask($("#link_edit_source_task_id").val()).calendar_id);
          $("#proposed_target_start_date").val(proposedStartDate);
          $(".link-edit-timings").html("<strong>" + gantt.getTask($("#link_edit_source_task_id").val()).text + "</strong><br> finishes on " + endSource.format("ddd Do MMM YYYY") + " @ " + endSource.format("HH:mm") + "<br><br><strong>" + gantt.getTask($("#link_edit_target_task_id").val()).text + "</strong><br> will start on " + moment(proposedStartDate).format("ddd Do MMM YYYY") + " @ " + moment(proposedStartDate).format("HH:mm"));
          $('#link_edit_type').removeAttr('disabled', 'disabled');
          $('#link_edit_offset_type').removeAttr('disabled', 'disabled');
          $('#link_edit_duration_hours').removeAttr('disabled', 'disabled');
          $('#link_edit_duration_mins').removeAttr('disabled', 'disabled');
          $('#link_edit_duration_custom').removeAttr('disabled', 'disabled');
          $('#link_edit_offset').removeAttr('disabled', 'disabled');
        } else {
          $('#link_edit_type').attr('disabled', 'disabled');
          $('#link_edit_offset_type').attr('disabled', 'disabled');
          $('#link_edit_duration_hours').attr('disabled', 'disabled');
          $('#link_edit_duration_mins').attr('disabled', 'disabled');
          $('#link_edit_duration_custom').attr('disabled', 'disabled');
          $('#link_edit_offset').attr('disabled', 'disabled');
        }
      });

 gantt.attachEvent("onLinkDblClick", function(id, e) {
          // if (window.ibex_gantt_config.selfCanEditLinks == true) {
          $('.mdb-select').material_select('destroy');
          $(".delete-link").show();
          $("#link_edit_type").val('1');
          $("#link_edit_offset_type").val('1');
          $("#link_edit_duration_hours").val('0');
          $("#link_edit_duration_mins").val('0');
          $("#link_edit_offset").val('0');
          var link = gantt.getLink(id);
          var task = gantt.getTask(link.source);
          $("#link_edit_id").val(id);
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
          $(".link-edit-timings").html("<strong>" + gantt.getTask($("#link_edit_source_task_id").val()).text + "</strong><br> finishes on " + endSource.format("ddd Do MMM YYYY") + " @ " + endSource.format("HH:mm") + "<br><br><strong>" + gantt.getTask($("#link_edit_target_task_id").val()).text + "</strong><br> will start on " + moment(proposedStartDate).format("ddd Do MMM YYYY") + " @ " + moment(proposedStartDate).format("HH:mm"));
          $('.mdb-select').material_select();
          // Set up timings
          if (window.ibex_gantt_config.periodDescriptor == "1") {
            // Hours and mins
            $("#link-edit-hours").show();
            $("#link-edit-mins").show();
            $("#link-edit-custom").hide();
          } else {
            if (window.ibex_gantt_config.periodDescriptor == "2") {
              window.ibex_gantt_config.periodDescriptorTextPlural = "days";
              pluralText = "days";
              singleText = "day";
            }
            if (window.ibex_gantt_config.periodDescriptor == "3") {
              window.ibex_gantt_config.periodDescriptorTextPlural = "nights";
            }
            if (window.ibex_gantt_config.periodDescriptor == "4") {
              window.ibex_gantt_config.periodDescriptorTextPlural = "shifts";
            }
            // Custom
            $("#link-edit-hours").hide();
            $("#link-edit-mins").hide();
            $("#link-edit-custom").show();
            $('#link_edit_duration_custom_label').text('Offset duration (' + window.ibex_gantt_config.periodDescriptorTextPlural + ')');
          }
          $("#modal_link_editor").modal('show');
          return false;
          /*
                                    } else {
                                      return false;
                                    }*/
        });
