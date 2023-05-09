
$("#task_edit_resource_id").select2({
        closeOnSelect: false,
        placeholder: "",
        multiple: true
      });


$("#task_edit_resource_id").change(function() {
        var existingResources = window.ibex_gantt_config.activeTaskResources;
        var selectedResources = $('#task_edit_resource_id').val();
        var stringSelectedResources = selectedResources.join(",");
        window.ibex_gantt_config.activeTaskResources = stringSelectedResources;
        var task = gantt.getTask(window.ibex_gantt_config.activeTaskID);
        task.resource_id = window.ibex_gantt_config.activeTaskResources;
        //gantt.updateTask(task.id);
        updateTaskAttributes("1", window.ibex_gantt_config.activeTaskID);
      });


 $("#task_edit_resource_group_id").change(function() 
 {
        var task = gantt.getTask(window.ibex_gantt_config.activeTaskID);
        task.resource_group_id = $("#task_edit_resource_group_id").val();
		  
		  // Get resource group and pick up units. If different from workload unit, warn on UI
		  window.ibex_gantt_config.resource_groups.forEach(function(group) {
   
		  if (group.id == $("#task_edit_resource_group_id").val())
			 	{
					if (group.outputs_unit != $("#task_edit_workload_unit").val())
					{
						alert('Units do not match between workload and resource group');
					}
			 	}
 });
 
		  window.ibex_gantt_config.resource_groups.forEach(function (group, index) 
		  {
			  
  		
			});

		 
        updateTaskAttributes("1", window.ibex_gantt_config.activeTaskID);
      });
