
  var predecessors = task.$target;
  var successors = task.$source;
  
$(".list-group-predecessors").html('');

$.each(predecessors, function(i) {


    // Load it
    var link = gantt.getLink(predecessors[i]);
    var type;
    if (link.type == "0") {
      type = "Finish to Start";
    }
    if (link.type == "1") {
      type = "Start to Start";
    }
    if (link.type == "2") {
      type = "Finish to Finish";
    }
    if (link.type == "3") {
      type = "Start to Finish";
    }

    var singleText, pluralText, offsetDuration;
    if (window.ibex_gantt_config.periodDescriptor == "1") {
      pluralText = "hours";
      singleText = "hour";
      offsetDuration = Math.floor(link.offset_minutes / 60);
    }
    if (window.ibex_gantt_config.periodDescriptor == "2") {
      pluralText = "days";
      singleText = "day";
      offsetDuration = convertMinutesToPeriod(link.offset_minutes, task.id);
    }
    if (window.ibex_gantt_config.periodDescriptor == "3") {
      pluralText = "nights";
      singleText = "night";
      offsetDuration = convertMinutesToPeriod(link.offset_minutes, task.id);
    }
    if (window.ibex_gantt_config.periodDescriptor == "4") {
      pluralText = "shifts";
      singleText = "shift";
      offsetDuration = convertMinutesToPeriod(link.offset_minutes, task.id);
    }
    if (window.ibex_gantt_config.periodDescriptor == "5") {
      pluralText = window.ibex_gantt_config.periodDescriptorTextPlural;
      singleText = window.ibex_gantt_config.periodDescriptorTextSingular;
      offsetDuration = convertMinutesToPeriod(link.offset_minutes, task.id);
    }
    if (offsetDuration == 1) {
      type += ": 1 " + singleText;
    } else {
      type += ": " + offsetDuration + " " + pluralText;
    }
    if (link.offset_type == "1") {
      type += " of lag";
    }
    if (link.offset_type == "2") {
      type += " of lead";
    }
    $(".list-group-predecessors").append('<li class="list-group-item d-flex justify-content-between align-items-center">' + gantt.getTask(gantt.getLink(predecessors[i]).source).text + '<br> (' + type + ')<span class="delete-link" data-index="' + predecessors[i] + '"><span class="select2-selection__choice__remove" role="presentation"><img src="img/svg/bin-1.svg" style="height: 16px"title="Remove this predecessor"></span></li>');
  });
  $(".list-group-successors").html('');
  $.each(successors, function(i) {
    var link = gantt.getLink(successors[i]);
    var type;
    if (link.type == "0") {
      type = "Finish to Start";
    }
    if (link.type == "1") {
      type = "Start to Start";
    }
    if (link.type == "2") {
      type = "Finish to Finish";
    }
    if (link.type == "3") {
      type = "Start to Finish";
    }
    var singleText, pluralText;
    var offsetDuration = convertMinutesToPeriod(link.offset_minutes, task.id);
    if (window.ibex_gantt_config.periodDescriptor == "1") {
      pluralText = "hours";
      singleText = "hour";
      offsetDuration = Math.floor(link.offset_minutes / 60);
    }
    if (window.ibex_gantt_config.periodDescriptor == "2") {
      pluralText = "days";
      singleText = "day";
    }
    if (window.ibex_gantt_config.periodDescriptor == "3") {
      pluralText = "nights";
      singleText = "night";
    }
    if (window.ibex_gantt_config.periodDescriptor == "4") {
      pluralText = "shifts";
      singleText = "shift";
    }
    if (window.ibex_gantt_config.periodDescriptor == "5") {
      pluralText = window.ibex_gantt_config.periodDescriptorTextPlural;
      singleText = window.ibex_gantt_config.periodDescriptorTextSingular;
    }
    if (offsetDuration == 1) {
      type += ": 1 " + singleText;
    } else {
      type += ": " + offsetDuration + " " + pluralText;
    }
    if (link.offset_type == "1") {
      type += " of lag";
    }
    if (link.offset_type == "2") {
      type += " of lead";
    }
    $(".list-group-successors").append('<li class="list-group-item d-flex justify-content-between align-items-center">' + gantt.getTask(gantt.getLink(successors[i]).target).text + ' <br>(' + type + ')<span class="delete-link" data-index="' + successors[i] + '"><span class="select2-selection__choice__remove" role="presentation"><img src="img/svg/bin-1.svg" style="height: 16px"title="Remove this successor"></span></li>');
  });
