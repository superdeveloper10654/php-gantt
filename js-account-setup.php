<script>
  

  
  
  
  $('.save-account-details').click(function(event) {
    $.getJSON("beta.ajax.php?action=update_account&account_first_name=" + $("#account_first_name").val(), function(data) {
      location.reload();
    });
  });
  
  // New account setup process
  if ($("#get_started").val() == "true") {
    $('#gantt').removeClass("show active");
    $('#account-setup').show();
    $('#welcome-tab').addClass("show active");
  };
  
  $('#continue_setup_calendars').click(function(e) {
    $('#welcome-tab').removeClass("show active");
    $('#setup-calendars-tab').addClass("show active");
  });
  
  $('#continue_setup_resources').click(function(e) {
    $('#setup-calendars-tab').removeClass("show active");
    $('#setup-resources-tab').addClass("show active");
  });
  
  $('#continue_setup_first_project').click(function(e) {
        $('#setup-resources-tab').removeClass("show active");
    $('#setup-first-project-tab').addClass("show active");
    $("#setup_first_project").focus();
  });
  
  $('#setup_first_project').click(function(e) {

    if ($("#setup_first_project").val().trim() != "") {
      // Go back by 2 weeks and find a date, then get next working date
      var startDate = moment().add(2, 'days');
      var defaultTaskCalendarID = 0;
      $.each(window.ibex_gantt_config.calendars, function(index) {
        if (window.ibex_gantt_config.calendars[index].is_default_task_calendar == 1) {
          defaultTaskCalendarID = window.ibex_gantt_config.calendars[index].id;
        }
      });
      var pointerDate;
      var task1StartDateFormatted = getNextWorkingDate(defaultTaskCalendarID, true, startDate);
      var task1EndDateFormatted = getTaskEndDate(moment(task1StartDateFormatted, "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm:ss"), '480', defaultTaskCalendarID);
      var task1Duration = "480";
      var task1StartDateFormatted = getNextWorkingDate(defaultTaskCalendarID, true, startDate);
      $.getJSON("beta.ajax.php?action=create_first_project&id=" + $("#programme_id").val() + "&project_name=" + $("#setup_first_project").val() + "&start_date=" + moment(task1StartDateFormatted, "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm:ss") + "&calendar_id=" + defaultTaskCalendarID + "&task_1_start_date=" + moment(task1StartDateFormatted, "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm:ss") + "&task_1_duration=600&task_1_end_date=" + getTaskEndDate(moment(task1StartDateFormatted, "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm:ss"), '600', defaultTaskCalendarID), function(data) {
        window.location.href = "beta.php?id=" + $("#programme_id").val();
      });
    }
  });
  
 
  
  /*
  
  $(".continue_create_project").click(function(e) {
    $('#work-environment').removeClass("show active");
    $('setup-invite-wrapper').hide();
    $('#create-project').addClass("show active");
    $("#setup_project_name").focus();
  });
  $('.continue_to_work_env').click(function(e) {
    $('#settings').removeClass("show active");
    $('#welcome').removeClass("show active");
    $('#work-environment').addClass("show active");
    $('#account-setup').show();
	  $.getJSON("beta.ajax.php?action=update_profile&first_name=" + $("#setup_first_name").val() + "&last_name=" + $("#setup_last_name").val(), function(data) {
	});
  });
  $('.continue_to_collaboration').click(function(e) {
    $('#st-trigger-effects').hide();
    $('#SettingsTabScheduling').hide();
    $('#setup-teams-wrapper').show();
    $('#work-environment-wrapper').hide();
    $("#setup_new_user_group_name").focus();
  });
  $('.continue_to_invite').click(function(e) {
    $('#setup-teams-wrapper').hide();
    $('#setup-invite-wrapper').show();
    $("#setup_new_user_add_email_address").focus();
  });
  $('.continue_to_finish').click(function(e) {
    $('#work-environment').removeClass("show active");
    $('#settings').removeClass("show active");
    $('#setup-complete').addClass("show active");
    $('#account-setup').show();
    $('#setup-finish').show();
  });

  $(".create-project").click(function() {
    if ($("#setup_project_name").val().trim() != "") {
      // Go back by 2 weeks and find a date, then get next working date
      var startDate = moment().subtract(3, 'days');
      var defaultTaskCalendarID = 0;
      $.each(window.ibex_gantt_config.calendars, function(index) {
        if (window.ibex_gantt_config.calendars[index].is_default_task_calendar == 1) {
          defaultTaskCalendarID = window.ibex_gantt_config.calendars[index].id;
        }
      });
      var pointerDate;
      var task1StartDateFormatted = getNextWorkingDate(defaultTaskCalendarID, true, startDate);
      var task1EndDateFormatted = getTaskEndDate(moment(task1StartDateFormatted, "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm:ss"), '480', defaultTaskCalendarID);
      var task1Duration = "480";
      var task1StartDateFormatted = getNextWorkingDate(defaultTaskCalendarID, true, startDate);
      $.getJSON("beta.ajax.php?action=create_first_project&id=" + $("#programme_id").val() + "&project_name=" + $("#setup_project_name").val() + "&start_date=" + moment(task1StartDateFormatted, "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm:ss") + "&calendar_id=" + defaultTaskCalendarID + "&task_1_start_date=" + moment(task1StartDateFormatted, "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm:ss") + "&task_1_duration=600&task_1_end_date=" + getTaskEndDate(moment(task1StartDateFormatted, "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm:ss"), '600', defaultTaskCalendarID), function(data) {
        window.location.href = "beta.php?id=" + $("#programme_id").val();
      });
    }
  });

  


  if ($("#get_started").val() == "true") {

    $('#gantt').removeClass("show active");
    $('#account-setup').show();
    $('#welcome').addClass("show active");
  };
  if ($("#get_support").val() == "true") {

    $('#gantt').removeClass("show active");
    $('#ibex-support').show();
    $('#support-home-wrapper').show();
  };
  $('.load_support').click(function(e) {
    $('#st-trigger-effects').hide();
    window.location.href = "beta.php?id=" + $("#programme_id").val() + "&support=true";
    ('#gantt').removeClass("show active");
    ('#resources').removeClass("show active");
    ('#files').removeClass("show active");
    ('#messages').removeClass("show active");
    ('#activity').removeClass("show active");
    ('#settings').removeClass("show active");
    $('#ibex-support').show();
    $('#support-home-wrapper').show();
  });
  $('#close-support-home').click(function(e) {
    window.location.href = "beta.php?id=" + $("#programme_id").val();
  });
  $('.close-support-article').click(function(e) {
    $('#support-articles-wrapper').hide();
    $('.support-article').hide();
    $('#support-home-wrapper').show();
  });
  $('.open-articles-scheduling').click(function(e) {
    $('#support-home-wrapper').hide();
    $('.support-article').hide();
    $('#support-articles-wrapper').show();
  });
  $('#open-article-1').click(function(e) {
    $('#article-1').show();
  });
  
 
  
  
  
  
  
  
  
  if ($("#load_gantt_after_setup").val() == "true") {
    $('#st-container').hide();
    $('#account-setup').show();
    $('#welcome').addClass("show active");
  };
  $('.skip-setup').click(function(e) {
    window.location.href = "beta.php?id=" + $("#programme_id").val();
  });
  
  $('#continue_no_experience').click(function(e) {
    $('#setup-experience').removeClass("show active");
    $('#has-no-experience').addClass("show active");
    //$('#work-environment').addClass("show active");
  });
  $('#continue_some_experience').click(function(e) {
    $('#setup-experience').removeClass("show active");
    $('#has-some-experience').addClass("show active");
    //$('#quiz').removeClass("show active");
  });
  $('#continue_lots_experience').click(function(e) {
    $('#setup-experience').removeClass("show active");
    $('#has-lots-experience').addClass("show active");
  });
  $('.continue_load_demo').click(function(e) {
    window.location.href = "beta.php?id=" + $("#programme_id").val() + "&demo=true";
    $('#account-setup').hide();
    $('#st-trigger-effects').show();
  });
  $('#continue_load_quiz').click(function(e) {
    $('#has-some-experience').removeClass("show active");
    $('#quiz').addClass("show active");
    $('#quiz-wrapper').show();
    $('#quiz-into').hide();
    $('#quiz-finished').hide();
    $('#quiz-question-1').show();
    $('#quiz-question-2').hide();
  });
  $('#quiz_answer_1a').click(function(e) {
    $('#quiz_answer_1a').css('border-color', '#000 !important');
    $('.quiz-question-1-reveal-correct').show();
    $('.quiz-question-1-reveal-incorrect').hide();
    $('#quiz_question_1_next').show();
    $('#quiz_question_1_info').show();
  });
  $('#quiz_answer_1b').click(function(e) {
    $('.quiz-question-1-reveal-correct').hide();
    $('.quiz-question-1-reveal-incorrect').show();
    $('#quiz_question_1_next').hide();
    $('#quiz_question_1_info').hide();
  });
  $('#quiz_question_1_next').click(function(e) {
    $('#quiz-question-1').hide();
    $('#quiz-question-2').show();
  });
  $('#quiz_answer_2a').click(function(e) {
    $('.quiz-question-2a-reveal-incorrect').show();
    $('.quiz-question-2b-reveal-incorrect').hide();
    $('.quiz-question-2c-reveal-correct').hide();
    $('#quiz_question_2_next').hide();
    $('#quiz_question_2a_hint').hide();
    $('#quiz_question_2b_hint').hide();
    $('#quiz_question_2_info').hide();
  });
  $('#quiz_answer_2b').click(function(e) {
    $('.quiz-question-2a-reveal-incorrect').hide();
    $('.quiz-question-2b-reveal-incorrect').show();
    $('.quiz-question-2c-reveal-correct').hide();
    $('#quiz_question_2_next').hide();
    $('#quiz_question_2a_hint').hide();
    $('#quiz_question_2b_hint').hide();
    $('#quiz_question_2_info').hide();
  });
  $('#quiz_answer_2c').click(function(e) {
    $('.quiz-question-2a-reveal-incorrect').hide();
    $('.quiz-question-2b-reveal-incorrect').hide();
    $('.quiz-question-2c-reveal-correct').show();
    $('#quiz_answer_2c').css('border-color', '#000 !important');
    $('#quiz_question_2_next').show();
    $('#quiz_question_2a_hint').show();
    $('#quiz_question_2b_hint').show();
    $('#quiz_question_2_info').show();
  });
  $('#quiz_question_2_next').click(function(e) {
    $('#quiz-question-2').hide();
    $('#quiz-question-3').show();
  });

  $('#quiz_answer_3a').click(function(e) {
    $('.quiz-question-3-reveal-correct').show();
    $('.quiz-question-3-reveal-incorrect').hide();
    $('#quiz_answer_3a').css('border-color', '#000 !important');
    $('#quiz_question_3_next').show();
    $('#quiz_question_3_info').show();
  });
  $('#quiz_answer_3b').click(function(e) {
    $('.quiz-question-3-reveal-correct').hide();
    $('.quiz-question-3-reveal-incorrect').show();
    $('#quiz_question_3_next').hide();
    $('#quiz_question_3_info').hide();
  });
  $('#quiz_question_3_next').click(function(e) {
    $('#quiz-question-3').hide();
    $('#quiz-question-4').show();
  });

  $('#quiz_answer_4a').click(function(e) {
    $('.quiz-question-4-reveal-correct').show();
    $('.quiz-question-4-reveal-incorrect').hide();
    $('#quiz_answer_4a').css('border-color', '#000 !important');
    $('#quiz_question_4_next').show();
    $('#quiz_question_4_info').show();
  });
  $('#quiz_answer_4b').click(function(e) {
    $('.quiz-question-4-reveal-correct').hide();
    $('.quiz-question-4-reveal-incorrect').show();
    $('#quiz_question_4_next').hide();
    $('#quiz_question_4_info').hide();
  });
  $('#quiz_question_4_next').click(function(e) {
    $('#quiz-question-4').hide();
    $('#quiz-question-5').show();
  });
  $('#quiz_answer_5a').click(function(e) {
    $('.quiz-question-5a-reveal-incorrect').show();
    $('.quiz-question-5b-reveal-incorrect').hide();
    $('.quiz-question-5c-reveal-correct').hide();
    $('#quiz_question_5_next').hide();
    $('#quiz_question_5_info').hide();
  });
  $('#quiz_answer_5b').click(function(e) {
    $('.quiz-question-5a-reveal-incorrect').hide();
    $('.quiz-question-5b-reveal-incorrect').show();
    $('.quiz-question-5c-reveal-correct').hide();
    $('#quiz_question_5_next').hide();
    $('#quiz_question_5_info').hide();
  });
  $('#quiz_answer_5c').click(function(e) {
    $('.quiz-question-5a-reveal-incorrect').hide();
    $('.quiz-question-5b-reveal-incorrect').hide();
    $('.quiz-question-5c-reveal-correct').show();
    $('#quiz_question_5_next').show();
    $('#quiz_question_5_info').show();
  });
  $('#quiz_question_5_next').click(function(e) {
    $('#quiz-wrapper').hide();
    $('#quiz-finished').show();
  });
  $('.go-back-experience').click(function(e) {
    $('#has-no-experience').removeClass("show active");
    $('#has-some-experience').removeClass("show active");
    $('#has-lots-experience').removeClass("show active");
    $('#quiz-wrapper').hide();
    $('#setup-experience').addClass("show active");
  });
  $('.continue_defaults_or_tweaks').click(function(e) {
    $('#has-lots-experience').removeClass("show active");
    $('#defaults-or-tweaks').addClass("show active");
    $('#quiz-finished').hide();
  });
  $('#continue_make_tweaks').click(function(e) {
    $('#defaults-or-tweaks').removeClass("show active");
    $('#account-setup').hide();
    $('#st-trigger-effects').hide();
    $('#settings').addClass("show active");
    $('#setup-scheduling-wrapper').show();
    //$('#collapseSettingsTabScheduling').addClass("show active");
    //$('[data-toggle="collapse"]').hide();
    $('#SettingsTabCollaboration').hide();
    $('#SettingsTabBilling').hide();
    $('#modal_get_started').hide();
    $('.modal-backdrop').hide();
    $('.gantt-header-toolbar').hide();
    $('.settings-header-toolbar').show();
    $('.hamburger').css('visibility', 'hidden');
    $('.profile').css('visibility', 'hidden');
    $('#continue-collaboration').show();
    $('#quiz-finished').hide();
  });
  $('.continue_to_work_env').click(function(e) {
    $('#settings').removeClass("show active");
    $('#defaults-or-tweaks').removeClass("show active");
    $('#work-environment').addClass("show active");
    $('#account-setup').show();
  });
  $('.continue_to_collaboration').click(function(e) {
    $('#account-setup').hide();
    $('#st-trigger-effects').hide();
    $('#work-environment').removeClass("show active");
    $('#settings').addClass("show active");
    $('#SettingsTabScheduling').hide();
    //$('[data-toggle="collapse"]').hide();
    $('#SettingsTabCollaboration').show();
    //$('#collapseSettingsTabScheduling').removeClass("show active");
    //$('#collapseSettingsTabCollaboration').addClass("show active");
    $('#setup-scheduling-wrapper').hide();
    $('#setup-collaboration-wrapper').show();
    $('.broadcasts').hide();
  });
  $('.continue_to_invite').click(function(e) {
    $('#setup-teams-wrapper').hide();
    $('#setup-invite-wrapper').show();
  });
  $('.continue_to_finish').click(function(e) {
    $('#work-environment').removeClass("show active");
    $('#settings').removeClass("show active");
    $('#setup-complete').addClass("show active");
    $('#account-setup').show();
    $('#setup-finish').show();
  });
  $('.get-started').click(function(e) {
    window.location.href = "beta.php?id=" + $("#programme_id").val();
    $('#st-trigger-effects').show();
  });
  $('.delete-demo').click(function(e) {
    $('#modal_get_started').modal("hide");
  });
  $('.cancel-programme-confirm').click(function(e) {
    window.location.href = "beta.php?id=" + $("#programme_id").val();
  });
  $(".close-teams-modal").click(function(e) {
    $('#settings').addClass("show active");
    //$('[data-toggle="collapse"]').show();
  });
  $("#settings-manage-calendars").click(function(e) {
    $('#settings').removeClass("show active");
    //$('[data-toggle="collapse"]').show();
  });
  $(".close-calendars-modal").click(function(e) {
    $('#settings').addClass("show active");
    //$('[data-toggle="collapse"]').hide();
  });
  $(".new-resource-group").click(function(e) {
    $('#modal_task_editor').modal('hide');
    $('#gantt').removeClass("show active");
    $('#resources').addClass("show active");
    $(".gantt-header-toolbar").hide();
    $(".resources-header-toolbar").show();
    $("#resource_group_id_local").val('0');
    $('.mdb-select').material_select('destroy');
    for (var calendar of window.ibex_gantt_config.calendars) {
      if (calendar.type == "1") {
        $('#resource_group_calendar_id').append($('<option>', {
          value: calendar['id'],
          text: calendar['name']
        }));
      }
    }
    $('.mdb-select').material_select();
    $("#modal_resource_groups_editor").modal('show');
  });
  $(".new-resource-group").click(function(e) {
    $('#modal_task_editor').modal('hide');
    $('#gantt').removeClass("show active");
    $('#resources').addClass("show active");
    $(".gantt-header-toolbar").hide();
    $(".resources-header-toolbar").show();
    $(".manage-resource-groups").click('');
  });
  $(".new-resource-item").click(function(e) {
    $('#modal_task_editor').modal('hide');
    $('#gantt').removeClass("show active");
    $('#resources').addClass("show active");
    $(".gantt-header-toolbar").hide();
    $(".resources-header-toolbar").show();
    //$(".add-resource").click('');
    $('#modal_resouce_editor').modal('show');
  });
  */
</script>