
  window.getNonWorkingPeriods = function getNonWorkingPeriods(from, to, calendarID, endDate) {
if (calendarID == "undefined" || calendarID == undefined) {

      $.each(window.ibex_gantt_config.calendars, function(index) {
        if (window.ibex_gantt_config.calendars[index].is_default_task_calendar == "1") {
          calendarID = window.ibex_gantt_config.calendars[index].id;
        }
      });
    }
    var calendar = getCalendar(calendarID);
    var startTimeParsed = moment(from).format("HH:mm");
    var startTime = moment(padLeadingZero(calendar['start_hour']) + ":" + padLeadingZero(calendar['start_minute']), "HH:mm");
    var endTime = moment(padLeadingZero(calendar['end_hour']) + ":" + padLeadingZero(calendar['end_minute']), "HH:mm");
    var duration = moment.duration(endTime.diff(startTime));
    var minutesInPeriod = Math.abs(duration.asMinutes());
    // Get number of mins in shift first
    var startTimeShift = moment(padLeadingZero(calendar["start_hour"]) + ":" + padLeadingZero(calendar['start_minute']), "HH:mm");
    var endTimeShift = moment(padLeadingZero(calendar["end_hour"]) + ":" + padLeadingZero(calendar['end_minute']), "HH:mm");
    var durationShift = moment.duration(endTimeShift.diff(startTimeShift));
    var shiftMinutes = Math.abs(durationShift.asMilliseconds() / 60 / 1000);
    var shiftNonMinutes = 1440 - shiftMinutes;
    // Get number of mins between now and end of THIS shift
    var startDateTimeThisShift = moment(from).format("HH:mm");
    var startTimeThisShift = moment(startDateTimeThisShift, "HH:mm");
    var endTimeThisShift = moment(padLeadingZero(calendar["end_hour"]) + ":" + padLeadingZero(calendar['end_minute']), "HH:mm");
    var durationThisShift = moment.duration(endTimeThisShift.diff(startTimeThisShift));
    var thisShiftMinutes = Math.abs(durationThisShift.asMilliseconds() / 60 / 1000);
    var minutesInInitialPeriod = 0;
    var processInitialMinutes = false;
    if (thisShiftMinutes == shiftMinutes) {
      minutesInInitialPeriod = shiftMinutes;
    } else {
      minutesInInitialPeriod = thisShiftMinutes;
    }
    var pointer = moment(from).format("YYYY-MM-DD HH:mm");
    var noWorkShifts = [];
    var unixTo = moment(to).format("X");
    var unixPointer = 0;
    do {
      endPointer = moment(pointer).add(shiftMinutes, 'minutes').format("YYYY-MM-DD HH:mm");
      if (startTimeShift.isBefore(endTimeShift, 'minute')) {
        pointer = moment(getNextWorkingDate(calendarID, false, endPointer), "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm");
      } else {
        pointer = moment(getNextWorkingDate(calendarID, true, endPointer), "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm");
      }
      unixPointer = moment(pointer).format("X");
      var moment1 = moment(endPointer);
      var moment2 = moment(endDate);
      if (moment1.isSameOrAfter(moment2)) {} else {
        noWorkShifts.push({
          start_date: new Date(endPointer),
          end_date: new Date(pointer)
        });
      }
    }
    while (unixPointer < unixTo);
    return noWorkShifts;
  }

                                 // RB 23.04.21 TO DO: FIX THE BLEED OF THE TASK END WHEN IT PUSHES INTO AN EXTRA SHIFT
                                 
  window.getTaskEndDate = function getTaskEndDate(startDate, durationMins, calendarID, isMilestone = false) {
    if (isMilestone == true) {}
    var calendar = getCalendar(calendarID);
    // Get number of mins in shift first
    var startTimeShift = moment(padLeadingZero(calendar["start_hour"]) + ":" + padLeadingZero(calendar['start_minute']), "HH:mm");
    var endTimeShift = moment(padLeadingZero(calendar["end_hour"]) + ":" + padLeadingZero(calendar['end_minute']), "HH:mm");
    var durationShift = moment.duration(endTimeShift.diff(startTimeShift));
    var shiftMinutes = Math.abs(durationShift.asMilliseconds() / 60 / 1000);
    // Get number of mins between now and end of THIS shift
    var startDateTimeThisShift = moment(startDate).format("HH:mm");
    var startTimeThisShift = moment(startDateTimeThisShift, "HH:mm");
    var endTimeThisShift = moment(padLeadingZero(calendar["end_hour"]) + ":" + padLeadingZero(calendar['end_minute']), "HH:mm");
    var durationThisShift = moment.duration(endTimeThisShift.diff(startTimeThisShift));
    var thisShiftMinutes = Math.abs(durationThisShift.asMilliseconds() / 60 / 1000);
    // Useful bits
    var shiftNonMinutes = 1440 - shiftMinutes;
    var minsRemaining = durationMins;
    var pointerDate = startDate;
    if (isMilestone == true) {
      if (durationMins == 0) {
        return startDate;
      } else {
        if (durationMins > 0) {
          // Lag
          if (durationMins > thisShiftMinutes) {
            // Spanning more than this shift
            var dateEnd;
            var dateLoop;
            var totalMinsRemaining = durationMins;
            dateEnd = moment(startDate).add(thisShiftMinutes, 'minutes').format("YYYY-MM-DD HH:mm");
            totalMinsRemaining = totalMinsRemaining - thisShiftMinutes;
            dateLoop = moment(getNextWorkingDate(calendarID, false, dateEnd), "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm");
            do {
              if (totalMinsRemaining > shiftMinutes) {
                // Loop again	
                dateLoop = moment(getNextWorkingDate(calendarID, false, dateLoop), "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm");
                totalMinsRemaining = totalMinsRemaining - shiftMinutes;
              } else {
                // Stop on this loop
                dateLoop = moment(dateLoop).add(totalMinsRemaining, 'minutes').format("YYYY-MM-DD HH:mm");
                totalMinsRemaining = 0;
                break;
              }
            }
            while (totalMinsRemaining != 0);
            return dateLoop;
          } else {
            var dateEnd = moment(startDate).add(durationMins, 'minutes').format("YYYY-MM-DD HH:mm");
            return dateEnd;
          }
        } else if (durationMins < 0) {
          // Lead
        }
      }
    }
    // Check conditions of input data
    if (thisShiftMinutes == shiftMinutes) {
      // We are scheduing from start of shift
      if (durationMins <= shiftMinutes) {
        // We are able to schedule this task within one single shift. Do it and return
        return moment(startDate).add(durationMins, 'minutes').format("YYYY-MM-DD HH:mm");
      } else {
        // Task runs to multiple shifts	- build up to end of first shift and then prepare for loop
        pointerDate = moment(startDate).add(shiftMinutes, 'minutes').format("YYYY-MM-DD HH:mm");
        minsRemaining = minsRemaining - shiftMinutes;
      }
    } else {
      // We are starting after shift start time - build up to end of first shift and then prepare for loop
      // 1. Get diff between start of shift and where we're atsrtaing 
      pointerDate = moment(startDate).add(thisShiftMinutes, 'minutes').format("YYYY-MM-DD HH:mm");
      minsRemaining = minsRemaining - thisShiftMinutes;
    }
    // Preparing for loop with mins remaining 
    if (minsRemaining > 0) {} else {
      return pointerDate;
    }
    var pointerDatePrepared;
    // Get next working date from this point. Beware we may need to invoke TRUE on getNextWorkingDay
    if (Number(calendar['start_hour']) > Number(calendar['end_hour'])) {
      pointerDatePrepared = moment(getNextWorkingDate(calendarID, true, pointerDate), "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm");
    } else {
      pointerDatePrepared = moment(getNextWorkingDate(calendarID, false, pointerDate), "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm");
    }
    var innerLoopDate;
    do {
      if (minsRemaining < shiftMinutes) {
        innerLoopDate = moment(pointerDatePrepared).add(minsRemaining, 'minutes').format("YYYY-MM-DD HH:mm");
        break;
      } else {
        innerLoopDate = moment(pointerDatePrepared).add(shiftMinutes, 'minutes').format("YYYY-MM-DD HH:mm");
        minsRemaining = minsRemaining - shiftMinutes;
        if (minsRemaining == 0) {
          break;
        }
        if (Number(calendar['start_hour']) > Number(calendar['end_hour'])) {
          pointerDatePrepared = moment(getNextWorkingDate(calendarID, true, innerLoopDate), "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm");
        } else {
          pointerDatePrepared = moment(getNextWorkingDate(calendarID, false, innerLoopDate), "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm");
        }
      }
    }
    while (minsRemaining > 0);
    return innerLoopDate;
  }

  window.getTaskStartDateX = function getTaskStartDateX(durationMins, taskID, endDate, calendarID, pad = false) {
    // Work it backwards
    var periods = convertMinutesToPeriod(durationMins, taskID);
    // Go backwards over working days until we hit periods max, then 1 more, then next working date
    var count = 0;
    var testDate = endDate;
    var testDateX;
    do {
      testDateX = moment(testDate).format("YYYY-MM-DD");
      var validDay = isDateWorkingDate(moment(testDateX).format("YYYY-MM-DD"), calendarID);
      if (validDay == true) {
        count++;
      }
      testDate = moment(testDateX).subtract(1, 'days').format("YYYY-MM-DD");
    }
    while (count < periods);
    var task1StartDateFormatted = getNextWorkingDate(calendarID, false, testDate);
    return task1StartDateFormatted;
  }

  window.convertMinutesToPeriodClean = function convertMinutesToPeriodClean(minutes, calendarID) {
    var startTime, endTime;
    $.each(window.ibex_gantt_config.calendars, function(index) {
      if (window.ibex_gantt_config.calendars[index].id == calendarID) {
        startTime = minTwoDigits(window.ibex_gantt_config.calendars[index].start_hour) + ":" + minTwoDigits(window.ibex_gantt_config.calendars[index].start_minute);
        endTime = minTwoDigits(window.ibex_gantt_config.calendars[index].end_hour) + ":" + minTwoDigits(window.ibex_gantt_config.calendars[index].end_minute);
      }
    });
    var startTimeObject = moment(startTime, "HH:mm");
    var endTimeObject = moment(endTime, "HH:mm");
    if (startTimeObject.isAfter(endTimeObject)) {
      endTimeObject.add(1, 'days');
    }
    var mins = startTimeObject.diff(endTimeObject, 'minutes');
    var minsAbs = Math.abs(mins);
    var periods = roundToTwo(minutes / minsAbs);
    return periods;
  }

  window.convertMinutesToPeriod = function convertMinutesToPeriod(minutes, taskID) {
    var calendarID = gantt.getTask(taskID).calendar_id;
	 
    var startTime, endTime;
    $.each(window.ibex_gantt_config.calendars, function(index) {
      if (window.ibex_gantt_config.calendars[index].id == calendarID) {
        startTime = minTwoDigits(window.ibex_gantt_config.calendars[index].start_hour) + ":" + minTwoDigits(window.ibex_gantt_config.calendars[index].start_minute);
        endTime = minTwoDigits(window.ibex_gantt_config.calendars[index].end_hour) + ":" + minTwoDigits(window.ibex_gantt_config.calendars[index].end_minute);
      }
    });
	 
    var startTimeObject = moment(startTime, "HH:mm");
    var endTimeObject = moment(endTime, "HH:mm");
    if (startTimeObject.isAfter(endTimeObject)) {
      endTimeObject.add(1, 'days');
    }
    var mins = startTimeObject.diff(endTimeObject, 'minutes');
    var minsAbs = Math.abs(mins);
    var periods = roundToTwo(minutes / minsAbs);
	 
    return periods;
  }
  

  window.convertPeriodToMinutes = function convertPeriodToMinutes(period, calendarID) {
    var startTime, endTime;
    $.each(window.ibex_gantt_config.calendars, function(index) {
      if (window.ibex_gantt_config.calendars[index].id == calendarID) {
        startTime = minTwoDigits(window.ibex_gantt_config.calendars[index].start_hour) + ":" + minTwoDigits(window.ibex_gantt_config.calendars[index].start_minute);
        endTime = minTwoDigits(window.ibex_gantt_config.calendars[index].end_hour) + ":" + minTwoDigits(window.ibex_gantt_config.calendars[index].end_minute);
      }
    });
    var startTimeObject = moment(startTime, "HH:mm");
    var endTimeObject = moment(endTime, "HH:mm");
    if (startTimeObject.isAfter(endTimeObject)) {
      endTimeObject.add(1, 'days');
    }
    var mins = startTimeObject.diff(endTimeObject, 'minutes');
    if(mins < 0) {
      var minsAbs = Math.abs(mins);
    }
    else {
      var minsAbs = mins;
      minsAbs = 1440 - minsAbs;
    }
      
    <!-- var minsAbs = Math.abs(mins); -->
    var minsReturn = period * minsAbs;
    return minsReturn;
  }

  window.updateCalendarWorkingDays = function updateCalendarWorkingDays() {

    window.ibex_gantt_config.globalNonWorkingDays = [];
    if (calendar.working_day_monday == 0) {
      window.ibex_gantt_config.globalNonWorkingDays.push(1);
    }
    if (calendar.working_day_tuesday == 0) {
      window.ibex_gantt_config.globalNonWorkingDays.push(2);
    }
    if (calendar.working_day_wednesday == 0) {
      window.ibex_gantt_config.globalNonWorkingDays.push(3);
    }
    if (calendar.working_day_thursday == 0) {
      window.ibex_gantt_config.globalNonWorkingDays.push(4);
    }
    if (calendar.working_day_friday == 0) {
      window.ibex_gantt_config.globalNonWorkingDays.push(5);
    }
    if (calendar.working_day_saturday == 0) {
      window.ibex_gantt_config.globalNonWorkingDays.push(6);
    }
    if (calendar.working_day_sunday == 0) {
      window.ibex_gantt_config.globalNonWorkingDays.push(0);
    }
    var overrides = getCalendarOverrides(calendar.id);
    for (i = 0; i < overrides.length; i++) {
      var startDateOverride = overrides[i].start_date;
      var endDateOverride = overrides[i].end_date;
      var nonWorkingDatesOverride = enumerateDaysBetweenDates(startDateOverride, endDateOverride, calendar.id);
      for (j = 0; j < nonWorkingDatesOverride.length; j++) {
        window.ibex_gantt_config.globalNonWorkingDays.push(nonWorkingDatesOverride[j]);
      }
    }
    $('#task_edit_start_date').datepicker('setDaysOfWeekDisabled', window.ibex_gantt_config.globalNonWorkingDays);
    // Set start date to next avail
    var dateNow = moment().format("YYYY-MM-DD HH:mm");
    var setNext = getNextWorkingDate($('#task_edit_calendar_id').val(), false, dateNow);
    $('#task_edit_start_date').val(moment(setNext, "DD/MM/YYYY").format("ddd D MMM YYYY"));
    $('#task_edit_start_time').val(moment(setNext, "DD/MM/YYYY").format("HH:mm"));
  }

  window.getNextWorkingDate = function getNextWorkingDate(calendarID, includeCurrentDate = false, startDate = null) {
  
    var validDay = false;
    var date;
    var calendar = getCalendar(calendarID);
    var calendarOverrides = getCalendarOverrides(calendarID);
	 
    if (includeCurrentDate == true) {
      if (startDate == null) {
        date = moment().format("YYYY-MM-DD");
      } else {
        date = moment(startDate).format("YYYY-MM-DD");
      }
    } else {
      if (startDate == null) {
        date = moment().add(1, 'day').format("YYYY-MM-DD");
      } else {
        date = moment(startDate).add(1, 'day').format("YYYY-MM-DD");
      }
    }
    do {
      var validDayLoop = true;
      if (calendarOverrides.length == 0) {
        validDayLoop = true;
      } else {
        for (var override of calendarOverrides) {
          var dateCompare = moment(date);
          var startDate = moment(override['start_date']);
          var endDate = moment(override['end_date']);
          if (dateCompare.isBetween(startDate, endDate, null, []) == true) {
            validDayLoop = false;
          }
        }
      }
      if (validDayLoop == true) {
        switch (moment(date).isoWeekday()) {
          case 1:
            if (calendar.working_day_monday != "1") {
              validDayLoop = false;
            }
            break;
          case 2:
            if (calendar.working_day_tuesday != "1") {
              validDayLoop = false;
            }
            break;
          case 3:
            if (calendar.working_day_wednesday != "1") {
              validDayLoop = false;
            }
            break;
          case 4:
            if (calendar.working_day_thursday != "1") {
              validDayLoop = false;
            }
            break;
          case 5:
            if (calendar.working_day_friday != "1") {
              validDayLoop = false;
            }
            break;
          case 6:
            if (calendar.working_day_saturday != "1") {
              validDayLoop = false;
            }
            break;
          case 7:
            if (calendar.working_day_sunday != "1") {
              validDayLoop = false;
            }
            break;
        }
      }
      if (validDayLoop == false) {
        date = moment(date).add(1, 'day').format("YYYY-MM-DD");
        validDay = false;
      } else {
        validDay = true;
        break;
      }
    }
    while (validDay == false);
    var buildDateTime = moment(date).format("DD/MM/YYYY") + " " + padLeadingZero(calendar.start_hour) + ":" + padLeadingZero(calendar.start_minute);
	
    return buildDateTime;
  }

  window.updateEndDateAndDurations = function updateEndDateAndDurations() {
    updateWorkloadDays();
    if (window.ibex_gantt_config.taskDurationUnit == 1) {
      var durationHours = 0,
        durationMins = 0;
      if ($("#task_edit_duration_hours").val() != "") {
        durationHours = parseInt($("#task_edit_duration_hours").val());
      }
      if ($("#task_edit_duration_mins").val() != "") {
        durationMins = parseInt($("#task_edit_duration_mins").val());
      }
      duration_worked = durationHours * 60 + durationMins;
    } else {
      var startTime = moment(padLeadingZero(calendar.start_hour) + ":" + padLeadingZero(calendar.start_minute), "HH:mm");
      var endTime = moment(padLeadingZero(calendar.end_hour) + ":" + padLeadingZero(calendar.end_minute), "HH:mm");
      var duration = moment.duration(endTime.diff(startTime));
      var minutes = parseInt(duration.asMinutes());
    }
    var startDate = moment($("#task_edit_start_date_d").val() + "/" + $("#task_edit_start_date_m").val() + "/" + $("#task_edit_start_date_y").val() + " " + $("#task_edit_start_time_h").val() + ":" + $("#task_edit_start_time_m").val(), "DD/MM/YYYY HH:mm").toDate();
    var calendarID = $("#task_edit_calendar_id").val();
    var endDate = moment(getTaskEndDate(moment(startDate).format("YYYY-MM-DD HH:mm"), duration_worked, calendarID));
    var endDateParsed = moment(endDate).format('ddd D MMM (HH:mm)')
    $("#task_edit_end_date").val(endDateParsed).trigger("change");
  }

  window.updateWorkingDaysUI = function updateWorkingDaysUI() {
    $start_date = 1;
    $end_date = 31;
    for (var n = 1; n < 32; ++n) {
      if (n < 10) {
        n = "0" + n;
      }
      var dateCheck = $("#task_edit_start_date_y").val() + "-" + $("#task_edit_start_date_m").val() + "-" + n;
      var dateFormatted = moment(dateCheck).format('ddd Do');
      var result = isDateWorkingDate(dateCheck, $("#task_edit_calendar_id").val());
      if (result == false) {
        $("#task_edit_start_date_d option[value=" + n + "]").attr('disabled', 'disabled');
      } else {
        $("#task_edit_start_date_d option[value=" + n + "]").removeAttr('disabled');
        $("#task_edit_start_date_d option[value=" + n + "]").text(dateFormatted);
      }
    }
  }

  $('#task_edit_start_date_d').on('change', function() {
    updateEndDateAndDurations();
  });
  $('#task_edit_start_date_m').on('change', function() {
    updateEndDateAndDurations();
    updateWorkingDaysUI();
  });
  $('#task_edit_start_date_y').on('change', function() {
    updateEndDateAndDurations();
    updateWorkingDaysUI();
  });
  $('#task_edit_start_time_h').on('change', function() {
    updateEndDateAndDurations();
  });
  $('#task_edit_start_time_m').on('change', function() {
    updateEndDateAndDurations();
  });

  window.addMinutesToDate = function addMinutesToDate(date, minutes, calendarID) {
    var totalMinutesToAdd = minutes;
    var minutesAdded = 0;
    var calendar;
    var referenceDate = date;
    $.each(window.ibex_gantt_config.calendars, function(index) {
      if (window.ibex_gantt_config.calendars[index].id == calendarID) {
        calendar = window.ibex_gantt_config.calendars[index];
      }
    });
    // Get number of mins in shift first
    var startTimeShift = moment(padLeadingZero(calendar["start_hour"]) + ":" + padLeadingZero(calendar['start_minute']), "HH:mm");
    var endTimeShift = moment(padLeadingZero(calendar["end_hour"]) + ":" + padLeadingZero(calendar['end_minute']), "HH:mm");
    var durationShift = moment.duration(endTimeShift.diff(startTimeShift));
    var shiftMinutes = Math.abs(durationShift.asMilliseconds() / 60 / 1000);
    // Get number of mins between now and end of THIS shift
    var startDateTimeThisShift = moment(date).format("HH:mm");
    var startTimeThisShift = moment(startDateTimeThisShift, "HH:mm");
    var endTimeThisShift = moment(padLeadingZero(calendar["end_hour"]) + ":" + padLeadingZero(calendar['end_minute']), "HH:mm");
    var durationThisShift = moment.duration(endTimeThisShift.diff(startTimeThisShift));
    var thisShiftMinutes = Math.abs(durationThisShift.asMilliseconds() / 60 / 1000);
    if (totalMinutesToAdd == 0) {
      return date;
    } else if (thisShiftMinutes < totalMinutesToAdd) {
      referenceDate = moment(date).add(thisShiftMinutes, 'minutes');
      totalMinutesToAdd = totalMinutesToAdd - thisShiftMinutes;
      referenceDate = getNextWorkingDate(calendarID, false, referenceDate);
    }
    //do 
    //{
    //} 
    //while (minutesAdded != totalMinutesToAdd);
  }

  window.getEncasedStartDateTime = function getEncasedStartDateTime(date, calendarID) {
    // Checks if proposed start date / time is boundary (end) of working period and if so returns the next period start
    var calendar = getCalendar(calendarID);
    if (moment(date).format("HH:mm") == padLeadingZero(calendar['end_hour']) + ":" + padLeadingZero(calendar['end_minute'])) {
      var dateNext = getNextWorkingDate(calendarID, false, date);
      return moment(dateNext, "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm");
    } else {
      return date;
    }
  }

  window.getMinutesBetweenDates = function getMinutesBetweenDates(from, to, calendarID, endDate) {
    var hoursTotal = 0;
    var minutesNonWorking = 0;
    var nonPeriods = getNonWorkingPeriods(from, to, calendarID, endDate);
    for (var period of nonPeriods) {
      var a = moment(period.end_date); //now
      var b = moment(period.start_date);
      var diff = (a.diff(b, 'minutes'));
      minutesNonWorking += diff;
    }
    var a = moment(to); //now
    var b = moment(from);
    var diff = (a.diff(b, 'minutes'));
    var test = Number(diff) - Number(minutesNonWorking);
    return test;
  }

  window.getWorkingPeriods = function getWorkingPeriods(from, to, calendarID, endDate) {
    if (calendarID == "undefined" || calendarID == undefined) {

      $.each(window.ibex_gantt_config.calendars, function(index) {
        if (window.ibex_gantt_config.calendars[index].is_default_task_calendar == "1") {
          calendarID = window.ibex_gantt_config.calendars[index].id;
        }
      });
    }
    var calendar = getCalendar(calendarID);
    var startTimeParsed = moment(from).format("HH:mm");
    var startTime = moment(padLeadingZero(calendar['start_hour']) + ":" + padLeadingZero(calendar['start_minute']), "HH:mm");
    var endTime = moment(padLeadingZero(calendar['end_hour']) + ":" + padLeadingZero(calendar['end_minute']), "HH:mm");
    var duration = moment.duration(endTime.diff(startTime));
    var minutesInPeriod = parseInt(duration.asMinutes());
    var minutesInInitialPeriod = 0;
    var processInitialMinutes = false;
    var pointer = moment(from).format("YYYY-MM-DD HH:mm");
    var noWorkShifts = [];
    var unixTo = moment(to).format("X");
    var unixPointer = 0;
    do {
      var endPointer;
      endPointer = moment(pointer).add(minutesInPeriod, 'minutes').format("YYYY-MM-DD HH:mm");
      var moment1 = moment(endPointer);
      var moment2 = moment(to);
      if (moment1.isSameOrAfter(moment2)) {
        noWorkShifts.push({
          start_date: new Date(pointer),
          end_date: new Date(to)
        });
      } else {
        noWorkShifts.push({
          start_date: new Date(pointer),
          end_date: new Date(endPointer)
        });
      }
      pointer = moment(getNextWorkingDate(calendarID, false, endPointer), "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm");
      unixPointer = moment(pointer).format("X");
    }
    while (unixPointer < unixTo);
    return noWorkShifts;
  }
