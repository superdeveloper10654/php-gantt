<?php
  session_start();
  date_default_timezone_set('Europe/London');
  include("../dbconfig.php");

  // error_reporting(E_NONE);
  error_reporting(E_ERROR | E_WARNING | E_PARSE);

  $programme_id = $_GET['id'];
  $_SESSION['gantt_id'] = $_GET['id'];
  // $last_login = $_SESSION['user']['last_login'];
  $user_id = $_SESSION['user']['id'];
  $sql = "SELECT * FROM users WHERE id='$user_id'";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
  unset($user['hash']);
  $_SESSION['user'] = $user;
  // $_SESSION['user']['last_login'] = $last_login;

  // Range
  $stmt = $db->prepare("SELECT * FROM gantt_user_programme_links WHERE programme_id='$programme_id' AND user_id='$user_id'");
  $stmt->execute();
  $count_range = $stmt->rowCount();
  $link_range = $stmt->fetch(PDO::FETCH_ASSOC);

  $range_start_date = $link_range['date_range_start'];
  $range_end_date = $link_range['date_range_end'];

  if ($range_start_date == NULL || $range_end_date == NULL) {
    $date_range_start = date('Y-m-d', strtotime('-30 days'));
    $date_range_end = date('Y-m-d', strtotime('+30 days'));

    $stmt = $db->prepare("UPDATE gantt_user_programme_links SET date_range_start='$date_range_start',date_range_end='$date_range_end' WHERE user_id = '$user_id' AND programme_id= '$programme_id'");
    $stmt->execute();
  }

  $stmt = $db->prepare("UPDATE users SET last_programme_id = :programme_id WHERE id= :user_id");
  $stmt->bindValue(':programme_id', $_GET['id']);
  $stmt->bindValue(':user_id', $_SESSION['user']['id']);
  $stmt->execute();

  // Get user groups linked to this programme
  $stmt = $db->prepare("SELECT * FROM gantt_user_groups WHERE programme_id='$programme_id' ORDER BY name ASC");
  $stmt->execute();
  $user_groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $tasks_array = array();
  $tasks_loop_array = array();

  $stmt = $db->prepare("SELECT * FROM gantt_tasks WHERE programme_id='$programme_id' AND type='task'");
  $stmt->execute();
  $tasks_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach ($tasks_array as $task) {
    array_push($tasks_loop_array, $task);
    $tasks_pending_count;
    $tasks_in_progress_count;
  }

  $sql = "SELECT terms_accepted FROM users WHERE id='$user_id'";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
  // $user_id = $user['id'];
  $terms_accepted = $user['terms_accepted'];
  $formatted_terms_accepted = date('jS M Y', $terms_accepted);


  $sql = "SELECT name FROM gantt_programmes WHERE id ='$programme_id'";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $programme = $stmt->fetch(PDO::FETCH_ASSOC);
  $account_name = $programme['name'];

  $stmt = $db->prepare("SELECT * FROM gantt_tasks WHERE programme_id='$programme_id' AND type!='project'");
  $stmt->execute();
  $gantt_entries_count = $stmt->rowCount();

  $stmt = $db->prepare("SELECT * FROM gantt_tasks WHERE programme_id='$programme_id' AND type='project'");
  $stmt->execute();
  $projects_total_count = $stmt->rowCount();
  $projects_total_count_operator = "";
  if ($projects_total_count > 1) {
    $projects_total_count_operator = "s";
  }

  $stmt = $db->prepare("SELECT * FROM gantt_tasks WHERE programme_id='$programme_id' AND type='milestone'");
  $stmt->execute();
  $milestones_total_count = $stmt->rowCount();
  $milestones_total_count_operator = "";
  if ($milestones_total_count > 1) {
    $milestones_total_count_operator = "s";
  }

  $stmt = $db->prepare("SELECT * FROM gantt_tasks WHERE programme_id='$programme_id' AND type='task'");
  $stmt->execute();
  $tasks_total_count = $stmt->rowCount();
  $tasks_total_count_operator = "";
  if ($tasks_total_count > 1) {
    $tasks_total_count_operator = "s";
  }

  // Count tasks in progress
  $stmt = $db->prepare("SELECT * FROM gantt_tasks WHERE programme_id='$programme_id' AND type='task' AND progress > 0 AND progress < 99");
  $stmt->execute();
  $tasks_in_progress_count = $stmt->rowCount();

  // Count tasks completed
  $stmt = $db->prepare("SELECT * FROM gantt_tasks WHERE programme_id='$programme_id' AND type='task' AND progress = 100");
  $stmt->execute();
  $tasks_completed_count = $stmt->rowCount();

  // Count tasks pending
  $stmt = $db->prepare("SELECT * FROM gantt_tasks WHERE programme_id='$programme_id' AND type='task' AND progress < 1");
  $stmt->execute();
  $tasks_pending_count = $stmt->rowCount();

  // Count team members
  $stmt = $db->prepare("SELECT * FROM users WHERE last_programme_id='$programme_id'");
  $stmt->execute();
  $team_members_count = $stmt->rowCount();
  $team_members_count_operator = "person";
  if ($team_members_count > 1) {
    $team_members_count_operator = "people";
  }

  // Count team members who are administrators
  $stmt = $db->prepare("SELECT * FROM gantt_user_groups WHERE programme_id='$programme_id' AND is_admin_group='1'");
  $stmt->execute();
  $team_members_admins_count = $stmt->rowCount();

  // Count files
  $stmt = $db->prepare("SELECT * FROM gantt_files WHERE programme_id='$programme_id'");
  $stmt->execute();
  $files_count = $stmt->rowCount();

  // Count resource items
  $stmt = $db->prepare("SELECT * FROM gantt_resources WHERE programme_id='$programme_id'");
  $stmt->execute();
  $resources_count = $stmt->rowCount();

  // Count activities/ versions
  $stmt = $db->prepare("SELECT * FROM gantt_versions WHERE programme_id='$programme_id'");
  $stmt->execute();
  $versions_count = $stmt->rowCount();

  // Count projects
  $stmt = $db->prepare("SELECT * FROM gantt_tasks WHERE programme_id='$programme_id' AND type='project'");
  $stmt->execute();
  $projects_count = $stmt->rowCount();

  // Get my user groups
  $user_id_self = $_SESSION['user']['id'];
  $stmt = $db->prepare("SELECT t2.name FROM gantt_user_groups_links t1 LEFT JOIN gantt_user_groups t2 ON t1.user_group_id = t2.id WHERE t1.user_id='$user_id_self'");
  $stmt->execute();
  $user_groups_self = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $group_string = "";
  foreach ($user_groups_self as $group_self) {
    $group_string = $group_string . $group_self['name'] . ",";
  }
  $self_user_groups = rtrim($group_string, ',');

  // Versioned?
  if (isset($_GET['version'])) {
    $stmt = $db->prepare("SELECT * FROM gantt_versions WHERE programme_id = :programme_id AND guid = :guid");
    $stmt->bindValue(':programme_id', $_GET['id']);
    $stmt->bindValue(':guid', $_GET['version']);
    $stmt->execute();
    $version = $stmt->fetch(PDO::FETCH_ASSOC);
    $gantt_data = $version['gantt_data'];
    $created = $version['created'];
  } else {
    $created = "0";
  }
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Ibex Gantt beta</title>
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,300i,500,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.css" />
    <link href="codebase/dhtmlxgantt.css" rel="stylesheet">
    <script src="codebase/sources/dhtmlxgantt.js?time=<?= rand(1000, 9999) ?>"></script>
    <script src="codebase/ext/dhtmlxgantt_marker.js"></script>
    <script src="codebase/ext/dhtmlxgantt_undo.js"></script>
    <script src="codebase/ext/dhtmlxgantt_critical_path.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/mdb.min.css" rel="stylesheet">
    <link href="css/bootstrap-clockpicker.css" rel="stylesheet">
    <link href="css/bootstrap-colorpicker.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.0/css/bootstrap-slider.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <!-- <link href="../css/bootstrap-tour-standalone.css" rel="stylesheet"> -->
    <link href="css/sidebar.css" rel="stylesheet">
    <link href="css/nlform.css" rel="stylesheet">
    <link href="css/ibex-gantt-rb.css" rel="stylesheet">
    <link href="css/ibex-responsive.css" rel="stylesheet">
    <link href="css/hamburgers.css" rel="stylesheet">
    <script src="js/modernizr.custom.js"></script>

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-125668819-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];

      function gtag() {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());
      gtag('config', 'UA-125668819-1');
    </script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" crossorigin="anonymous"></script>
    <script>
      window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
    </script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link href="css/emoji.css" rel="stylesheet">
  </head>

  <body>

    <style>
      .link-lag {
        background-color: #f90 !important;
      }

      .drag_handle {
        background-image: radial-gradient(plum, orange, white);
        opacity: 0.2;
        width: 60px;
        height: 34px;
      }

      .task_drag {
        /*display: inline;*/
        float: left;
        width: 100%;
      }

      .dragging_task,
      .dragging_task.gantt_task_line.gantt_milestone .gantt_link_control .gantt_link_point,
      .dragging_task.gantt_task_line.gantt_milestone .gantt_task_content,
      .dragging_task.gantt_task_line.gantt_milestone .gantt_side_content {
        visibility: hidden !important;
      }

      .gantt_critical_task {
        background-color: #e63030 !important;
        border-color: #9d3a3a !important;
      }

      .GaugeMeter {
        Position: Relative;
        Text-Align: Center;
        Overflow: Hidden;
        Cursor: Default;
        display: inline-block;
      }

      .GaugeMeter SPAN,
      .GaugeMeter B {
        Width: 50%;
        Position: Absolute;
        left: 25%;
        Text-align: Center;
        Display: Inline-Block;
        Color: RGBa(0, 0, 0, .8);
        Font-Weight: 100;
        Overflow: Hidden;
        White-Space: NoWrap;
        Text-Overflow: Ellipsis;
      }

      .GaugeMeter[data-style="Semi"] B {}

      .GaugeMeter S,
      .GaugeMeter U {
        Text-Decoration: None;
        font-size: 18px;
        color: #999;
      }

      .gaugeMeter span {
        line-height: 140px !important;
      }
    </style>

    <?php if (isset($_GET['settings'])) { ?>
      <input type="hidden" id="get_started" value="<?=$_GET['settings']?>">
    <?php } ?>

    <?php include "account-setup.php" ?>
    
    <?php if (isset($_GET['reports'])) { ?>
      <input type="hidden" id="get_reports" value="<?=$_GET['reports']?>">
    <?php } ?>
    
    <?php if (isset($_GET['load_gantt'])) { ?>
      <input type="hidden" id="load_gantt_after_setup" value="<?=$_GET['load_gantt']?>">
    <?php } ?>
    
    <?php include "modals-session.php"; ?>
    <?php include "modals-account.php"; ?>
    <?php include "modals-gantt.php"; ?>
    <?php include "modals-resources.php"; ?>
    <?php include "modals-files.php"; ?>
    <?php include "modals-commercial.php"; ?>
    <?php include "modals-activity.php"; ?>
    <?php include "modals-people.php"; ?>
    <?php include "modals-calendars.php"; ?>
    <?php include "modals-reports.php"; ?>

    <div id="st-container" class="st-container">

      <?php include "header.php"; ?>
      <?php include "nav-sidebar.php"; ?>
      <?php include "broadcast.php"; ?>

      <div class="st-pusher">
        <div class="st-content">
          <div id="container" class="container">
    
          <?php if (isset($_GET['version'])) { ?>
            <div id="prompt" class="animated fadeIn">
              <div id="rollback-time"></div>
              <div id="rollback-options">
                <button type="button" id="rollback-confirm" style="">Yes</button>
                <a href="beta.php?id=<?=$_GET['id']?>"><button type="button" id="rollback-cancel">No</button></a>
              </div>
            </div>
          <?php } ?>
            
            <input type="hidden" id="programme_id" value="<?=$_GET['id']?>">
            <input type="hidden" id="user_id" value="<?=$_SESSION['user']['id']?>">
            
          <?php if (isset($_GET['invite'])) { ?>
            <input type="hidden" id="invite_team" value="<?=$_GET['invite']?>">
          <?php } ?>
          
          <?php if (isset($_GET['version'])) { ?>
            <input type="hidden" id="version" value='<?=$_GET['version']?>'>
          <?php } ?>
          
            <input type="hidden" id="version_timestamp" value='<?=$created?>'>
            
          <?php if (isset($gantt_data)) { ?>
            <input type="hidden" id="gantt_data_versioned" value='<?=$gantt_data?>'>
          <?php } ?>
          
          <?php if (isset($range_start_date)) { ?>
            <input type="hidden" id="range_start_date" value='<?=$range_start_date?>'>
          <?php } ?>
          
          <?php if (isset($range_end_date)) { ?>
            <input type="hidden" id="range_end_date" value='<?=$range_end_date?>'>
          <?php } ?>
          
            <div class="tab-content">
            
            <?php include "tabpanel-gantt.php"; ?>
            <?php include "tabpanel-activity.php"; ?>
            <?php include "tabpanel-resources.php"; ?>
            <?php include "tabpanel-files.php"; ?>
            <?php include "tabpanel-teams.php"; ?>
            <?php include "tabpanel-commercial.php"; ?>
            <?php include "tabpanel-reports.php"; ?>
            <?php include "tabpanel-messages.php"; ?>
            
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://v4-alpha.getbootstrap.com/assets/js/ie10-viewport-bug-workaround.js"></script>
    <script src="js/moment2.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.13.0/umd/popper.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/mdb.min.js"></script>
    <script src="js/bootstrap-clockpicker.js"></script>
    <script src="js/bootstrap-colorpicker.js"></script>
    <script src="js/jquery.sortable.min.js"></script>
    <script src="js/notify.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.0/bootstrap-slider.js"></script>
    <script src="js/BootstrapMenu.js"></script>
    <script src="js/contextmenu.js"></script>
    <script src="codebase/ext/dhtmlxgantt_multiselect.js"></script>
    
    <?php include "js-account-setup.php"; ?>
    <?php include "js-gantt-config.php"; ?>
    <?php include "ui-task-editor.php"; ?>
    
    <script>
      $('.filter-tasks').click(function(e) {
        window.ibex_gantt_config.filterType = 1;
        ibex_gantt_config.filterType == 2
        window.ibex_gantt_config.filterValue = $('.search-box').val();
        gantt.refreshData();
      });

      $('.restore-task-view').click(function(e) {
        window.ibex_gantt_config.filterType = 0;
        $('.search-box').val('');
        window.ibex_gantt_config.filterValue = "";
        gantt.refreshData();
      });
      
      $('.highlight-tasks').click(function(e) {
        window.ibex_gantt_config.filterType = 2;
        window.ibex_gantt_config.filterValue = $('.search-box').val();
        gantt.refreshData();
      });

      window.ibex_gantt_config.critical_path_shown = false;

      function matchStart(params, data) {
        // If there are no search terms, return all of the data
        if ($.trim(params.term) === '') {
          return data;
        }
        // Skip if there is no 'children' property
        if (typeof data.children === 'undefined') {
          return null;
        }
        // `data.children` contains the actual options that we are matching against
        var filteredChildren = [];
        $.each(data.children, function(idx, child) {
          if (child.text.toUpperCase().indexOf(params.term.toUpperCase()) == 0) {
            filteredChildren.push(child);
          }
        });
        // If we matched any of the timezone group's children, then set the matched children on the group
        // and return the group object
        if (filteredChildren.length) {
          var modifiedData = $.extend({}, data, true);
          modifiedData.children = filteredChildren;
          // You can return modified objects from here
          // This includes matching the `children` how you want in nested data sets
          return modifiedData;
        }
        // Return `null` if the term should not be displayed
        return null;
      }

      if ($("#version_timestamp").val() != "0") {
        var timeCreated = moment.unix($("#version_timestamp").val());
        var timeAgo = timeCreated.fromNow();
        $("#rollback-time").html('<img style="position: relative; top: -2px;" src="img/svg/rollback.svg"> Roll back to ' + timeAgo + '?')
      }

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

        if (parseInt(calendar['start_hour']) < parseInt(calendar['end_hour'])) {
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
        } else { //night
          var duration = moment.duration(startTime.diff(endTime));
          var minutesInPeriod = Math.abs(duration.asMinutes());
          // Get number of mins in shift first
          var startTimeShift = moment(padLeadingZero(calendar["start_hour"]) + ":" + padLeadingZero(calendar['start_minute']), "HH:mm");
          var endTimeShift = moment(padLeadingZero(calendar["end_hour"]) + ":" + padLeadingZero(calendar['end_minute']), "HH:mm");
          var durationShift = moment.duration(startTimeShift.diff(endTimeShift));
          var shiftMinutes = Math.abs(durationShift.asMilliseconds() / 60 / 1000);
          var shiftNonMinutes = 1440 - shiftMinutes;
          // Get number of mins between now and end of THIS shift
          var startDateTimeThisShift = moment(from).format("HH:mm");
          var startTimeThisShift = moment(startDateTimeThisShift, "HH:mm");
          var endTimeThisShift = moment(padLeadingZero(calendar["end_hour"]) + ":" + padLeadingZero(calendar['end_minute']), "HH:mm");
          var durationThisShift = moment.duration(startTimeThisShift.diff(endTimeThisShift));
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
            endPointer = moment(pointer).add(shiftNonMinutes, 'minutes').format("YYYY-MM-DD HH:mm");
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
        }
        return noWorkShifts;
      }

      window.getTaskEndDate = function getTaskEndDate(startDate, durationMins, calendarID, isMilestone = false) {
        if (isMilestone == true) {

        }
        var calendar;
        if(calendarID) {
          calendar = getCalendar(calendarID);
        } else {
          calendar = getCalendar(1);
        }
        
        // Get number of mins in shift first

        if (parseInt(calendar["start_hour"]) < parseInt(calendar["end_hour"])) { //day schedule
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
        } else { //night schedule
          var startTimeShift = moment(padLeadingZero(calendar["start_hour"]) + ":" + padLeadingZero(calendar['start_minute']), "HH:mm");
          var endTimeShift = moment(padLeadingZero(calendar["end_hour"]) + ":" + padLeadingZero(calendar['end_minute']), "HH:mm");
          var durationShift = moment.duration(endTimeShift.diff(startTimeShift));
          var shiftMinutes = Math.abs(durationShift.asMilliseconds() / 60 / 1000);
          shiftMinutes = 1440 - shiftMinutes;
          // Get number of mins between now and end of THIS shift
          var startDateTimeThisShift = moment(startDate).format("HH:mm");
          var startTimeThisShift = moment(startDateTimeThisShift, "HH:mm");
          var endTimeThisShift = moment(padLeadingZero(calendar["end_hour"]) + ":" + padLeadingZero(calendar['end_minute']), "HH:mm");
          var durationThisShift = moment.duration(endTimeThisShift.diff(startTimeThisShift));
          var thisShiftMinutes = Math.abs(durationThisShift.asMilliseconds() / 60 / 1000);
          // Useful bits
          // var shiftNonMinutes = 1440 - shiftMinutes;
          thisShiftMinutes = 1440 - thisShiftMinutes;
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
        if (mins < 0) {
          var minsAbs = Math.abs(mins);
        } else {
          var minsAbs = mins;
          minsAbs = 1440 - minsAbs;
        }
        // var minsAbs = Math.abs(mins);
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

        if (mins < 0) {
          var minsAbs = Math.abs(mins);
        } else {
          var minsAbs = mins;
          minsAbs = 1440 - minsAbs;
        }
        // var mins = startTimeObject.diff(endTimeObject, 'minutes');
        // var minsAbs = Math.abs(mins);
        var periods = roundToTwo(minutes / minsAbs);
        return periods;
      }

      window.convertPeriodToMinutes = function convertPeriodToMinutes(period, calendarID) {
        var startTime, endTime, calendar;

        $.each(window.ibex_gantt_config.calendars, function(index) {
          if (window.ibex_gantt_config.calendars[index].id == calendarID) {
            startTime = minTwoDigits(window.ibex_gantt_config.calendars[index].start_hour) + ":" + minTwoDigits(window.ibex_gantt_config.calendars[index].start_minute);
            endTime = minTwoDigits(window.ibex_gantt_config.calendars[index].end_hour) + ":" + minTwoDigits(window.ibex_gantt_config.calendars[index].end_minute);
            calendar = window.ibex_gantt_config.calendars[index];
          }
        });

        var startTimeObject = moment(startTime, "HH:mm");
        var endTimeObject = moment(endTime, "HH:mm");

        if (startTimeObject.isAfter(endTimeObject)) {
          endTimeObject.add(1, 'days');
        }

        var mins = startTimeObject.diff(endTimeObject, 'minutes');

        if (mins < 0) {
          var minsAbs = Math.abs(mins);
        } else {
          var minsAbs = mins;
          minsAbs = 1440 - minsAbs;
        }
        // var mins = startTimeObject.diff(endTimeObject, 'minutes');
        // var minsAbs = Math.abs(mins);
        var minsReturn = period * minsAbs;
        return minsReturn;
      }

      window.updateCalendarWorkingDays = function updateCalendarWorkingDays() {
        if ($('#task_edit_calendar_id').val() != null)
          var calendar = getCalendar($('#task_edit_calendar_id').val());
        else
          var calendar = getCalendar($('#task_edit_calendar_id_init').val());
        if (calendar) {
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
          if ($('#task_edit_calendar_id').val() != null)
            var setNext = getNextWorkingDate($('#task_edit_calendar_id').val(), false, dateNow);
          else
            var setNext = getNextWorkingDate($('#task_edit_calendar_id_init').val(), false, dateNow);

          $('#task_edit_start_date').val(moment(setNext, "DD/MM/YYYY").format("ddd D MMM YYYY"));
          $('#task_edit_start_time').val(moment(setNext, "DD/MM/YYYY").format("HH:mm"));
        }
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

        setTimeout(() => {
          // var calendarID = activeTask.calendar_id;
          var calendarID = $("#task_edit_calendar_id").val();
          for (var calendar of window.ibex_gantt_config.calendars) {
            if (calendar.id == calendarID) {
              var startTime = moment(padLeadingZero(calendar.start_hour) + ":" + padLeadingZero(calendar.start_minute), "HH:mm");
              var endTime = moment(padLeadingZero(calendar.end_hour) + ":" + padLeadingZero(calendar.end_minute), "HH:mm");

              if (parseInt(calendar.start_hour) < parseInt(calendar.end_hour)) {
                var duration = moment.duration(endTime.diff(startTime));
                var minutes = parseInt(duration.asMinutes());
              } else {
                var duration = moment.duration(startTime.diff(endTime));
                var minutes = parseInt(duration.asMinutes());
                minutes = 1440 - minutes;
              }
              break;
            }
          }
        });
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

        if (parseInt(calendar.start_hour) < parseInt(calendar.end_hour)) {
          var duration = moment.duration(endTime.diff(startTime));
          var minutesInPeriod = parseInt(duration.asMinutes());
        } else {
          var duration = moment.duration(startTime.diff(endTime));
          var minutesInPeriod = parseInt(duration.asMinutes());
          minutesInPeriod = 1440 - minutesInPeriod;
        }

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

      <?php include "js-settings.php" ?>
      <?php include "js-activity-feed.php" ?>
      <?php include "js-undo-redo.php" ?>
      <?php include "js-sequencing.php" ?>
      <?php include "js-resources.php" ?>
      <?php include "js-task-editor-workload.php" ?>

      function roundToTwo(num) {
        return +(Math.round(num + "e+2") + "e-2");
      }

      window.minTwoDigits = function minTwoDigits(n) {
        return (n < 10 ? '0' : '') + n;
      }

    <?php
      if (!isset($_GET['set'])) {
      //die();
      }
    ?>

      $("#gantt-tab").click(function(e) {
        window.location.href = "beta.php?id=" + $("#programme_id").val();
      });

      $("#activity-tab").click(function(e) {
        $("div#gantt-activity").remove();
      });

      gantt.attachEvent("onBeforeRowDragEnd", function(id, parent, tindex) {
        refreshUIOrder();
        return true;
      });

      window.checkUIViews = function checkUIViews() {
        if (window.ibex_gantt_config.baselinesVisible == true) {
          $(".baseline").show();
        }
        if (window.ibex_gantt_config.deadlinesVisible == true) {
          $(".deadline").show();
        }
      }

      $('.mdb-select').material_select();

      $('.mdb-select').on("change", function() {
        $(".dropdown-content").hide();
        $(".select-dropdown").removeClass("active");
      });

      $(document).on("click", ".undo-version", function() {
        var dataLocal = JSON.stringify(gantt.serialize());
        setTimeout(function() {
          reloadActivityFeed();
        }, 1000);
        $.ajax({
          url: 'beta.ajax.php?action=snapshot_gantt_undo',
          type: 'POST',
          dataType: 'json',
          success: function(data) {},
          data: {
            gantt_data: dataLocal
          }
        });
      });

      $(document).on("click", ".redo-version", function() {
        $.getJSON("beta.ajax.php?action=redo_version", function(data) {
          window.location.reload();
        });
      });

      $(document).on("click", ".toggle-critical-path-visibility", function() {
        if (window.ibex_gantt_config.critical_path_shown == false) {
          window.ibex_gantt_config.critical_path_shown = true;
          $(this).html('<span class="small">Hide critical path</span>');
        } else {
          window.ibex_gantt_config.critical_path_shown = false;
          $(this).html('<span class="small">Show critical path</span>');
        }
        gantt.render();
      });

      setTimeout(function() {
        //$(".account-warning").fadeIn();
      }, 3000);

      $(".show-rows-or-cards").click(function(e) {
        $(".show-rows").hide();
        $(".show-cards").show();
        $("a.edit-resource").addClass("row");
        $(".resource-name").addClass("row");
        $(".resource-group").addClass("row");
        $(".resource-notes").addClass("row");
        $(".resource-company").addClass("row");
        $("#resource-cost-rate").addClass("row");
      });

      $(".show-cards").click(function(e) {
        $(".show-card").hide();
        $(".show-rows").show();
        $("a.edit-resource").removeClass("row");
        $(".resource-name").removeClass("row");
        $(".resource-group").removeClass("row");
        $(".resource-notes").removeClass("row");
        $(".resource-company").removeClass("row");
        $("#resource-cost-rate").removeClass("row");
      });

      $(".reset-permission-group-defaults").click(function(e) {
        var task = gantt.getTask(window.ibex_gantt_config.activeTaskID);
        task.custom_permission_groups = null;
        gantt.updateTask(task.id);
      });

      $(".add-user-group").click(function(e) {
        if ($("#new_user_group_name").val().trim() != '') {
          $.getJSON("beta.ajax.php?action=add_user_group&name=" + $("#new_user_group_name").val(), function(data) {
            $("#new_user_group_name").val('');
            $("#table_groups > tbody").html('');
            $.each(data.user_groups, function(index) {
              $("#table_groups > tbody").append("<tr><td>" + data.user_groups[index].name + "</td><td data-index='" + data.user_groups[index].id + "'></div> <div class='delete-group' data-index='" + data.user_groups[index].id + "'><img src='img/svg/bin-1.svg'></div></td></tr>");
            });
          });
        }
      });

      $(".setup-add-user-group").click(function(e) {
        $("#setup_new_user_group_name").focus();
        if ($("#setup_new_user_group_name").val().trim() != '') {
          $.getJSON("beta.ajax.php?action=add_user_group&name=" + $("#setup_new_user_group_name").val(), function(data) {
            $("#setup_new_user_group_name").val('');
            $("#setup_table_groups > tbody").html('');
            $.each(data.user_groups, function(index) {
              $("#setup_table_groups > tbody").append("<tr><td>" + data.user_groups[index].name + "</td><td data-index='" + data.user_groups[index].id + "'></div> <div class='delete-group' data-index='" + data.user_groups[index].id + "'><img src='img/svg/bin-1.svg'></div></td></tr>");
            });
          });
        }
      });

      $(".undo-last-action").click(function(e) {
        var stack = gantt.getUndoStack();
        gantt.undo();
      });

      $(".redo-last-action").click(function(e) {
        gantt.redo();
      });

    <?php include "js-dependencies.php" ?>

      window.autoScheduleTasks = function autoScheduleTasks() {
        if (window.ibex_gantt_config.autoSchedulerRunning == 0) {
          try {
            window.ibex_gantt_config.autoSchedulerRunning = 1;
            window.ibex_gantt_config.processAutoSchedulingAfterLinkChanges = false;

            gantt.batchUpdate(function() {
              var allLinks = gantt.getLinks();
              $.each(allLinks, function(index) {
                gantt.refreshLink(allLinks[index].id);
                
                var linkID = allLinks[index].id;

                gantt.refreshLink(linkID);
                
                var link = gantt.getLink(linkID);

                var task = gantt.getTask(link.source);
                
                if (task != undefined) {
                  var endSource = moment(task.end_date);
                  var startSource = moment(task.start_date).format("YYYY-MM-DD HH:mm");
                  var existingDurationSource = task.duration_worked;
                  var targetTask = gantt.getTask(link.target);

                  if (task.type == "task" && targetTask.type == "task") {
                    // Both tasks
                    if (task.calendar_id == targetTask.calendar_id) {
                      var offsetDuration = link.offset_minutes;
                      var newSumDuration;
                
                      if (link.offset_type == "1") {
                        newSumDuration = Number(offsetDuration) + Number(existingDurationSource);
                      } else {
                        newSumDuration = Number(existingDurationSource) - Number(offsetDuration);
                      }
                
                      var proposedStartDate = getTaskEndDate(startSource, newSumDuration, task.calendar_id);
                      proposedStartDate = getEncasedStartDateTime(proposedStartDate, task.calendar_id);
                      var proposedEndDate = getTaskEndDate(proposedStartDate, targetTask.duration_worked, targetTask.calendar_id);
                      targetTask.start_date = moment(proposedStartDate).toDate();
                      targetTask.end_date = moment(proposedEndDate).toDate();

                      gantt.updateTask(targetTask.id);
                      if (link.offset_minutes != "0") {
                        if (link.offset_type == "1") {
                          link.color = "#ffc04c";
                        } else {
                          link.color = "#51c185";
                        }
                      } else {
                        link.color = "#999";
                      }
                      gantt.updateLink(link.id);
                    }
                  } else if (task.type == "milestone" && targetTask.type == "task") {
                    // Milestone into task
                    var startSource = moment(task.start_date).format("YYYY-MM-DD HH:mm");
                    var existingDurationSource = 0;
                    var offsetDuration = link.offset_minutes;
                    var newSumDuration;
                
                    if (link.offset_type == "1") {
                      newSumDuration = Number(offsetDuration) + 0;
                    } else {
                      newSumDuration = 0 - Number(offsetDuration);
                    }
                
                    var proposedStartDate = getTaskEndDate(startSource, newSumDuration, task.calendar_id, true);
                    proposedStartDate = getEncasedStartDateTime(proposedStartDate, task.calendar_id, true);
                    targetTask.start_date = moment(proposedStartDate).toDate();
                    var proposedEndDate = getTaskEndDate(proposedStartDate, targetTask.duration_worked, targetTask.calendar_id);
                    targetTask.end_date = moment(proposedEndDate).toDate();
                    gantt.updateTask(targetTask.id);
                
                    if (link.offset_minutes != "0") {
                      if (link.offset_type == "1") {
                        link.color = "#ffc04c";
                      } else {
                        link.color = "#51c185";
                      }
                    } else {
                      link.color = "#999";
                    }
                    gantt.updateLink(link.id);
                  } else if (task.type == "task" && targetTask.type == "milestone") {
                    // Task into milestone
                    var startSource = moment(task.start_date).format("YYYY-MM-DD HH:mm");
                    var existingDurationSource = task.duration_worked;
                    var offsetDuration = link.offset_minutes;
                    var newSumDuration;
                    if (link.offset_type == "1") {
                      newSumDuration = Number(offsetDuration) + Number(existingDurationSource);
                    } else {
                      newSumDuration = Number(existingDurationSource) - Number(offsetDuration);
                    }
                    var proposedStartDate = getTaskEndDate(startSource, newSumDuration, task.calendar_id);
                    proposedStartDate = getEncasedStartDateTime(proposedStartDate, task.calendar_id);
                    targetTask.start_date = moment(proposedStartDate).toDate();
                    targetTask.end_date = moment(proposedStartDate).toDate();
                    gantt.updateTask(targetTask.id);
                    if (link.offset_minutes != "0") {
                      if (link.offset_type == "1") {
                        link.color = "#ffc04c";
                      } else {
                        link.color = "#51c185";
                      }
                    } else {
                      link.color = "#999";
                    }
                    gantt.updateLink(link.id);
                  }
                }
              });
            });
            gantt.render();
            checkUIViews();
          } catch (er1) {
            //alert(er1);
          }
          window.ibex_gantt_config.autoSchedulerRunning = 0;
          if (window.ibex_gantt_config.reloadPageAfterScheduling == 1) {
            window.ibex_gantt_config.reloadPageAfterScheduling = 0;
            location.reload();
          }
        }
      }

      $(document).on('click', '.undo-ui', function(e) {
        gantt.undo();
      });

      $(document).on('click', '.redo-ui', function(e) {
        gantt.redo();
      });

      $(document).on('click', '.reset-programme-confirm', function(e) {
        $.getJSON("beta.ajax.php?action=reset_programme", function(data) {
          //  location.reload();
          window.location.href = "beta.php?id=" + $("#programme_id").val() + "&settings=true";

          $('#welcome').removeClass("show active");
          $('#work-environment').removeClass("show active");
          $('#create-project').addClass("show active");
        });
      });

      function set_scale_units(mode) {
        if (mode && mode.getAttribute) {
          mode = mode.getAttribute("value");
        }

        switch (mode) {
          case "work_hours":
            gantt.config.subscales = [{
              unit: "hour",
              step: 1,
              date: "%H"
            }];
            gantt.ignore_time = function(date) {
              if (date.getHours() < 9 || date.getHours() > 16) {
                return true;
              } else {
                return false;
              }
            };
            break;
          case "full_day":
            gantt.config.subscales = [{
              unit: "hour",
              step: 3,
              date: "%H"
            }];
            gantt.ignore_time = null;
            break;
          case "work_week":
            gantt.ignore_time = function(date) {
              if (date.getDay() == 0 || date.getDay() == 6) {
                return true;
              } else {
                return false;
              }
            };
            break;
          default:
            gantt.ignore_time = null;
            break;
        }
        gantt.render();
      }

      // Default scale
      gantt.config.task_height = 24;
      gantt.config.scale_height = 50;
      gantt.config.scale_unit = "day";
      gantt.config.date_scale = "%D %d %M";
      gantt.config.subscales = [{
        unit: "month",
        step: 1,
        date: "%Y"
      }];

    <?php include "js-calendars.php" ?>

      $(".toggle-width-task-editor").click(function() {
        if ($(".modal-dialog-task-editor").hasClass("modal-lg")) {
          $(".modal-dialog-task-editor").removeClass("modal-lg").addClass("modal-sm");
        } else {
          $(".modal-dialog-task-editor").removeClass("modal-sm").addClass("modal-lg");
        }
      });

      function shouldHighlightTask(task) {
        var store = gantt.$resourcesStore;
        var taskResource = task[gantt.config.resource_property],
          selectedResource = store.getSelectedId();

        if (taskResource == selectedResource || store.isChildOf(taskResource, selectedResource)) {
          return true;
        }
      }

      // QUARTERS
      var quarterScaleTemplate = function(date) {
        var dateToStr = gantt.date.date_to_str("%M");
        var endDate = gantt.date.add(date, 2, "month");
        return dateToStr(date) + " - " + dateToStr(endDate);
      };

      $("#btn-zoom-quarters").click(function(e) {
        window.ibex_gantt_config.currentZoomLevel = "quarter";
        gantt.config.scale_unit = "quarter";
        gantt.config.date_scale = "%Y";
        gantt.config.subscales = [{
          unit: "quarter",
          step: 1,
          date: "%F",
          template: quarterScaleTemplate
        }];
        gantt.render();
      });

      // WEEKS
      var weekScaleTemplate = function(date) {
        var dateToStr = gantt.date.date_to_str("%d %M");
        var endDate = gantt.date.add(gantt.date.add(date, 1, "week"), -1, "day");
        return dateToStr(date) + " - " + dateToStr(endDate);
      };

      $("#btn-zoom-weeks").click(function(e) {
        window.ibex_gantt_config.currentZoomLevel = "month";
        gantt.config.scale_unit = "week";
        gantt.config.date_scale = "%Y (Week %W)";
        gantt.config.min_column_width = "100";
        gantt.config.subscales = [{
          unit: "week",
          step: 1,
          date: "%D %d %Y",
          template: weekScaleTemplate
        }];
        gantt.render();
      });

      // DAYS
      $("#btn-zoom-days").click(function(e) {
        window.ibex_gantt_config.currentZoomLevel = "day";
        gantt.config.task_height = 24;
        gantt.config.scale_height = 50;
        gantt.config.scale_unit = "day";
        gantt.config.date_scale = "%D %d %M";
        gantt.config.min_column_width = "70";
        gantt.config.subscales = [{
          unit: "month",
          step: 1,
          date: "%Y"
        }];
        gantt.render();
      });

      // HOURS
      $("#btn-zoom-hours").click(function(e) {
        window.ibex_gantt_config.currentZoomLevel = "hour";
        gantt.config.task_height = 24;
        gantt.config.scale_height = 50;
        gantt.config.scale_unit = "hour";
        gantt.config.date_scale = "%D %d %M";
        gantt.config.min_column_width = "70";
        gantt.config.subscales = [{
          unit: "hour",
          step: 1,
          date: "%H %i"
        }];
        gantt.render();
      });

      gantt.locale.labels.section_owner = "Owner";
      gantt.config.lightbox.sections = [
        {
          name: "description",
          height: 38,
          map_to: "text",
          type: "textarea",
          focus: true
        },
        {
          name: "owner",
          height: 22,
          map_to: "owner_id",
          type: "select",
          options: gantt.serverList("people")
        },
        {
          name: "time",
          type: "duration",
          map_to: "auto"
        }
      ];

      function getResourceTasks(resourceId) {
      }

      gantt.attachEvent("onGanttRender", function() {
        if (window.ibex_gantt_config.baselinesVisible == true) {
          $(".baseline").show();
        }
        if (window.ibex_gantt_config.deadlinesVisible == true) {
          $(".deadline").show();
        }
      });

      var resourceMode = "hours";

      gantt.attachEvent("onGanttReady", function() {
        var radios = [].slice.call(gantt.$container.querySelectorAll("input[type='radio']"));
        radios.forEach(function(r) {
          gantt.event(r, "change", function(e) {
            var radios = [].slice.call(gantt.$container.querySelectorAll("input[type='radio']"));
            radios.forEach(function(r) {
              r.parentNode.className = r.parentNode.className.replace("active", "");
            });

            if (this.checked) {
              resourceMode = this.value;
              this.parentNode.className += " active";
              gantt.getDatastore(gantt.config.resource_store).refresh();
            }
          });
        });
      });

      gantt.$resourcesStore = gantt.createDatastore({
        name: gantt.config.resource_store,
        type: "treeDatastore",
        initItem: function(item) {
          item.parent = item.parent || gantt.config.root_id;
          item[gantt.config.resource_property] = item.parent;
          item.open = true;
          return item;
        }
      });

      gantt.$resourcesStore.attachEvent("onAfterSelect", function(id) {
        gantt.refreshData();
      });

      function toggleGroups(input) {
        gantt.$groupMode = !gantt.$groupMode;
        if (gantt.$groupMode) {
          input.value = "show gantt view";
          var groups = gantt.$resourcesStore.getItems().map(function(item) {
            var group = gantt.copy(item);
            group.group_id = group.id;
            group.id = gantt.uid();
            return group;
          });
          gantt.groupBy({
            groups: groups,
            relation_property: gantt.config.resource_property,
            group_id: "group_id",
            group_text: "text"
          });
        } else {
          input.value = "show resource view";
          gantt.groupBy(false);
        }
      }

      gantt.$resourcesStore.attachEvent("onParse", function() {
        var people = [];
        gantt.$resourcesStore.eachItem(function(res) {
          if (!gantt.$resourcesStore.hasChild(res.id)) {
            var copy = gantt.copy(res);
            copy.key = parseInt(res.id);
            copy.label = res.text;
            copy.resource_id = parseInt(res.id);
            people.push(copy);
          }
        });
        gantt.updateCollection("people", people);
      });

      window.reloadSettings = function reloadSettings() {
        $.getJSON("beta.ajax.php?action=reload_gantt", function(data) {
          window.ibex_gantt_config.calendars = data.calendars;
          window.ibex_gantt_config.calendarOverrides = data.calendar_overrides;
          window.ibex_gantt_config.settings = data.settings;
          loadCalendarsToUI();

          // Cleanu any leftovers
          $("#modal_calendar_editor").modal('hide');
        });
      }

      $(document).on('click', '.toggle-default-group-permission', function(e) {
        var groupArray = {};
        var count = 0;
        $(".toggle-default-group-permission").each(function() {
          var checked = false;
          if ($(this).is(':checked')) {
            checked = true;
            //groupArray.push($(this).data("group"));
          }
          groupArray[$(this).data("group")] = checked;
          count++;
        });

        $.getJSON("beta.ajax.php?action=update_default_permission_groups&data=" + JSON.stringify(groupArray), function(data) {
        });
      });

      $(document).on('click', '.toggle-default-permission-set', function(e) {
        var groupArray = {};
        $('.toggle-default-permission-set').each(function() {
          var checked = false;
          if ($(this).is(':checked')) {
            checked = true;
            //groupArray.push($(this).data("group"));
          }
          groupArray["group_" + $(this).data("group") + "_set_" + $(this).data('set')] = checked;
        });
        $.getJSON("beta.ajax.php?action=update_default_permission_sets&data=" + JSON.stringify(groupArray), function(data) {
        });
      });

      $.getJSON("beta.ajax.php?action=load_gantt")
        .done(function(data) {
          console.log("done");
          console.log(data);
          // Cache task resource links
          window.ibex_gantt_config.resources = data.resources;
          window.ibex_gantt_config.resource_groups = data.resource_groups;
          window.ibex_gantt_config.calendars = data.calendars;
          window.ibex_gantt_config.calendarOverrides = data.calendar_overrides;
          window.ibex_gantt_config.columns = data.columns;
          window.ibex_gantt_config.settings = data.settings;
          window.ibex_gantt_config.files = data.files;
          window.ibex_gantt_config.groups = data.groups;
          window.ibex_gantt_config.userGroups = data.user_groups;
          window.ibex_gantt_config.defaultPermissionGroups = data.settings.default_permission_groups;
          window.ibex_gantt_config.defaultPermissionSets = data.settings.default_permission_sets;
          window.ibex_gantt_config.summaryTaskArray = data.summary_tasks;
          $('.mdb-select').material_select('destroy');
          window.ibex_gantt_config.periodDescriptor = data.settings.period_descriptor;
          //$("#settings_timing_unit").val(data.settings.timing_unit);
          $(".timing-unit").removeClass("active-setting");
          $('.timing-unit').each(function(i, obj) {
            if (parseInt($(this).data("index")) == parseInt(data.settings.timing_unit)) {
              $(this).addClass("active-setting");
            }
          });
          //$("#settings_new_task_behaviour").val(data.settings.task_insertion_mode);
          $(".task-placement").removeClass("active-setting");
          $('.task-placement').each(function(i, obj) {
            if (parseInt($(this).data("index")) == parseInt(data.settings.task_insertion_mode)) {
              $(this).addClass("active-setting");
            }
          });
          window.ibex_gantt_config.taskInsertionMethod = data.settings.task_insertion_mode;
          window.ibex_gantt_config.autoSchedulerActive = data.settings.automatic_scheduling_enabled;
          //$("#settings_new_task_behaviour").val(data.settings.task_insertion_mode);
          $(".auto-scheduler").removeClass("active-setting");
          $('.auto-scheduler').each(function(i, obj) {
            if (parseInt($(this).data("index")) == parseInt(data.settings.automatic_scheduling_enabled)) {
              $(this).addClass("active-setting");
            }
          });
          window.ibex_gantt_config.autoSchedulerRunning = false;
          $("#settings_automatic_scheduling").val(data.settings.automatic_scheduling_enabled);
          window.ibex_gantt_config.periodDescriptor = data.settings.period_descriptor;
          if (window.ibex_gantt_config.periodDescriptor == "5") {
            $("#period-descriptors-wrapper").show();
          }
          window.ibex_gantt_config.periodDescriptorTextSingular = data.settings.period_descriptor_text_singular;
          window.ibex_gantt_config.periodDescriptorTextPlural = data.settings.period_descriptor_text_plural;
          $("#period_descriptor_singular").val(data.settings.period_descriptor_text_singular);
          $("#period_descriptor_plural").val(data.settings.period_descriptor_text_plural);
          $("#settings_period_descriptor").val(data.settings.period_descriptor);
          window.ibex_gantt_config.periodDescriptorText = data.settings.period_descriptor_text;
          $('.mdb-select').material_select();
          // Set settings
          $(".container-permission-groups-settings").html('');
          var permissionGroups = JSON.parse(window.ibex_gantt_config.defaultPermissionGroups);
          var permissionSets = JSON.parse(window.ibex_gantt_config.defaultPermissionSets);
          $("#settings_duration_unit").val(data.settings.duration_unit).change();
          $("#settings_default_unit").val(data.settings.time_unit);
          $("#settings_automatic_scheduling").val(data.settings.automatic_scheduling_enabled);
          $("#settings_task_insertion_method").val(data.settings.task_insertion_mode);
          var resourceGroups = window.ibex_gantt_config.resource_groups;
          var parsedGroups = [];
          // Add resources to UI and datastores
          $("#table_resource_groups > tbody").empty();
          $.each(data.resource_groups, function(index) {
            var uiObject = {
              id: data.resource_groups[index].id,
              text: data.resource_groups[index].name,
              is_group: data.resource_groups[index].is_group,
              parent: 0
            };
            parsedGroups.push(uiObject);
            $("#table_resource_groups > tbody").append("<tr><td>" + data.resource_groups[index].name + "</td><td style='text-align: right'><span aria-hidden='true'><img src='img/svg/edit.svg' class='edit-resource-group' data-index='" + data.resource_groups[index].id + "'></img></span></td></tr>");
          });
          gantt.$resourcesStore.parse(parsedGroups);
          $("#table_resources").empty();
          if (window.ibex_gantt_config.resources.length == 0) {
            $('.no-resources').show();
            $('#resources-header-toolbar').hide();
          } else {
            $(".no-resources").hide();
            $('#resources-header-toolbar').show();
          }
          for (var resource of window.ibex_gantt_config.resources) {
            var parentGroupName;
            for (var resourceGroup of window.ibex_gantt_config.resource_groups) {
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
            $('#table_resources').append("<tr><td><img style='width: 100px;' src='" + resource.resource_image_url + "'></td><td>" + resource.name + "</td><td class='edit-resource' data-index='" + resource.id + "'><img style='cursor: pointer;' src='img/svg/edit.svg'></td></tr>");
            $('#setup_table_resources').append("<tr><td><img style='width: 100px;' src='" + resource.resource_image_url + "'></td><td>" + resource.name + "</td><td class='edit-resource' data-index='" + resource.id + "'><img style='cursor: pointer;' src='img/svg/edit.svg'></td></tr>");
          }
          // Add calendars
          loadCalendarsToUI();

          // Do resource columns
          window.ibex_gantt_config.resourceColumnArray = [];
          var columns = JSON.parse(data.columns.resource_columns);
          for (var i = 0; i < columns.length; i++) {
            var obj = columns[i];
            for (var key in obj) {
              var attrName = key;
              var attrValue = obj[key];
              if (attrValue == true) {
                var columnInsert = prepareResourceColumnInsert(key);
                window.ibex_gantt_config.resourceColumnArray.push(columnInsert);
              }
            }
          }

          // Do columns
          var columnArray = [];
          var columns = JSON.parse(data.columns.task_columns);
          for (var i = 0; i < columns.length; i++) {
            var obj = columns[i];
            for (var key in obj) {
              var attrName = key;
              var attrValue = obj[key];
              if (attrValue == true) {
                var columnInsert = prepareColumnInsert(key);
                columnArray.push(columnInsert);
              }
            }
          }
          columnArray.push({
            name: "add",
            label: "",
            width: 44
          });
          gantt.config.columns = columnArray;

          window.loadResources = function loadResources() {
            $.getJSON("beta.ajax.php?action=reload_gantt", function(data) {
              window.ibex_gantt_config.resources = data.resources;
              $("#table_resources").empty();
              if (window.ibex_gantt_config.resources.length == 0) {
                $('.no-resources').show();
                $('#resources-header-toolbar').hide();
              } else {
                $(".no-resources").hide();
                $('#resources-header-toolbar').show();
              }
              for (var resource of window.ibex_gantt_config.resources) {
                var parentGroupName;
                for (var resourceGroup of window.ibex_gantt_config.resource_groups) {
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
                $('#table_resources').append("<tr><td><img style='width: 100px;' src='" + resource.resource_image_url + "'></td><td>" + resource.name + "</td></td><td class='edit-resource' data-index='" + resource.id + "'</td><img style='cursor: pointer;' src='img/svg/edit.svg'></tr>");
              }
            })
          }

          var onEmptyClickPreviousElement = null;
          gantt.attachEvent("onEmptyClick", function(e) {
            if (onEmptyClickPreviousElement == e.target) {
              onEmptyClickPreviousElement = null;
              if (e.target.className == "gantt_task_cell" || e.target.className == "gantt_cell" || e.target.classList[0] == "gantt_cell") {
                var id = e.target.parentNode.attributes[1].nodeValue;
                var name = e.target.parentNode.attributes[1].nodeName;
                if (name == 'resource_id') {
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
                  $.getJSON("beta.ajax.php?action=get_resource&id=" + id, function(data) {
                    $('.mdb-select').material_select('destroy');
                    $("#resource_edit_id").val(data.resource.id);
                    $("#resource_edit_name").val(data.resource.name).trigger("change");
                    $("#resource_edit_parent").val(data.resource.group_id);
                    $("#resource_edit_calendar_id").val(data.resource.calendar_id);
                    $("#resource_edit_notes").val(data.resource.notes).trigger("change");
                    $("#resource_edit_cost_rate").val(data.resource.cost_rate).trigger("change");
                    $("#resource_edit_company").val(data.resource.company).trigger("change");
                    $("#resource_edit_unit_of_measure").val(data.resource.unit_of_measure).trigger("change");
                    $('.mdb-select').material_select();
                    $(".delete-resource").show();
                    $.each(data.linked_tasks, function(index) {
                      $('#table_resource_linked_tasks > tbody').append("<tr class='resource-task-allocation'><td>" + data.linked_tasks[index].task_text + "</td></tr>");
                    });
                    $("#modal_resource_editor").modal('show');
                  });
                }
              }
              if (e.target.className == "gantt_tree_content" || e.target.classList[0] == "gantt_tree_icon") {
                var id = e.target.parentNode.parentNode.attributes[1].nodeValue;
                var name = e.target.parentNode.parentNode.attributes[1].nodeName;
                if (name == 'resource_id') {
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

                  $.getJSON("beta.ajax.php?action=get_resource&id=" + id, function(data) {
                    $('.mdb-select').material_select('destroy');
                    $("#resource_edit_id").val(data.resource.id);
                    $("#resource_edit_name").val(data.resource.name).trigger("change");
                    $("#resource_edit_parent").val(data.resource.group_id);
                    $("#resource_edit_calendar_id").val(data.resource.calendar_id);
                    $("#resource_edit_notes").val(data.resource.notes).trigger("change");
                    $("#resource_edit_cost_rate").val(data.resource.cost_rate).trigger("change");
                    $("#resource_edit_company").val(data.resource.company).trigger("change");
                    $("#resource_edit_unit_of_measure").val(data.resource.unit_of_measure).trigger("change");
                    $('.mdb-select').material_select();
                    $(".delete-resource").show();

                    $.each(data.linked_tasks, function(index) {
                      $('#table_resource_linked_tasks > tbody').append("<tr class='resource-task-allocation'><td>" + data.linked_tasks[index].task_text + "</td></tr>");
                    });

                    $("#modal_resource_editor").modal('show');
                  });
                }
              }
            }
            onEmptyClickPreviousElement = e.target;
          });

          <?php include "js-columns.php" ?>
          <?php include "js-context-menu.php" ?>
          
          $(document).on('change', '#resource_edit_parent', function(e) {
            // Get resource group units, then lock this resource to them
            $.getJSON("beta.ajax.php?action=get_resource_group&id=" + $(this).val(), function(data) {
              $('.mdb-select').material_select('destroy');
    
              if (data.resource_group.outputs_unit == "hr") {
                $("#resource_edit_unit_of_measure").val("4")
              }
    
              if (data.resource_group.outputs_unit == "day") {
                $("#resource_edit_unit_of_measure").val("5")
              }
    
              if (data.resource_group.outputs_unit == "week") {
                $("#resource_edit_unit_of_measure").val("6")
              }
    
              if (data.resource_group.outputs_unit == "month") {
                $("#resource_edit_unit_of_measure").val("7")
              }
    
              if (data.resource_group.outputs_unit == "item") {
                $("#resource_edit_unit_of_measure").val("2")
              }
    
              if (data.resource_group.outputs_unit == "no") {
                $("#resource_edit_unit_of_measure").val("1")
              }
    
              if (data.resource_group.outputs_unit == "mm") {
                $("#resource_edit_unit_of_measure").val("8")
              }
    
              if (data.resource_group.outputs_unit == "m") {
                $("#resource_edit_unit_of_measure").val("9")
              }
    
              if (data.resource_group.outputs_unit == "km") {
                $("#resource_edit_unit_of_measure").val("10")
              }
    
              if (data.resource_group.outputs_unit == "m2") {
                $("#resource_edit_unit_of_measure").val("11")
              }
    
              if (data.resource_group.outputs_unit == "km2") {
                $("#resource_edit_unit_of_measure").val("12")
              }
    
              if (data.resource_group.outputs_unit == "kg") {
                $("#resource_edit_unit_of_measure").val("13")
              }
    
              if (data.resource_group.outputs_unit == "t") {
                $("#resource_edit_unit_of_measure").val("14")
              }
    
              if (data.resource_group.outputs_unit == "m3") {
                $("#resource_edit_unit_of_measure").val("15")
              }
    
              if (data.resource_group.outputs_unit == "l") {
                $("#resource_edit_unit_of_measure").val("16")
              }
    
              $("#resource_edit_unit_of_measure").attr('disabled', 'disabled');
              $('.mdb-select').material_select();
            });
          });

          $(document).on('change', '#resource_group_outputs_unit', function(e) {
            if ($(this).val() == "hr" || $(this).val() == "day" || $(this).val() == "week" || $(this).val() == "month") {
              $(".col-outputs-period").hide();
            } else {
              $(".col-outputs-period").show();
            }
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

          $.each(data.resources, function(index) {
            gantt.$resourcesStore.parse([{
              id: parseInt(data.resources[index].id),
              text: data.resources[index].name,
              calendar_id: data.resources[index].calendar_id,
              cost_rate: data.resources[index].cost_rate,
              notes: data.resources[index].notes,
              unit_of_measure: data.resources[index].unit_of_measure,
              company: data.resources[index].company,
              is_group: data.resources[index].is_group,
              parent: data.resources[index].group_id,
              outputs_unit: data.resources[index].outputs_unit,
            }]);
          });

          window.ibex_gantt_config.initialDataLoaded = true;

          // Global calendar, 6 - 6, no sat sun
          gantt.setWorkTime({
            hours: ["6:00-18:00"]
          });
    
          gantt.setWorkTime({
            day: 6,
            hours: false
          });
    
          gantt.setWorkTime({
            day: 7,
            hours: false
          });

          var calendars = gantt.getCalendars();
    
          gantt.config.duration_unit = "day";
          gantt.config.duration_step = 1;
          gantt.config.work_time = false;
          gantt.config.correct_work_time = false;
          gantt.config.autofit = true;
          gantt.config.touch = "force";
          gantt.config.auto_scheduling = true;
          gantt.config.work_time = true;
          gantt.config.auto_scheduling_strict = true;
          gantt.config.resource_store = "resource";
          gantt.config.resource_property = "resource_id";
          gantt.config.order_branch = true;
          gantt.config.open_tree_initially = true;
          gantt.config.scale_height = 50;
          gantt.config.min_grid_column_width = 30;
          gantt.config.fit_tasks = true;

          gantt.init("#gantt_here");
          if ($("#gantt_data_versioned").val() == undefined) {
            console.log("No Version");
            console.log(gantt.version);
            // gantt.load("data.php");
            // $.ajax({
            //   url: "data.php",
            //   type: "post",
            //   success: function(data) {
            //     console.log(JSON.parse(data));
            //     gantt.parse(JSON.parse(data));
            //   }
            // });
            gantt.parse({
              data: [
                { id: 1, text: "Project #2", start_date: "01-04-2023", duration: 18, progress: 0.4, open: true, resource_group_id: 0 },
                { id: 2, text: "Task #1", start_date: "02-04-2023", duration: 8, progress: 0.6, parent: 1, resource_group_id: 0 },
                { id: 3, text: "Task #2", start_date: "11-04-2023", duration: 8, progress: 0.6, parent: 1, resource_group_id: 0 }
              ],
              links: [
                {id: 1, source: 1, target: 2, type: "1"},
                {id: 2, source: 2, target: 3, type: "0"}
              ]
            });
            // gantt.addTask({ id: 1, text: "Project #2", start_date: "01-04-2023", duration: 18, progress: 0.4, open: true, resource_group_id: 0 }, 0);
            // gantt.render();
            // gantt.refreshData();
          } else {
            console.log("Version");
            gantt.render();
            // gantt.load("beta.ajax.php?action=prepare_rollback_data&guid=<?php // echo $_GET['version']?>");
          }
          // setInterval(() => {
          //   console.log(gantt.serialize());
          //   gantt.refreshData();
          // }, 10000);
          debugger;
          var dp = new gantt.dataProcessor("data.php");
          dp.setTransactionMode("GET", true);
          dp.enableDebug(true);
          dp.init(gantt);
          gantt.render();
          $("#rollback-confirm").click(function(e) {
            window.ibex_gantt_config.rollbackActive = true;
            $("#prompt").hide();
            $(".container").hide();
            $.getJSON("beta.ajax.php?action=purge_programme&id=" + $("#programme_id").val() + "&version=" + $("#version").val())
              .done(function(data) {
                window.location.href = "beta.php?id=" + $("#programme_id").val();
              })
              .fail(function(data) {
                console.log(data);
              })
          });

          window.createSnapshot = function createSnapshot() {
            window.ibex_gantt_config.snapshotData = JSON.stringify(gantt.serialize());
          }

          window.forceSnapshotUpdate = function forceSnapshotUpdate() {
            //window.ibex_gantt_config.snapshotData = JSON.stringify(gantt.serialize());
            if (window.ibex_gantt_config.lastSnapshotTime == 0 || moment.unix() - window.ibex_gantt_config.lastSnapshotTime > 1) {
              setTimeout(function() {
                reloadActivityFeed();
              }, 1000);
            } else {
            }
            $.ajax({
              url: 'beta.ajax.php?action=snapshot_gantt',
              type: 'POST',
              dataType: 'json',
              success: function(data) {
                var lastSaveTime = data.save_time;
                window.ibex_gantt_config.lastSaveTime = Number(lastSaveTime) + 1;
              },
              data: {
                primary_guid: window.ibex_gantt_config.activityPrimaryGUID,
                secondary_guid: window.ibex_gantt_config.activitySecondaryGUID,
                action_type: window.ibex_gantt_config.activityAction,
                type: window.ibex_gantt_config.activityType,
                info: window.ibex_gantt_config.activityInfo,
                gantt_data: window.ibex_gantt_config.snapshotData
              }
            });
            window.ibex_gantt_config.lastSnapshotTime = moment().unix();
          }

          dp.attachEvent("onBeforeUpdate", function(id, action, tid, response) {
            // Check if task!
            var testTask = gantt.getTask(id);
            if (testTask != undefined && testTask != "undefined" && testTask.start_date != "undefined" && testTask.start_date != undefined) {
              var startDatePreLock = moment(gantt.getTask(id).start_date).format("HH:mm:ss");
              if (startDatePreLock == "00:00:00") {
                return false;
              }
            }
            return true;
          });

          dp.attachEvent("onAfterUpdate", function(id, action, tid, response) {
            
            forceSnapshotUpdate();
            checkUIViews();
            if (action == "inserted") {
              var task = gantt.getTask(tid);
              gantt.addTaskLayer(function(task) {
                if (task.type == "task") {
                  if (task.is_summary == "1") {
                    return "";
                  } else {
                    var from = new Date(task.start_date);
                    var to = new Date(task.end_date);
                    var noWorkShifts = getNonWorkingPeriods(from, to, task.calendar_id, task.end_date);
                    var shiftsDiv = document.createElement("div");
                    for (var i = 0; i < noWorkShifts.length; i++) {
                      var sizes = gantt.getTaskPosition(task, noWorkShifts[i].start_date, noWorkShifts[i].end_date);
                      var el = document.createElement("div");
                      el.className = "no-work-shift task-layer-" + task.guid;
                      el.style.left = sizes.left + 'px';
                      el.style.top = sizes.top + 4 + 'px';
                      el.style.width = sizes.width + 'px';
                      el.style.height = (sizes.height - 10) + 'px';
                      shiftsDiv.appendChild(el);
                    }
                    return shiftsDiv;
                  }
                }
              });
              
              gantt.render();
            }
          })

          var shiftStart = 6 * 60,
            shiftEnd = 18 * 60;

          function getNextDate(date) {
            var dateMinutes = getMinutesValue(date);

            if (dateMinutes < shiftStart) {
              return gantt.date.add(gantt.date.day_start(new Date(date)), shiftStart, "minute");
            } else if (dateMinutes < shiftEnd) {
              return gantt.date.add(gantt.date.day_start(new Date(date)), shiftEnd, "minute");
            } else {
              return gantt.date.add(gantt.date.add(gantt.date.day_start(new Date(date)), shiftStart, "minute"), 1, "day");
            }
          }

          function getMinutesValue(date) {
            return date.getHours() * 60 + date.getMinutes();
          }

          function isWorkShift(date) {
            var dateValue = getMinutesValue(date);
            if (!gantt.isWorkTime(new Date(date))) {
              return false;
            }

            if (dateValue < shiftEnd && dateValue >= shiftStart) {
              return true;
            } else {
              return false;
            }
          }

          window.isDateWorkingDate = function isDateWorkingDate(date, calendarID) {
            if (date == undefined || calendarID == undefined || calendarID == 0) {
              return true;
            }

            var validDay = false;
            var calendar = getCalendar(calendarID);

            if (calendar) {
              var calendarOverrides = getCalendarOverrides(calendarID);

              if (calendarOverrides.length != 0) {
                for (var override of calendarOverrides) {
                  var dateCompare = moment(date);
                  var startDate = moment(override['start_date']);
                  var endDate = moment(override['end_date']);

                  if (dateCompare.isBetween(startDate, endDate, null, []) == true) {
                    validDay = false;
                    validDay = true;
                    break;
                  } else {
                    validDay = true;
                  }
                }
              } else {
                validDay = true;
              }

              if (validDay == true) {
                switch (moment(date).isoWeekday()) {
                  case 1:
                    if (calendar.working_day_monday != "1") {
                      validDay = false;
                    }
                    break;
                  case 2:
                    if (calendar.working_day_tuesday != "1") {
                      validDay = false;
                    }
                    break;
                  case 3:
                    if (calendar.working_day_wednesday != "1") {
                      validDay = false;
                    }
                    break;
                  case 4:
                    if (calendar.working_day_thursday != "1") {
                      validDay = false;
                    }
                    break;
                  case 5:
                    if (calendar.working_day_friday != "1") {
                      validDay = false;
                    }
                    break;
                  case 6:
                    if (calendar.working_day_saturday != "1") {
                      validDay = false;
                    }
                    break;
                  case 7:
                    if (calendar.working_day_sunday != "1") {
                      validDay = false;
                    }
                    break;
                }
                return validDay;
              } else {
                return false;
              }
            } else {
              return false;
            }
          }

          gantt.templates.task_cell_class = function(task, date) {
            if (task.calendar_id != undefined && task.calendar_id != 0) {
              if (isDateWorkingDate(date, task.calendar_id) == false) {
                return "non_working";
              }
            }
            return "";
          };

          window.addDaysToDate = function addDaysToDate(calendarID, startDate, days, showPreviousStopTime = false) {
            var validDay = false;
            var date;
            var added = 0;
            var calendar = getCalendar(calendarID);
            var calendarOverrides = getCalendarOverrides(calendarID);

            date = moment(startDate).format("YYYY-MM-DD");

            do {
              var validDayLoop = false;

              for (var override of calendarOverrides) {
                var dateCompare = moment(date);
                var startDate = moment(override['start_date']);
                var endDate = moment(override['end_date']);

                if (dateCompare.isBetween(startDate, endDate, null, []) == true) {
                  validDayLoop = false;
                  break;
                } else {
                  validDayLoop = true;
                }
              }

              if (validDayLoop == true) {
                switch (moment(date).isoWeekday()) {
                  case 1:
                    if (calendar.working_day_monday == "1") {
                      validDayLoop = true;
                    } else {
                      validDayLoop = false;
                    }
                    break;
                  case 2:
                    if (calendar.working_day_tuesday == "1") {
                      validDayLoop = true;
                    } else {
                      validDayLoop = false;
                    }
                    break;
                  case 3:
                    if (calendar.working_day_wednesday == "1") {
                      validDayLoop = true;
                    } else {
                      validDayLoop = false;
                    }
                    break;
                  case 4:
                    if (calendar.working_day_thursday == "1") {
                      validDayLoop = true;
                    } else {
                      validDayLoop = false;
                    }
                    break;
                  case 5:
                    if (calendar.working_day_friday == "1") {
                      validDayLoop = true;
                    } else {
                      validDayLoop = false;
                    }
                    break;
                  case 6:
                    if (calendar.working_day_saturday == "1") {
                      validDayLoop = true;
                    } else {
                      validDayLoop = false;
                    }
                    break;
                  case 7:
                    if (calendar.working_day_sunday == "1") {
                      validDayLoop = true;
                    } else {
                      validDayLoop = false;
                    }
                    break;
                }
              }

              if (validDayLoop == false) {
                date = moment(date).add(1, 'day').format("YYYY-MM-DD");
              } else {
                validDay = true;
                added++;
                if (added == days) {
                  break;
                }
                date = moment(date).add(1, 'day').format("YYYY-MM-DD");
              }
            }
            while (added < days);

            return moment(date).format("DD/MM/YYYY") + " " + padLeadingZero(calendar.start_hour) + ":" + padLeadingZero(calendar.start_minute);
          }

          window.calculateTaskEndDate = function calculateTaskEndDate(startDate, duration, calendarID) {
            // Get end of calendar
            var endTime, shiftLength, remainderLength, iterated = 1,
              loopDate, localDate;
            for (var calendar of window.ibex_gantt_config.calendars) {
              if (calendar['id'] == calendarID) {
                endTime = calendar['end_time'];
                shiftLength = Math.abs(calendar['end_hour'] - calendar['start_hour']);
                remainderLength = 24 - shiftLength;
                break;
              }
            }

            // Return single block by adding shift hours to start time
            if (duration == 0 || duration == 1) {
              var endDate = moment(startDate).add(shiftLength, 'hours').toDate();
              return endDate;
            } else {
              // Returning more than one block, loop through on and off time
              localDate = startDate;

              do {
                var iteratorDate = moment(localDate).add(shiftLength, 'hours').toDate();
                loopDate = gantt.getClosestWorkTime({
                  date: iteratorDate,
                  dir: "Pending",
                  unit: "hour"
                });
                localDate = loopDate;
                iterated++;
              }
              while (iterated < duration);
              var endDate = moment(loopDate).add(shiftLength, 'hours').toDate();
              return endDate;
            }
          }

          <?php include "js-baselines.php" ?>

          gantt.addTaskLayer(function draw_deadline(task) {
            var deadlineDate = moment(task.deadline, 'YYYY-MM-DD HH:mm:ss', true);
            if (deadlineDate.isValid() == true) {
              var sizes = gantt.getTaskPosition(task, moment(task.deadline).toDate());
              var el = document.createElement('div');
              el.className = 'deadline';
              el.style.left = sizes.left + 'px';
              el.style.width = sizes.width + '24px';
              el.style.top = sizes.top + 'px';
              return el;
            }
            return false;
          });
        })
        .fail(function(data) {
          console.log("fail");
          console.log(data);
          window.ibex_gantt_config.resources = data.resources;
          window.ibex_gantt_config.resource_groups = data.resource_groups;
          window.ibex_gantt_config.calendars = data.calendars;
          window.ibex_gantt_config.calendarOverrides = data.calendar_overrides;
          window.ibex_gantt_config.columns = data.columns;
          window.ibex_gantt_config.settings = data.settings;
          window.ibex_gantt_config.files = data.files;
          window.ibex_gantt_config.groups = data.groups;
          window.ibex_gantt_config.userGroups = data.user_groups;
          window.ibex_gantt_config.summaryTaskArray = data.summary_tasks;

          gantt.init("gantt_here");
          gantt.parse({
            data: [
              { id: 1, text: "Project #2", start_date: "01-04-2023", duration: 18, progress: 0.4, open: true, resource_group_id: 0},
              { id: 2, text: "Task #1", start_date: "02-04-2023", duration: 8, progress: 0.6, parent: 1, resource_group_id: 0 },
              { id: 3, text: "Task #2", start_date: "11-04-2023", duration: 8, progress: 0.6, parent: 1, resource_group_id: 0 }
            ],
            links: [
              {id: 1, source: 1, target: 2, type: "1"},
              {id: 2, source: 2, target: 3, type: "0"}
            ]
          });
        });
      
        $(".delete-link").click(function() {
        window.ibex_gantt_config.activityPrimaryGUID = $("#link_edit_source_task_guid").val();
        window.ibex_gantt_config.activitySecondaryGUID = $("#link_edit_target_task_guid").val();
        window.ibex_gantt_config.activityInfo = "";
        window.ibex_gantt_config.activityAction = "deleted"
        window.ibex_gantt_config.activityType = "link";
        // window.ibex_gantt_config.snapshotData = JSON.stringify(gantt.serialize());

        setTimeout(function() {
          reloadActivityFeed();
        }, 1000);

        $.ajax({
          url: 'beta.ajax.php?action=snapshot_gantt',
          type: 'POST',
          dataType: 'json',
          success: function(data) {
            var lastSaveTime = data.save_time;
            window.ibex_gantt_config.lastSaveTime = Number(lastSaveTime) + 1;
          },
          data: {
            primary_guid: window.ibex_gantt_config.activityPrimaryGUID,
            secondary_guid: window.ibex_gantt_config.activitySecondaryGUID,
            action_type: window.ibex_gantt_config.activityAction,
            type: window.ibex_gantt_config.activityType,
            info: window.ibex_gantt_config.activityInfo,
            gantt_data: window.ibex_gantt_config.snapshotData
          }
        });

        window.ibex_gantt_config.lastSnapshotTime = moment().unix();

        gantt.deleteLink($("#link_edit_id").val());
        $("#modal_link_editor").modal('hide');
      });

      $(".cancel-delete-task").click(function() {
        $("#modal_delete_task").modal('hide');
        $("#modal_task_editor").modal('show');
      });

      $(".confirm-delete-task").click(function() {
        //window.ibex_gantt_config.snapshotData = JSON.stringify(gantt.serialize());
        createSnapshot();
        window.ibex_gantt_config.lastSnapshotTime = moment().unix();
        // Get previous and next task
        var prevTask = gantt.getTask(gantt.getPrevSibling(window.ibex_gantt_config.activeTaskID));
        var nextTask = gantt.getTask(gantt.getNextSibling(window.ibex_gantt_config.activeTaskID));
        var deleteTask = gantt.getTask(window.ibex_gantt_config.activeTaskID);
        gantt.deleteTask(window.ibex_gantt_config.activeTaskID);

        var parent = gantt.getTask(deleteTask.parent);
        if (parent) {
          window.ibex_gantt_config.suppressParentUpdateID = parent.id;
          // var minutesToNow = getMinutesBetweenDates(moment.unix(dates.start_date), moment.unix(dates.end_date), parent.calendar_id);
          // var periodsWorking = getWorkingPeriods(moment.unix(dates.start_date).toDate(), moment.unix(dates.end_date).toDate(), item.calendar_id, moment.unix(dates.end_date).toDate());
          var childrenTasks = gantt.getChildren(parent.id);
          var totalMins = 0;
          for (let index = 0; index < childrenTasks.length; index++) {
            const id = childrenTasks[index];
            if (id != window.ibex_gantt_config.activeTaskID) {
              var childrenTask = gantt.getTask(id);
              totalMins = totalMins + parseInt(childrenTask.duration_worked);
            }
          }
          // for (i = 0; i < periodsWorking.length; ++i) {
          //   var a = moment(periodsWorking[i].start_date);
          //   var b = moment(periodsWorking[i].end_date);
          //   var diff = (b.diff(a, 'minutes'));
          //   totalMins = totalMins + diff;
          // }
          parent.duration_worked = 89897; //totalMins;
          setTimeout(() => {
            $.getJSON("beta.ajax.php?action=updateDurationWorked&id=" + parent.id + "&duration_worked=" + totalMins, function(data) {});

          });
        }

        $("#modal_task_editor").modal('hide');
        $("#modal_delete_task").modal('hide');

        if ($('#auto_schedule_after_delete').is(':checked')) {
          if (nextTask && prevTask) {
            window.ibex_gantt_config.overrideLinkBeforeAddBehaviour = true;
            gantt.addLink({
              programme_id: $("#programme_id").val(),
              source: prevTask.id,
              source_guid: prevTask.guid,
              target: nextTask.id,
              target_guid: nextTask.guid,
              type: "0",
              offset_minutes: "0",
              offset_type: "1",
              // created: moment().unix(),
              created: moment().format("ddd D MMM YYYY"), //Added by RB 12.04.19
            });
            autoScheduleTasks();
          }
        }
      });

      $(".delete-task").click(function() {
        $("#modal_task_editor").modal('hide');
        $(".delete-task-text").html('You are about to delete<br><strong>' + $(".task_edit_name").val() + "</strong>");
        $("#task_id_to_delete").val(window.ibex_gantt_config.activeTaskID);
        $("#modal_delete_task").modal('show');
      });

      $(".save-task-link").click(function() {
        window.ibex_gantt_config.linkTaskAfterInsert = true;
        $(".save-task").trigger("click");
      });

      $(".save-task").click(function() {
        createSnapshot();
        var task = gantt.getTask(window.ibex_gantt_config.activeTaskID);
        task.programme_id = $("#programme_id").val();
        task.timing_overriden = $("#task_edit_timings_overriden").val();
        task.type = $("#task_edit_type").val();
        task.text = $("#task_edit_name").val();
        task.price = $("#task_edit_price").val();
        task.budget_at_completion = $("#task_edit_budget_at_completion").val();
        task.budget_cost_completion = $("#task_edit_budget_at_completion").val();
        task.actual_cost_completion = $("#task_edit_actual_costs").val();
        task.baseline_start = moment($("#task_edit_baseline_start_date_d").val() + "/" + $("#task_edit_baseline_start_date_m").val() + "/" + $("#task_edit_baseline_start_date_y").val() + " " + $("#task_edit_baseline_start_time_h").val() + ":" + $("#task_edit_baseline_start_time_m").val(), "DD/MM/YYYY HH:mm").toDate();
        task.baseline_end = moment($("#task_edit_baseline_end_date_d").val() + "/" + $("#task_edit_baseline_end_date_m").val() + "/" + $("#task_edit_baseline_end_date_y").val() + " " + $("#task_edit_baseline_end_time_h").val() + ":" + $("#task_edit_baseline_end_time_m").val(), "DD/MM/YYYY HH:mm").toDate();
        task.deadline = moment($("#task_edit_deadline_date_d").val() + "/" + $("#task_edit_deadline_date_m").val() + "/" + $("#task_edit_deadline_date_y").val() + " " + $("#task_edit_deadline_time_h").val() + ":" + $("#task_edit_deadline_time_m").val(), "DD/MM/YYYY HH:mm").toDate();
        task.costs_incurred = $("#costs_incurred").val();
        task.calendar_id = $("#task_edit_calendar_id").val();
        task.duration_unit = "2" // days      //window.ibex_gantt_config.taskDurationUnit;
        task.workload_quantity = $("#task_edit_workload_total_quantity").val();
        task.workload_quantity_unit = $("#task_edit_workload_unit").val();

        // Baseline progress additions

        if (task.custom_permission_groups == "undefined") {
          task.custom_permission_groups = NULL;
        }
        if (window.ibex_gantt_config.periodDescriptor == "1" || $("#task_edit_timings_overriden").val() == "1") {
          var durationHours = 0,
            durationMins = 0;
          if ($("#task_edit_duration_hours").val() != "") {
            durationHours = parseInt($("#task_edit_duration_hours").val());
          }
          if ($("#task_edit_duration_mins").val() != "") {
            durationMins = parseInt($("#task_edit_duration_mins").val());
          }
          task.duration_worked = durationHours * 60 + durationMins;
        } else {
          $.each(window.ibex_gantt_config.calendars, function(index) {
            if (window.ibex_gantt_config.calendars[index].id == task.calendar_id) {
              var calendar = window.ibex_gantt_config.calendars[index];
              var startTime = moment(padLeadingZero(calendar.start_hour) + ":" + padLeadingZero(calendar.start_minute), "HH:mm");
              var endTime = moment(padLeadingZero(calendar.end_hour) + ":" + padLeadingZero(calendar.end_minute), "HH:mm");
              if (parseInt(calendar.start_hour) < parseInt(calendar.end_hour)) {
                var duration = moment.duration(endTime.diff(startTime));
                minutes = parseInt(duration.asMinutes());
              } else {
                var duration = moment.duration(startTime.diff(endTime));
                minutes = parseInt(duration.asMinutes());
                minutes = 1440 - minutes;
              }
              task.duration_worked = parseInt($("#task_edit_duration_custom").val()) * minutes;
            }
          });
        }

        if (window.ibex_gantt_config.periodDescriptor != "1" && $("#task_edit_timings_overriden").val() == "0") {
          // Scale up
          var mins = convertPeriodToMinutes($("#task_edit_duration_custom").val(), $("#task_edit_calendar_id").val());
          task.duration_worked = mins; //5858
        }
        task.start_date = moment($("#task_edit_start_date_d").val() + "/" + $("#task_edit_start_date_m").val() + "/" + $("#task_edit_start_date_y").val() + " " + $("#task_edit_start_time_h").val() + ":" + $("#task_edit_start_time_m").val(), "DD/MM/YYYY HH:mm").toDate();
        task.calendar_id = $("#task_edit_calendar_id").val();
        task.end_date = moment(getTaskEndDate(moment(task.start_date, "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm"), task.duration_worked, task.calendar_id)).toDate();

        if ($("#task_edit_type").val() == "project") {
          task.calendar_id = $("#task_edit_calendar_id").val();
          var minutes;
          $.each(window.ibex_gantt_config.calendars, function(index) {
            if (window.ibex_gantt_config.calendars[index].id == task.calendar_id) {
              var calendar = window.ibex_gantt_config.calendars[index];
              var startTime = moment(padLeadingZero(calendar.start_hour) + ":" + padLeadingZero(calendar.start_minute), "HH:mm");
              var endTime = moment(padLeadingZero(calendar.end_hour) + ":" + padLeadingZero(calendar.end_minute), "HH:mm");
              var duration = moment.duration(endTime.diff(startTime));
              if (parseInt(calendar.start_hour) < parseInt(calendar.end_hour)) {
                var duration = moment.duration(endTime.diff(startTime));
                minutes = parseInt(duration.asMinutes());
              } else {
                var duration = moment.duration(startTime.diff(endTime));
                minutes = parseInt(duration.asMinutes());
                minutes = 1440 - minutes;
              }
            }
          });
          var nextWorkingDate = getNextWorkingDate($("#task_edit_calendar_id").val());
          task.start_date = moment(nextWorkingDate, "DD/MM/YYYY HH:mm").toDate();
          task.end_date = moment(getTaskEndDate(moment(task.start_date).format("YYYY-MM-DD HH:mm"), minutes - 1, task.calendar_id)).toDate();
        } else if ($("#task_edit_type").val() == "task") {
          task.duration = parseInt($("#task_edit_duration_hours").val());
          task.color = $("#task_edit_bar_colour").val();
          <?php include "js-task-editor-status.php" ?>
          task.resource_group_id = $("#task_edit_resource_group_id").val();
        } else if ($("#task_edit_type").val() == "milestone") {
          task.start_date = moment($("#task_edit_start_date_d").val() + "/" + $("#task_edit_start_date_m").val() + "/" + $("#task_edit_start_date_y").val() + " " + $("#task_edit_start_time_h").val() + ":" + $("#task_edit_start_time_m").val(), "DD/MM/YYYY HH:mm").toDate();
          task.duration_worked = "0";
          task.end_date = task.start_date;
          task.color = $("#task_edit_bar_colour").val();
        }

        if (task.parent != 0) {
          gantt.getTask(task.parent).is_summary = "1";
          gantt.updateTask(task.parent);
          task.parent_guid = gantt.getTask(task.parent).guid;
        }
        // Bind resources
        task.resource_id = window.ibex_gantt_config.activeTaskResources;
        var afterForm = JSON.stringify($("#form_task_editor").serializeArray());
        var testBefore = JSON.parse(window.ibex_gantt_config.beforeTaskEditorForm);
        var testAfter = JSON.parse(afterForm);
        var changeString = "";

        for (var i = 0; i < testBefore.length; i++) {
          var name = testBefore[i].name;
          var value = testBefore[i].value;
          if (value != testAfter[i].value) {
            var val1 = value;
            var val2 = testAfter[i].value;
            if (val1 == '') {
              val1 = 'not set';
            }
            changeString += name + " was " + val1 + " and is now " + val2 + "<br>";
          }
        }
        var finalChangeString = changeString.substring(0, changeString.length - 4);

        if (task.$new) {
          window.ibex_gantt_config.activityPrimaryGUID = task.guid;
          window.ibex_gantt_config.activitySecondaryGUID = '';
          window.ibex_gantt_config.activityInfo = "Starts " + moment(task.start_date).format("ddd D MMM (HH:mm)");
          window.ibex_gantt_config.activityAction = "added"
          window.ibex_gantt_config.activityType = task.type;
        } else {
          window.ibex_gantt_config.activityPrimaryGUID = task.guid;
          window.ibex_gantt_config.activitySecondaryGUID = '';
          window.ibex_gantt_config.activityInfo = finalChangeString;
          window.ibex_gantt_config.activityAction = "edited"
          window.ibex_gantt_config.activityType = task.type;
        }

        setTimeout(function() {
          reloadActivityFeed();
        }, 1000);

        $.ajax({
          url: 'beta.ajax.php?action=snapshot_gantt',
          type: 'POST',
          dataType: 'json',
          success: function(data) {
            var lastSaveTime = data.save_time;
            window.ibex_gantt_config.lastSaveTime = Number(lastSaveTime) + 1;
          },
          data: {
            primary_guid: window.ibex_gantt_config.activityPrimaryGUID,
            secondary_guid: window.ibex_gantt_config.activitySecondaryGUID,
            action_type: window.ibex_gantt_config.activityAction,
            type: window.ibex_gantt_config.activityType,
            info: window.ibex_gantt_config.activityInfo,
            gantt_data: window.ibex_gantt_config.snapshotData
          }
        });

        window.ibex_gantt_config.lastSnapshotTime = moment().unix();
        // Set durtaion abs
        var durationNonAbs = task.duration_worked;
        task.duration_worked = Math.abs(durationNonAbs);

        if (task.$new == true) {
          gantt.addTask(task, task.parent);
        } else {
          gantt.updateTask(task.id);
        }
        // Compare this task with pre-object
        window.ibex_gantt_config.originalTaskEditorObject = task;
        // Check for changes

        if (window.ibex_gantt_config.linkTaskAfterInsert == true) {
          // Get previous task
          var prev_id = gantt.getPrevSibling(task.id);
          var prevTask = gantt.getTask(prev_id);
          if (prevTask.type != "project" && prevTask.is_summary != "1") {
            window.ibex_gantt_config.overrideLinkBeforeAddBehaviour = true;
            gantt.addLink({
              programme_id: $("#programme_id").val(),
              source: prevTask.id,
              source_guid: prevTask.guid,
              target: task.id,
              target_guid: task.guid,
              type: "0",
              offset_minutes: "0",
              offset_type: "1",
              // created: moment().unix(),
              created: moment().format("ddd D MMM YYYY"), //Added by RB 12.04.19
            });
            autoScheduleTasks();
          }
        }

        window.ibex_gantt_config.linkTaskAfterInsert = false;
        autoScheduleTasks();

        $("#modal_task_editor").modal('hide');

        gantt.showTask(task.id);

        setTimeout(function() {
          checkUIViews();
        }, 200);
      });

      gantt.attachEvent("onAfterTaskAdd", function(id, item) {
        //any custom logic here
        var task = gantt.getTask(id);
        task.$new = false;
        gantt.updateTask(task.id);
        reloadSettings();
      });

      gantt.attachEvent("onAfterTaskUpdate", function(id, item) {
        if (item.parent != 0) {
          var dates = getSummaryTaskDates(item.parent);
          var parent = gantt.getTask(item.parent);
          parent.start_date = moment.unix(dates.start_date).toDate();
          parent.end_date = moment.unix(dates.end_date).toDate();
          window.ibex_gantt_config.suppressParentUpdateID = parent.id;
          var childrenTasks = gantt.getChildren(parent.id);
          var totalMins = 0;
          for (let index = 0; index < childrenTasks.length; index++) {
            const id = childrenTasks[index];
            var childrenTask = gantt.getTask(id);
            totalMins = totalMins + parseInt(childrenTask.duration_worked);
          }
          parent.duration_worked = totalMins;
          setTimeout(() => {
            $.getJSON("beta.ajax.php?action=updateDurationWorked&id=" + parent.id + "&duration_worked=" + totalMins, function(data) {});
          });
        }
      });

      window.getSummaryTaskDates = function getSummaryTaskDates(id) {
        var tasks = gantt.getChildren(id);
        var startDate = 0,
          endDate = 0;
        for (i = 0; i < tasks.length; i++) {
          var task = gantt.getTask(tasks[i]);
          if (i == 0) {
            startDate = moment(task.start_date).format("X");
            endDate = moment(task.end_date).format("X");
          }

          if (moment(task.start_date).format("X") < startDate) {
            startDate = moment(task.start_date).format("X");
          }

          if (moment(task.end_date).format("X") > endDate) {
            endDate = moment(task.end_date).format("X");
          }
        }
        var dates = {
          "start_date": startDate,
          "end_date": endDate
        }
        return dates;
      }

      gantt.attachEvent("onAfterTaskAdd", function(id, item) {
        if (item.parent != 0) {
          var dates = getSummaryTaskDates(item.parent);
          var parent = gantt.getTask(item.parent);
          parent.start_date = moment.unix(dates.start_date).toDate();
          parent.end_date = moment.unix(dates.end_date).toDate();
          window.ibex_gantt_config.suppressParentUpdateID = parent.id;
          var childrenTasks = gantt.getChildren(parent.id);
          var totalMins = 0;

          for (let index = 0; index < childrenTasks.length; index++) {
            const id = childrenTasks[index];
            var childrenTask = gantt.getTask(id);
            totalMins = totalMins + parseInt(childrenTask.duration_worked);
          }

          parent.duration_worked = totalMins;
          gantt.updateTask(parent.id);

          setTimeout(() => {
            $.getJSON("beta.ajax.php?action=updateDurationWorked&id=" + parent.id + "&duration_worked=" + totalMins, function(data) {});
          });

          $(".task-layer-" + parent.guid).hide();
        }
        refreshUIOrder();
        gantt.render();
      });

      window.generateGUID = function generateGUID() {
        var S4 = function() {
          var output = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1).toUpperCase();
          return output;
        };
        return (S4() + S4() + "-" + S4() + "-" + S4() + "-" + S4() + "-" + S4() + S4() + S4());
      }

      gantt.attachEvent("onBeforeTaskAdd", function(id, item) {
        return true;
      })

      window.enumerateDaysBetweenDates = function enumerateDaysBetweenDates(startDate, endDate, calendarID) {
        startDate = moment(startDate);
        endDate = moment(endDate);
        var now = startDate,
          dates = [];
        while (now.isBefore(endDate) || now.isSame(endDate)) {
          var nowFormatted = now.format('YYYY-MM-DD');
          if (isDateWorkingDate(nowFormatted, calendarID) == true) {
            dates.push(nowFormatted);
          }
          now.add(1, 'days');
        }
        return dates;
      };

      $("#modal_link_editor").on("shown.bs.modal", function() {
        createSnapshot();
      });

      var activityTimeout = setTimeout(inActive, 120000);

      function resetActive() {
        $(document.body).attr('class', 'active');
        clearTimeout(activityTimeout);
        activityTimeout = setTimeout(inActive, 120000);
      }

      function inActive() {
        $.getJSON("beta.ajax.php?action=release_task_locks", function(data) {});
        $("#modal_task_editor").modal('hide');
      }

      $(document).bind('mousemove', function() {
        resetActive()
      });

      $(document).bind('keypress', function() {
        resetActive()
      });

      $("#modal_task_editor").on("shown.bs.modal", function() {
        $('.mdb-select').material_select('destroy');
        var calID = $('#task_edit_start_time_h').val();
        var mins;

        $('#task_edit_calendar_id').on('change', function() {
          updateCalendarWorkingDays();
          var calendarID = this.value;
          for (var calendar of window.ibex_gantt_config.calendars) {
            if (calendar.id == calendarID) {
              $('.mdb-select').material_select('destroy');
              $('#task_edit_start_time').val(padLeadingZero(calendar.start_hour) + ":" + padLeadingZero(calendar.start_minute));
              if (parseInt(calendar.start_hour) < parseInt(calendar.end_hour)) { //day
                mins = moment(calendar.end_time, "HH:mm").diff(moment(calendar.start_time, "HH:mm")) / (60 * 1000);
                $("#task_edit_start_time_h").empty();
                var availableHoursArray = [];
                for (let index = 0; index < 24; index++) {
                  if (index <= parseInt(calendar.end_hour)) {
                    if (index >= parseInt(calendar.start_hour)) {
                      availableHoursArray.push(parseInt(index));
                    }
                  }
                }

                var availableHoursString = '';

                for (let index = 0; index < availableHoursArray.length; index++) {
                  const element = availableHoursArray[index];
                  var hours;
                  if (element < 10)
                    hours = "0" + element;
                  else
                    hours = element;

                  availableHoursString += '<option value="' + hours + '">' + element + '</option>';
                }

                $("#task_edit_start_time_h").append(availableHoursString);
              } else {
                mins = moment(calendar.start_time, "HH:mm").diff(moment(calendar.end_time, "HH:mm")) / (60 * 1000);
                mins = 1440 - mins;

                $("#task_edit_start_time_h").empty();
                var availableHoursArray = [];
                for (let index = 0; index < 24; index++) {
                  if (index <= parseInt(calendar.end_hour)) {
                    availableHoursArray.push(parseInt(index));
                  } else {
                    if (index >= parseInt(calendar.start_hour)) {
                      availableHoursArray.push(parseInt(index));
                    }
                  }
                }
                availableHoursArray.sort();
                availableHoursArray.sort(function(a, b) {
                  return a - b;
                });

                var availableHoursString = '';

                for (let index = 0; index < availableHoursArray.length; index++) {
                  const element = availableHoursArray[index];
                  var hours;
                  if (element < 10)
                    hours = "0" + element;
                  else
                    hours = element;

                  availableHoursString += '<option value="' + hours + '">' + element + '</option>';
                }

                $("#task_edit_start_time_h").append(availableHoursString);
              }
              $("#task_edit_start_time_h").val(padLeadingZero(calendar.start_hour)).trigger("change");
              $("#task_edit_start_time_m").val(padLeadingZero(calendar.start_minute)).trigger("change");
              var startDate = moment($("#task_edit_start_date_d").val() + "/" + $("#task_edit_start_date_m").val() + "/" + $("#task_edit_start_date_y").val() + " " + $("#task_edit_start_time_h").val() + ":" + $("#task_edit_start_time_m").val(), "DD/MM/YYYY HH:mm").toDate();
              $("#task_edit_end_date").val(moment(getTaskEndDate(moment(startDate).format("YYYY-MM-DD HH:mm"), mins * $('#task_edit_duration_custom').val(), calendarID)).format("ddd D MMM (HH:mm)")).trigger("change");
              $('.mdb-select').material_select();
              break;
            }
          }
        });

        var task = gantt.getTask(window.ibex_gantt_config.activeTaskID);
        $('#task_edit_calendar_id_init').val(task.calendar_id);
        $('#task_edit_calendar_id').trigger('change');
      });

      window.enumerateDaysBetweenDatesX = function enumerateDaysBetweenDatesX(startDate, endDate, calendarID) {
        startDate = moment(startDate);
        endDate = moment(endDate);
        var now = startDate,
          dates = [];
        while (now.isBefore(endDate) || now.isSame(endDate)) {
          dates.push(now.toDate());
          now.add(1, 'days');
        }
        return dates;
      };

      window.canUserEditTask = function canUserEditTask(taskID) {
        // var canEdit = true;
        // return canEdit;
        var task = gantt.getTask(taskID);

        if (task.custom_permission_groups == null || task.custom_permission_groups == "") {
          for (i = 0; i < window.ibex_gantt_config.userGroups.length; i++) {
            var userGroupID = window.ibex_gantt_config.userGroups[i].id;
            var permissionGroups = JSON.parse(window.ibex_gantt_config.defaultPermissionGroups);

            for (var k in permissionGroups) {
              if (k == userGroupID) {
                if (permissionGroups[k] == true) {
                  canEdit = true;
                  break;
                }
              }
            }
          }
        } else {
          // Custom groups	
          var groupArray = [];
          var groups = task.custom_permission_groups;
          for (i = 0; i < groups.length; i++) {
            groupArray.push(groups[i]);
          }

          for (i = 0; i < window.ibex_gantt_config.userGroups.length; i++) {
            var userGroupID = window.ibex_gantt_config.userGroups[i].id;
            var permissionGroups = JSON.parse(task.custom_permission_groups);

            for (var k in permissionGroups) {
              if (k == userGroupID) {
                if (permissionGroups[k] == true) {
                  canEdit = true;
                  break;
                }
              }
            }
          }
        }
        return canEdit;
      }

      window.canGroupEditTask = function canGroupEditTask(taskID, groupID) {
        var groups = [];
        var canEdit = false;
        var task = gantt.getTask(taskID);
        if (task.custom_permission_groups == null) {
          // Default groups from settings
        } else {
          var groupsArray = JSON.parse(task.custom_permission_groups);
          if ($.inArray(groupID, groupsArray) !== -1) {
            canEdit = true;
          }
        }
        return canEdit;
      }

      $(document).on('click', '.toggle-group-permission', function(e) {
        var taskID = $(this).data("task");
        var groupID = $(this).data('group');
        var groupArray = {};
        var count = 0;
        $(".toggle-group-permission").each(function() {
          var checked = false;
          if ($(this).is(':checked')) {
            checked = true;
          }
          groupArray[$(this).data("group")] = checked;
          count++;
        });
        var task = gantt.getTask(taskID);
        task.custom_permission_groups = JSON.stringify(groupArray);
        gantt.updateTask(task.id);
        $.getJSON("beta.ajax.php?action=update_permission_groups&task_id=" + taskID + "&data=" + JSON.stringify(groupArray), function(data) {});
      });

      localStorage.clear();

      // These listeners have look for changes in any of the month and year dropdowns and then go about disabling the day component based on non working days
      $('#task_edit_start_date_m').on("change", function() {
        alert('The start month has changed');
      });

      <?php include "js-show-task-editor.php" ?>

      window.isDefined = function isDefined(variable) {
        if (variable != "" && variable != null && variable != "undefined" && variable != undefined && variable != "null") {
          return true;
        } else {
          return false;
        }
      }

      window.updateTaskAttributes = function updateTaskAttributes(type, taskID) {
        if (type == 1) {
          // Update duration from workload and resource outputs
          var task = gantt.getTask(taskID);
          var workloadTotal = $("#task_edit_workload_total_quantity").val();
          var resourceGroupID = $("#task_edit_resource_group_id").val();

          if (isDefined(workloadTotal) && isDefined(resourceGroupID)) {
            $("#task_edit_duration_custom").attr('disabled', 'disabled'); // RB 26.04.21

            var outputAmount, outputPeriod;

            $.each(window.ibex_gantt_config.resource_groups, function(index) {
              if (window.ibex_gantt_config.resource_groups[index].id == resourceGroupID) {
                outputAmount = window.ibex_gantt_config.resource_groups[index].max_output_value;
                outputPeriod = window.ibex_gantt_config.resource_groups[index].period;

                if (outputPeriod == "1") {
                  // Need to convert down to mins
                  var outputPerMinute = outputAmount / 60;
                  var totalCount = workloadTotal / outputPerMinute;

                  $("#task_edit_duration_hours").val(Math.floor(totalCount / 60)).trigger("change");
                  $("#task_edit_duration_mins").val(totalCount / 60).trigger("change");
                  $("#task_edit_duration_custom").val(convertMinutesToPeriodClean(totalCount, $("#task_edit_calendar_id").val())).trigger("change");

                  var startDateTime = $("#task_edit_start_date_d").val() + "/" + $("#task_edit_start_date_m").val() + "/" + $("#task_edit_start_date_y").val() + " " + $("#task_edit_start_time_h").val() + ":" + $("#task_edit_start_time_m").val();

                  $("#task_edit_end_date").val(moment(getTaskEndDate(moment(startDateTime, "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm"), totalCount, $("#task_edit_calendar_id").val())).format("ddd D MMM (HH:mm)")).trigger("change");
                }

                if (outputPeriod == "2") {
                  // get hours in 'day', then down to mins
                  var minsInDay = window.getMinutesInCalendarShift($("#task_edit_calendar_id").val());
                  var hours = minsInDay / 60;
                  var outputPerMinute = outputAmount / minsInDay;
                  var totalCount = workloadTotal / outputPerMinute;

                  $("#task_edit_duration_hours").val(Math.floor(totalCount / 60)).trigger("change");
                  $("#task_edit_duration_mins").val(totalCount / 60).trigger("change");

                  var startDateTime = $("#task_edit_start_date_d").val() + "/" + $("#task_edit_start_date_m").val() + "/" + $("#task_edit_start_date_y").val() + " " + $("#task_edit_start_time_h").val() + ":" + $("#task_edit_start_time_m").val();

                  $("#task_edit_duration_custom").val(convertMinutesToPeriodClean(totalCount, $("#task_edit_calendar_id").val())).trigger("change");
                  $("#task_edit_end_date").val(moment(getTaskEndDate(moment(startDateTime, "DD/MM/YYYY HH:mm").format("YYYY-MM-DD HH:mm"), totalCount, $("#task_edit_calendar_id").val())).format("ddd D MMM (HH:mm)")).trigger("change");
                }

                if (outputPeriod == "3") {
                  // convert
                  var minsInDay = window.getMinutesInCalendarShift($("#task_edit_calendar_id").val());
                  var hours = (minsInDay * 5) / 60;
                  var outputPerMinute = outputAmount / (minsInDay * 5);
                  var totalCount = workloadTotal / outputPerMinute;

                  $("#task_edit_duration_hours").val(Math.floor(totalCount / 60)).trigger("change");
                  $("#task_edit_duration_mins").val(totalCount / 60).trigger("change");

                  var startDateTime = $("#task_edit_start_date_d").val() + "/" + $("#task_edit_start_date_m").val() + "/" + $("#task_edit_start_date_y").val() + " " + $("#task_edit_start_time_h").val() + ":" + $("#task_edit_start_time_m").val();

                  $("#task_edit_duration_custom").val(convertMinutesToPeriodClean(totalCount, $("#task_edit_calendar_id").val())).trigger("change");
                  $("#task_edit_end_date").val(moment(getTaskEndDate(moment(startDateTime, "DD/MM/YYYY").format("YYYY-MM-DD HH:mm"), totalCount, $("#task_edit_calendar_id").val())).format("ddd D MMM (HH:mm)")).trigger("change");
                }

                if (outputPeriod == "4") {
                  // convert
                  var minsInDay = window.getMinutesInCalendarShift($("#task_edit_calendar_id").val());
                  var hours = (minsInDay * 30) / 60;
                  var outputPerMinute = outputAmount / (minsInDay * 30);
                  var totalCount = workloadTotal / outputPerMinute;

                  $("#task_edit_duration_hours").val(Math.floor(totalCount / 60)).trigger("change");
                  $("#task_edit_duration_mins").val(totalCount / 60).trigger("change");

                  var startDateTime = $("#task_edit_start_date_d").val() + "/" + $("#task_edit_start_date_m").val() + "/" + $("#task_edit_start_date_y").val() + " " + $("#task_edit_start_time_h").val() + ":" + $("#task_edit_start_time_m").val();

                  $("#task_edit_duration_custom").val(convertMinutesToPeriodClean(totalCount, $("#task_edit_calendar_id").val())).trigger("change");
                  $("#task_edit_end_date").val(moment(getTaskEndDate(moment(startDateTime, "DD/MM/YYYY").format("YYYY-MM-DD HH:mm"), totalCount, $("#task_edit_calendar_id").val())).format("ddd D MMM (HH:mm)")).trigger("change");
                }
              }
            });
          } else {
          }
        }

        if (type == 2) {
          // Update total workload from duration and resource outputs
          var durationHours = 0,
            durationMins = 0;

          if ($("#task_edit_duration_hours").val() != "") {
            durationHours = parseInt($("#task_edit_duration_hours").val());
          }

          if ($("#task_edit_duration_mins").val() != "") {
            durationMins = parseInt($("#task_edit_duration_mins").val());
          }

          var duration_worked = durationHours * 60 + durationMins;
          var task = gantt.getTask(taskID);
          var resourceGroupID = task.resource_group_id;
          var outputAmount, outputPeriod;

          $.each(window.ibex_gantt_config.resource_groups, function(index) {
            if (window.ibex_gantt_config.resource_groups[index].id == resourceGroupID) {
              outputAmount = window.ibex_gantt_config.resource_groups[index].max_output_value;
              outputPeriod = window.ibex_gantt_config.resource_groups[index].outputs_unit;
              if (outputPeriod == "hr") {
                // Need to convert down to mins
                var outputPerMinute = outputAmount / 60;
                var workloadTotal = outputPerMinute * duration_worked;
                $("#task_edit_workload_total_quantity").val(workloadTotal);
              }
              if (outputPeriod == "day") {
                // get hours in 'day', then down to mins
                var minsInDay = window.getMinutesInCalendarShift(task.calendar_id);
                var outputPerMinute = outputAmount / minsInDay;
                var totalCount = workloadTotal / outputPerMinute;
                var workloadTotal = outputPerMinute * duration_worked;

                $("#task_edit_workload_total_quantity").val(workloadTotal);
              }
              if (outputPeriod == "week") {
                // convert
                var minsInDay = window.getMinutesInCalendarShift(task.calendar_id);
                var outputPerMinute = outputAmount / (minsInDay * 5);
                var totalCount = workloadTotal / outputPerMinute;
                var workloadTotal = outputPerMinute * duration_worked;

                $("#task_edit_workload_total_quantity").val(workloadTotal);
              }
              if (outputPeriod == "month") {
                // convert
                var minsInDay = window.getMinutesInCalendarShift(task.calendar_id);
                var outputPerMinute = outputAmount / (minsInDay * 30);
                var workloadTotal = outputPerMinute * duration_worked;

                $("#task_edit_workload_total_quantity").val(workloadTotal);
              }
            }
          });
        }

        if (type == 3) {
          // Provide resource group info from total workload and duration
        }
      }

      $(document).on('click', '.toggle-task-duration-unit', function(e) {});

      gantt.attachEvent("onAfterAutoSchedule", function(taskId, updatedTasks) {
        // any custom logic here
      });

      var links = gantt.getLinks();

      var taskDragging = false;
      gantt.attachEvent("onTaskDrag", function(id, mode, task, original) {
        if (window.ibex_gantt_config.taskBeingDragged == false) {
          window.ibex_gantt_config.originalTaskEditorObject = original;
        }
        window.ibex_gantt_config.taskBeingDragged = true;
        window.ibex_gantt_config.draggedTaskEndDate = task.end_date;
      });

      gantt.attachEvent("onAfterTaskDrag", function(id, mode, e) {
        window.ibex_gantt_config.taskBeingDragged = true;
        var instantSchedule = true;
        var task = gantt.getTask(id);
        var validDayEndSequence = isDateWorkingDate(moment(window.ibex_gantt_config.draggedTaskEndDate).format("YYYY-MM-DD"), task.calendar_id);
        if (mode == "move") {
          var test1 = getNextWorkingDate(task.calendar_id, true, task.start_date);
          task.start_date = moment(test1, "DD/MM/YYYY HH:mm").toDate();
          var endDate = getTaskEndDate(moment(task.start_date).format("YYYY-MM-DD HH:mm"), task.duration_worked, task.calendar_id);
          task.end_date = moment(endDate).toDate();
          var incomingLinks = task.$target;
          if (incomingLinks.length == 0) {} else {
            $(".override-edit-dependency").attr('data-id', incomingLinks[0]);
            $("#modal_prevent_drag").modal('show');
          }
          window.ibex_gantt_config.activityPrimaryGUID = task.guid;
          window.ibex_gantt_config.activitySecondaryGUID = '';
          window.ibex_gantt_config.activityInfo = 'Start was ' + moment(window.ibex_gantt_config.originalTaskEditorObject.start_date).format("ddd D MMM (HH:mm)") + " and is now " + moment(task.start_date).format("ddd D MMM (HH:mm)");
          window.ibex_gantt_config.activityAction = "edited"
          window.ibex_gantt_config.activityType = "task";
          setTimeout(function() {
            reloadActivityFeed();
          }, 1000);
          $.ajax({
            url: 'beta.ajax.php?action=snapshot_gantt',
            type: 'POST',
            dataType: 'json',
            success: function(data) {
              var lastSaveTime = data.save_time;
              window.ibex_gantt_config.lastSaveTime = Number(lastSaveTime) + 1;
            },
            data: {
              primary_guid: window.ibex_gantt_config.activityPrimaryGUID,
              secondary_guid: window.ibex_gantt_config.activitySecondaryGUID,
              action_type: window.ibex_gantt_config.activityAction,
              type: window.ibex_gantt_config.activityType,
              info: window.ibex_gantt_config.activityInfo,
              gantt_data: window.ibex_gantt_config.snapshotData
            }
          });
          window.ibex_gantt_config.lastSnapshotTime = moment().unix();
          gantt.updateTask(id);
        } else {
          var test1 = getNextWorkingDate(task.calendar_id, true, task.start_date);
          var endTime;
          var spansMidnight = false;
          $.each(window.ibex_gantt_config.calendars, function(index) {
            if (window.ibex_gantt_config.calendars[index].id == task.calendar_id) {
              endTime = padLeadingZero(window.ibex_gantt_config.calendars[index].end_hour) + ":" + padLeadingZero(window.ibex_gantt_config.calendars[index].end_minute);
              if (window.ibex_gantt_config.calendars[index].start_hour > window.ibex_gantt_config.calendars[index].end_hour) {
                spansMidnight = false;
              } else {
                spansMidnight = true;
              }
            }
          });
          var test2b = moment(getNextWorkingDate(task.calendar_id, true, window.ibex_gantt_config.draggedTaskEndDate), "DD/MM/YYYY HH:mm").format("YYYY-MM-DD") + " " + endTime;
          var test2 = getMinutesBetweenDates(moment(task.start_date).toDate(), moment(test2b).toDate(), task.calendar_id, moment(test2b).toDate());
          var test3 = window.getMinutesInCalendarShift(task.calendar_id);
          var test4 = Math.floor(Number(test2) / Number(test3));
          var test4b;
          if (validDayEndSequence == false && spansMidnight == true) {
            test4b = test4 + 1;
          } else {
            test4b = test4;
          }
          var test5 = Number(test3) * test4b;
          task.duration_worked = test5;
          task.start_date = moment(test1, "DD/MM/YYYY HH:mm").toDate();
          var endDate = getTaskEndDate(moment(task.start_date).format("YYYY-MM-DD HH:mm"), test5, task.calendar_id);
          task.end_date = moment(endDate).toDate();
          var changedAspects = '';
          if (window.ibex_gantt_config.originalTaskEditorObject.start_date != task.start_date) {
            changedAspects = 'Start was ' + moment(window.ibex_gantt_config.originalTaskEditorObject.start_date).format("ddd D MMM (HH:mm)") + " and is now " + moment(task.start_date).format("ddd D MMM (HH:mm)") + "<br>";
          }
          if (window.ibex_gantt_config.originalTaskEditorObject.end_date != task.end_date) {
            changedAspects = 'Finish was ' + moment(window.ibex_gantt_config.originalTaskEditorObject.end_date).format("ddd D MMM (HH:mm)") + " and is now " + moment(task.end_date).format("ddd D MMM (HH:mm)") + "<br>";
          }
          window.ibex_gantt_config.activityPrimaryGUID = task.guid;
          window.ibex_gantt_config.activitySecondaryGUID = '';
          window.ibex_gantt_config.activityInfo = changedAspects;
          window.ibex_gantt_config.activityAction = "edited"
          window.ibex_gantt_config.activityType = "task";
          setTimeout(function() {
            reloadActivityFeed();
          }, 1000);
          $.ajax({
            url: 'beta.ajax.php?action=snapshot_gantt',
            type: 'POST',
            dataType: 'json',
            success: function(data) {
              var lastSaveTime = data.save_time;
              window.ibex_gantt_config.lastSaveTime = Number(lastSaveTime) + 1;
            },
            data: {
              primary_guid: window.ibex_gantt_config.activityPrimaryGUID,
              secondary_guid: window.ibex_gantt_config.activitySecondaryGUID,
              action_type: window.ibex_gantt_config.activityAction,
              type: window.ibex_gantt_config.activityType,
              info: window.ibex_gantt_config.activityInfo,
              gantt_data: window.ibex_gantt_config.snapshotData
            }
          });
          window.ibex_gantt_config.lastSnapshotTime = moment().unix();
          window.ibex_gantt_config.taskBeingDragged = false;
          gantt.updateTask(id);
        }
        // Resource validation failed with drag etc?
        window.ibex_gantt_config.activeTaskWorkingDates = enumerateDaysBetweenDates(moment(task.start_date).format("YYYY-MM-DD"), moment(task.end_date).format("YYYY-MM-DD"), task.calendar_id);
        var resourceEnabled = true,
          resourceDisabledReason = 0,
          resourceName;
        if (task.resource_id != "") {
          for (resource of window.ibex_gantt_config.resources) {
            if (resource['id'] == task.resource_id) {
              $.each(window.ibex_gantt_config.activeTaskWorkingDates, function(index) {
                if (isDateWorkingDate(window.ibex_gantt_config.activeTaskWorkingDates[index], resource.calendar_id) == false) {
                  resourceEnabled = false;
                  resourceName = resource['name'];
                  resourceDisabledReason = 1;
                }
              });
              if (resourceEnabled == true) {
                // Check other task clashes
                var resourceClashes = false;
                var tasksAll = gantt.getTaskByTime();
                for (var j = 0; j < tasksAll.length; j++) {
                  if (tasksAll[j].resource_id == resource['id'] && tasksAll[j].id != task.id) {
                    $.each(window.ibex_gantt_config.activeTaskWorkingDates, function(indexInner) {
                      var moment1 = moment(window.ibex_gantt_config.activeTaskWorkingDates[indexInner]);
                      var moment2 = moment(tasksAll[j].start_date);
                      var moment3 = moment(tasksAll[j].end_date);
                      if (moment1.isBetween(moment2, moment3, 'seconds', '[]')) {
                        if (moment3.isBefore(moment(task.start_date))) {} else if (moment3.isSame(moment(task.start_date))) {} else {
                          resourceName = resource['name'];
                          resourceEnabled = false;
                        }
                      }
                    });
                  }
                }
              }
            }
          }
        }
        if (resourceEnabled == false) {
          $("#clashed_resource_id").val(task.resource_id);
          $("#clashed_resource_task_id").val(task.id);
          $(".clash-intro").html(task.text + " has a resource assigned (" + resourceName + ") that is constrained within this date range.");
          $(".clash-text").html("This can either be:<br><br>- A calendar constraint: the resource calendar prevents working on a day within this date range<br><br>- A task constraint: another task is already assigned this resource within this date range<br><br>You can choose to unassign " + resourceName + " from this task or revert this task back to it's original date range.");
          $("#modal_resource_clash").modal('show');
        }
        if (instantSchedule == true) {
          autoScheduleTasks(id);
        }
        updateTaskAttributes("2", id);
        taskDragging = false;
      });

      $(".modal").on("show.bs.modal", function(e) {
        var _this = $(this);
        var sections = $(this).find(".nav-link");

        sections.each(function() {
          $(this).removeClass("active");
        });

        _this.find(".nav-link:first").addClass("active");
        var panes = $(this).find(".tab-pane");

        panes.each(function() {
          $(this).removeClass("active");
          $(this).removeClass("show");
        });

        _this.find(".tab-pane:first").addClass("active");
        _this.find(".tab-pane:first").addClass("show");
      });

      window.ibex_gantt_config.criticalPathArray = [];

      window.getLatestTaskInSequence = function getLatestTaskInSequence() {
        var tasksAll = gantt.getTaskByTime();
        var datesArray = [];
        var latestTasks = [];

        for (var j = 0; j < tasksAll.length; j++) {
          datesArray.push(moment(tasksAll[j].end_date).format("YYYY-MM-DD HH:mm:ss"));
        }

        var max = datesArray[0],
          min = datesArray[0];

        // iterate over array values and update min & max
        datesArray.forEach(function(v) {
          max = new Date(v) > new Date(max) ? v : max;
          min = new Date(v) < new Date(min) ? v : min;
        });

        for (var k = 0; k < tasksAll.length; k++) {
          if (moment(tasksAll[k].end_date).format("YYYY-MM-DD HH:mm:ss") == max && tasksAll[k].type == "task") {
            latestTasks.push(tasksAll[k].id);
          }
        }
        return latestTasks;
      }

      window.getCriticalPath = function getCriticalPath() {
        // Get last task
        var lastTaskArray = getLatestTaskInSequence();
        window.ibex_gantt_config.criticalPathArray = [];
        // Order tasks by finish date/time
        var a = ["a", "b", "c"];
        lastTaskArray.forEach(function(entry) {
          var lastInsertedTaskToArray = entry;
          window.ibex_gantt_config.criticalPathArray.push(entry);
          // Now we know final task, work backwards to start task
          var iterate = true;
          do {
            // Get task before last inserted
            var taskInsertedTarget = gantt.getTask(lastInsertedTaskToArray).$target;
            if (taskInsertedTarget.length > 0) {
              var linkSource = gantt.getLink(taskInsertedTarget).source;
              window.ibex_gantt_config.criticalPathArray.push(linkSource);
              lastInsertedTaskToArray = linkSource;
            } else {
              iterate = false;
              break;
            }
            //iterate = false;
          }
          while (iterate == true);
        });
        return window.ibex_gantt_config.criticalPathArray;
      }

      window.isTaskCritical = function isTaskCritical(task) {
        var criticalPath = getCriticalPath();
        if (window.ibex_gantt_config.criticalPathArray.indexOf(task.id) != -1) {
          return true;
        }
      }

      gantt.templates.task_class = function(start, end, task) {
        if (window.ibex_gantt_config.critical_path_shown == true) {
          var testCritical = isTaskCritical(task);

          if (testCritical == true) {
            return "gantt_critical_task"
          }
        }
        if (task.is_summary == "1") {
          return "project";
        }
        return "";
      };

      window.ibex_gantt_config.shadowTaskResourceLinks = [];

      gantt.attachEvent("onBeforeTaskDisplay", function(id, task) {
        var shadowArray = Array();
        if (task.$new != true && task.resource_group_id != "0") {
          // Existing task res arrya
          var existingResources = task.resource_id;
          var existingResourcesArray;
          if (existingResources.indexOf(',') > -1) {
            existingResourcesArray = existingResources.split(',');
          } else {
            existingResourcesArray = [existingResources];
          }
          // Now get all items in group attached to task
          for (var resource of window.ibex_gantt_config.resources) {
            if (resource.group_id == task.resource_group_id) {
              // Match - already in array?
              var testIncl = existingResourcesArray.includes(resource.id);
              if (testIncl == false) {
                var arrayShow = {
                  task_id: task.id,
                  resource_id: resource.id
                };
                window.ibex_gantt_config.shadowTaskResourceLinks.push(arrayShow);
                existingResourcesArray.push(resource.id.trim());
              }
            }
          }
          task.resource_id = existingResourcesArray.join(",");
        }

        if (task.$new != true && window.ibex_gantt_config.taskBeingDragged == false) {
          task.end_date = moment(getTaskEndDate(task.start_date, task.duration_worked, task.calendar_id)).toDate();
          window.ibex_gantt_config.taskBeingDragged = false;
        }
        if (task.type == "milestone") {}
        return true;
      });

      <?php include "js-task-editor-delete.php" ?>

      gantt.attachEvent("onBeforeTaskDrag", function(id, mode, e) {
        var task = gantt.getTask(id);

        if (isDefined(task.resource_group_id) && isDefined(task.workload_quantity) && mode == "resize") {
          gantt.message({
            type: "error",
            text: "Please adjust the workload to change the duration",
            expire: 5000
          });
          return false;
        }

        window.ibex_gantt_config.originalTaskEditorObject = gantt.getTask(id);
        createSnapshot();
        return true;
      });

      function remove(array, element) {
        const index = array.indexOf(element);
        array.splice(index, 1);
      }

      gantt.attachEvent("onBeforeTaskMove", function(id, parent, tindex) {
        if (canUserEditTask(id) == false) {
          return false;
        } else {
          window.ibex_gantt_config.originalTaskEditorObject = gantt.getTask(id);
          return true;
        }
      });

      gantt.attachEvent("onBeforeTaskUpdate", function(id, new_item) {});

      var timer;
      $('#activity_search_text').keyup(function() {
        clearTimeout(timer);
        timer = setTimeout(function() {
          var searchText = $("#activity_search_text").val().toLowerCase();
          $.each($(".text"), function() {
            if ($(this).text().toLowerCase().indexOf(searchText) === -1) {
              $(this).closest('.feed-item').hide();
            } else {
              $(this).closest('.feed-item').show();
            }
          });
        }, 400);
      });

      $("#activity_filter_user").change(function() {
        if ($(this).val() == "-1") // all
        {
          $.each($(".text"), function() {
            $(this).closest('.feed-item').show();
          });
        }
        if ($(this).val() == "-2") // mine
        {
          $.each($(".feed-item"), function() {
            if ($(this).data("self") == true) {
              $(this).show();
            } else {
              $(this).hide();
            }
          });
        }
      });

      $(".clear-activity-search").click(function() {
        $("#activity_search_text").val('');
        $.each($(".text"), function() {
          $(this).closest('.feed-item').show();
        });
      });

      $(".clear-file-search").click(function() {
        $("#file_search_text").val('');
        $.each($(".text"), function() {
          $(this).closest('.file-edit').show();
        });
      });
    </script>

    <script>
      $(".activity-poll").click(function(e) {
        reloadActivityFeed();
      });

      // Show or hide the buttons in the header bar, dependening on which tab is shown
      // Gantt Header Toolbar
      $('a[href="#gantt"]').click(function(event) {
        $("#toolbar-header-text").text("Gantt");
        $("#gantt-header-toolbar").show();
        $("#resources-header-toolbar").hide();
        $("#files-header-toolbar").hide();
        $("#teams-header-toolbar").hide();
        $("#reports-header-toolbar").hide();
      });
      // Resources Header Toolbar
      $('a[href="#resources"]').click(function(event) {
        $("#toolbar-header-text").text("Resources");
        $("#gantt-header-toolbar").hide();
        $("#resources-header-toolbar").show();
        $("#files-header-toolbar").hide();
        $("#teams-header-toolbar").hide();
        $("#reports-header-toolbar").hide();
      });
      // Files Header Toolbar
      $('a[href="#files"]').click(function(event) {
        $("#toolbar-header-text").text("Files");
        $("#gantt-header-toolbar").hide();
        $("#resources-header-toolbar").hide();
        $("#files-header-toolbar").show();
        $("#teams-header-toolbar").hide();
        $("#reports-header-toolbar").hide();
      });
      // Teams Header Toolbar
      $('a[href="#teams"]').click(function(event) {
        $("#toolbar-header-text").text("Teams");
        $("#gantt-header-toolbar").hide();
        $("#resources-header-toolbar").hide();
        $("#files-header-toolbar").hide();
        $("#teams-header-toolbar").show();
        $("#reports-header-toolbar").hide();
      });
      // Reports Header Toolbar
      $('a[href="#reports"]').click(function(event) {
        $("#toolbar-header-text").text("Reports");
        $("#gantt-header-toolbar").hide();
        $("#resources-header-toolbar").hide();
        $("#files-header-toolbar").hide();
        $("#teams-header-toolbar").hide();
        $("#reports-header-toolbar").show();
      });

      $("#gantt-activity-toggle").click(function(e) {
        $("#gantt-activity").addClass("show active");
        $("#gantt-activity-toggle").addClass("disabled");
      });

      $('#gantt-activity-close').click(function(e) {
        $('#gantt-activity').removeClass("show active");
        $("#gantt-activity-toggle").removeClass("disabled");
      });

      $("#gantt-activity-expand").click(function(e) {
        $("#gantt-activity-expand").hide();
        $("#gantt-activity-shrink").show();
        $("#gantt-activity").addClass("gantt-activity-header-expand");
        $(".activity-feed").addClass("gantt-activity-feed-expand");
      });

      $("#gantt-activity-shrink").click(function(e) {
        $("#gantt-activity-shrink").hide();
        $("#gantt-activity-expand").show();
        $("#gantt-activity").removeClass("gantt-activity-header-expand");
        $(".activity-feed").removeClass("gantt-activity-feed-expand");
      });
      
      $("#gantt-activity-expand").click(function(e) {
        $("#gantt-activity-expand").hide();
        $("#gantt-activity-shrink").show();
        $("#gantt-activity").addClass("gantt-activity-header-expand");
        $(".activity-feed").addClass("gantt-activity-feed-expand");
      });

      $("#gantt-activity-shrink").click(function(e) {
        $("#gantt-activity-shrink").hide();
        $("#gantt-activity-expand").show();
        $("#gantt-activity").removeClass("gantt-activity-header-expand");
        $(".activity-feed").removeClass("gantt-activity-feed-expand");
      });

      $("#show-messages").click(function(e) {
        $("#show-messages").addClass("disabled");
        $("#site-messages").show();
      });

      $('#messages-close').click(function(e) {
        $("#show-messages").removeClass("disabled");
        $("#site-messages").hide();
      });

      $("#messages-shrink").click(function(e) {
        $("#messages-shrink").hide();
        $("#messages-contacts").hide();
        $("#messages-content").addClass("messages-content-shrunk");
        $("#messages-expand").show();
        $("#modal_messages").addClass("messages-shrunk");
        $("#conversation_you_and_name").hide();
        $("div#new-message-input-group").removeClass("col-md-9");
        $("input#new_message_wysiwyg").addClass("message-input-group-shrunk");
        $("div#messages-footer").addClass("messages-footer-shrunk");
      });

      $("#messages-expand").click(function(e) {
        $("#messages-expand").hide();
        $("#messages-contacts").show();
        $("#messages-content").removeClass("messages-content-shrunk");
        $("#messages-shrink").show();
        $("#modal_messages").removeClass("messages-shrunk");
        $("#conversation_you_and_name").show();
        $("div#new-message-input-group").addClass("col-md-9");
        $("input#new_message_wysiwyg").removeClass("message-input-group-shrunk");
        $("div#messages-footer").removeClass("messages-footer-shrunk");
      });

      gantt.attachEvent("onTemplatesReady", function() {
        if (window.ibex_gantt_config.currentZoomLevel == "month") {}
      });
    </script>

    <script>
      var $hamburger = $(".hamburger");
      $hamburger.on("click", function(e) {
        $hamburger.toggleClass("is-active");
        // Do something else, like open/close menu
      });
    </script>

    <!-- Begin emoji-picker JavaScript -->
    <script src="js/config.js"></script>
    <script src="js/util.js"></script>
    <script src="js/jquery.emoji.js"></script>
    <script src="js/emoji-picker.js"></script>
    <!-- End emoji-picker JavaScript -->
    <script>
      $(function() {
        // Initializes and creates emoji set from sprite sheet
        window.emojiPicker = new EmojiPicker({
          emojiable_selector: '[data-emojiable=true]',
          assetsPath: 'img/',
        });
        // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
        // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
        // It can be called as many times as necessary; previously converted input fields will not be converted again
        window.emojiPicker.discover();
      });

      var timerGantt;
      $('#gantt_search_text').keyup(function() {
        clearTimeout(timerGantt);
        timerGantt = setTimeout(function() {
          var searchText = $("#gantt_search_text").val().toLowerCase();
          $.each($(".gantt_row"), function() {
            if ($(this).text().toLowerCase().indexOf(searchText) === -1) {
              $(this).closest('.gantt_row').hide();
              $(this).closest('.gantt_task_row').hide();
            } else {
              $(this).closest('.gantt_row').show();
            }
          });
        }, 400);
      });
    </script>
    <script src="js/classie.js"></script>
    <script src="js/sidebarEffects.js"></script>

    <?php include "ui-date-range.php" ?>
    <?php include "ui-gantt-templates.php" ?>
    <?php include "js-zoom.php" ?>
    <?php include "js-teams.php" ?>
    <?php include "js-permissions.php" ?>
    <?php include "js-messages.php" ?>
    <?php include "js-files.php" ?>
    <?php include "js-deadlines.php" ?>
    <?php include "js-commercial.php" ?>
    <?php include "js-broadcasts.php" ?>
    <?php include "support.php" ?>
    <?php include "ui-support.php" ?>
    <?php include "ui-reports.php" ?>
  </body>
</html>