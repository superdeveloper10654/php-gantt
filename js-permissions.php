<script>
  
  window.loadUserGroups = function loadUserGroups() {
    // Reload user groups, clear container, add to container
    $(".groups-container").html('');
    $.getJSON("beta.ajax.php?action=get_user_groups", function(data) {
      window.ibex_gantt_config.user_groups = data.groups;
      window.ibex_gantt_config.user_groups_self = data.self_groups;

      // DO ALL THE UI ADJUSTMENTS HERE BASED ON USER GROUPS - TWO EXAMPLES BELOW
      // The groups all live in a window object called window.ibex_gantt_config.user_groups_self.
      // Compare with database table called gantt_user_groups

      // Group-wise, there are three settings per group as per the UI you built. 2 = manage everything, 1 = view, 0 = no acccess

      
      
      
      // PERMISSIONS - TASK EDITOR/ SCHEDULING > GENERAL
      if (window.ibex_gantt_config.user_groups_self.scheduling_general == "2") // MANAGE
      {
      }
      if (window.ibex_gantt_config.user_groups_self.scheduling_general == "1") // VIEW
      {
        $("button.header-toolbar-button.undo.undo-ui").remove();
        $("button.header-toolbar-button.redo.redo-ui").remove();
        $("a.btn-reset-deadlines").remove();
        $("a.btn-reset-baselines").remove();
        $("button.save-task").remove();
        $("button.mr-auto.delete-task").remove();
        $("div#task-editor-footer").html("<p style='margin: auto'>Sorry <?=$_SESSION['user']['first_name']?>, you don't have permission to save any changes</p>");
        gantt.config.readonly = true;
        // find a way to disable or remove the context menu!
        $("div#modal_delete_task").remove();
        $("div#modal_prevent_drag").remove();
        $("div#modal-reset-programme").remove();
        $("div#modal_task_locked").remove();
        $("div#modal_reset_baselines").remove();

      }

      // PERMISSIONS - TASK EDITOR/ SCHEDULING > WORKLOAD
      if (window.ibex_gantt_config.user_groups_self.scheduling_workload == "2") // MANAGE
      {
      }
      if (window.ibex_gantt_config.user_groups_self.scheduling_workload == "1") // VIEW
      {
        $("#task_edit_workload_total_quantity").addClass("antiquewhite").attr("disabled", true);
        $("#task_edit_workload_unit").addClass("antiquewhite").attr("disabled", true);
        $("#dist-workload-qty").remove();
        //$("#task_edit_workload_days").remove();
      }
      if (window.ibex_gantt_config.user_groups_self.scheduling_workload == "0") // NONE
      {
        $("div#task-editor-workload-section").remove();
        $("div#block-workload").remove();
        $("div#modal_workload_lock").remove();
        $("div#modal_add_to_workload_root").remove();
      }

      // PERMISSIONS - TASK EDITOR/ SCHEDULING > CALENDARS
      if (window.ibex_gantt_config.user_groups_self.scheduling_calendars == "2") // MANAGE
      {
      }
      if (window.ibex_gantt_config.user_groups_self.scheduling_calendars == "1") // VIEW
      {
        $("#calendars-modal-add-task-calendar").addClass("antiquewhite").attr("disabled", true);
        $("input#task_calendar_edit_name").addClass("antiquewhite").attr("disabled", true);
        $("input#task_calendar_edit_default").addClass("antiquewhite").attr("disabled", true);
        $("input#task_calendar_edit_working_day_monday").addClass("antiquewhite").attr("disabled", true);
        $("input#task_calendar_edit_working_day_tuesday").addClass("antiquewhite").attr("disabled", true);
        $("input#task_calendar_edit_working_day_wednesday").addClass("antiquewhite").attr("disabled", true);
        $("input#task_calendar_edit_working_day_thursday").addClass("antiquewhite").attr("disabled", true);
        $("input#task_calendar_edit_working_day_friday").addClass("antiquewhite").attr("disabled", true);
        $("input#task_calendar_edit_working_day_saturday").addClass("antiquewhite").attr("disabled", true);
        $("input#task_calendar_edit_working_day_sunday").addClass("antiquewhite").attr("disabled", true);
        $("input#task_calendar_edit_start_time").addClass("antiquewhite").attr("disabled", true);
        $("input#task_calendar_edit_end_time").addClass("antiquewhite").attr("disabled", true);
      }
      if (window.ibex_gantt_config.user_groups_self.scheduling_calendars == "0") // NONE
      {
        $(".toggle-calendars").remove();

      }
      
      // PERMISSIONS - TASK EDITOR/ SCHEDULING > DEPENDENCIES
      if (window.ibex_gantt_config.user_groups_self.scheduling_dependencies == "2") // MANAGE
      {
        //console.log('Has permission to fully manage dependencies');
      }
      if (window.ibex_gantt_config.user_groups_self.scheduling_dependencies == "1") // VIEW
      {
        //console.log('Has permission to view dependencies');
        $("#modal_link_editor").remove();
      }

      
      // PERMISSIONS - RESOURCES > GENERAL

    $("#resources_general_0").on('change', function () {   // None
      //$("#resources_general_0").not(this).attr('checked', false);  
          if (this.checked) {
				 
          $("#resources_availability_0").attr("checked", true);
          $("#resources_allocation_0").attr("checked", true);
          $("#resources_financial_0").attr("checked", true);
          $("#resources_groups_0").attr("checked", true);
          $("#resources_calendars_0").attr("checked", true);
          $("#resources_general_2").attr("checked", false);
          $("#resources_general_1").attr("checked", false);
          $("#resources_availability_2").attr("checked", false);
          $("#resources_availability_1").attr("checked", false);
          $("#resources_allocation_2").attr("checked", false);
          $("#resources_allocation_1").attr("checked", false);
          $("#resources_financial_2").attr("checked", false);
          $("#resources_financial_1").attr("checked", false);
          $("#resources_groups_2").attr("checked", false);
          $("#resources_groups_1").attr("checked", false);
          $("#resources_calendars_2").attr("checked", false);
          $("#resources_calendars_1").attr("checked", false);
			  $("#resources_general_1").prop("checked", false);
			  $("#resources_general_2").prop("checked", false);
        }
    });
	 
	 
	  $(document).on('change', '#resources_general_1', function(e) {
		  
		  
           // $("#resources_general_1").not(this).attr('checked', false); 
        if (this.checked) {
			 
			   $("#resources_general_0").prop("checked", false);
				$("#resources_general_2").prop("checked", false);
          $("#resources_general_1").attr("checked", true);
          $("#resources_availability_1").attr("checked", true);
          $("#resources_allocation_1").attr("checked", true);
          $("#resources_financial_1").attr("checked", true);
          $("#resources_groups_1").attr("checked", true);
          $("#resources_calendars_1").attr("checked", true);
          $("#resources_general_0").attr("checked", false);
          $("#resources_general_2").attr("checked", false);
          $("#resources_availability_0").attr("checked", false);
          $("#resources_availability_2").attr("checked", false);
          $("#resources_allocation_0").attr("checked", false);
          $("#resources_allocation_2").attr("checked", false);
          $("#resources_financial_0").attr("checked", false);
          $("#resources_financial_2").attr("checked", false);
          $("#resources_groups_0").attr("checked", false);
          $("#resources_groups_2").attr("checked", false);
          $("#resources_calendars_0").attr("checked", false);
          $("#resources_calendars_2").attr("checked", false);
        }
    });             
          $("#resources_general_2").on('change', function () {   // Manage
            $("#resources_general_2").not(this).attr('checked', false); 
        if (this.checked) {
			  $("#resources_general_0").attr("checked", false);
			  $("#resources_general_1").attr("checked", false);
          $("#resources_general_2").attr("checked", true);
          $("#resources_availability_2").attr("checked", true);
          $("#resources_allocation_2").attr("checked", true);
          $("#resources_financial_2").attr("checked", true);
          $("#resources_groups_2").attr("checked", true);
          $("#resources_calendars_2").attr("checked", true);
          $("#resources_general_0").attr("checked", false);
          $("#resources_general_1").attr("checked", false);
          $("#resources_availability_0").attr("checked", false);
          $("#resources_availability_1").attr("checked", false);
          $("#resources_allocation_0").attr("checked", false);
          $("#resources_allocation_1").attr("checked", false);
          $("#resources_financial_0").attr("checked", false);
          $("#resources_financial_1").attr("checked", false);
          $("#resources_groups_0").attr("checked", false);
          $("#resources_groups_1").attr("checked", false);
          $("#resources_calendars_0").attr("checked", false);
          $("#resources_calendars_1").attr("checked", false);
			  $("#resources_general_0").prop("checked", false);
			  $("#resources_general_1").prop("checked", false);
        }
    });   
                          


      if (window.ibex_gantt_config.user_groups_self.resources_general == "2") // MANAGE
      {
        //console.log('Has permission to fully manage resources - general');
      }
      if (window.ibex_gantt_config.user_groups_self.resources_general == "1") // VIEW
      {
        //console.log('Has permission to view resources - general section');
        $("#collapseResourceEditorGeneral input").attr("disabled", true);
        $("#collapseResourceEditorGeneral select").attr("disabled", true);
        $("button.add-resource").addClass("antiquewhite").attr("disabled", true);
        $("button.delete-resource").addClass("antiquewhite").attr("disabled", true);
        $("button.save-resource").addClass("antiquewhite").attr("disabled", true);
      }
      if (window.ibex_gantt_config.user_groups_self.resources_general == "0") // NONE
      // Hide all references to resources, including the resources gantt and in the task editor
      {
        //console.log('No permission to view any resources');
		  
		    gantt.config.layout = {
          css: "gantt_container",
          rows: [{
              gravity: 2,
              id: "main-gantt", // Added by RB 02.12.18
              cols: [{
                  view: "grid",
                  group: "grids",
                  scrollY: "scrollVer",
                },
                {
                  resizer: true,
                  width: 1
                },
                {
                  view: "timeline",
                  scrollX: "scrollHor",
                  scrollY: "scrollVer"
                },
                {
                  view: "scrollbar",
                  id: "scrollVer",
                  group: "vertical"
                }
              ]
            },
            {
              resizer: false,
              width: 1,
              next: "scrollHor"
            },
				
            {
              view: "scrollbar",
              id: "scrollHor"
            }
          ]
		 };
		 gantt.init("gantt_here");
		 
          gantt.load("data.php");
			 
        var dp = new gantt.dataProcessor("data.php");
        dp.setTransactionMode("GET", true);
        dp.enableDebug(true);
        dp.init(gantt);
		  
		  setTimeout(function(){ 	  
		  gantt.eachTask(function(task){
    task.$open = true;
});
gantt.render();
}, 500);
		  
		  
        $("#resources-tab").remove();
        $("#resources").remove();
        $("#resources-header-toolbar").remove();
        $("#collapseTaskEditorResources").remove();
        $(".task-editor-resources-locked").show();
      }
      // PERMISSIONS - RESOURCES > AVAILABILIY
      if (window.ibex_gantt_config.user_groups_self.resources_availability == "2") // MANAGE
      {
      }
      if (window.ibex_gantt_config.user_groups_self.resources_availability == "1") // VIEW
      {
        $(".resources-locked").remove();
        $("#ResourceEditorAvailability select").attr("disabled", true);
        $("a.small.new-calendar").remove();
        $("div#task-editor-resources-section select").attr("disabled", true);
        $("li.select2-search.select2-search--inline").attr("disabled", true);
        $("#calendars-modal-add-resource-calendar").addClass("antiquewhite").attr("disabled", true);
        $("#modal_resource_calendar_editor input").addClass("antiquewhite").attr("disabled", true);
        $("#modal_resource_calendar_editor button").addClass("antiquewhite").attr("disabled", true);
      }
      if (window.ibex_gantt_config.user_groups_self.resources_availability == "0") // NONE
      {
        $("#card-header-availability-section").addClass("antiquewhite").attr("disabled", true);
        $("resources-locked").show().addClass("antiquewhite").attr("disabled", true);
        $("#collapseResourceEditorAvailability").remove();
        $("#header-resource-calendars").addClass("antiquewhite").attr("disabled", true);
      }

      // PERMISSIONS - RESOURCES > ALLOCATION
      if (window.ibex_gantt_config.user_groups_self.resources_allocation == "2") // MANAGE
      {
        //console.log('Has permission to fully manage resources - allocation section');
      }
      if (window.ibex_gantt_config.user_groups_self.resources_allocation == "1") // VIEW
      {
        //console.log('Has permission to view resources - allocation section');
        $(".resources-locked").remove();
        $("#ResourceEditorAllocation select").attr("disabled", true);
        $("li.select2-search.select2-search--inline").attr("disabled", true);
        $("task-editor-resource-items-allocated").attr("disabled", true);
      }
      if (window.ibex_gantt_config.user_groups_self.resources_allocation == "0") // NONE
      {
        //console.log('No permission to view resources - allocation section');
        $("#card-header-allocation-section").addClass("antiquewhite").attr("disabled", true);
        $(".resources-locked").show().addClass("antiquewhite").attr("disabled", true);
        $("#collapseResourceEditorAvailability").remove();
        $("div#task-editor-resources-section").remove();

      }

      // PERMISSIONS - RESOURCES > FINANCIAL
      if (window.ibex_gantt_config.user_groups_self.resources_financial == "2") // MANAGE
      {
        //console.log('Has permission to fully manage resources - financial');
      }
      if (window.ibex_gantt_config.user_groups_self.resources_financial == "1") // VIEW
      {
        //console.log('Has permission to view resources - financial');
        $("#ResourceEditorFinancial input").attr("disabled", true);
        $("#ResourceEditorFinancial select").attr("disabled", true);
        $("#task-editor-finances-section input").attr("disabled", true);
        $("#task-editor-finances-section select").attr("disabled", true);
        $("a.small.calc-task-financials").remove();
      }
      if (window.ibex_gantt_config.user_groups_self.resources_financial == "0") // NONE
      {
        $("#card-header-financial-section").addClass("antiquewhite").attr("disabled", true);
        $("#resource-cost-rate").remove();
        $(".edit-resource").css("height", "120px");
        $("div#task-editor-finances-section").remove();
      }

      // PERMISSIONS - RESOURCES > GROUPS
      if (window.ibex_gantt_config.user_groups_self.resources_groups == "2") // MANAGE
      {
      }
      if (window.ibex_gantt_config.user_groups_self.resources_groups == "1") // VIEW
      {
        //console.log('Has permission to view resources - groups');
        $("button#resources-tab-add-group").addClass("antiquewhite").attr("disabled", true);
        $("button.add-resource-group").addClass("antiquewhite").attr("disabled", true);
        $("button.delete-resource-group").addClass("antiquewhite").attr("disabled", true);
        $("#modal_resource_groups_editor input").attr("disabled", true);
        $("#modal_resource_groups_editor select").attr("disabled", true);
        $("#resources-tab-add-group").attr("disabled", true);
        $(".resources-locked").show();
        $("#collapseTaskEditorResources select").attr("disabled", true);
        $("a.small.new-resource-group").remove();
        $("a.small.new-resource-item").remove();
      }
      if (window.ibex_gantt_config.user_groups_self.resources_groups == "0") // NONE
      {
        //console.log('No permission to view resources - groups');
        $("#collapseResourceGroups").remove();
        $("#header-resource-groups").addClass("antiquewhite").attr("disabled", true);
        $(".resources-locked").show();
      }

      // PERMISSIONS - RESOURCES > CALENDARS
      if (window.ibex_gantt_config.user_groups_self.resources_calendars == "2") // MANAGE
      {
        //console.log('Has permission to fully manage resources - calendars');
        $("#ResourceEditorCalendarsLocked").remove();
      }
      if (window.ibex_gantt_config.user_groups_self.resources_calendars == "1") // VIEW
      {
        //console.log('Has permission to view resources - calendars');
        $("button#resources-tab-add-calendar").addClass("antiquewhite").attr("disabled", true);
        $("button#calendars-modal-add-calendar").addClass("antiquewhite").attr("disabled", true);
        $("button#add_calendar_override").addClass("antiquewhite").attr("disabled", true);
        $("button.close-calendars-modal").addClass("antiquewhite").attr("disabled", true);
        $("button#save-calendar").addClass("antiquewhite").attr("disabled", true);
        $("button.delete-calendar").addClass("antiquewhite").attr("disabled", true);
        $("#modal_calendar_editor input").attr("disabled", true);
        $("#modal_calendar_editor select").attr("disabled", true);
        $(".resources-locked").show();
      }
      if (window.ibex_gantt_config.user_groups_self.resources_calendars == "0") // NONE
      {
        //console.log('No permission to view resources - calendars');
        $("#ResourceEditorCalendarsLocked").show();
        $("#collapseResourceCalendars").remove();
        $(".calendar-locked").show();
      }

      // PERMISSIONS - VERSION CONTROL > ROLLBACK
      if (window.ibex_gantt_config.user_groups_self.version_control_rollback == "2") {
        //console.log('Has permission to fully manage rollbacks');
      }
      if (window.ibex_gantt_config.user_groups_self.version_control_rollback == "1") {
        //console.log('No permission to manage rollbacks');
        $(".activity-feed button").remove();
      }
      
      
      
      
      
      
      
      
      // PERMISSIONS - MESSAGING
      if (window.ibex_gantt_config.user_groups_self.collaboration_messaging == "2") // MANAGE
      {
        //console.log('Has permission to fully manage messages');
      }
      if (window.ibex_gantt_config.user_groups_self.collaboration_messaging == "0") // NONE
      {
        //console.log('No permission to view any messages');
        $("button#show-messages").remove();
      }
      
      
         
      
      // PERMISSIONS - COMMENTING
      if (window.ibex_gantt_config.user_groups_self.collaboration_commenting == "2") // MANAGE
      {
      }
      if (window.ibex_gantt_config.user_groups_self.collaboration_commenting == "1") // VIEW
      {
       $("div#card-comments").css("padding-top", "0");
       $("div#row-comments").remove();
      }
      if (window.ibex_gantt_config.user_groups_self.collaboration_commenting == "0") // NONE
      {
        $("div#task-editor-comments-section").remove();
        $("div#block-comments").remove();
      }
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      

      // PERMISSIONS - COLLABORATION > TEAM MEMBERS
      if (window.ibex_gantt_config.user_groups_self.collaboration_team_members == "2") {
        //console.log('Has permission to fully manage team members');
      }
      if (window.ibex_gantt_config.user_groups_self.collaboration_team_members == "0") {
        //console.log('No permission to manage team members');
        $("a#teams-tab").remove();
        $("div#teams").remove();
        $("div#modal_add_activity_comment").remove();
        $("div#modal_manage_team").remove();
        $("div#modal_invite_team").remove();
        $("div#modal_view_user_groups").remove();
        $("div#modal_permissions").remove();
      }
      
      
      // PERMISSIONS - FILES
      if (window.ibex_gantt_config.user_groups_self.collaboration_files == "2") // MANAGE
      {
      }
      if (window.ibex_gantt_config.user_groups_self.collaboration_files == "1") // VIEW
      {
        $("h4#no-files").text("No files yet");
        $("p#upload-files").html("Sorry <?=$_SESSION['user']['first_name']?>, you don't have permission to upload any files here.<br>You may view/download any files that your team members upload");
        $("p#shared-files").html("<?=$_SESSION['user']['first_name']?>, you may view/download any files here");
        $(".add-file-unique").remove();
        $("#modal_edit_file").remove();
        $(".add-file-to-task").remove();
        $(".remove-task-file").remove();
        $("#task_edit_files td span a").css("padding", "0");
      }
      if (window.ibex_gantt_config.user_groups_self.collaboration_files == "0") // NONE
      {
        //console.log('No permission to view any files');
        $("#files-tab").remove();
        $("#files-header-toolbar").remove();
        $(".add-file-to-task").remove();
        $("#task_edit_files").remove();
      }

      // PERMISSIONS - PROJECT DATA (SCHEDULING, RESOURCES, FILES, ACTIVITY)
      if (window.ibex_gantt_config.user_groups_self.version_control_project_data == "2") // MANAGE
      {
        //console.log('Has permission to manage all project data - scheduling, resources, files, activity');
      }
      if (window.ibex_gantt_config.user_groups_self.version_control_project_data == "1") // VIEW
      {
        //console.log('Has permission to view all project data - scheduling, resources, files, activity');



      }

      $.each(data.groups, function(index) {
        // Decode members
        var memberString = "";
        var members = data.groups[index].members;
        $.each(members, function(indexMember) {
          memberString += members[indexMember] += ", ";
        });
        memberString = memberString.substring(0, memberString.length - 2);
        $(".groups-container").append('<button data-id="' + data.groups[index].id + '"class="edit-group-permission" style="padding: 0; margin: 10px; width: 250px; border-radius: 50px !important;"><br><h5>' + data.groups[index].name + '</h5><p>' + memberString + '</p></button>').addClass('mx-auto');
      });
    });
  }
  loadUserGroups();

  $(document).on('click', '.save-permission-groups', function(e) {
    var groupArray = {};
    var count = 0;
    // Loop through each and send to save
    $(".check-permission-groups").each(function(index) {
      if ($(this).is(':checked')) {
        var index = $(this).data("value");
        var name = $(this).attr("id");
        var _this = $(this);
        var formattedName = name.substring(0, name.length - 2);
        groupArray[formattedName] = index;
        count++;
      };
    });

    $.getJSON("beta.ajax.php?action=update_group_permissions&id=" + $("#modal_permissions_group_id").val() + "&data=" + JSON.stringify(groupArray), function(data) {
      $("#modal_permissions").modal('hide');
    });
  });

  $(document).on('click', '.edit-group-permission', function(e) {
    $.getJSON("beta.ajax.php?action=get_group_permission&id=" + $(this).data("id"), function(data, value) {
      $(".modal-title-permissions").html('Permissions for <strong>' + data.group.name + '</strong>');
      $("#modal_permissions_group_id").val(data.group.id);
      // Loop through each row of permissions and get from group 
      $(".check-permission-groups").each(function(index) {
        var index = $(this).data("value");
        var name = $(this).attr("id");
        var _this = $(this);
        // Get name with no increment
        var formattedName = name.substring(0, name.length - 2);
        for (var key in data.group) {
          if (data.group.hasOwnProperty(key)) {
            var value = data.group[key];
            if (key == formattedName) {
              if (value == index) {
                _this.attr('checked', true);
              }
            }
          }
        }
      });
      $("#modal_permissions").modal('show');
    });
  });

  // Update siblings when check is checked
  $(document).on('change', '.check-permission-groups', function() {
    var indexHold = $(this).data("value");
    if (this.checked) {
      var siblings = $(this).parent().parent().find(".check-permission-groups");
      $.each(siblings, function(indexParse) {
        var jElm = $(siblings[indexParse]);
        if (jElm.data("value") != indexHold) {
          jElm.attr("checked", false);
        }
      });
    }
  });

  $(".save-access-permissions").click(function(e) {
    var myArray = {};
    $('#table_permissions_scheduling tbody tr').each(function(i, row) {
      var row = $(this);
      var index = row.data("index");
      var test2 = row.find('input[type="checkbox"]:checked');
      var enabled = false;
      if (row.find('input[type="checkbox"]').is(':checked')) {
        enabled = true;
      }
      myArray[index] = enabled;
    });
    var myJSON = JSON.stringify(myArray);
    $.getJSON("beta.ajax.php?action=save_columns&data=" + myJSON, function(data) {
      location.reload();
    });
  });

  $(".save-access-permissions").click(function(e) {
    var myArray = {};
    $('#table_permissions_resources tbody tr').each(function(i, row) {
      var row = $(this);
      var index = row.data("index");
      var test2 = row.find('input[type="checkbox"]:checked');
      var enabled = false;
      if (row.find('input[type="checkbox"]').is(':checked')) {
        enabled = true;
      }
      myArray[index] = enabled;
    });
    var myJSON = JSON.stringify(myArray);
    $.getJSON("beta.ajax.php?action=save_columns&data=" + myJSON, function(data) {
      location.reload();
    });
  });

  $(".save-access-permissions").click(function(e) {
    var myArray = {};
    $('#table_permissions_collaboration tbody tr').each(function(i, row) {
      var row = $(this);
      var index = row.data("index");
      var test2 = row.find('input[type="checkbox"]:checked');
      var enabled = false;
      if (row.find('input[type="checkbox"]').is(':checked')) {
        enabled = true;
      }
      myArray[index] = enabled;
    });
    var myJSON = JSON.stringify(myArray);
    $.getJSON("beta.ajax.php?action=save_columns&data=" + myJSON, function(data) {
      location.reload();
    });
  });

  $(".save-access-permissions").click(function(e) {
    var myArray = {};
    $('#table_permissions_version_control tbody tr').each(function(i, row) {
      var row = $(this);
      var index = row.data("index");
      var test2 = row.find('input[type="checkbox"]:checked');
      var enabled = false;
      if (row.find('input[type="checkbox"]').is(':checked')) {
        enabled = true;
      }
      myArray[index] = enabled;
    });
    var myJSON = JSON.stringify(myArray);
    $.getJSON("beta.ajax.php?action=save_columns&data=" + myJSON, function(data) {
      location.reload();
    });
  });
  
</script>