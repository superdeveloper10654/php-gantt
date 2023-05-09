<script>

  function adjustTaskZoomLevel(type) {
    if (window.ibex_gantt_config.resourcesEnabled == true) {
      $("#gantt-wrapper-resources").fadeOut(500, function() {
        $(".resources-hidden-indicator").show();
      });
    }
    switch (type) {
      case "in":
        switch (window.ibex_gantt_config.currentZoomLevel) {
          case "year":
            window.ibex_gantt_config.currentZoomLevel = "quarter";
            gantt.config.scale_unit = "year";
            gantt.config.date_scale = "%Y";
            gantt.config.subscales = [{
              unit: "quarter",
              step: 1,
              template: decadeScaleTemplate // monthScaleTemplate
            }];
            gantt.render();
            $(".btn-zoom-out").attr('disabled', false);
            $(".btn-zoom-in").attr('disabled', false);
            break;
          case "quarter":
            window.ibex_gantt_config.currentZoomLevel = "month";
            gantt.config.scale_unit = "year";
            gantt.config.date_scale = "%Y";
            gantt.config.subscales = [{
              unit: "month",
              step: 1,
              date: "%F"
            }];
            gantt.render();
            $(".btn-zoom-out").attr('disabled', false);
            $(".btn-zoom-in").attr('disabled', false);
            break;
          case "month":
            window.ibex_gantt_config.currentZoomLevel = "week";
            gantt.config.scale_unit = "week";
            gantt.config.date_scale = "%F %Y (Week %W)";
            gantt.config.subscales = [{
              unit: "day",
              step: 1,
              date: "%D %d"
            }];
            if (window.ibex_gantt_config.resourcesEnabled == true) {
              $("#gantt-wrapper-resources").fadeIn(500);
              $(".resources-hidden-indicator").remove();
            };
            gantt.render();
            $(".btn-zoom-out").attr('disabled', false);
            $(".btn-zoom-in").attr('disabled', false);
            break;
          case "week":
            window.ibex_gantt_config.currentZoomLevel = "day";
            gantt.config.scale_unit = "day";
            gantt.config.date_scale = "%D %d %F %Y";
            gantt.config.subscales = [{
              unit: "hour",
              step: 12,
              date: "%H:00"
            }];
            gantt.render();
            $(".btn-zoom-out").attr('disabled', false);
            break;
          case "day":
            window.ibex_gantt_config.currentZoomLevel = "hour";
            gantt.config.scale_unit = "day";
            gantt.config.date_scale = "%D %d %F %Y";
            gantt.config.subscales = [{
              unit: "hour",
              step: 1,
              date: "%H:00"
            }];
            gantt.render();
            $(".btn-zoom-in").attr('disabled', true);
            $(".btn-zoom-out").attr('disabled', false);
            break;
        }
        break;
      case "out":
        switch (window.ibex_gantt_config.currentZoomLevel) {
          case "quarter":
            window.ibex_gantt_config.currentZoomLevel = "year";
            gantt.config.scale_unit = "year";
            gantt.config.date_scale = "%Y";
            gantt.config.subscales = [{
              unit: "year",
              step: 1,
              date: "%Y"
            }];
            gantt.render();
            $(".btn-zoom-out").attr('disabled', true);
            $(".btn-zoom-in").attr('disabled', false);
            break;
          case "month":
            window.ibex_gantt_config.currentZoomLevel = "quarter";
            gantt.config.scale_unit = "year";
            gantt.config.date_scale = "%Y";
            gantt.config.subscales = [{
              unit: "quarter",
              step: 1,
              template: monthScaleTemplate
            }];
            gantt.render();
            $(".btn-zoom-out").attr('disabled', false);
            $(".btn-zoom-in").attr('disabled', false);
            break;
          case "week":
            window.ibex_gantt_config.currentZoomLevel = "month";
            gantt.config.scale_unit = "year";
            gantt.config.date_scale = "%Y";
            gantt.config.subscales = [{
              unit: "month",
              step: 1,
              date: "%F"
            }];
            gantt.render();
            $(".btn-zoom-out").attr('disabled', false);
            $(".btn-zoom-in").attr('disabled', false);
            break;
          case "day":
            window.ibex_gantt_config.currentZoomLevel = "week";
            gantt.config.scale_unit = "week";
            gantt.config.date_scale = "%F %Y (Week %W)";
            gantt.config.subscales = [{
              unit: "day",
              step: 1,
              date: "%D %d"
            }];
            if (window.ibex_gantt_config.resourcesEnabled == true) {
              $("#gantt-wrapper-resources").fadeIn(500);
              $(".resources-hidden-indicator").remove();
            }
            gantt.render();
            $(".btn-zoom-out").attr('disabled', false);
            $(".btn-zoom-in").attr('disabled', false);
            break;
          case "hour":
            window.ibex_gantt_config.currentZoomLevel = "day";
            gantt.config.scale_unit = "day";
            gantt.config.date_scale = "%D %d %F %Y";
            gantt.config.subscales = [{
              unit: "hour",
              step: 12,
              date: "%H:00"
            }];
            gantt.render();
            $(".btn-zoom-out").attr('disabled', false);
            $(".btn-zoom-in").attr('disabled', false);
            break;
        }
    }
  }

  
  //Setting available scales
  var scaleConfigs = [
    // hours
    {
      unit: "hour",
      step: 1,
      scale_unit: "day",
      date_scale: "%j %M",
      subscales: [{
        unit: "hour",
        step: 1,
        date: "%H:%i"
      }]
    },
    // days
    {
      unit: "day",
      step: 1,
      scale_unit: "month",
      date_scale: "%F",
      subscales: [{
        unit: "day",
        step: 1,
        date: "%j"
      }]
    },
    // weeks
    {
      unit: "week",
      step: 1,
      scale_unit: "month",
      date_scale: "%F",
      subscales: [{
        unit: "week",
        step: 1,
        template: function(date) {
          var dateToStr = gantt.date.date_to_str("%d %M");
          var endDate = gantt.date.add(gantt.date.add(date, 1, "week"), -1, "day");
          return dateToStr(date) + " - " + dateToStr(endDate);
        }
      }]
    },
    // months
    {
      unit: "month",
      step: 1,
      scale_unit: "year",
      date_scale: "%Y",
      subscales: [{
        unit: "month",
        step: 1,
        date: "%M"
      }]
    },
    // quarters
    {
      unit: "month",
      step: 3,
      scale_unit: "year",
      date_scale: "%Y",
      subscales: [{
        unit: "month",
        step: 3,
        template: function(date) {
          var dateToStr = gantt.date.date_to_str("%M");
          var endDate = gantt.date.add(gantt.date.add(date, 3, "month"), -1, "day");
          return dateToStr(date) + " - " + dateToStr(endDate);
        }
      }]
    },
    // years
    {
      unit: "year",
      step: 1,
      scale_unit: "year",
      date_scale: "%Y",
      subscales: [{
        unit: "year",
        step: 5,
        template: function(date) {
          var dateToStr = gantt.date.date_to_str("%Y");
          var endDate = gantt.date.add(gantt.date.add(date, 5, "year"), -1, "day");
          return dateToStr(date) + " - " + dateToStr(endDate);
        }
      }]
    },
    // decades
    {
      unit: "year",
      step: 10,
      scale_unit: "year",
      template: function(date) {
        var dateToStr = gantt.date.date_to_str("%Y");
        var endDate = gantt.date.add(gantt.date.add(date, 10, "year"), -1, "day");
        return dateToStr(date) + " - " + dateToStr(endDate);
      },
      subscales: [{
        unit: "year",
        step: 100,
        template: function(date) {
          var dateToStr = gantt.date.date_to_str("%Y");
          var endDate = gantt.date.add(gantt.date.add(date, 100, "year"), -1, "day");
          return dateToStr(date) + " - " + dateToStr(endDate);
        }
      }]
    }
  ];

  // get number of columns in timeline
  function getUnitsBetween(from, to, unit, step) {
    var start = new Date(from),
      end = new Date(to);
    var units = 0;
    while (start.valueOf() < end.valueOf()) {
      units++;
      start = gantt.date.add(start, step, unit);
    }
    return units;
  }

  function applyConfig(config, dates) {
    gantt.config.scale_unit = config.scale_unit;
    if (config.date_scale) {
      gantt.config.date_scale = config.date_scale;
      gantt.templates.date_scale = null;
    } else {
      gantt.templates.date_scale = config.template;
    }
    gantt.config.step = config.step;
    gantt.config.subscales = config.subscales;
    if (dates) {
      gantt.config.start_date = gantt.date.add(dates.start_date, -1, config.unit);
      gantt.config.end_date = gantt.date.add(gantt.date[config.unit + "_start"](dates.end_date), 2, config.unit);
    } else {
      gantt.config.start_date = gantt.config.end_date = null;
    }
  }

  function zoomAuto() {
    var project = gantt.getSubtaskDates(),
      areaWidth = gantt.$task.offsetWidth;
    for (var i = 0; i < scaleConfigs.length; i++) {
      var columnCount = getUnitsBetween(project.start_date, project.end_date, scaleConfigs[i].unit, scaleConfigs[i].step);
      if ((columnCount + 2) * gantt.config.min_column_width <= areaWidth) {
        break;
      }
    }

    if (i == scaleConfigs.length) {
      i--;
    }

    applyConfig(scaleConfigs[i], project);
    gantt.render();
  }
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  


  
  
  

  $(".btn-zoom-in").click(function(e) {
    adjustTaskZoomLevel("in");
  });
  $(".btn-zoom-out").click(function(e) {
    adjustTaskZoomLevel("out");
  });
  $(".btn-zoom-auto").click(function(e) {
    zoomAuto();
  });


</script>