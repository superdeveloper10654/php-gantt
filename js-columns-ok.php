
  function prepareColumnInsert(columnName) {
    var object;
    var columnWidth = Number(window.ibex_gantt_config.columns[columnName]);
    if (columnName == "wbs") {
      object = {
        name: "wbs",
        align: "left",
        width: columnWidth,
        resize: true,
        template: function(task) {
          return '<div class="drag-handle"><img src=""> ' + gantt.getWBSCode(gantt.getTask(task.id)) + '</div>';
        }
      };
    }
    if (columnName == "text") {
      object = {
        name: "text",
        align: "left",
        tree: true,
        width: columnWidth,
        resize: true
      };
    }
    if (columnName == "status") {
	 

      object = {
        name: "status",
        align: "left",
        width: columnWidth,
        resize: true,
        label: 'Status',
        template: function(task) {
		  	 
		  if (moment(task.baseline_start, 'YYYY-MM-DD HH:mm:ss', true).isValid())
		 {
			 
		 } 
		 else
		 {
			 task.baseline_start = "2999-12-31 23:59:59";
		 }
		  if (moment(task.baseline_end, 'YYYY-MM-DD HH:mm:ss', true).isValid())
		 {
			 
		 } 
		 else
		 {
			 task.baseline_end= "2999-12-31 23:59:59";
		 }
		  if (moment(task.deadline, 'YYYY-MM-DD HH:mm:ss', true).isValid())
		 {
			 
		 } 
		 else
		 {
			 task.deadline= "2999-12-31 23:59:59";
		 }
		

			 
          if (task.type != "task" || task.is_summary == "1") {
            return "";
          }
          var dateNow = moment().startOf('day').format("YYYY-MM-DD HH:mm");
          if (task.progress == "100" && task.manually_delayed == "0" && task.actual_costs > task.budget_at_completion) {
			 
            return "<img src='img/svg/pound-sign-over-budget.svg' title='Over Budget'>";
          }
			 else
			 
          if (task.manually_delayed == "1") {
			 
            return "<img src='img/svg/delayed.svg' title='Delayed'>";
          }
			 else if (moment(task.baseline_start, 'YYYY-MM-DD HH:mm:ss', true).isValid() && moment(task.baseline_end, 'YYYY-MM-DD HH:mm:ss', true).isValid())
				 {
								 if (moment(task.start_date).isAfter(moment(task.baseline_start)) && moment(task.end_date).isAfter(moment(task.baseline_end) && task.progress != "0")) {
						 
			            return "<img src='img/svg/delayed.svg' title='Delayed'>";
						
			
				 }
				 else
				 {
					  	return "???";
				 }
			 }
			 else if (moment(task.baseline_start, 'YYYY-MM-DD HH:mm:ss', true).isValid())
				  {
			 
		          if (moment(task.baseline_start).isAfter(moment(task.start_date)) && task.manually_delayed == "0" && task.progress != "0") 
					 {
					 
		            return "<img src='img/svg/accelerated.svg' title='Accelerated'>";
		          }
					else
					{
							return "???";
					}
				}
		 
			 else if (moment(task.start_date).isSame(moment(task.baseline_start)) && moment(task.end_date).isAfter(moment(task.baseline_end)) && task.progress != "0") {
			 
            return "<img src='img/svg/prolonged.svg' title='Prolonged'>";
          }
			 else
          if (moment(task.start_date).isSame(moment(task.baseline_start)) && moment(task.baseline_end).isAfter(moment(task.end_date)) && task.progress != "0") {
			 

            return "<img src='img/svg/expediated.svg' title='Expediated'>";
          }
			 else
			 
          if (moment(task.start_date).isAfter(moment(task.baseline_start)) && moment(task.baseline_end).isSame(moment(task.end_date)) && task.progress != "0") {
			 
	
            return "<img src='img/svg/expediated.svg' title='Expediated'>";
          }
			 else
          if (task.manually_delayed == "0" && task.progress == "0") {
			 
            return "<img src='img/svg/pending.svg' title='Pending'>";
          }
			 else 
          if (moment(task.start_date).isSame(moment(task.baseline_start)) && moment(task.baseline_end).isSame(moment(task.end_date)) && task.manually_delayed == "0" && task.progress != "0") {
            return "<img src='img/svg/in-progress.svg' title='In Progress'>";
          }
       else 
          if (moment(task.start_date).isBefore(moment(dateNow)) && task.progress != "0" && task.progress != "100") {
            return "<img src='img/svg/in-progress.svg' title='In Progress'>";
          }
			 else 
          if (task.progress == "100") {
            return "<img src='img/svg/check-complete.svg' title='Complete'>";
          }
			 else
			 {
				 // DEFAULT BLOCK FOR TASKS THAT DON'T MEET CONDITIONS ABOVE
				 
		return "???";
			 }
        }
      }
    }
    if (columnName == "start_date") {
      object = {
        name: "start_date",
        align: "left",
        width: columnWidth,
        resize: true,
        label: 'Start',
        template: function(task) {
          var date = moment(task.start_date, 'YYYY-MM-DD HH:mm:ss', true);
          if (date.isValid() == true) {
            return moment(task.start_date).format("ddd D MMM (HH:mm)");
          } else {
            return "";
          }
        }
      };
    }
    if (columnName == "end_date") {
      object = {
        name: "end_date",
        align: "left",
        width: columnWidth,
        resize: true,
        label: 'Finish',
        template: function(task) {
          var date = moment(task.end_date, 'YYYY-MM-DD HH:mm:ss', true);
          if (date.isValid() == true) {
            return moment(task.end_date).format("ddd D MMM (HH:mm)");
          } else {
            return "";
          }
        }
      };
    }
    if (columnName == "baseline_start") {
      object = {
        name: "baseline_start",
        align: "left",
        width: columnWidth,
        resize: true,
        label: 'Baseline start',
        template: function(task) {
          var date = moment(task.baseline_start, 'YYYY-MM-DD HH:mm:ss', true);
          if (date.isValid() == true) {
            return moment(task.baseline_start).format("ddd D MMM (HH:mm)");
          } else {
            return "";
          }
        }
      };
    }
    if (columnName == "baseline_end") {
      object = {
        name: "baseline_end",
        align: "left",
        width: columnWidth,
        resize: true,
        label: 'Baseline end',
        template: function(task) {
          var date = moment(task.baseline_end, 'YYYY-MM-DD HH:mm:ss', true);
          if (date.isValid() == true) {
            return moment(task.baseline_end).format("ddd D MMM (HH:mm)");
          } else {
            return "";
          }
        }
      };
    }
    if (columnName == "deadline") {
      object = {
        name: "deadline",
        align: "left",
        width: columnWidth,
        resize: true,
        label: 'Deadline',
        template: function(task) {
          var date = moment(task.deadline, 'YYYY-MM-DD HH:mm:ss', true);
          if (date.isValid() == true) {
            return moment(task.deadline).format("ddd D MMM (HH:mm)");
          } else {
            return "";
          }
          if (task.type == "milestone") {
            return "";
          }
        }
      };
    }
    if (columnName == "constraint_type") {
      object = {
        name: "constraint_type",
        align: "left",
        width: columnWidth,
        resize: true,
        label: 'Constraint type',
        template: function(task) {
          return task.constraint_type;
        }
      };
    }
    if (columnName == "constraint_date") {
      object = {
        name: "constraint_date",
        align: "left",
        width: columnWidth,
        resize: true,
        label: 'Constraint date',
        template: function(task) {
          var date = moment(task.constraint_date, 'YYYY-MM-DD HH:mm:ss', true);
          if (date.isValid() == true) {
            return moment(task.constraint_date).format("ddd D MMM (HH:mm)");
          } else {
            return "";
          }
        }
      };
    }
    if (columnName == "resource_id") {
      object = {
        name: "resource_id",
        align: "left",
        width: columnWidth,
        resize: true,
        label: 'Resources',
        template: function(task) {
          if (task.type == gantt.config.types.project) {
            return "";
          }
          if (task.resource_id == null) {
            return "";
          }
          if (task.resource_id == "") {
            return "";
          }
          if (task.resource_id == "NULL") {
            return "";
          }
          if (task.resource_id == "undefined") {
            return "";
          }
          if (task.resource_id.indexOf(",") == -1) {
            // Just one
            var name;
            $.each(window.ibex_gantt_config.resources, function(i) {
              if (window.ibex_gantt_config.resources[i].id == task.resource_id) {
                name = window.ibex_gantt_config.resources[i].name;
              }
            });
          } else {
            // Multiple
            // Get first
            var resources = task.resource_id.split(',');
            var totalResources = Number(resources.length);
            var firstResource = resources[0];
            var endString = " (+" + Number(totalResources - 1) + " more)";
            $.each(window.ibex_gantt_config.resources, function(i) {
              if (window.ibex_gantt_config.resources[i].id == firstResource) {
                name = window.ibex_gantt_config.resources[i].name + endString;
              }
            });
          }
          return name;
        }
      };
    }
    if (columnName == "progress") {
      object = {
        name: "progress",
        align: "left",
        width: columnWidth,
        resize: true,
        label: 'Progress',
        template: function(task) {
          return Number(task.progress) + "%";
        }
      };
    }
    if (columnName == "duration_worked") {
      object = {
        name: "duration_worked",
        align: "left",
        width: columnWidth,
        resize: true,
        label: 'Duration',
        template: function(task) {
          if (window.ibex_gantt_config.periodDescriptor == "1") {
            // Hours and mins
            if (task.type != "milestone") {
              var mins = padLeadingZero(Math.floor(task.duration_worked % 60));
              if (mins == "00") {
                return padLeadingZero(Math.floor(task.duration_worked / 60)) + " hours";
              } else {
                return padLeadingZero(Math.floor(task.duration_worked / 60)) + "h" + padLeadingZero(Math.floor(task.duration_worked % 60) + "m");
              }
            }
            if (task.type == "milestone") {
              return "";
            }
          } else {
            if (task.timing_overriden == "1") {
              var mins = padLeadingZero(Math.floor(task.duration_worked % 60));
              if (mins == "00") {
                return padLeadingZero(Math.floor(task.duration_worked / 60)) + " hours";
              } else {
                return padLeadingZero(Math.floor(task.duration_worked / 60)) + "h" + padLeadingZero(Math.floor(task.duration_worked % 60) + "m");
              }

            }
            var descriptorSingular = "",
              descriptorPlural = "";
            if (window.ibex_gantt_config.periodDescriptor == "2") {
              descriptorSingular = "day";
              descriptorPlural = "days";
            }
            if (window.ibex_gantt_config.periodDescriptor == "3") {
              descriptorSingular = "night";
              descriptorPlural = "nights";
            }
            if (window.ibex_gantt_config.periodDescriptor == "4") {
              descriptorSingular = "shift";
              descriptorPlural = "shifts";
            }
            if (window.ibex_gantt_config.periodDescriptor == "5") {
              descriptorSingular = window.ibex_gantt_config.periodDescriptorTextSingular;
              descriptorPlural = window.ibex_gantt_config.periodDescriptorTextPlural;
            }
            if (task.type != "milestone") {
              var mins = convertMinutesToPeriod(task.duration_worked, task.id);
              if (mins == 1) {
                return mins + " " + descriptorSingular;
              } else {
                return mins + " " + descriptorPlural;
              }
            }
            if (task.type == "milestone") {
              return "";
            }
          }
        }
      };
    }
    if (columnName == "task_calendar") {
      object = {
        name: "calendar_id",
        align: "left",
        width: columnWidth,
        label: "Calendar",
        template: function(task) {
          var calendarName;
          $.each(window.ibex_gantt_config.calendars, function(index) {
            if (window.ibex_gantt_config.calendars[index].id == task.calendar_id) {
              calendarName = window.ibex_gantt_config.calendars[index].name;
            }
          });
          return calendarName;
        }
      }
    }
    return object;
  }

  function prepareResourceColumnInsert(columnName) {

    var object;
    var columnWidth = 50;
    if (columnName == "name") {
      object = {
        name: "name",
        label: "Name",
        align: "left",
        width: columnWidth,
        resize: true,
        template: function(resource) {
          return resource.text;
        },
      };
    }
    if (columnName == "company") {
      object = {
        name: "company",
        label: "Company",
        align: "left",
        width: columnWidth,
        resize: true,
        template: function(resource) {
          if (resource.is_group == "1") {
            return "";
          }
          if (resource.company == "null" || resource.company == null) {
            return "-";
          }
          return resource.company;
        },
      };
    }
    if (columnName == "notes") {
      object = {
        name: "notes",
        label: "Notes",
        align: "left",
        width: columnWidth,
        resize: true,
        template: function(resource) {
          if (resource.is_group == "1") {
            return "";
          }
          return resource.notes;
        },
      };
    }
    if (columnName == "cost_rate") {
      object = {
        name: "cost_rate",
        label: "Cost Rate",
        align: "left",
        width: columnWidth,
        resize: true,
        template: function(resource) {
          if (resource.is_group == "1") {
            return "";
          }
          var unit_of_measure = "";
          // Quantity
          if (resource.unit_of_measure == "1") {
            unit_of_measure = "/no";
          }
          if (resource.unit_of_measure == "2") {
            unit_of_measure = "/item";
          }
          // Time
          if (resource.unit_of_measure == "3") {
            unit_of_measure = "/min";
          }
          if (resource.unit_of_measure == "4") {
            unit_of_measure = "/hr";
          }
          if (resource.unit_of_measure == "5") {
            unit_of_measure = "/day";
          }
          if (resource.unit_of_measure == "6") {
            unit_of_measure = "/wk";
          }
          if (resource.unit_of_measure == "7") {
            unit_of_measure = "/mo";
          }
          // linear
          if (resource.unit_of_measure == "8") {
            unit_of_measure = "/mm";
          }
          if (resource.unit_of_measure == "9") {
            unit_of_measure = "/m";
          }
          if (resource.unit_of_measure == "10") {
            unit_of_measure = "/km";
          }
          // Area
          if (resource.unit_of_measure == "11") {
            unit_of_measure = "/m2";
          }
          if (resource.unit_of_measure == "12") {
            unit_of_measure = "/km2";
          }
          // Weight
          if (resource.unit_of_measure == "13") {
            unit_of_measure = "/kg";
          }
          if (resource.unit_of_measure == "14") {
            unit_of_measure = "/t";
          }
          // volume
          if (resource.unit_of_measure == "15") {
            unit_of_measure = "/m3";
          }
          if (resource.unit_of_measure == "16") {
            unit_of_measure = "/l";
          }
          return "£" + resource.cost_rate + unit_of_measure;
        },
      };
    }
    if (columnName == "resource_calendar") {
      object = {
        name: "calendar",
        label: "Calendar",
        align: "left",
        tree: true,
        width: columnWidth,
        resize: true,
        template: function(resource) {
          if (resource.is_group == "1") {
            return "";
          }
          if (resource.calendar_id == "0" || resource.calendar_id == 0 || resource.calendar_id == "null" || resource.calendar_id == null) {
            return "[None]";
          }
          var calendarName;
          for (var calendar of window.ibex_gantt_config.calendars) {
            if (calendar['id'] == resource.calendar_id) {
              calendarName = calendar['name'];
              break;
            }
          }
          return calendarName;
        },
      };
    }
    return object;
  }

  gantt.attachEvent("onColumnResizeEnd", function(index, column, new_width) {
    $.getJSON("beta.ajax.php?action=update_column_width&programme_id=" + $("#programme_id").val() + "&column_name=" + column.name + "&new_width=" + new_width, function(data) {
    });
    // updateResources();
    return true;
  });

  window.ibex_gantt_config.resourceConfig = {
    columns: window.ibex_gantt_config.resourceColumnArray
	 
  };
  
  $(".save-task-columns").click(function(e) {
    var myArray = {};
    $('#table_task_columns tbody tr').each(function(i, row) {
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

  $(".edit-task-columns").click(function(e) {
    $("#table_task_columns tbody").html('');
    var columns = JSON.parse(window.ibex_gantt_config.columns.task_columns);
    for (var i = 0; i < columns.length; i++) {
      var obj = columns[i];
      for (var key in obj) {
        var attrName = key;
        var attrValue = obj[key];
        var attrNameUI;
        if (attrName == "wbs") {
          attrNameUI = "WBS";
        }
        if (attrName == "text") {
          attrNameUI = "Name";
        }
        if (attrName == "start_date") {
          attrNameUI = "Start date";
        }
        if (attrName == "end_date") {
          attrNameUI = "Finish date";
        }
        if (attrName == "progress") {
          attrNameUI = "Progress";
        }
        if (attrName == "duration_worked") {
          attrNameUI = "Duration";
        }
        if (attrName == "baseline_start") {
          attrNameUI = "Baseline start date";
        }
        if (attrName == "baseline_end") {
          attrNameUI = "Baseline finish date";
        }
        if (attrName == "task_calendar") {
          attrNameUI = "Calendar";
        }
        if (attrName == "deadline") {
          attrNameUI = "Deadline";
        }
        if (attrName == "constraint_type") {
          attrNameUI = "Constraint type";
        }
        if (attrName == "constraint_date") {
          attrNameUI = "Constraint date";
        }
        if (attrName == "resource_id") {
          attrNameUI = "Resources";
        }
        if (attrName == "status") {
          attrNameUI = "Status";
        }
        var checked = "";
        if (attrValue == true) {
          checked = "checked";
        }
        $("#table_task_columns tbody").append('<tr data-index="' + attrName + '" draggable="true"><td><span>' + attrNameUI + '</span></td><td><input class="form-check-input " type="checkbox" ' + checked + ' id="task_column_' + attrName + '"><label class="form-check-label" for="task_column_' + attrName + '"></label></td></tr>');
      }
    }
    $("#modal_edit_task_columns").modal('show');
  });

  $(".save-resource-columns").click(function(e) {
    var myArray = {};
    $('#table_resource_columns tbody tr').each(function(i, row) {
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
    $.getJSON("beta.ajax.php?action=save_columns_resources&data=" + myJSON, function(data) {
      location.reload();
    });
  });

  $(".edit-resource-columns").click(function(e) {
    $("#table_resource_columns tbody").html('');
    var columns = JSON.parse(window.ibex_gantt_config.columns.resource_columns);
    for (var i = 0; i < columns.length; i++) {
      var obj = columns[i];
      for (var key in obj) {
        var attrName = key;
        var attrValue = obj[key];
        var attrNameUI;
        if (attrName == "name") {
          attrNameUI = "Name";
        }
        if (attrName == "resource_calendar") {
          attrNameUI = "Calendar";
        }
        if (attrName == "company") {
          attrNameUI = "Company";
        }
        if (attrName == "notes") {
          attrNameUI = "Notes";
        }
        if (attrName == "cost_rate") {
          attrNameUI = "Cost Rate";
        }
        var checked = "";
        if (attrValue == true) {
          checked = "checked";
        }
        $("#table_resource_columns tbody").append('<tr data-index="' + attrName + '" draggable="true"><td><span>' + attrNameUI + '</span></td><td><input class="form-check-input " type="checkbox" ' + checked + ' id="resource_column_' + attrName + '"><label class="form-check-label" for="resource_column_' + attrName + '"></label></td></tr>');
      }
    }
    $("#modal_edit_resource_columns").modal('show');
  });
  
  
  
  
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
              resizer: true,
              width: 1,
              next: "resources-gantt"
            },
            {
              gravity: 1,
              id: "resources-gantt",
              config: window.ibex_gantt_config.resourceConfig,
              templates: resourceTemplates,
              cols: [{
                  view: "resourceGrid",
                  group: "grids",
                  scrollY: "resourceVScroll"
                },
                {
                  resizer: true,
                  width: 1
                },
                {
                  view: "resourceTimeline",
                  scrollX: "scrollHor",
                  scrollY: "resourceVScroll"
                },
                {
                  view: "scrollbar",
                  id: "resourceVScroll",
                  group: "vertical"
                }
              ]
            },
            {
              view: "scrollbar",
              id: "scrollHor"
            }
          ]
        };
		  
		  
		  
 
