<script>
  
 function prepareInitialisation()
 {
	 $(".reset-init").val('');
 }
  
  function prepareTaskEditorUI(type) {
    // Adjust UI to suit projects
    
    $('#task_edit_start_date_d').closest('.row').show();
      $('#task_edit_duration_hours').closest('.row').show();
      $('#task_edit_duration_custom').closest('.col').show();
      $('.col.start').closest('.col').show();
      $('#task_edit_end_date').closest('.md-form').show();
      $('#task_edit_calendar_id').closest('.md-form').show();
      $('#task_edit_bar_colour').closest('.md-form').show();
      $("#task-editor-workload-section").show();
      $("#task-editor-resources-section").show();
      $("#task-editor-status-section").show();
      $("#task-editor-dependencies-section").show();
      $("#task-editor-deadline-section").show();
      $("#task-editor-baselines-section").show();
      $("#task-editor-finances-section").show();
    
    if (type == "project") {
      $('#task_edit_start_date_d').closest('.row').show();
      $('#task_edit_duration_hours').closest('.row').show();
      $('#task_edit_duration_custom').closest('.col').show();
      $('.col.start').closest('.col').show();
      $('#task_edit_end_date').closest('.md-form').show();
      $('#task_edit_calendar_id').closest('.md-form').show();
      $('#task_edit_bar_colour').closest('.md-form').hide();
      $("#task-editor-workload-section").hide();
      $("#task-editor-resources-section").hide();
      $("#task-editor-status-section").hide();
      $("#task-editor-dependencies-section").hide();
      $("#task-editor-baselines-section").hide();
      $("#task-editor-finances-section").hide();
      $("#task-editor-permissions-section").hide();
      $("#task-editor-comments-section").css('border-bottom', 'none !important');
    }
    if (type == "task") {
      $('#task_edit_start_date_d').closest('.row').show();
      $('#task_edit_duration_hours').closest('.row').show();
      $('#task_edit_duration_custom').closest('.col').show();
      $('.col.start').closest('.col').show();
      $('#task_edit_end_date').closest('.md-form').show();
      $('#task_edit_calendar_id').closest('.md-form').show();
      $('#task_edit_bar_colour').closest('.md-form').show();
      $("#task-editor-workload-section").show();
      $("#task-editor-resources-section").show();
      $("#task-editor-status-section").show();
      $("#task-editor-dependencies-section").show();
      $("#task-editor-deadline-section").show();
      $("#task-editor-baselines-section").show();
      $("#task-editor-finances-section").show();
    }
    if (type == "milestone") {
      $('#task_edit_start_date_d').closest('.row').show();
      $('#task_edit_duration_hours').closest('.row').hide();
      $('#task_edit_duration_custom').closest('.col').hide();
      $('.col.start').closest('.col').hide();
      $('#task_edit_end_date').closest('.md-form').hide();
      $('.task-edit-bar-colour').closest('.form-group').hide();
      $("#task-editor-workload-section").hide();
      $("#task-editor-resources-section").hide();
      $("#task-editor-status-section").hide();
      $("#task-editor-dependencies-section").hide();
      $("#task-editor-deadline-section").hide();
      $("#task-editor-baselines-section").hide();
      $("#task-editor-finances-section").hide();
      $("#task-editor-permissions-section").hide();
      $("#task-editor-comments-section").css('border-bottom', 'none !important');
    }
  }

  $("#task_edit_type").change(function() {
    prepareTaskEditorUI($("#task_edit_type").val()); 
  });

  $('#modal_delete_task').on('shown.bs.modal', function() {
    $("#auto_schedule_after_delete").attr('checked', true);
  })

  $('#modal_task_editor').on('show.bs.modal', function() 
  {
	  prepareInitialisation();
    if (window.ibex_gantt_configsuppressDynamicTaskMode == true) {} else {
      $('.card-header').addClass('collapsed');
      if (window.ibex_gantt_config.newObjectType == null) {
        $('.mdb-select').material_select('destroy');
        $("#task_edit_type").val('task');
        $('.mdb-select').material_select();
      }
      if (window.ibex_gantt_config.newObjectType == "2") {
        $('.mdb-select').material_select('destroy');
        $("#task_edit_type").val('project');
        $('.mdb-select').material_select();
      }
      if (window.ibex_gantt_config.newObjectType == "3") {
        $('.mdb-select').material_select('destroy');
        $("#task_edit_type").val('milestone');
        $('.mdb-select').material_select();
      }
      prepareTaskEditorUI($("#task_edit_type").val());
	
    }
    window.ibex_gantt_config.newObjectType = null;
    window.ibex_gantt_configsuppressDynamicTaskMode = false;
  })

   $('.modal').on('shown.bs.modal', function() {
		
		$(this).find(".accordion-item-ui").removeClass("show");
		$(this).find(".accordion-item-ui:first").addClass("show");
		
		
	});
  
  $('#modal_task_editor').on('shown.bs.modal', function() {

	  // Collapse accordion
	  $(".accordion-item-ui").removeClass("show");
	  $(".accordion-item-ui:first").addClass("show");
    
    
    
    var task = gantt.getTask(window.ibex_gantt_config.activeTaskID);
    if (task.$new) {
      $(".task_edit_name").focus();
      
      // RB 13.05.21
      $('#task-editor-title').html('New Task');
      $('#task_edit_duration_custom').removeAttr('readonly'); // RB 24.02.22
      $("#task-editor-status-section").hide();
      $("#task-editor-dependencies-section").hide();
      $('.mdb-select').material_select('destroy');
	    $('.mdb-select').material_select();
    } else {
      $('#task-editor-title').html('Task Editor');
      
    }
    if (window.ibex_gantt_config.periodDescriptor == "1") {
      var task = gantt.getTask(window.ibex_gantt_config.activeTaskID);
      $("#task_edit_custom_duration_label").text('Duration');
      $(".row-task-editor-period-descriptors-custom").hide();
      $(".row-task-editor-period-descriptors-default").show();
    } else {
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
      if (task.timing_overriden == "1") {
        $("#task_edit_custom_duration_label").text('Duration');
        $(".row-task-editor-period-descriptors-custom").hide();
        $(".row-task-editor-period-descriptors-default").show();
        $("#label_task_edit_duration_hours").html('Duration (hrs & mins) <a style="text-decoration: underline; margin-left: 15px; position: relative; z-index: 99999;" class="revert-task-timings">' + pluralsText + '?</a>');
      }
    }
  })
  
  
  window.onbeforeunload = function() {
        $.getJSON("beta.ajax.php?action=release_task_locks", function(data) {
        });
      };
      $('#modal_task_editor').on('hidden.bs.modal', function(e) {
        $.getJSON("beta.ajax.php?action=release_task_locks", function(data) {
        });
      })
</script>