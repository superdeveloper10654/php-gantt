$("#task_edit_budget_at_completion").val(task.budget_cost_completion).trigger("change");
$("#task_edit_actual_costs").val(task.actual_cost_completion).trigger("change");

window.calculateTaskActualFinances = function calculateTaskActualFinances() 

{
	
        // Do actuals
        var task = gantt.getTask(window.ibex_gantt_config.activeTaskID);
		  task.progress = $("#task_edit_progress").val();
		  
			 task.workload_quantity = $("#task_edit_workload_total_quantity").val();
			  
		 
	
		  //console.log(task);
        var taskResources = [];
        if (task.resource_id != "") {
          //taskResources = task.resource_id.split(',');
        }
		  //console.log(taskResources);
        var taskResourceGroupID = task.resource_group_id;
		  //console.log(window.ibex_gantt_config.resources);
        if (taskResourceGroupID == "0" || taskResourceGroupID == null || taskResourceGroupID == "") {} else {
          // We need to get all items in this resource group too and append to string above
          $.each(window.ibex_gantt_config.resources, function(i) {
            if (window.ibex_gantt_config.resources[i].group_id == taskResourceGroupID) {
              taskResources.push(window.ibex_gantt_config.resources[i].id);
            }
          });
        }
console.log('task res are ' + taskResources);

console.log('11');
        if (taskResources.length > 0) {
          var totalCost = 0;

          // We need to go through each resource of this task and check units involved
          for (var localResource of taskResources) 
			 {
				 console.log('workng on ' + localResource);
            var resourceObject = window.ibex_gantt_config.resources.filter(function(e) 
				{
			
              return e.id == localResource;
            });
				console.log('22 is ');
				console.log(resourceObject[0]);
            var resourceLocated = resourceObject[0];
				console.log('res loc is ' + resourceLocated);
				console.log(resourceLocated);
            // We need cost per minute
            var costPerMinute = 0;

            // no
            if (resourceLocated.unit_of_measure == "1") {
              totalCost = (totalCost + (resourceLocated.cost_rate * task.workload_quantity) * (task.progress / 100));
            }
            // item
            if (resourceLocated.unit_of_measure == "2") {
              totalCost = (totalCost + (resourceLocated.cost_rate * task.workload_quantity) * (task.progress / 100));
            }
            if (resourceLocated.unit_of_measure == "3") {
              // Time based measure - mins, easy
              costPerMinute = resourceLocated.cost_rate;
              totalCost = (totalCost + (costPerMinute * task.duration_worked) * (task.progress / 100));
            }
            if (resourceLocated.unit_of_measure == "4") {
              // Time based measure - hour - cascade down to mins
              costPerMinute = resourceLocated.cost_rate / 60;
              totalCost = (totalCost + (costPerMinute * task.duration_worked) * (task.progress / 100));
            }
            if (resourceLocated.unit_of_measure == "5") {
              // Time based measure - day										
              costPerMinute = resourceLocated.cost_rate / window.getMinutesInCalendarShift(task.calendar_id);
              totalCost = (totalCost + (costPerMinute * task.duration_worked) * (task.progress / 100));
            }
            if (resourceLocated.unit_of_measure == "6") {
              // Time based measure - week								
              costPerMinute = resourceLocated.cost_rate / (5 * window.getMinutesInCalendarShift(task.calendar_id));
              totalCost = (totalCost + (costPerMinute * task.duration_worked) * (task.progress / 100));
            }
            if (resourceLocated.unit_of_measure == "7") {
              // Time based measure - month
              costPerMinute = resourceLocated.cost_rate / (30 * window.getMinutesInCalendarShift(task.calendar_id));
              totalCost = (totalCost + (costPerMinute * task.duration_worked) * (task.progress / 100));
            }
            // Other ones that need converting

            //mm
            if (resourceLocated.unit_of_measure == "8" && task.workload_quantity != "" && task.workload_quantity != null) {
              // mm 
              if (task.workload_quantity_unit == "mm") {
                // Straight through
                totalCost = (totalCost + ((resourceLocated.cost_rate * task.workload_quantity) * (task.progress / 100)));
              }
              if (task.workload_quantity_unit == "m") {
                // Convert										
                totalCost = (totalCost + ((1000 * resourceLocated.cost_rate * task.workload_quantity) * (task.progress / 100)));
              }
              if (task.workload_quantity_unit == "km") {
                // Convert
                totalCost = (totalCost + ((1000000 * resourceLocated.cost_rate * task.workload_quantity) * (task.progress / 100)));
              }
            }
            //m
            if (resourceLocated.unit_of_measure == "9" && task.workload_quantity != "" && task.workload_quantity != null) {
              // mm 
              if (task.workload_quantity_unit == "mm") {

                // Convert
                totalCost = (totalCost + ((resourceLocated.cost_rate * task.workload_quantity) / 1000) * (task.progress / 100));
              }
              if (task.workload_quantity_unit == "m") {

                // Straight through
                totalCost = (totalCost + ((resourceLocated.cost_rate * task.workload_quantity) * (task.progress / 100)));
              }
              if (task.workload_quantity_unit == "km") {

                // Convert
                totalCost = (totalCost + ((1000 * resourceLocated.cost_rate * task.workload_quantity) * (task.progress / 100)));
              }
            }
            // km
            if (resourceLocated.unit_of_measure == "10" && task.workload_quantity != "" && task.workload_quantity != null) {
              // mm 
              if (task.workload_quantity_unit == "mm") {

                // Convert
                totalCost = (totalCost + (((resourceLocated.cost_rate * task.workload_quantity) / 1000000) * (task.progress / 100)));
              }
              if (task.workload_quantity_unit == "m") {

                // Straight through
                totalCost = (totalCost + (((resourceLocated.cost_rate * task.workload_quantity) / 1000) * (task.progress / 100)));
              }
              if (task.workload_quantity_unit == "km") {
                // Convert										
                // Straight through
                totalCost = (totalCost + ((resourceLocated.cost_rate * task.workload_quantity) * (task.progress / 100)));
              }
            }
            // m2
            if (resourceLocated.unit_of_measure == "11" && task.workload_quantity != "" && task.workload_quantity != null) {
              if (task.workload_quantity_unit == "m2") {
                // Straight through

                totalCost = (totalCost + ((resourceLocated.cost_rate * task.workload_quantity) * (task.progress / 100)));
              }
              if (task.workload_quantity_unit == "km2") {


                totalCost = (totalCost + (((resourceLocated.cost_rate * task.workload_quantity) * 1000000) * (task.progress / 100)));
              }
            }
            // km2
            if (resourceLocated.unit_of_measure == "12" && task.workload_quantity != "" && task.workload_quantity != null) {
              if (task.workload_quantity_unit == "m2") {
                totalCost = (totalCost + (((resourceLocated.cost_rate * task.workload_quantity) / 1000000) * (task.progress / 100)));

              }
              if (task.workload_quantity_unit == "km2") {
                totalCost = (totalCost + ((resourceLocated.cost_rate * task.workload_quantity) * (task.progress / 100)));
              }
            }
            // kg
            if (resourceLocated.unit_of_measure == "13" && task.workload_quantity != "" && task.workload_quantity != null) {
              if (task.workload_quantity_unit == "kg") {
                totalCost = (totalCost + (resourceLocated.cost_rate * task.workload_quantity) * (task.progress / 100));
              }
              if (task.workload_quantity_unit == "t") {
                totalCost = (totalCost + ((resourceLocated.cost_rate * task.workload_quantity) * 1000) * (task.progress / 100));
              }
            }
            // t
            if (resourceLocated.unit_of_measure == "14" && task.workload_quantity != "" && task.workload_quantity != null) {
              if (task.workload_quantity_unit == "kg") {
                totalCost = (totalCost + (((resourceLocated.cost_rate * task.workload_quantity) / 1000) * (task.progress / 100)));
              }
              if (task.workload_quantity_unit == "t") {

                totalCost = (totalCost + (resourceLocated.cost_rate * task.workload_quantity) * (task.progress / 100));
              }
            }
            // m3
            if (resourceLocated.unit_of_measure == "15" && task.workload_quantity != "" && task.workload_quantity != null) {
              totalCost = (totalCost + (resourceLocated.cost_rate * task.workload_quantity) * (task.progress / 100));
            }
				
            // l
            if (resourceLocated.unit_of_measure == "16" && task.workload_quantity != "" && task.workload_quantity != null) {
              totalCost = (totalCost + ((resourceLocated.cost_rate * task.workload_quantity)) * (task.progress / 100));
            }

            
          }
        }
		  $("#task_edit_actual_costs").val(totalCost);
      }
		
		
      window.calculateTaskBudgetFinances = function calculateTaskBudgetFinances() {
		console.log('abc');
        var task = gantt.getTask(window.ibex_gantt_config.activeTaskID);
        var taskResources = [];
        if (task.resource_id != "") {
         // taskResources = task.resource_id.split(',');
        }
		   task.progress = $("#task_edit_progress").val();
			
			  task.workload_quantity = $("#task_edit_workload_total_quantity").val();
			 
		 
         var taskResourceGroupID = task.resource_group_id;
		  //console.log(window.ibex_gantt_config.resources);
        if (taskResourceGroupID == "0" || taskResourceGroupID == null || taskResourceGroupID == "") {} else {
          // We need to get all items in this resource group too and append to string above
          $.each(window.ibex_gantt_config.resources, function(i) {
            if (window.ibex_gantt_config.resources[i].group_id == taskResourceGroupID) {
              taskResources.push(window.ibex_gantt_config.resources[i].id);
            }
          });
        }
console.log('task res are ' + taskResources);

console.log('11');
if (taskResources.length > 0) {
          var totalCost = 0;

          // We need to go through each resource of this task and check units involved
          for (var localResource of taskResources) {
            var resourceObject = window.ibex_gantt_config.resources.filter(function(e) {
              return e.id == localResource;
            });
				console.log('working on res2 ' + resourceObject[0]);
            var resourceLocated = resourceObject[0];
            // We need cost per minute
            var costPerMinute = 0;

            // no
            if (resourceLocated.unit_of_measure == "1") {
              totalCost = totalCost + (resourceLocated.cost_rate * task.workload_quantity);
            }
            // item
            if (resourceLocated.unit_of_measure == "2") {
              totalCost = totalCost + (resourceLocated.cost_rate * task.workload_quantity);
            }
            if (resourceLocated.unit_of_measure == "3") {
              // Time based measure - mins, easy
              costPerMinute = resourceLocated.cost_rate;
              totalCost = totalCost + (costPerMinute * task.duration_worked);
            }
            if (resourceLocated.unit_of_measure == "4") {
              // Time based measure - hour - cascade down to mins
              costPerMinute = resourceLocated.cost_rate / 60;
              totalCost = totalCost + (costPerMinute * task.duration_worked);
            }
            if (resourceLocated.unit_of_measure == "5") {
              // Time based measure - day										
              costPerMinute = resourceLocated.cost_rate / window.getMinutesInCalendarShift(task.calendar_id);
              totalCost = totalCost + (costPerMinute * task.duration_worked);
            }
            if (resourceLocated.unit_of_measure == "6") {
              // Time based measure - week						
              costPerMinute = resourceLocated.cost_rate / (5 * window.getMinutesInCalendarShift(task.calendar_id));
              totalCost = totalCost + (costPerMinute * task.duration_worked);
            }
            if (resourceLocated.unit_of_measure == "7") {
              // Time based measure - month									
              costPerMinute = resourceLocated.cost_rate / (30 * window.getMinutesInCalendarShift(task.calendar_id));
              totalCost = totalCost + (costPerMinute * task.duration_worked);
            }
            // Other ones that need converting

            //mm
            if (resourceLocated.unit_of_measure == "8" && task.workload_quantity != "" && task.workload_quantity != null) {
              // mm 
              if (task.workload_quantity_unit == "mm") {
                // Straight through
                totalCost = totalCost +  (resourceLocated.cost_rate * task.workload_quantity);
              }
              if (task.workload_quantity_unit == "m") {
                // Convert										
                totalCost = totalCost + (1000 * resourceLocated.cost_rate * task.workload_quantity);
              }
              if (task.workload_quantity_unit == "km") {
                // Convert
                totalCost = totalCost + (1000000 * resourceLocated.cost_rate * task.workload_quantity);
              }
            }
            //m
            if (resourceLocated.unit_of_measure == "9" && task.workload_quantity != "" && task.workload_quantity != null) {
              // mm 
              if (task.workload_quantity_unit == "mm") {

                totalCost = totalCost + (resourceLocated.cost_rate * task.workload_quantity) / 1000;
              }
              if (task.workload_quantity_unit == "m") {

                totalCost = totalCost + (resourceLocated.cost_rate * task.workload_quantity);
              }
              if (task.workload_quantity_unit == "km") {

                totalCost = totalCost + (1000 * resourceLocated.cost_rate * task.workload_quantity);
              }
            }
            // km
            if (resourceLocated.unit_of_measure == "10" && task.workload_quantity != "" && task.workload_quantity != null) {
              // mm 
              if (task.workload_quantity_unit == "mm") {

                totalCost = totalCost + ((resourceLocated.cost_rate * task.workload_quantity) / 1000000);
              }
              if (task.workload_quantity_unit == "m") {

                totalCost = totalCost + (resourceLocated.cost_rate * task.workload_quantity) / 1000;
              }
              if (task.workload_quantity_unit == "km") {

                totalCost = totalCost + (resourceLocated.cost_rate * task.workload_quantity);
              }
            }
            // m2
            if (resourceLocated.unit_of_measure == "11" && task.workload_quantity != "" && task.workload_quantity != null) {
              if (task.workload_quantity_unit == "m2") {

                totalCost = totalCost + (resourceLocated.cost_rate * task.workload_quantity);
              }
              if (task.workload_quantity_unit == "km2") {

                totalCost = totalCost + (resourceLocated.cost_rate * task.workload_quantity) * 1000000;
              }
            }
            // km2
            if (resourceLocated.unit_of_measure == "12" && task.workload_quantity != "" && task.workload_quantity != null) {
              if (task.workload_quantity_unit == "m2") {
                totalCost = totalCost + ((resourceLocated.cost_rate * task.workload_quantity) / 1000000);
              }
              if (task.workload_quantity_unit == "km2") {
                totalCost = totalCost + (resourceLocated.cost_rate * task.workload_quantity);
              }
            }
            // kg
            if (resourceLocated.unit_of_measure == "13" && task.workload_quantity != "" && task.workload_quantity != null) {
              if (task.workload_quantity_unit == "kg") {
                totalCost = totalCost + (resourceLocated.cost_rate * task.workload_quantity);
              }
              if (task.workload_quantity_unit == "t") {
                totalCost = totalCost + (resourceLocated.cost_rate * task.workload_quantity) * 1000;
              }
            }
            // t
            if (resourceLocated.unit_of_measure == "14" && task.workload_quantity != "" && task.workload_quantity != null) {
              if (task.workload_quantity_unit == "kg") {
                totalCost = totalCost + (resourceLocated.cost_rate * task.workload_quantity) / 1000;
              }
              if (task.workload_quantity_unit == "t") {
                totalCost = totalCost + (resourceLocated.cost_rate * task.workload_quantity);
              }
            }
            // m3
            if (resourceLocated.unit_of_measure == "15" && task.workload_quantity != "" && task.workload_quantity != null) {
              totalCost = totalCost + (resourceLocated.cost_rate * task.workload_quantity);
            }
            // l
            if (resourceLocated.unit_of_measure == "16" && task.workload_quantity != "" && task.workload_quantity != null) {
              totalCost = totalCost + (resourceLocated.cost_rate * task.workload_quantity);
            }
          }

          
        }
		  $("#task_edit_budget_at_completion").val(totalCost);
      }

      $(".calc-task-financials").click(function() {
        // Do actual calcs								 
        calculateTaskActualFinances();
      });

if (task.actual_costs > task.budget_at_completion && task.progress == "100") {
          $("#task-editor-status-over-budget").show();
          $("#card-header-finances-section").css('background', '#fce8e6 !important');
        }


$("#task_edit_price").val();