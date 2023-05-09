  $('#resource_edit_parent').on('change', function() {
    $('.mdb-select').material_select('destroy');
    // If calendar is not yet set, set it to parent value
    var _this = this;
    for (var group of window.ibex_gantt_config.resource_groups) {
      if (group.id == _this.value) {
        $("#resource_edit_calendar_id").val(group.calendar_id).trigger("change");
        break;
      }
    }
    $('.mdb-select').material_select();
  });

  $(document).on('click', '.save-resource', function(e) {
    if ($("#resource_edit_name").val() != "" && $("#resource_edit_parent").val() != "0" && $("#resource_edit_cost_rate").val() != "") {
      if ($("#resource_edit_id").val() == "0") {
        $.getJSON("beta.ajax.php?action=add_resource&name=" + $("#resource_edit_name").val() + "&parent=" + $("#resource_edit_parent").val() + "&notes=" + $("#resource_edit_notes").val() + "&cost_rate=" + $("#resource_edit_cost_rate").val() + "&company=" + $("#resource_edit_company").val() + "&calendar_id=" + $("#resource_edit_calendar_id").val() + "&unit_of_measure=" + $("#resource_edit_unit_of_measure").val() + "&guid=" + $("#resource_edit_guid").val(), function(data) {
          window.ibex_gantt_config.resources = data.resources;
          window.ibex_gantt_config.resource_groups = data.resource_groups;
          $("#table_resources").empty();
          if (data.resources.length == 0) {
            $('.no-resources').show();
            $('#resources-header-toolbar').hide();
          } else {
            $(".no-resources").hide();
            $('#resources-header-toolbar').show();
          }
          for (var resource of data.resources) {
            var parentGroupName;
            for (var resourceGroup of data.resource_groups) {
              if (resource.group_id == resourceGroup.id) {
                parentGroupName = resourceGroup.name;
                break;
              }
            }
            if (resource.company == "null" || resource.company == null) {
              resource.company = "-";
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
            $('#table_resources').append("<button class='edit-resource' data-index='" + resource.id + "'>" + "<div class='resource-img'></div><div class='resource-text'><h5 class='resource-name'>" + resource.name + "</h5><div class='resource-group'>" + parentGroupName + "</div><div class='resource-notes'>" + resource.notes + "</div><div class='resource-company'>" + resource.company + "</div><div id='resource-cost-rate'>£" + resource.cost_rate + unit_of_measure + "</div></div></button>");
          }
          $("#modal_resource_editor").modal('hide');
          gantt.render();
          loadResources();
        });
      } else {
        $.getJSON("beta.ajax.php?action=update_resource&id=" + $("#resource_edit_id").val() + "&name=" + $("#resource_edit_name").val() + "&parent=" + $("#resource_edit_parent").val() + "&notes=" + $("#resource_edit_notes").val() + "&cost_rate=" + $("#resource_edit_cost_rate").val() + "&company=" + $("#resource_edit_company").val() + "&calendar_id=" + $("#resource_edit_calendar_id").val() + "&unit_of_measure=" + $("#resource_edit_unit_of_measure").val(), function(data) {
          loadResources();
          $("#modal_resource_editor").modal('hide');
        });
      }
    }
  });

  $(document).on('click', '.delete-resource', function(e) {
    $.getJSON("beta.ajax.php?action=delete_resource&id=" + $("#resource_edit_id").val(), function(data) {
      window.ibex_gantt_config.resources = data.resources;
      window.ibex_gantt_config.resource_groups = data.resource_groups;
      $("#table_resources").empty();
      if (data.resources.length == 0) {
        $('.no-resources').show();
        $('#resources-header-toolbar').hide();
      } else {
        $(".no-resources").hide();
        $('#resources-header-toolbar').show();
      }
      for (var resource of data.resources) {
        var parentGroupName;
        for (var resourceGroup of data.resource_groups) {
          if (resource.group_id == resourceGroup.id) {
            parentGroupName = resourceGroup.name;
            break;
          }
        }
        if (resource.company == "null" || resource.company == null) {
          resource.company = "-";
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
        $('#table_resources').append("<button class='edit-resource' data-index='" + resource.id + "'>" + "<div class='resource-img'></div><div class='resource-text'><h5 class='resource-name'>" + resource.name + "</h5><div class='resource-group'>" + parentGroupName + "</div><div class='resource-notes'>" + resource.notes + "</div><div class='resource-company'>" + resource.company + "</div><div id='resource-cost-rate'>£" + resource.cost_rate + unit_of_measure + "</div></div></button>");
      }
      $("#modal_resource_editor").modal('hide');
      gantt.render();
    });
  });

  $(".new-resource-group").click(function(e) {
    alert('This will take you to the Resources tab');
    $('#modal_task_editor').modal('hide');
    $('#gantt').removeClass("show active");
    $('#resources').addClass("show active");
    $("#gantt-header-toolbar.").hide();
    $("#resources-header-toolbar").show();
    $("#resource_group_id_local").val('0');
    $('.mdb-select').material_select('destroy');
    for (var calendar of window.ibex_gantt_config.calendars) {
      if (calendar.type == "2") {
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
    alert('1xx');
    $('#modal_task_editor').modal('hide');
    $('#gantt').removeClass("show active");
    $('#resources').addClass("show active");
    $("#gantt-header-toolbar").hide();
    $("#resources-header-toolbar").show();
    $(".manage-resource-groups").click('');
  });

  $(".new-resource-item").click(function(e) {
    $('#modal_task_editor').modal('hide');
    $('#gantt').removeClass("show active");
    $('#resources').addClass("show active");
    $("#gantt-header-toolbar").hide();
    $("#resources-header-toolbar").show();
    $('#modal_resouce_editor').modal('show');
  });

  $(document).on('click', '.add-resource-group', function(e) {
    if ($("#resource_group_new_name").val() != "") {
      var containsHumanResources = false;
      if ($("#resource_group_contains_humans").is(':checked')) {
        containsHumanResources = true;
      }
      var containsConsumableResources = false;
      if ($("#resource_group_contains_consumables").is(':checked')) {
        containsConsumableResources = true;
      }
      // Get mins to base a day upon
      var calendarID = $("#resource_group_calendar_id").val();
      var periodMinutes = 0;
      var outputPerMinute = 0;
      for (var calendar of window.ibex_gantt_config.calendars) {
        if (calendar.id == calendarID) {
          var startTimeShift = moment(padLeadingZero(calendar["start_hour"]) + ":" + padLeadingZero(calendar['start_minute']), "HH:mm");
          var endTimeShift = moment(padLeadingZero(calendar["end_hour"]) + ":" + padLeadingZero(calendar['end_minute']), "HH:mm");
          var durationShift = moment.duration(endTimeShift.diff(startTimeShift));
          var periodMinutesPre = Math.abs(durationShift.asMilliseconds() / 60 / 1000);
          if ($("#resource_group_outputs_period").val() == "1") {
            periodMinutes = 60;
          }
          if ($("#resource_group_outputs_period").val() == "2") {
            periodMinutes = periodMinutesPre;
          }
          if ($("#resource_group_outputs_period").val() == "3") {
            periodMinutes = Number(periodMinutesPre) * 5;
          }
          if ($("#resource_group_outputs_period").val() == "4") {
            periodMinutes = Number(periodMinutesPre) * 30;
          }
          outputPerMinute = Number($("#resource_group_max_output_value").val()) / Number(periodMinutes);
        }
      }
      $.getJSON("beta.ajax.php?action=add_resource_group&id=" + $("#resource_group_id_local").val() + "&name=" + $("#resource_group_new_name").val() + "&contains_human_resources=" + containsHumanResources + "&contains_consumable_resources=" + containsConsumableResources + "&unit=" + $("#resource_group_outputs_unit").val() + "&period=" + $("#resource_group_outputs_period").val() + "&min_output=" + $("#resource_group_min_output_value").val() + "&max_output=" + $("#resource_group_max_output_value").val() + "&calendar_id=" + $("#resource_group_calendar_id").val() + "&period_minutes=" + periodMinutes + "&output_per_minute=" + outputPerMinute, function(data) {
        $("#table_resource_groups > tbody").empty();
        $("#resource_group_new_name").val('');
        $.each(data.resource_groups, function(index) {
          window.ibex_gantt_config.resource_groups = data.resource_groups;
          $("#table_resource_groups > tbody").append("<tr><td>" + data.resource_groups[index].name + "</td><td class='text-align: right'><img style='height: 16px; cursor: pointer' src='img/svg/edit.svg' class='edit-resource-group' data-index='" + data.resource_groups[index].id + "'></td></tr>");
        });
      });
    }
    $("#resource_edit_outputs_unit").val('');
  });

  $(document).on('click', '.view-resource-groups', function(e) {
    $.getJSON("beta.ajax.php?action=get_resource_groups", function(data) {
          $("#table_resource_groups > tbody").empty();
      $.each(data.resource_groups, function(index) {
		console.log(data.resource_groups[index].name);
      $("#table_resource_groups > tbody").append("<tr><td>" + data.resource_groups[index].name + "</td><td style='text-align: right'><img style='height: 16px; cursor: pointer' src='img/svg/edit.svg' class='edit-resource-group' data-index='" + data.resource_groups[index].id + "'></td></tr>");
          });
      $("#modal_resource_groups").modal('show');
    });
  });

  $(document).on('click', '.manage-resource-groups', function(e) {
    $("#resource_group_id_local").val('0');
    $('.mdb-select').material_select('destroy');
    for (var calendar of window.ibex_gantt_config.calendars) {
      if (calendar.type == "2") {
        $('#resource_group_calendar_id').append($('<option>', {
          value: calendar['id'],
          text: calendar['name']
        }));
      }
    }
    $('.mdb-select').material_select();
    $("#modal_resource_groups_editor").modal('show');
  });

  $(document).on('click', '.add-resource', function(e) {
    var resourceGUID = generateGUID();
    $('.mdb-select').material_select('destroy');
    $("#resource_edit_name").val('');
    $("#resource_edit_guid").val(resourceGUID);
    $("#resource_edit_company").val('');
    $("#resource_edit_cost_rate").val('0');
    $("#resource_edit_unit_of_measure").val('');
    $("#resource_edit_notes").val('');
    $("#resource_edit_calendar_id").empty();
    $("#resource_edit_parent").empty();
    var resources = window.ibex_gantt_config.resource_groups.sort();
    resources.reverse();
    $('#resource_edit_parent').append($('<option>', {
      value: '',
      text: 'Select'
    }));
    $.each(resources, function(index) {
      $('#resource_edit_parent').append($('<option>', {
        value: resources[index].id,
        text: resources[index].name
      }));
    });
    $('#resource_edit_calendar_id').append($('<option>', {
      value: "1",
      text: 'Select'
    }));
    $.each(window.ibex_gantt_config.calendars, function(index) {
      if (window.ibex_gantt_config.calendars[index].type == 2) {
        $('#resource_edit_calendar_id').append($('<option>', {
          value: window.ibex_gantt_config.calendars[index].id,
          text: window.ibex_gantt_config.calendars[index].name
        }));
      }
    });
    $('.mdb-select').material_select();
    $("#modal_resource_editor").modal('show');
  });

  $(document).on('click', '.edit-resource', function(e) {
    var resources = window.ibex_gantt_config.resource_groups.sort();
    resources.reverse();
    $('#resource_edit_parent').append($('<option>', {
      value: '',
      text: 'Select'
    }));
    $.each(resources, function(index) {
      $('#resource_edit_parent').append($('<option>', {
        value: resources[index].id,
        text: resources[index].name
      }));
    });
    $("#resource_edit_calendar_id").empty();
    $('#resource_edit_calendar_id').append($('<option>', {
      value: "1",
      text: 'Select'
    }));
    $.each(window.ibex_gantt_config.calendars, function(index) {
      if (window.ibex_gantt_config.calendars[index].type == 2) {
        $('#resource_edit_calendar_id').append($('<option>', {
          value: window.ibex_gantt_config.calendars[index].id,
          text: window.ibex_gantt_config.calendars[index].name
        }));
      }
    });
    $.getJSON("beta.ajax.php?action=get_resource&id=" + $(this).data("index"), function(data) {
      $('.mdb-select').material_select('destroy');
      $("#resource_edit_id").val(data.resource.id);
      $("#resource_edit_name").val(data.resource.name).trigger("change");
      $("#resource_edit_parent").val(data.resource.group_id);
      $("#resource_edit_guid").val(data.resource.guid);
      $("#resource_edit_calendar_id").val(data.resource.calendar_id);
      $("#resource_edit_notes").val(data.resource.notes).trigger("change");
      $("#resource_edit_cost_rate").val(data.resource.cost_rate).trigger("change");
      $("#resource_edit_company").val(data.resource.company).trigger("change");
      $("#resource_edit_unit_of_measure").val(data.resource.unit_of_measure).trigger("change");
      $("#resource_edit_image_url").attr('src', data.resource.resource_image_url);
      $('.mdb-select').material_select();
      $(".delete-resource").show();
      $.each(data.linked_tasks, function(index) {
        $('#table_resource_linked_tasks > tbody').append("<tr class='resource-task-allocation'><td>" + data.linked_tasks[index].task_text + "</td></tr>");
      });
      $("#modal_resource_editor").modal('show');
    });
  });

  $(document).on('click', '.delete-resource-group', function(e) {
    $.getJSON("beta.ajax.php?action=delete_resource_group&id=" + $("#resource_group_edit_id").val(), function(data) {
      $("#modal_resource_groups_editor").modal('hide');
      $("#table_resource_groups > tbody").empty();
      $.each(data.resource_groups, function(index) {
        $("#table_resource_groups > tbody").append("<tr><td>" + data.resource_groups[index].name + "</td><td style='text-align: right'><img style='height: 16px; cursor: pointer' src='img/svg/edit.svg' class='edit-resource-group' data-index='" + data.resource_groups[index].id + "'></td></tr>");
      });
    });
  });

  $(document).on('click', '.edit-resource-group', function(e) {
    var groupID = $(this).data("index");
    $.getJSON("beta.ajax.php?action=get_resource_group&id=" + $(this).data("index"), function(data) {
      $('.mdb-select').material_select('destroy');
      $("#resource_group_edit_id").val(data.resource_group.id);
      $("#resource_group_new_name").val(data.resource_group.name).trigger("change");
      if (data.resource_group.contains_human_resources == "1") {
        $("#resource_group_contains_humans").attr('checked', true);
      } else {
        $("#resource_group_contains_humans").attr('checked', false);
      }
      if (data.resource_group.contains_consumable_resources == "1") {
        $("#resource_group_contains_consumables").attr('checked', true);
      } else {
        $("#resource_group_contains_consumables").attr('checked', false);
      }
      $("#resource_group_min_output_value").val(data.resource_group.min_output_value);
      $("#resource_group_max_output_value").val(data.resource_group.max_output_value);
      for (var calendar of window.ibex_gantt_config.calendars) {
        if (calendar.type == "2") {
          $('#resource_group_calendar_id').append($('<option>', {
            value: calendar['id'],
            text: calendar['name']
          }));
        }
      }
      $("#resource_group_calendar_id").val(data.resource_group.calendar_id).trigger("change");
      $("#resource_group_outputs_unit").val(data.resource_group.outputs_unit).trigger("change");
      $("#resource_group_outputs_period").val(data.resource_group.period).trigger("change");
      resource_group_outputs_period
      $('.mdb-select').material_select();
      $("#resource_group_id_local").val(groupID);
      $("#modal_resource_groups").modal('hide');
      $("#modal_resource_groups_editor").modal('show');
      $(".delete-resource-group").show();
    });
  });

  $(document).on('click', '.clash-resource-unassign', function(e) {
    var task = gantt.getTask($("#clashed_resource_task_id").val());
    task.resource_id = '';
    gantt.updateTask(task.id);
    gantt.render();
    $("#modal_resource_clash").modal('hide');
  });

  $(document).on('click', '.clash-resource-revert', function(e) {
    gantt.undo();
    gantt.render();
    $("#modal_resource_clash").modal('hide');
  });

  // Assign resources to task (from task editor)
  $(document).on('click', '.assign-resource-to-task', function(e) {
    gantt.getTask(window.ibex_gantt_config.activeTaskID).resource_id = $(this).data("index");
    gantt.updateTask(window.ibex_gantt_config.activeTaskID);
    var resource;
    for (resource of window.ibex_gantt_config.resources) {
      if (resource['id'] == $(this).data("index")) {
        break;
      }
    }
    $(this).hide();
    $(".search-resources-lists").val('');
    $(".list-group-assigned li").show();
    $(".list-group-unassigned li").show();
    $(".list-group-assigned").append("<li class='list-group-item unassign-resource-from-task' data-index='" + $(this).data("index") + "'>" + resource['name'] + "<img class='modal-icon-bin' src='img/svg/bin-1.svg'></li>");
  });

  // Unassign resources to task (from task editor)
  $(document).on('click', '.unassign-resource-from-task', function(e) {
    gantt.getTask(window.ibex_gantt_config.activeTaskID).resource_id = 0;
    gantt.updateTask(window.ibex_gantt_config.activeTaskID);
    var resource;
    for (resource of window.ibex_gantt_config.resources) {
      if (resource['id'] == $(this).data("index")) {
        break;
      }
    }
    $(this).hide();
    $(".search-resources-lists").val('');
    $(".list-group-assigned li").show();
    $(".list-group-unassigned li").show();
    $(".list-group-unassigned").append("<li class='list-group-item assign-resource-to-task' data-index='" + $(this).data("index") + "'>" + resource['name'] + "<span class='badge badge-success' style='background-color: #999999'><</span></li>");
  });

  var timerResource;
  $('#resource_search_text').keyup(function() {
    clearTimeout(timerResource);
    timerResource = setTimeout(function() {
      var searchText = $("#resource_search_text").val().toLowerCase();
      $.each($(".edit-resource"), function() {
        if ($(this).text().toLowerCase().indexOf(searchText) === -1) {
          $(this).closest('.edit-resource').hide();
        } else {
          $(this).closest('.edit-resource').show();
        }
      });
    }, 400);
  });

  $(".clear-resource-search").click(function() {
    $("#resource_search_text").val('');
    $.each($(".text"), function() {
      $(this).closest('.edit-resource').show();
    });
  });
            
            
            
            $(document).on('click', '.continue_setup_resources_groups', function(e) {
    $.getJSON("beta.ajax.php?action=get_resource_groups", function(data) {
      $("#setup_table_resource_groups > tbody").empty();
      $.each(data.resource_groups, function(index) {
        $("#setup_table_resource_groups > tbody").append("<tr><td></td></td><td>" + data.resource_groups[index].name + "</td><td style='text-align: right'><img style='height: 16px; cursor: pointer' src='img/svg/edit.svg' class='edit-resource-group' data-index='" + data.resource_groups[index].id + "'></td></tr>");
          });
    });
  });
            
