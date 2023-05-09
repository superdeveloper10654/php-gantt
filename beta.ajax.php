<?php
	session_start();
	date_default_timezone_set('UTC');
	include("../dbconfig.php");
	//include("includes/sendgrid-php.php");

	error_reporting(E_ERROR | E_WARNING | E_PARSE);

	function isTaskParent($array, $task_id)
	{
		$task_is_parent = false;
		for ($i = 0; $i < count($array); $i++) {
			if ($array[$i]['parent'] == $task_id) {
				$task_is_parent = true;
				break;
			}
		}
		return $task_is_parent;
	}

	function generateGUID()
	{
		if (function_exists('com_create_guid')) {
			return com_create_guid();
		} else {
			mt_srand((float)microtime() * 10000);
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45); // "-"
			$uuid = substr($charid, 0, 8) . $hyphen
				. substr($charid, 8, 4) . $hyphen
				. substr($charid, 12, 4) . $hyphen
				. substr($charid, 16, 4) . $hyphen
				. substr($charid, 20, 12);
			return $uuid;
		}
}

	function multiKeyExists(array $arr, $key)
	{
		// is in base array?
		if (array_key_exists($key, $arr)) {
			return true;
		}

		// check arrays contained in this array
		foreach ($arr as $element) {
			if (is_array($element)) {
				if (multiKeyExists($element, $key)) {
					return true;
				}
			}
		}
		return false;
	}

	function getCalendarStartTime($calendar_id)
	{
		global $global_calendars;
		foreach ($global_calendars as $global_calendar) {
			if ($global_calendar['id'] == $calendar_id) {
				return $global_calendar['start_time'];
			}
		}
	}

	function getCalendarEndTime($calendar_id)
	{
		global $global_calendars;
		foreach ($global_calendars as $global_calendar) {
			if ($global_calendar['id'] == $calendar_id) {
				return $global_calendar['end_time'];
			}
		}
	}

	function getTaskEndDateFromMinutesv2($start_date, $duration_worked, $calendar_id)
	{
		// Get calendar
		global $global_calendars;
		foreach ($global_calendars as $global_calendar) {
			if ($global_calendar['id'] == $calendar_id) {
				$calendar = $global_calendar;
				break;
			}
		}
		$start_time = $calendar['start_time'];
		$end_time = $calendar['end_time'];
		// Iterate through minutes in this task to get to end
		$total_minutes_in_task = $duration_worked;
		$running_total = $total_minutes_in_task;
		// Get mins in each shift
		$shift_length_mins = abs($calendar['end_hour'] - $calendar['start_hour']) * 60;
		// Get diff between start date and end of this shift
		$date_aspect = DateTime::createFromFormat('Y-m-d H:i:s', $start_date)->format("Y-m-d");
		$date_aspect_dmy = DateTime::createFromFormat('Y-m-d H:i:s', $start_date)->format("d/m/Y");
		$start_date_mins = new DateTime($start_date);
		$end_date = $date_aspect . " " . $end_time . ":00";
		$since_start = $start_date_mins->diff(new DateTime($end_date));
		$minutes = $since_start->days * 24 * 60;
		$minutes += $since_start->h * 60;
		$minutes += $since_start->i;
		// Test if just one shift
		if ($total_minutes_in_task < $minutes) {
			$task_end_date_verified = DateTime::createFromFormat('Y-m-d H:i:s', $start_date)->modify('+' . $duration_worked . " minutes")->format("Y-m-d H:i:s");
		} else if ($minutes >= $total_minutes_in_task) {
			$task_end_date_verified = DateTime::createFromFormat('Y-m-d H:i:s', $start_date)->modify('+' . $duration_worked . " minutes")->format("Y-m-d H:i:s");
		} else if ($total_minutes_in_task > $minutes) {
			$running_total = $total_minutes_in_task;
			// Fill up to end of shift
			if ($minutes > 0) {
				$task_end_date_verified = DateTime::createFromFormat('Y-m-d H:i:s', $start_date)->modify('+' . $minutes . " minutes")->format("Y-m-d H:i:s");
				$running_total = $running_total - $minutes;
			}
			$next_day = getNextWorkingDay($date_aspect_dmy, $calendar_id);
			$next_day_formatted = DateTime::createFromFormat('d/m/Y', $next_day)->format("Y-m-d") . " " . $start_time . ":00";
			do {
				if ($running_total > $shift_length_mins) {
					// More than this shift - get next working day and deduct mins
					$next_day_loop = DateTime::createFromFormat('Y-m-d H:i:s', $next_day_formatted)->format("d/m/Y");
					$next_day_loop_output = getNextWorkingDay($next_day_loop, $calendar_id);
					$next_day_formatted = DateTime::createFromFormat('d/m/Y', $next_day_loop_output)->format("Y-m-d") . " " . $start_time . ":00";
					$running_total = $running_total - $shift_length_mins;
				} else {
					// Ends in this shift
					$task_end_date_verified = DateTime::createFromFormat('Y-m-d H:i:s', $next_day_formatted)->modify('+' . $running_total . " minutes")->format("Y-m-d H:i:s");
					$running_total = 0;
					break;
				}
			} while ($running_total > 0);
		}
		return $task_end_date_verified;
	}

	function getTaskEndDateFromMinutesv2Output($start_date, $duration_worked, $calendar_id, $db)
	{
		// Get calendar
		$sql = "SELECT * FROM gantt_calendars WHERE id='$calendar_id'";
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$calendar = $stmt->fetch(PDO::FETCH_ASSOC);
		$start_time = $calendar['start_time'];
		$end_time = $calendar['end_time'];
		// Iterate through minutes in this task to get to end
		$total_minutes_in_task = $duration_worked;
		$running_total = $total_minutes_in_task;
		// Get mins in each shift
		$shift_length_mins = abs($calendar['end_hour'] - $calendar['start_hour']) * 60;
		// Get diff between start date and end of this shift
		$date_aspect = DateTime::createFromFormat('Y-m-d H:i:s', $start_date)->format("Y-m-d");
		$date_aspect_dmy = DateTime::createFromFormat('Y-m-d H:i:s', $start_date)->format("d/m/Y");
		$start_date_mins = new DateTime($start_date);
		$end_date = $date_aspect . " " . $end_time . ":00";
		$since_start = $start_date_mins->diff(new DateTime($end_date));
		$minutes = $since_start->days * 24 * 60;
		$minutes += $since_start->h * 60;
		$minutes += $since_start->i;
		// Test if just one shift
		if ($total_minutes_in_task < $minutes) {
			$task_end_date_verified = DateTime::createFromFormat('Y-m-d H:i:s', $start_date)->modify('+' . $duration_worked . " minutes")->format("Y-m-d H:i:s");
		} else if ($minutes >= $total_minutes_in_task) {
			$task_end_date_verified = DateTime::createFromFormat('Y-m-d H:i:s', $start_date)->modify('+' . $duration_worked . " minutes")->format("Y-m-d H:i:s");
		} else if ($total_minutes_in_task > $minutes) {
			$running_total = $total_minutes_in_task;
			// Fill up to end of shift
			if ($minutes > 0) {
				$task_end_date_verified = DateTime::createFromFormat('Y-m-d H:i:s', $start_date)->modify('+' . $minutes . " minutes")->format("Y-m-d H:i:s");
				$running_total = $running_total - $minutes;
			}
			$next_day = getNextWorkingDayOutput($date_aspect_dmy, $calendar_id, $db);
			$next_day_formatted = DateTime::createFromFormat('d/m/Y', $next_day)->format("Y-m-d") . " " . $start_time . ":00";
			do {
				if ($running_total > $shift_length_mins) {
					// More than this shift - get next working day and deduct mins
					$next_day_loop = DateTime::createFromFormat('Y-m-d H:i:s', $next_day_formatted)->format("d/m/Y");
					$next_day_loop_output = getNextWorkingDay($next_day_loop, $calendar_id);
					$next_day_formatted = DateTime::createFromFormat('d/m/Y', $next_day_loop_output)->format("Y-m-d") . " " . $start_time . ":00";
					$running_total = $running_total - $shift_length_mins;
				} else {
					// Ends in this shift
					$task_end_date_verified = DateTime::createFromFormat('Y-m-d H:i:s', $next_day_formatted)->modify('+' . $running_total . " minutes")->format("Y-m-d H:i:s");
					$running_total = 0;
					break;
				}
			} while ($running_total > 0);
		}
		return $task_end_date_verified;
	}

	function getTaskEndDateFromMinutes($start_date, $duration_worked, $calendar_id)
	{
		// Get calendar
		global $global_calendars;
		foreach ($global_calendars as $global_calendar) {
			if ($global_calendar['id'] == $calendar_id) {
				$calendar = $global_calendar;
				break;
			}
		}
		$start_time = $calendar['start_time'];
		$end_time = $calendar['end_time'];
		// Iterate through minutes in this task to get to end
		$total_minutes_in_task = $duration_worked;
		$running_total = $total_minutes_in_task;
		// Get mins in each shift
		$shift_length_mins = abs($calendar['end_hour'] - $calendar['start_hour']) * 60;
		// Get diff between start date and end of this shift
		$date_aspect = DateTime::createFromFormat('Y-m-d H:i:s', $start_date)->format("Y-m-d");
		$date_aspect_dmy = DateTime::createFromFormat('Y-m-d H:i:s', $start_date)->format("d/m/Y");
		$start_date_mins = new DateTime($start_date);
		$end_date = $date_aspect . " " . $end_time . ":00";
		$since_start = $start_date_mins->diff(new DateTime($end_date));
		$minutes = $since_start->days * 24 * 60;
		$minutes += $since_start->h * 60;
		$minutes += $since_start->i;
		// Test if just one shift
		if ($minutes >= $total_minutes_in_task) {
			$task_end_date_verified = DateTime::createFromFormat('Y-m-d H:i:s', $start_date)->modify('+' . $duration_worked . " minutes")->format("Y-m-d H:i:s");
		} else if ($total_minutes_in_task > $minutes) {
			$running_total = $total_minutes_in_task;
			$next_day = $date_aspect_dmy;
			do {
				if ($running_total > $shift_length_mins) {
					$running_total = $running_total - $shift_length_mins;
					$next_day = getNextWorkingDay($next_day, $calendar_id);
				} else {
					if (isLocalDateWorkingDay($next_day, $calendar_id) == false) {
						$added_next_day = getNextWorkingDay($next_day, $calendar_id);
						$task_end_string = $added_next_day . " " . $start_time . ":00";
					} else {
						$task_end_string = $next_day . " " . $start_time . ":00";
					}
					$task_end_date_verified = DateTime::createFromFormat('d/m/Y H:i:s', $task_end_string)->modify('+' . $running_total . " minutes")->format("Y-m-d H:i:s");
					break;
				}
			} while ($running_total > 0);
		}
		return $task_end_date_verified;
	}

	function getTaskEndDateFromMinutesImported($start_date, $duration_worked, $calendar)
	{
		// Get calendar
		$start_time = $calendar['start_time'];
		$end_time = $calendar['end_time'];
		// Iterate through minutes in this task to get to end
		$total_minutes_in_task = $duration_worked;
		$running_total = $total_minutes_in_task;
		// Get mins in each shift
		$shift_length_mins = abs($calendar['end_hour'] - $calendar['start_hour']) * 60;
		// Get diff between start date and end of this shift
		$date_aspect = DateTime::createFromFormat('Y-m-d H:i:s', $start_date)->format("Y-m-d");
		$date_aspect_dmy = DateTime::createFromFormat('Y-m-d H:i:s', $start_date)->format("d/m/Y");
		$start_date_mins = new DateTime($start_date);
		$end_date = $date_aspect . " " . $end_time . ":00";
		$since_start = $start_date_mins->diff(new DateTime($end_date));
		$minutes = $since_start->days * 24 * 60;
		$minutes += $since_start->h * 60;
		$minutes += $since_start->i;
		// Test if just one shift
		if ($minutes >= $total_minutes_in_task) {
			$task_end_date_verified = DateTime::createFromFormat('Y-m-d H:i:s', $start_date)->modify('+' . $duration_worked . " minutes")->format("Y-m-d H:i:s");
		} else if ($total_minutes_in_task > $minutes) {
			$running_total = $total_minutes_in_task;
			$next_day = $date_aspect_dmy;
			do {
				if ($running_total > $shift_length_mins) {
					$running_total = $running_total - $shift_length_mins;
					$next_day = getNextWorkingDay($next_day, $calendar_id);
				} else {
					if (isLocalDateWorkingDay($next_day, $calendar_id) == false) {
						$added_next_day = getNextWorkingDay($next_day, $calendar_id);
						$task_end_string = $added_next_day . " " . $start_time . ":00";
					} else {
						$task_end_string = $next_day . " " . $start_time . ":00";
					}
					$task_end_date_verified = DateTime::createFromFormat('d/m/Y H:i:s', $task_end_string)->modify('+' . $running_total . " minutes")->format("Y-m-d H:i:s");
					break;
				}
			} while ($running_total > 0);
		}
		return $task_end_date_verified;
	}

	function getTaskEndDate($start_date, $duration_worked, $calendar_id, $db_ibex)
	{
		// Get calendar
		$sql = "SELECT * FROM gantt_calendars WHERE id='$calendar_id'";
		$stmt = $db_ibex->prepare($sql);
		$stmt->execute();
		$calendar = $stmt->fetch(PDO::FETCH_ASSOC);
		$start_hour = sprintf("%02d", $calendar['start_hour']);
		$end_hour = sprintf("%02d", $calendar['end_hour']);
		$loop_to_seek_end_date = true;
		if ($duration_worked == "1") {
			$loop_to_seek_end_date = false;
		}
		if ($calendar['calendar_spans_midnight'] == "0") {
			$duration_worked--;
		}
		if ($loop_to_seek_end_date == true) {
			$incremented_duration = 1;
			$loop_start_date = $start_date;
			$loop_end_date;
			do {
				$loop_end_date = DateTime::createFromFormat('d/m/Y', $loop_start_date);
				$loop_end_date->modify('+1 day');
				$loop_end_date_parsed = $loop_end_date->format('d/m/Y');

				if (isDateWorkingDay($loop_end_date_parsed, $calendar_id, $db_ibex) == true) {
					$incremented_duration++;
				} else {
					if (($duration_worked - $incremented_duration == 1) && $calendar['calendar_spans_midnight'] == "1") {
						$incremented_duration++;
					}
				}
				$loop_start_date = $loop_end_date_parsed;
			} while ($incremented_duration < $duration_worked);
		} else {
			$loop_start_date = $start_date;
			$loop_end_date;
			if ($calendar['calendar_spans_midnight'] == "1") {
				$loop_end_date = DateTime::createFromFormat('d/m/Y', $loop_start_date);
				$loop_end_date->modify('+1 day');
				$loop_end_date_parsed = $loop_end_date->format('d/m/Y');
			} else {
				$loop_end_date = DateTime::createFromFormat('d/m/Y', $loop_start_date);
				$loop_end_date_parsed = $loop_end_date->format('d/m/Y');
			}
		}
		return $loop_end_date_parsed . " " . $calendar['end_time'];
	}

	function getNonWorkingDaysWithinDateRange($first, $last, $calendar_id, $step = '+1 day', $output_format = 'd/m/Y')
	{
		$dates = array();
		$current = strtotime($first);
		$last = strtotime($last);
		$count = 0;
		while ($current <= $last) {
			$test = date($output_format, $current);
			if (isLocalDateWorkingDay($test, $calendar_id) == false) {
				$count++;
			}
			$current = strtotime($step, $current);
		}
		return $count;
	}

	function getDaysWithinDateRange($first, $last, $calendar_id, $step = '+1 day', $output_format = 'd/m/Y')
	{
		$dates = array();
		$current = strtotime($first);
		$last = strtotime($last);
		while ($current <= $last) {
			$dates[] = date($output_format, $current);
			$current = strtotime($step, $current);
		}
		return $dates;
	}

	function getAdjustedEndDate($start_date, $end_date, $new_start_date)
	{
		// Get all minutes in this period
		$start_date_mins = new DateTime($start_date);
		$since_start = $start_date_mins->diff(new DateTime($end_date));
		$minutes = $since_start->days * 24 * 60;
		$minutes += $since_start->h * 60;
		$minutes += $since_start->i;
		// Add to new start date
		$new_end_date = DateTime::createFromFormat('Y-m-d H:i:s', $new_start_date);
		$new_end_date->modify('+' . $minutes . ' minutes');
		return $new_end_date->format('Y-m-d H:i:s');
	}

	function isDateWorkingDay($date, $calendar_id, $db_ibex)
	{
		$check_date = DateTime::createFromFormat('d/m/Y', $date);
		$check_date_parsed = $check_date->format('U');
		// Validate if this date is a working date as per calendar
		$working_date = true;
		$nonWorkingDayISOIDs = array();
		$nonCustomWorkingDays = array();
		// Evaluate standard non-working days first
		$iso_id = date("N", $check_date_parsed);
		// Get calendar
		$sql = "SELECT *FROM gantt_calendars WHERE id='$calendar_id'";
		$stmt = $db_ibex->prepare($sql);
		$stmt->execute();
		$calendar = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($iso_id == 1) {
			if ($calendar['working_day_monday'] == "0") {
				$working_date = false;
			}
		}
		if ($iso_id == 2) {
			if ($calendar['working_day_tuesday'] == "0") {
				$working_date = false;
			}
		}
		if ($iso_id == 3) {
			if ($calendar['working_day_wednesday'] == "0") {
				$working_date = false;
			}
		}
		if ($iso_id == 4) {
			if ($calendar['working_day_thursday'] == "0") {
				$working_date = false;
			}
		}
		if ($iso_id == 5) {
			if ($calendar['working_day_friday'] == "0") {
				$working_date = false;
			}
		}
		if ($iso_id == 6) {
			if ($calendar['working_day_saturday'] == "0") {
				$working_date = false;
			}
		}
		if ($iso_id == 7) {
			if ($calendar['working_day_sunday'] == "0") {
				$working_date = false;
			}
		}
		// Get calendar overrides
		$sql = "SELECT * FROM gantt_calendar_overrides WHERE calendar_id='$calendar_id'";
		$stmt = $db_ibex->prepare($sql);
		$stmt->execute();
		$calendar_overrides = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$check_date_override = DateTime::createFromFormat('d/m/Y', $date);
		$check_date_override_parsed = $check_date_override->format('Y-m-d');
		foreach ($calendar_overrides as $calendar_override) {
			if ($check_date_override_parsed == $calendar_override['date']) {
				if ($calendar_override['override_type'] == "0") {
					$working_date = false;
				}
				if ($calendar_override['override_type'] == "1") {
					$working_date = true;
				}
			}
		}
		return $working_date;
	}

	function getNextWorkingDayOutput($date, $calendar_id, $db)
	{
		$loop_date = $date;
		$valid = false;
		do {
			$test_loop_date = DateTime::createFromFormat('d/m/Y', $loop_date);
			$test_loop_date->modify('+1 day');
			$test_loop_date_parsed = $test_loop_date->format('d/m/Y');
			if (isLocalDateWorkingDayOutput($test_loop_date_parsed, $calendar_id, $db) == true) {
				$valid = true;
				return $test_loop_date_parsed;
				break;
			}
			$loop_date = $test_loop_date->format('d/m/Y');
		} while ($valid == false);
	}

	function getNextWorkingDay($date, $calendar_id)
	{
		$loop_date = $date;
		$valid = false;
		do {
			$test_loop_date = DateTime::createFromFormat('d/m/Y', $loop_date);
			$test_loop_date->modify('+1 day');
			$test_loop_date_parsed = $test_loop_date->format('d/m/Y');
			if (isLocalDateWorkingDay($test_loop_date_parsed, $calendar_id) == true) {
				$valid = true;
				return $test_loop_date_parsed;
				break;
			}
			$loop_date = $test_loop_date->format('d/m/Y');
		} while ($valid == false);
	}

	function isLocalDateWorkingDayOutput($date, $calendar_id, $db)
	{
		$check_date = DateTime::createFromFormat('d/m/Y', $date);
		$check_date_parsed = $check_date->format('U');
		// Validate if this date is a working date as per calendar
		$working_date = true;
		$nonWorkingDayISOIDs = array();
		$nonCustomWorkingDays = array();
		// Evaluate standard non-working days first
		$iso_id = date("N", $check_date_parsed);
		// Get calendar
		$sql = "SELECT * FROM gantt_calendars WHERE id='$calendar_id'";
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$calendar = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($iso_id == 1) {
			if ($calendar['working_day_monday'] == "0") {
				$working_date = false;
			}
		}
		if ($iso_id == 2) {
			if ($calendar['working_day_tuesday'] == "0") {
				$working_date = false;
			}
		}
		if ($iso_id == 3) {
			if ($calendar['working_day_wednesday'] == "0") {
				$working_date = false;
			}
		}
		if ($iso_id == 4) {
			if ($calendar['working_day_thursday'] == "0") {
				$working_date = false;
			}
		}
		if ($iso_id == 5) {
			if ($calendar['working_day_friday'] == "0") {
				$working_date = false;
			}
		}
		if ($iso_id == 6) {
			if ($calendar['working_day_saturday'] == "0") {
				$working_date = false;
			}
		}
		if ($iso_id == 7) {
			if ($calendar['working_day_sunday'] == "0") {
				$working_date = false;
			}
		}
		$sql = "SELECT * FROM gantt_calendar_overrides WHERE calendar_id='$calendar_id'";
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$global_calendars_overrides = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach ($global_calendars_overrides as $calendar_override) {
			if ($check_date_override_parsed == $calendar_override['date']) {
				if ($calendar_override['override_type'] == "0") {
					$working_date = false;
				}
				if ($calendar_override['override_type'] == "1") {
					$working_date = true;
				}
			}
		}
		return $working_date;
	}

	function isLocalDateWorkingDay($date, $calendar_id)
	{
		$check_date = DateTime::createFromFormat('d/m/Y', $date);
		$check_date_parsed = $check_date->format('U');
		// Validate if this date is a working date as per calendar
		$working_date = true;
		$nonWorkingDayISOIDs = array();
		$nonCustomWorkingDays = array();
		// Evaluate standard non-working days first
		$iso_id = date("N", $check_date_parsed);
		// Get calendar
		global $global_calendars;
		foreach ($global_calendars as $global_calendar) {
			if ($global_calendar['id'] == $calendar_id) {
				$calendar = $global_calendar;
				break;
			}
		}
		if ($iso_id == 1) {
			if ($calendar['working_day_monday'] == "0") {
				$working_date = false;
			}
		}
		if ($iso_id == 2) {
			if ($calendar['working_day_tuesday'] == "0") {
				$working_date = false;
			}
		}
		if ($iso_id == 3) {
			if ($calendar['working_day_wednesday'] == "0") {
				$working_date = false;
			}
		}
		if ($iso_id == 4) {
			if ($calendar['working_day_thursday'] == "0") {
				$working_date = false;
			}
		}
		if ($iso_id == 5) {
			if ($calendar['working_day_friday'] == "0") {
				$working_date = false;
			}
		}
		if ($iso_id == 6) {
			if ($calendar['working_day_saturday'] == "0") {
				$working_date = false;
			}
		}
		if ($iso_id == 7) {
			if ($calendar['working_day_sunday'] == "0") {
				$working_date = false;
			}
		}
		// Get calendar overrides
		global $global_calendars_overrides;
		foreach ($global_calendars_overrides as $calendar_override) {
			if ($check_date_override_parsed == $calendar_override['date']) {
				if ($calendar_override['override_type'] == "0") {
					$working_date = false;
				}
				if ($calendar_override['override_type'] == "1") {
					$working_date = true;
				}
			}
		}
		return $working_date;
	}




	// Globals
	if (isset($_SESSION['demo_session'])) {
		$programme_id = $_COOKIE['ibex_demo_programme_id'];
		$account_id = $_COOKIE['ibex_demo_account_id'];
	} else {
		$programme_id = $_SESSION['current_programme_id'];
		// $account_id = $_SESSION['user']['account_id'];
		$account_id = $_SESSION['user']['account_setup'];
	}

	// Global links and tasks and cals array for auto scheduling
	$global_links;
	$global_tasks;
	$global_calendars;
	$global_calendar_overrides;


	function singleLinkOutOfTask($task_id)
	{
		global $global_links;
		$count = 0;
		foreach ($global_links as $link) {
			if ($link['source'] == $task_id) {
				$count++;
			}
		}
		if ($count == 0 || $count == 2) {
			return false;
		} else if ($count == 1) {
			return true;
		}
	}

	function linkInToTaskExists($task_id)
	{
		global $global_links;
		$count = 0;
		foreach ($global_links as $link) {
			if ($link['target'] == $task_id) {
				$count++;
			}
		}
		if ($count == 0) {
			return false;
		} else {
			return true;
		}
	}

	function calculateLinkLastDate($task_id, $start_date_ref, $duration_worked_ref, $calendar_id_ref)
	{
		global $global_tasks;
		global $global_links;
		$dates_array = array();

		foreach ($global_links as $link) {
			if ($link['target'] == $task_id) {
				$source_task = $link['source'];
				foreach ($global_tasks as $task) {
					if ($task['id'] == $source_task) {
						$start_date_current = $task['start_date'];
						$end_date_current = $task['end_date'];
						$duration_worked_current = $task['duration_worked'];

						// Lag / lead variables
						// 0 and 0
						if ($link['lag'] == "0" && $link['lead'] == "0") {

							$valid = false;
							// Just get next working date
							$loop_start_date = DateTime::createFromFormat('Y-m-d H:i:s', $end_date_current)->format('d/m/Y');
							do {
								$loop_end_date = DateTime::createFromFormat('d/m/Y', $loop_start_date);
								$loop_end_date_parsed = $loop_end_date->format('d/m/Y');

								$valid = isLocalDateWorkingDay($loop_end_date_parsed, $task['calendar_id']);
								if ($valid == true) {
									break;
								}
								$loop_start_date = DateTime::createFromFormat('d/m/Y', $loop_end_date_parsed)->format('d/m/Y');
							} while ($valid == false);

							// Determine if this 
							$end_time = DateTime::createFromFormat('Y-m-d H:i:s', $task['end_date'])->format('H:i:s');
							// Get next working date
							$task_array_end = array("end_date" => $loop_end_date_parsed . " " . $end_time, "calendar_id" => $task['calendar_id']);
							array_push($dates_array, $task_array_end);
						}

						// Lag



						else if ($link['lag'] != "0") {

							$duration_worked_append = intval($duration_worked_current) + (60 * intval($link['lag']));

							$end_date_with_output = getTaskEndDateFromMinutesv2($start_date_current, $duration_worked_append, $calendar_id_ref);
							$end_date_with_output = DateTime::createFromFormat('Y-m-d H:i:s', $end_date_with_output)->format('d/m/Y H:i:s');

							$task_array_end = array("end_date" => $end_date_with_output, "calendar_id" => $task['calendar_id']);
							array_push($dates_array, $task_array_end);
						}

						// Lead
						else if ($link['lead'] != "0") {

							$duration_worked_append = intval($duration_worked_current) - (60 * intval($link['lead']));


							$end_date_with_output = getTaskEndDateFromMinutesv2($start_date_current, $duration_worked_append, $calendar_id_ref);
							$end_date_with_output = DateTime::createFromFormat('Y-m-d H:i:s', $end_date_with_output)->format('d/m/Y H:i:s');

							$task_array_end = array("end_date" => $end_date_with_output, "calendar_id" => $task['calendar_id']);
							array_push($dates_array, $task_array_end);
						}
					}
				}
			}
		}
		$last_date = 0;
		$last_date_formatted;
		$calendar_id = 0;



		foreach ($dates_array as $date_array) {
			$date_full = DateTime::createFromFormat('d/m/Y H:i:s', $date_array['end_date'])->format('Y-m-d H:i:s');
			$current = strtotime($date_full);
			if ($current > $last_date) {
				$last_date = $current;
				$calendar_id = $date_array['calendar_id'];
			}
		}
		$last_date = date('Y-m-d H:i:s', $last_date);

		// Is this date right up to end of shift in calendar?
		if (DateTime::createFromFormat('Y-m-d H:i:s', $last_date)->format('H:i') == getCalendarEndTime($calendar_id)) {

			// Get next working date and start time
			$seek_date = DateTime::createFromFormat('Y-m-d H:i:s', $last_date)->format('d/m/Y');
			$next_day = getNextWorkingDay($seek_date, $calendar_id);
			$date_to_return = $next_day . " " . getCalendarStartTime($calendar_id) . ":00";

			$date_to_return = DateTime::createFromFormat('d/m/Y H:i:s', $date_to_return)->format('Y-m-d H:i:s');
		} else {

			$date_to_return = $last_date;
		}


		return $date_to_return;
	}






	function convertYMDtoDMY($date)
	{
		return DateTime::createFromFormat('Y-m-d', $date)->format('d/m/Y');
	}



	$action = $_REQUEST['action'];
	$programme_id = $_SESSION['gantt_id'];
	switch ($action) {


		case "reported_task":
			$task_id = $_REQUEST['id'];
			$stmt = $db->prepare("SELECT * FROM gantt_tasks WHERE programme_id = '$programme_id' AND id='$task_id' AND active = '1'");
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);

			// Parent
			$parent_id = $task['parent'];
			$stmt = $db->prepare("SELECT * FROM gantt_tasks WHERE programme_id = '$programme_id' AND id='$parent_id'");
			$stmt->execute();
			$parent = $stmt->fetch(PDO::FETCH_ASSOC);
			$task['parent_object'] = $parent;

			// Cal
			$calendar_id = $task['calendar_id'];
			$stmt = $db->prepare("SELECT * FROM gantt_calendars WHERE programme_id = '$programme_id' AND id='$calendar_id'");
			$stmt->execute();
			$calendar = $stmt->fetch(PDO::FETCH_ASSOC);
			$task['calendar_object'] = $calendar;

			// Files
			$files = $task['file_id'];
			/*
		// Multiple?
		if( strpos($files, ",") !== false ) 
		{
		// Yes
				$files_objects = array();
				
			$files_objects = explode(',', $files); //split string into array seperated by ', '
			foreach($files_objects as $file_object) //loop over values
			{
				$stmt = $db->prepare("SELECT * FROM gantt_files WHERE programme_id='$programme_id'");    
				$stmt->execute();
				$file = $stmt->fetch(PDO::FETCH_ASSOC);
				$files_objects = array();
				array_push($files_objects, $file);
				
			}
			$task['files_objects'] = $files_objects;
	
		}
		else 
		{
			// No
			$stmt = $db->prepare("SELECT * FROM gantt_files WHERE programme_id='$programme_id'");
			$stmt->execute();
			$file = $stmt->fetch(PDO::FETCH_ASSOC);
			$files_objects = array();
			array_push($files_objects, $file);
			$task['files_objects'] = $files_objects;
		}

		*/

			// Get files
			$files_array = array();
			foreach ($files as $file) {

				$stmt = $db->prepare("SELECT * FROM gantt_files WHERE programme_id='$programme_id' AND id= :id");
				$stmt->bindValue(':id', $file);
				$stmt->execute();
				$file_object = $stmt->fetch(PDO::FETCH_ASSOC);
				array_push($files_array, $file_object);
			}












			// Cal overrides

			$stmt = $db->prepare("SELECT * FROM gantt_calendar_overrides WHERE programme_id = '$programme_id' AND calendar_id='$calendar_id'");
			$stmt->execute();
			$calendar_overrides = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$task['calendar_override_objects'] = $calendar_overrides;

			// Res
			$resources = $task['resource_id'];

			// Multiple?
			if (strpos($resources, ",") !== false) {
				// Yes
				$resources_objects = array();

				$resources_objects = explode(',', $resources); //split string into array seperated by ', '
				foreach ($resources_objects as $resource_object) //loop over values
				{
					$stmt = $db->prepare("SELECT * FROM gantt_resources WHERE id='$resource_object'");
					$stmt->execute();
					$resource = $stmt->fetch(PDO::FETCH_ASSOC);
					$resources_objects = array();
					array_push($resources_objects, $resource);
				}
				$task['resources_objects'] = $resources_objects;
			} else {
				// No
				$stmt = $db->prepare("SELECT * FROM gantt_resources WHERE id='$resources'");
				$stmt->execute();
				$resource = $stmt->fetch(PDO::FETCH_ASSOC);
				$resources_objects = array();
				array_push($resources_objects, $resource);
				$task['resources_objects'] = $resources_objects;
			}


			$payload = array("task" => $task);
			echo json_encode($payload);

			break;




		case "create_first_project":
			$project_1_guid = generateGUID();
			$order_ui = "0";
			$parent_id = "0";
			$text = $_REQUEST['project_name'];
			$type = "project";
			$duration_worked = "1";
			$stmt = $db->prepare("INSERT INTO gantt_tasks(guid,order_ui,parent,programme_id,text,start_date,end_date,calendar_id,type,duration_worked) VALUES (?,?,?,?,?,?,?,?,?,?)");
			$stmt->bindParam(1, $project_1_guid);
			$stmt->bindParam(2, $order_ui);
			$stmt->bindParam(3, $parent_id);
			$stmt->bindParam(4, $programme_id);
			$stmt->bindParam(5, $text);
			$stmt->bindParam(6, $_REQUEST['start_date']);
			$stmt->bindParam(7, $_REQUEST['end_date']);
			$stmt->bindParam(8, $_REQUEST['calendar_id']);
			$stmt->bindParam(9, $type);
			$stmt->bindParam(10, $duration_worked);
			$stmt->execute();
			$project_1_id = $db->lastInsertId();
			$payload = array("created" => true);
			echo json_encode($payload);
			break;
			die();


		






		case "get_tasks_commercial":
			$task_id = $_REQUEST['id'];
			$stmt = $db->prepare("SELECT * FROM gantt_tasks WHERE programme_id = '$programme_id' AND id='$task_id'");
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$payload = array("task" => $task);
			echo json_encode($payload);
			break;



		case "updateDurationWorked":
			$task_id = $_REQUEST['id'];
			$duration_worked = $_REQUEST['duration_worked'];
			$stmt = $db->prepare("UPDATE gantt_tasks SET duration_worked='$duration_worked' WHERE id='$task_id'");
			$stmt->execute();
			$payload = array("updated gantt duration_worked!" => true);
			echo json_encode($payload);
			break;


			/*************************************************************************************************************/
			/************************************************* MAKE THIS WORK ********************************************/
			/*************************************************************************************************************/
		case "save_permissions_scheduling":
			$data = '[' . $_REQUEST['data'] . ']';
			$user_id = $_SESSION['user']['id'];
			$sql = "UPDATE gantt_columns SET task_columns='$data' WHERE programme_id='$programme_id' AND user_id='$user_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$payload = array("columns_updated" => true);
			echo json_encode($payload);
			break;
			die();






		case "save_columns":
			$data = '[' . $_REQUEST['data'] . ']';
			$user_id = $_SESSION['user']['id'];
			$sql = "UPDATE gantt_columns SET task_columns='$data' WHERE programme_id='$programme_id' AND user_id='$user_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$payload = array("columns_updated" => true);
			echo json_encode($payload);
			break;
			die();



		case "save_columns_resources":

			$data = '[' . $_REQUEST['data'] . ']';
			$user_id = $_SESSION['user']['id'];

			$sql = "UPDATE gantt_columns SET resource_columns='$data' WHERE programme_id='$programme_id' AND user_id='$user_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$payload = array("columns_updated" => true);
			echo json_encode($payload);



			break;
			die();



		case "delete_calendar_override":

			$override_id = $_REQUEST['id'];
			$calendar_id = $_REQUEST['calendar_id'];

			$sql = "DELETE FROM gantt_calendar_overrides WHERE programme_id='$programme_id' AND id='$override_id' AND calendar_id='$calendar_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$stmt = $db->prepare("SELECT * FROM gantt_calendar_overrides WHERE programme_id='$programme_id' AND calendar_id='$calendar_id' ORDER BY start_date ASC");
			$stmt->execute();
			$overrides = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$payload = array("created" => true, "calendar_overrides" => $overrides);
			echo json_encode($payload);


			die();


		case "prepare_rollback_data":
			$stmt = $db->prepare("SELECT * FROM gantt_versions WHERE guid= :version_id AND programme_id = '$programme_id'");
			$stmt->bindValue(':version_id', $_REQUEST['guid']);
			$stmt->execute();
			$version = $stmt->fetch(PDO::FETCH_ASSOC);
			$data = $version['gantt_data'];
			$decoded = json_decode($data, true);
			$tasks_raw = $decoded['data'];
			$count = 0;
			foreach ($tasks_raw as $task) {
				if (!isset($task['is_summary'])) {
					unset($tasks_raw[$count]);
				}
				$count++;
			}
			$decoded['data'] = $tasks_raw;
			$data = json_encode($decoded);
			echo $data;
			die();


		case "delete_task":

			$stmt = $db->prepare("UPDATE gantt_tasks SET active='0' WHERE programme_id='$programme_id' AND id= :task_id");
			$stmt->bindValue(':task_id', $_REQUEST['task_id']);
			$stmt->execute();

			// Now remove any links this task had
			$stmt = $db->prepare("UPDATE gantt_links SET active='0' WHERE (target = :task_id OR source = :task_id) AND programme_id = '$programme_id'");
			$stmt->bindValue(':task_id', $_REQUEST['task_id']);
			$stmt->execute();

			$payload = array("processed" => true);
			echo json_encode($payload);

			break;
			die();


		case "purge_programme":
					
			$version = $_REQUEST['version'];

			$stmt = $db->prepare("SELECT * FROM gantt_versions WHERE guid = '$version' AND programme_id = '$programme_id'");
			// $stmt->bindValue(':version_id', $_REQUEST['version']);
			$stmt->execute();
			$version = $stmt->fetch(PDO::FETCH_ASSOC);
			$id = $version['id'];
			
			$stmt = $db->prepare("UPDATE gantt_versions SET active='0' WHERE programme_id='$programme_id' AND id > '$id'");
			// $stmt->bindValue(':version_id', $_REQUEST['version']);

			$stmt = $db->prepare("UPDATE gantt_versions SET active='1' WHERE programme_id='$programme_id' AND id <= '$id'");
			// $stmt->bindValue(':version_id', $_REQUEST['version']);
			$stmt->execute();

			// Delete existing
			$stmt = $db->prepare("DELETE FROM gantt_tasks WHERE programme_id='$programme_id'");
			$stmt->execute();

			$stmt = $db->prepare("DELETE FROM gantt_links WHERE programme_id='$programme_id'");
			$stmt->execute();

			// Reconstruct from version
			$data = json_decode($version['gantt_data'], true);


			$tasks = $data['data'];
			$links = $data['links'];

			// Insert tasks
			foreach ($tasks as $task) {
				if (isset($task['is_summary'])) {
					unset($task['id']);
					$table = "gantt_tasks";
					$sql = 'INSERT INTO ' . $table . '(`' . implode('`,`', array_keys($task)) . '`) values (:' . implode(',:', array_keys($task)) . ');';
					$stmt = $db->prepare($sql);
					foreach ($task as $field => $value) {
						$params[":{$field}"] = $value;
					}
					$stmt->execute($params);
				}
			}

			$sql = "";
			$params = "";
			// Insert links
			foreach ($links as $link) {
				unset($link['id']);

				$table = "gantt_links";
				$sql = 'INSERT INTO ' . $table . '(`' . implode('`,`', array_keys($link)) . '`) values (:' . implode(',:', array_keys($link)) . ');';
				$stmt = $db->prepare($sql);
				foreach ($link as $field => $value) {
					$params[":{$field}"] = $value;
				}


				$stmt->execute($params);
			}

			// Re-link
			$stmt = $db->prepare("SELECT id,guid,parent,parent_guid FROM gantt_tasks WHERE programme_id = '$programme_id'");
			$stmt->execute();
			$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$tasks_copy = $tasks;

			foreach ($tasks as $task) {
				$task_id = $task['id'];
				if ($task['parent'] != "0") {
					$parent_guid = $task['parent_guid'];

					foreach ($tasks_copy as $task_copy) {
						if ($task_copy['guid'] == $parent_guid) {
							$parent_found = $task_copy['id'];
							$sql = "UPDATE gantt_tasks SET parent='$parent_found' WHERE id='$task_id' AND programme_id='$programme_id'";
							$stmt = $db->prepare($sql);
							$stmt->execute();
						}
					}
				}
			}


			$stmt = $db->prepare("SELECT id,guid FROM gantt_tasks WHERE programme_id = '$programme_id'");
			$stmt->execute();
			$tasks_new = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$tasks_new_copy = $tasks_new;
			$order = 0;
			foreach ($tasks_new_copy as $task_new_copy) {
				$task_id = $task_new_copy['id'];
				$sql = "UPDATE gantt_tasks SET order_ui='$order' WHERE id='$task_id' AND programme_id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$order++;
			}


			$stmt = $db->prepare("SELECT * FROM gantt_links WHERE programme_id = '$programme_id'");
			$stmt->execute();
			$links = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($links as $link) {

				$link_id = $link['id'];

				$source_id = 0;
				$target_id = 0;

				$source_guid = $link['source_guid'];
				$target_guid = $link['target_guid'];
				foreach ($tasks_new as $task_new) {
					if ($task_new['guid'] == $source_guid) {
						$source_id = $task_new['id'];
					}
					if ($task_new['guid'] == $target_guid) {
						$target_id = $task_new['id'];
					}
				}

				$sql = "UPDATE gantt_links SET source='$source_id',target='$target_id' WHERE id='$link_id' AND programme_id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
			}

			$created = time();
			$author_id = $_SESSION['user']['id'];
			$sql = "UPDATE gantt_programmes SET last_save_time='$created',last_save_author_id='$author_id' WHERE id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();



			$payload = array("purged" => true);
			echo json_encode($payload);
			break;
			die();

		case "repair_programme":

			// Put parent IDs back on track

			$stmt = $db->prepare("SELECT id,guid,parent,parent_guid FROM gantt_tasks WHERE programme_id = '$programme_id'");
			$stmt->execute();
			$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$tasks_copy = $tasks;

			foreach ($tasks as $task) {
				$task_id = $task['id'];
				if ($task['parent'] != "0") {
					$parent_guid = $task['parent_guid'];

					foreach ($tasks_copy as $task_copy) {
						if ($task_copy['guid'] == $parent_guid) {

							$parent_found = $task_copy['id'];
							$sql = "UPDATE gantt_tasks SET parent='$parent_found' WHERE id='$task_id' AND programme_id='$programme_id'";
							$stmt = $db->prepare($sql);
							$stmt->execute();
						}
					}
				}
			}

			$stmt = $db->prepare("SELECT id,guid FROM gantt_tasks WHERE programme_id = '$programme_id'");
			$stmt->execute();
			$tasks_new = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$tasks_new_copy = $tasks_new;
			$order = 0;
			foreach ($tasks_new_copy as $task_new_copy) {
				$task_id = $task_new_copy['id'];
				$sql = "UPDATE gantt_tasks SET order_ui='$order' WHERE id='$task_id' AND programme_id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$order++;
			}


			$stmt = $db->prepare("SELECT * FROM gantt_links WHERE programme_id = '$programme_id'");
			$stmt->execute();
			$links = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($links as $link) {

				$link_id = $link['id'];

				$source_id = 0;
				$target_id = 0;

				$source_guid = $link['source_guid'];
				$target_guid = $link['target_guid'];
				foreach ($tasks_new as $task_new) {
					if ($task_new['guid'] == $source_guid) {
						$source_id = $task_new['id'];
					}
					if ($task_new['guid'] == $target_guid) {
						$target_id = $task_new['id'];
					}
				}

				$sql = "UPDATE gantt_links SET source='$source_id',target='$target_id' WHERE id='$link_id' AND programme_id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
			}











			$payload = array("repaired" => true);
			echo json_encode($payload);


			break;
			die();

		case "reset_programme":

			// Reset all

			// Added by RB 12.04.19
			$stmt = $db->prepare("DELETE FROM gantt_versions WHERE programme_id='$programme_id'");
			$stmt->execute();

			$stmt = $db->prepare("DELETE FROM gantt_tasks WHERE programme_id='$programme_id'");
			$stmt->execute();

			$stmt = $db->prepare("DELETE FROM gantt_links WHERE programme_id='$programme_id'");
			$stmt->execute();

			$stmt = $db->prepare("DELETE FROM gantt_calendars WHERE programme_id='$programme_id'");
			$stmt->execute();

			$stmt = $db->prepare("DELETE FROM gantt_calendar_overrides WHERE programme_id='$programme_id'");
			$stmt->execute();

			$stmt = $db->prepare("DELETE FROM gantt_files WHERE programme_id='$programme_id'");
			$stmt->execute();

			$stmt = $db->prepare("DELETE FROM gantt_resource_groups WHERE programme_id='$programme_id'");
			$stmt->execute();

			$stmt = $db->prepare("DELETE FROM gantt_resources WHERE programme_id='$programme_id'");
			$stmt->execute();
		
		


			$working_days = '{"working_day_monday":1,"working_day_tuesday":1,"working_day_wednesday":1,"working_day_thursday":1,"working_day_friday":1,"working_day_saturday":0,"working_day_sunday":0}';

			// Working days
			$working_days = json_decode($working_days, true);
			$task_calendar_name = "Default Task calendar";
			$is_default_task_calendar = "1";


			$start_time = "07:00";
			$start_hour = "7";
			$start_minute = "0";
			$end_time = "17:00";
			$end_hour = "17";
			$end_minute = "0";
			$stmt = $db->prepare("INSERT INTO gantt_calendars(programme_id,name,is_default_task_calendar,start_time,end_time,start_hour,start_minute,end_hour,end_minute,working_day_monday,working_day_tuesday,working_day_wednesday,working_day_thursday,working_day_friday,working_day_saturday,working_day_sunday) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $task_calendar_name);
			$stmt->bindParam(3, $is_default_task_calendar);
			$stmt->bindParam(4, $start_time);
			$stmt->bindParam(5, $end_time);
			$stmt->bindParam(6, $start_hour);
			$stmt->bindParam(7, $start_minute);
			$stmt->bindParam(8, $end_hour);
			$stmt->bindParam(9, $end_minute);
			$stmt->bindParam(10, $working_days['working_day_monday']);
			$stmt->bindParam(11, $working_days['working_day_tuesday']);
			$stmt->bindParam(12, $working_days['working_day_wednesday']);
			$stmt->bindParam(13, $working_days['working_day_thursday']);
			$stmt->bindParam(14, $working_days['working_day_friday']);
			$stmt->bindParam(15, $working_days['working_day_saturday']);
			$stmt->bindParam(16, $working_days['working_day_sunday']);

			$stmt->execute();
			$calendar_id = $db->lastInsertId();

	// Create a default resource calendar
		$working_days = '{"working_day_monday":1,"working_day_tuesday":1,"working_day_wednesday":1,"working_day_thursday":1,"working_day_friday":1,"working_day_saturday":0,"working_day_sunday":0}';
			$working_days = json_decode($working_days, true);
			$calendar_name = "Default resource calendar";
		$type = "2";
			$is_default_resource_calendar = "1";
			$start_time = "07:00";
			$end_time = "17:00";
			$start_hour = "7";
			$start_minute = "0";
			$end_hour = "17";
			$end_minute = "0";
			$stmt = $db->prepare("INSERT INTO gantt_calendars(programme_id,name,is_default_resource_calendar,start_time,end_time,start_hour,start_minute,end_hour,end_minute,working_day_monday,working_day_tuesday,working_day_wednesday,working_day_thursday,working_day_friday,working_day_saturday,working_day_sunday,type) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $calendar_name);
			$stmt->bindParam(3, $is_default_resource_calendar);
			$stmt->bindParam(4, $start_time);
			$stmt->bindParam(5, $end_time);
			$stmt->bindParam(6, $start_hour);
			$stmt->bindParam(7, $start_minute);
			$stmt->bindParam(8, $end_hour);
			$stmt->bindParam(9, $end_minute);
			$stmt->bindParam(10, $working_days['working_day_monday']);
			$stmt->bindParam(11, $working_days['working_day_tuesday']);
			$stmt->bindParam(12, $working_days['working_day_wednesday']);
			$stmt->bindParam(13, $working_days['working_day_thursday']);
			$stmt->bindParam(14, $working_days['working_day_friday']);
			$stmt->bindParam(15, $working_days['working_day_saturday']);
			$stmt->bindParam(16, $working_days['working_day_sunday']);
		$stmt->bindParam(17, $type);
			$stmt->execute();
			$res_calendar_id = $db->lastInsertId();


			$payload = array("processed" => true);
			echo json_encode($payload);

			break;
			die();


			
			
			case "rollback_version_by_id":
			$stmt = $db->prepare("SELECT * FROM gantt_versions WHERE id= :id AND programme_id = '$programme_id'");
			$stmt->bindValue(':id', $_REQUEST['id']);
			$stmt->execute();
			$version = $stmt->fetch(PDO::FETCH_ASSOC);
			$created = $version['created'];
			
			$payload = array("processed" => true, "guid" => $version['guid']);
			echo json_encode($payload);
			die();
			$gantt_data = json_decode($version['gantt_data'], true);
			$tasks = $gantt_data['data'];
			$links = $gantt_data['links'];
			// 1. Mark existing tasks for deletion
			$sql = "UPDATE gantt_tasks SET pending_deletion='1' WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			var_dump($tasks);
			// 2. Insert new tasks
			foreach ($tasks as $task) {
				$task['pending_deletion'] = "0";
				unset($task['id']);
				$table = "gantt_tasks";
				$sql = 'INSERT INTO ' . $table . '(`' . implode('`,`', array_keys($task)) . '`) values (:' . implode(',:', array_keys($task)) . ');';
				$stmt = $db->prepare($sql);
				foreach ($task as $field => $value) {
					if ($value == "") {
						$value = NULL;
					}
					$params[":{$field}"] = $value;
				}
				$stmt->execute($params);
			}
			// 3. Clean up parent IDs by using GUIDs and make sure re-linked
			$assign_array = array();
			$stmt = $db->prepare("SELECT id,guid,parent,parent_guid FROM gantt_tasks WHERE programme_id = '$programme_id'");
			$stmt->execute();
			$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$tasks_copy = $tasks;
			foreach ($tasks as $task) {
				$task_id = $task['id'];
				if ($task['parent_guid'] != "") {
					$guid_parent = $task['parent_guid'];
					foreach ($tasks_copy as $task_copy) {
						if ($task_copy['guid'] == $guid_parent) {
							$parent_id_found = $task_copy['id'];
							$sql = "UPDATE gantt_tasks SET parent='$parent_id_found' WHERE id='$task_id' AND programme_id='$programme_id'";
							$stmt = $db->prepare($sql);
							$stmt->execute();
						}
					}
				}
			}
			$sql = "DELETE FROM gantt_tasks WHERE programme_id = '$programme_id' AND pending_deletion = '1'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$payload = array("processed" => true, "guid" => $version['guid']);
			echo json_encode($payload);
			break;
			die();
			
			
			
			
			

		case "rollback_version":
			$stmt = $db->prepare("SELECT * FROM gantt_versions WHERE guid= :version_id AND programme_id = '$programme_id'");
			$stmt->bindValue(':version_id', $_REQUEST['id']);
			$stmt->execute();
			$version = $stmt->fetch(PDO::FETCH_ASSOC);
			$created = $version['created'];
			
			$payload = array("processed" => true, "guid" => $version['guid']);
			echo json_encode($payload);
			die();
			$gantt_data = json_decode($version['gantt_data'], true);
			$tasks = $gantt_data['data'];
			$links = $gantt_data['links'];
			// 1. Mark existing tasks for deletion
			$sql = "UPDATE gantt_tasks SET pending_deletion='1' WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			var_dump($tasks);
			// 2. Insert new tasks
			foreach ($tasks as $task) {
				$task['pending_deletion'] = "0";
				unset($task['id']);
				$table = "gantt_tasks";
				$sql = 'INSERT INTO ' . $table . '(`' . implode('`,`', array_keys($task)) . '`) values (:' . implode(',:', array_keys($task)) . ');';
				$stmt = $db->prepare($sql);
				foreach ($task as $field => $value) {
					if ($value == "") {
						$value = NULL;
					}
					$params[":{$field}"] = $value;
				}
				$stmt->execute($params);
			}
			// 3. Clean up parent IDs by using GUIDs and make sure re-linked
			$assign_array = array();
			$stmt = $db->prepare("SELECT id,guid,parent,parent_guid FROM gantt_tasks WHERE programme_id = '$programme_id'");
			$stmt->execute();
			$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$tasks_copy = $tasks;
			foreach ($tasks as $task) {
				$task_id = $task['id'];
				if ($task['parent_guid'] != "") {
					$guid_parent = $task['parent_guid'];
					foreach ($tasks_copy as $task_copy) {
						if ($task_copy['guid'] == $guid_parent) {
							$parent_id_found = $task_copy['id'];
							$sql = "UPDATE gantt_tasks SET parent='$parent_id_found' WHERE id='$task_id' AND programme_id='$programme_id'";
							$stmt = $db->prepare($sql);
							$stmt->execute();
						}
					}
				}
			}
			$sql = "DELETE FROM gantt_tasks WHERE programme_id = '$programme_id' AND pending_deletion = '1'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$payload = array("processed" => true, "guid" => $version['guid']);
			echo json_encode($payload);
			break;
			die();


		case "set_internal_date_range":

			$start = $_REQUEST['start'];
			$end = $_REQUEST['end'];
			$user_id = $_SESSION['user']['id'];

			$stmt = $db->prepare("UPDATE gantt_user_programme_links SET date_range_start='$start',date_range_end='$end' WHERE programme_id='$programme_id' AND user_id='$user_id'");
			$stmt->execute();

			$payload = array("processed" => true);
			echo json_encode($payload);


			break;
			die();


		case "set_internal_date_default":

			$start = date('Y-m-d', strtotime('-30 days'));
			$end = date('Y-m-d', strtotime('+30 days'));

			$user_id = $_SESSION['user']['id'];

			$stmt = $db->prepare("UPDATE gantt_user_programme_links SET date_range_start='$start',date_range_end='$end' WHERE programme_id='$programme_id' AND user_id='$user_id'");
			$stmt->execute();

			$payload = array("processed" => true);
			echo json_encode($payload);


			break;
			die();

		case "override_task_timings":

			$task_id = $_REQUEST['id'];
			$stmt = $db->prepare("UPDATE gantt_tasks SET timing_overriden='1' WHERE programme_id='$programme_id' AND id='$task_id'");
			$stmt->execute();

			$payload = array("processed" => true);
			echo json_encode($payload);

			break;
			die();

		case "undo_override_task_timings":

			$task_id = $_REQUEST['id'];
			$stmt = $db->prepare("UPDATE gantt_tasks SET timing_overriden='0' WHERE programme_id='$programme_id' AND id='$task_id'");
			$stmt->execute();

			$payload = array("processed" => true);
			echo json_encode($payload);

			break;
			die();

		case "get_activities":

			$sql = "DELETE u1 FROM gantt_versions u1, gantt_versions u2 WHERE u1.id < u2.id AND u1.created = u2.created AND u1.programme_id = u2.programme_id AND u1.primary_object_guid = u2.primary_object_guid";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$sql = "DELETE u1 FROM gantt_versions u1, gantt_versions u2 WHERE u1.id < u2.id AND u1.primary_object_guid = u2.primary_object_guid AND u1.programme_id = u2.programme_id AND u1.aux_data = u2.aux_data";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$activities_array = array();

			$stmt = $db->prepare("SELECT t1.id,t1.guid,t1.active,t1.programme_id,t1.to_finalise,t1.aux_data,t1.user_id,t1.created,t1.action,t1.type,t1.description_2,t1.primary_object_guid,t1.secondary_object_guid, t1.ui_string, t2.first_name, t2.last_name, t2.avatar_url, t3.text AS primary_object_text FROM gantt_versions t1 LEFT JOIN users t2 on t2.id = t1.user_id LEFT JOIN gantt_tasks t3 on t3.guid = t1.primary_object_guid WHERE t1.programme_id = '$programme_id' AND t1.to_finalise='0' ORDER by t1.created DESC ");


			$stmt->execute();
			$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($activities as $activity) {
				$activity_id = $activity['id'];
				$stmt = $db->prepare("SELECT t1.*,t2.id,t2.first_name,t2.last_name FROM gantt_version_comments t1 LEFT JOIN users t2 ON t1.author_id = t2.id WHERE t1.version_id='$activity_id' ORDER BY t1.id ASC");
				$stmt->execute();
				$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$activity['comments'] = $comments;
				$activities_array[] = $activity;
			}

			$stmt = $db->prepare("SELECT * FROM gantt_programmes WHERE id='$programme_id'");
			$stmt->execute();
			$programme = $stmt->fetch(PDO::FETCH_ASSOC);

			$payload = array("activities" => $activities_array, "self_id" => $_SESSION['user']['id'], "version" => $programme['current_version_id']);
			echo json_encode($payload);
			break;
			die();



		case "get_files":

			$stmt = $db->prepare("SELECT t1.*,t2.first_name AS user_first_name,t2.last_name AS user_last_name FROM gantt_files t1 LEFT JOIN users t2 on t2.id = t1.uploaded_by WHERE t1.programme_id = '$programme_id' ORDER by t1.uploaded DESC ");

			$stmt->execute();
			$files = $stmt->fetchAll(PDO::FETCH_ASSOC);


			$payload = array("files" => $files, "self_id" => $_SESSION['user']['id']);
			echo json_encode($payload);
			break;
			die();



		case "get_file":


			$stmt = $db->prepare("SELECT t1.*,t2.first_name AS user_first_name,t2.last_name AS user_last_name FROM gantt_files t1 LEFT JOIN users t2 on t2.id = t1.uploaded_by WHERE t1.programme_id = '$programme_id' AND t1.id=:file_id ");
			$stmt->bindValue(':file_id', $_REQUEST['id']);
			$stmt->execute();
			$file = $stmt->fetch(PDO::FETCH_ASSOC);
			$payload = array("file" => $file, "self_id" => $_SESSION['user']['id']);
			echo json_encode($payload);
			break;
			die();




		case "snapshot_gantt":


			$created = time();

			//$data = json_decode($_POST['data'], true);
			$data = $_REQUEST['gantt_data'];
			$guid = generateGUID();
			$primary_object_guid = $_REQUEST['primary_guid'];
			$secondary_object_guid = $_REQUEST['secondary_guid'];
			$action_type = $_REQUEST['action_type'];
			$type = $_REQUEST['type'];
			$aux_data = $_REQUEST['info'];

			if ($type == "task") {
				// Get task name as of now
				$stmt = $db->prepare("SELECT text FROM gantt_tasks WHERE guid = :guid");
				$stmt->bindValue(':guid', $_REQUEST['primary_guid']);
				$stmt->execute();
				$task_ui = $stmt->fetch(PDO::FETCH_ASSOC);

				$to_finalise = "0";
				if ($action_type == "added") {
					$to_finalise = "1";

					$ui_string = $action_type . " " . $type . " <span>'" . $task_ui['text'] . "'<span>";
				}
				if ($action_type == "edited") {
					$to_finalise = "0";

					$ui_string = $action_type . " " . $type . " <span>'" . $task_ui['text'] . "'<span>";
				}
				if ($action_type == "deleted") {
					$to_finalise = "0";

					$ui_string = $action_type . " " . $type . " <span>'" . $task_ui['text'] . "'<span>";
				}
			}


			if ($type == "link") {
				$to_finalise = "0";
				// Get task name as of now
				$stmt = $db->prepare("SELECT text FROM gantt_tasks WHERE guid = :guid");
				$stmt->bindValue(':guid', $_REQUEST['primary_guid']);
				$stmt->execute();
				$task_ui_primary = $stmt->fetch(PDO::FETCH_ASSOC);

				$stmt = $db->prepare("SELECT text FROM gantt_tasks WHERE guid = :guid");
				$stmt->bindValue(':guid', $_REQUEST['secondary_guid']);
				$stmt->execute();
				$task_ui_secondary = $stmt->fetch(PDO::FETCH_ASSOC);

				$ui_string = $action_type . " a dependency between <span>'" . $task_ui_primary['text'] . "'<span> and <span>'" . $task_ui_secondary['text'] . "'<span>";
			}





			$stmt = $db->prepare("INSERT INTO gantt_versions(programme_id,gantt_data,user_id,created,action,type,primary_object_guid,secondary_object_guid,aux_data,guid,ui_string,to_finalise) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $data);
			$stmt->bindParam(3, $_SESSION['user']['id']);
			$stmt->bindParam(4, $created);
			$stmt->bindParam(5, $action_type);
			$stmt->bindParam(6, $type);
			$stmt->bindParam(7, $primary_object_guid);
			$stmt->bindParam(8, $secondary_object_guid);
			$stmt->bindParam(9, $aux_data);
			$stmt->bindParam(10, $guid);
			$stmt->bindParam(11, $ui_string);
			$stmt->bindParam(12, $to_finalise);
			$stmt->execute();
			$version_id = $db->lastInsertId();

			$author_id = $_SESSION['user']['id'];
			$sql = "UPDATE gantt_programmes SET current_version_id='$version_id',undo_redo_version_id='$version_id',last_save_time='$created',last_save_author_id='$author_id' WHERE id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();



			$payload = array("processed" => true, "version_id" => $version_id, "save_time" => $created);
			echo json_encode($payload);



			break;
			die();

		case "get_task_locks":


			$guid = $_REQUEST['guid'];

			$stmt = $db->prepare("SELECT id,first_name,last_name FROM users WHERE last_programme_id = '$programme_id' AND active_task_guid='$guid'");
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
			$locked = true;
			if ($stmt->rowCount() == 0) {
				$locked = false;
				// Lock it
				$guid = $_REQUEST['guid'];
				$user_id = $_SESSION['user']['id'];
				$time = time();
				$stmt = $db->prepare("UPDATE users SET active_task_guid='$guid',active_task_lock_time='$time' WHERE id = :id");
				$stmt->bindValue(':id', $_SESSION['user']['id']);
				$stmt->execute();
			}
			$payload = array("locked" => $locked, "user" => $user);
			echo json_encode($payload);


			break;
			die();



		case "set_task_lock":


			$guid = $_REQUEST['guid'];
			$user_id = $_SESSION['user']['id'];
			$time = time();
			$stmt = $db->prepare("UPDATE users SET active_task_guid='$guid',active_task_lock_time='$time' WHERE id = :id");
			$stmt->bindValue(':id', $_SESSION['user']['id']);
			$stmt->execute();


			break;
			die();


		case "release_task_locks":



			$stmt = $db->prepare("UPDATE users SET active_task_guid= NULL,active_task_lock_time= NULL WHERE id = :id");
			$stmt->bindValue(':id', $_SESSION['user']['id']);
			$stmt->execute();

			break;
			die();

		case "remove_task_file":

			$stmt = $db->prepare("SELECT id,files FROM gantt_tasks WHERE programme_id='$programme_id' AND guid= :guid");
			$stmt->bindValue(':guid', $_REQUEST['task_guid']);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$task_id = $task['id'];

			$files = json_decode($task['files']);

			$fields = array_flip($files);
			unset($fields[$_REQUEST['file_id']]);
			$fields = array_flip($fields);


			$encoded_files = json_encode($fields);
			$stmt = $db->prepare("UPDATE gantt_tasks SET files='$encoded_files' WHERE id='$task_id' AND programme_id='$programme_id'");
			$stmt->execute();

			// Get files
			$files_array = array();
			foreach ($fields as $file) {
				$stmt = $db->prepare("SELECT * FROM gantt_files WHERE programme_id='$programme_id' AND id= :id");
				$stmt->bindValue(':id', $file);
				$stmt->execute();
				$file_object = $stmt->fetch(PDO::FETCH_ASSOC);
				array_push($files_array, $file_object);
			}
			$payload = array("processed" => true, "task_id" => $task_id, "files" => $files_array);
			echo json_encode($payload);

			break;
			die();



		case "unlink_file_from_task":

			$file = $_REQUEST['file_id'];

			$stmt = $db->prepare("SELECT id,files FROM gantt_tasks WHERE programme_id='$programme_id' AND id= :id");
			$stmt->bindValue(':id', $_REQUEST['task_id']);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$task_id = $task['id'];

			$files = json_decode($task['files']);

			$fields = array_flip($files);
			unset($fields[$_REQUEST['file_id']]);
			$fields = array_flip($fields);


			$encoded_files = json_encode($fields);
			$stmt = $db->prepare("UPDATE gantt_tasks SET files='$encoded_files' WHERE id='$task_id' AND programme_id='$programme_id'");
			$stmt->execute();

			// Get files
			$files_array = array();
			foreach ($fields as $file) {
				$stmt = $db->prepare("SELECT * FROM gantt_files WHERE programme_id='$programme_id' AND id= :id");
				$stmt->bindValue(':id', $file);
				$stmt->execute();
				$file_object = $stmt->fetch(PDO::FETCH_ASSOC);
				array_push($files_array, $file_object);
			}
			$payload = array("processed" => true);
			echo json_encode($payload);






			break;
			die();


		case "delete_file":

			$stmt = $db->prepare("DELETE FROM gantt_files WHERE programme_id='$programme_id' AND id= :id");
			$stmt->bindValue(':id', $_REQUEST['file_id']);
			$stmt->execute();

			// Remove from all tasks
			$file_id = $_REQUEST['file_id'];

			$stmt = $db->prepare("SELECT id,files FROM gantt_tasks WHERE programme_id='$programme_id'");
			$stmt->execute();
			$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($tasks as $task) {
				$task_id = $task['id'];

				$files = json_decode($task['files']);

				if (in_array($file_id, $files)) {

					$fields = array_flip($files);
					unset($fields[$_REQUEST['file_id']]);
					$fields = array_flip($fields);


					$encoded_files = json_encode($fields);
					$stmt = $db->prepare("UPDATE gantt_tasks SET files='$encoded_files' WHERE id='$task_id' AND programme_id='$programme_id'");
					$stmt->execute();
				}
			}
			$payload = array("processed" => true);
			echo json_encode($payload);


			break;
			die();

		case "link_file_to_task":

			$file_id = $_REQUEST['file_id'];

			$stmt = $db->prepare("SELECT id,files FROM gantt_tasks WHERE programme_id='$programme_id' AND id= :id");
			$stmt->bindValue(':id', $_REQUEST['task_id']);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$task_id = $task['id'];

			$files = json_decode($task['files']);
			if ($files == NULL) {
				$files = array();
				array_push($files, $file_id);
				$encoded_files = json_encode($files);
			} else {
				array_push($files, $file_id);
			}

			$encoded_files = json_encode($files);


			$stmt = $db->prepare("UPDATE gantt_tasks SET files='$encoded_files' WHERE id='$task_id' AND programme_id='$programme_id'");
			$stmt->execute();

			$payload = array("processed" => true);
			echo json_encode($payload);

			break;
			die();



		case "add_version_comment":

			$version_id = $_REQUEST['id'];
			$comment_text = $_REQUEST['text'];
			$author_id = $_SESSION['user']['id'];
			$created = time();
			$stmt = $db->prepare("INSERT INTO gantt_version_comments(programme_id,version_id,author_id,`text`,created) VALUES (?,?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $version_id);
			$stmt->bindParam(3, $author_id);
			$stmt->bindParam(4, $comment_text);
			$stmt->bindParam(5, $created);
			$stmt->execute();
			$payload = array("processed" => true);
			echo json_encode($payload);
			break;
			die();




		case "set_profile_image":
			$filename_existing = $_FILES['file']['name'];
			$extension = pathinfo($filename_existing, PATHINFO_EXTENSION);
			$filename = hash('sha256', time() + md5(rand(0, 999999999999))) . "." . $extension;
			move_uploaded_file($_FILES['file']['tmp_name'], 'files/profile-images/' . $filename);
			$url = "https://beta.ibex.software/mmb-basic/files/profile-images/" . $filename;
			$user_id = $_SESSION['user']['id'];
			$stmt = $db->prepare("UPDATE users SET avatar_url='$url' WHERE id= '$user_id'");
			$stmt->execute();
			$payload = array("processed" => true, "avatar_url" => $url);
			echo json_encode($payload);
			break;
			die();



		case "set_background_image":
			$filename_existing = $_FILES['file']['name'];
			$extension = pathinfo($filename_existing, PATHINFO_EXTENSION);
			$filename = hash('sha256', time() + md5(rand(0, 999999999999))) . "." . $extension;
			move_uploaded_file($_FILES['file']['tmp_name'], 'files/backgrounds/' . $filename);
			$url = "https://beta.ibex.software/mmb-basic/files/backgrounds/" . $filename;
			$user_id = $_SESSION['user']['id'];
			$stmt = $db->prepare("UPDATE users SET background_url='$url' WHERE id= '$user_id'");
			$stmt->execute();
			$payload = array("processed" => true, "background_url" => $url);
			echo json_encode($payload);
			break;
			die();

		case "set_background_opacity":
			$background_opacity = $_REQUEST['background_opacity'];
			$user_id = $_SESSION['user']['id'];
			$stmt = $db->prepare("UPDATE users SET background_opacity='$background_opacity' WHERE id= '$user_id'");
			$stmt->execute();
			$payload = array("processed" => true);
			echo json_encode($payload);
			break;
			die();

		case "set_opacity_font":
			$opacity_font = $_REQUEST['opacity_font'];
			$user_id = $_SESSION['user']['id'];
			$stmt = $db->prepare("UPDATE users SET opacity_font='$opacity_font' WHERE id= '$user_id'");
			$stmt->execute();
			$payload = array("processed" => true);
			echo json_encode($payload);
			break;
			die();

		case "set_resource_image":

			// We might not have this resource yet - it may be being added, add a blank ref if not


			$filename_existing = $_FILES['file']['name'];
			$extension = pathinfo($filename_existing, PATHINFO_EXTENSION);
			$filename = hash('sha256', time() + md5(rand(0, 999999999999))) . "." . $extension;
			move_uploaded_file($_FILES['file']['tmp_name'], 'files/resources/' . $filename);
			$url = "https://beta.ibex.software/mmb-basic/files/resources/" . $filename;
			$resource_id = $_REQUEST['resource_guid'];
			$guid = $_REQUEST['guid'];

			// Exist?
			$sql = "SELECT id FROM gantt_resources WHERE guid='$guid' AND programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			if ($stmt->rowCount() == 0) {
				// No - insert placeholder
				$created = time();
				$stmt = $db->prepare("INSERT INTO gantt_resources(programme_id,guid,created,resource_image_url) VALUES (?,?,?,?)");
				$stmt->bindParam(1, $programme_id);
				$stmt->bindParam(2, $_REQUEST['guid']);
				$stmt->bindParam(3, $created);
				$stmt->bindParam(4, $url);
				$stmt->execute();
				$resource_id = $db->lastInsertId();
			} else {
				$resource = $stmt->fetch(PDO::FETCH_ASSOC);
				$resource_id = $resource['id'];

				// Yes - update
				$stmt = $db->prepare("UPDATE gantt_resources SET resource_image_url='$url' WHERE id='$resource_id' AND programme_id='$programme_id'");
				$stmt->execute();
			}


			$payload = array("processed" => true, "resource_image_url" => $url, "resource_id" => $resource_id);
			echo json_encode($payload);
			break;
			die();




		case "get_activity_broadcasts":
			$sql = "SELECT t1.*,t2.first_name,t2.last_name FROM gantt_broadcasts t1 LEFT JOIN users t2 ON t2.id = t1.author_id WHERE t1.programme_id='$programme_id' ORDER BY t1.created DESC";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$broadcasts = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$payload = array("processed" => true, "broadcasts" => $broadcasts);
			echo json_encode($payload);
			break;
			die();



		case "add_file_to_task":
			$filename_existing = $_FILES['file']['name'];
			$extension = pathinfo($filename_existing, PATHINFO_EXTENSION);
			$filename = hash('sha256', time() + md5(rand(0, 999999999999))) . "." . $extension;
			$test = move_uploaded_file($_FILES['file']['tmp_name'], 'files/' . $filename);
			$url = "https://beta.ibex.software/mmb-basic/files/" . $filename;
			$created = time();
			$stmt = $db->prepare("INSERT INTO gantt_files(programme_id,name,hashed_name,extension,uploaded,url,uploaded_by) VALUES (?,?,?,?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $filename_existing);
			$stmt->bindParam(3, $filename);
			$stmt->bindParam(4, $extension);
			$stmt->bindParam(5, $created);
			$stmt->bindParam(6, $url);
			$stmt->bindParam(7, $_SESSION['user']['id']);
			$stmt->execute();
			$file_id = $db->lastInsertId();

			$stmt = $db->prepare("SELECT id,files FROM gantt_tasks WHERE programme_id='$programme_id' AND guid= :guid");
			$stmt->bindValue(':guid', $_REQUEST['task_guid']);
			$stmt->execute();
			$check_task = $stmt->rowCount();
			if ($check_task == 0) {
				// No such file yet - we need to add this to pending table - bt first check no such previous pendings!

				// Any existing items to be inserted?
				$stmt = $db->prepare("SELECT id,encoded_files FROM gantt_files_pending_insert WHERE programme_id='$programme_id' AND task_guid= :guid");
				$stmt->bindValue(':guid', $_REQUEST['task_guid']);
				$stmt->execute();
				$check_insert = $stmt->rowCount();
				if ($check_insert == 0) {

					$files = array();
					array_push($files, $file_id);
					$encoded_files = json_encode($files);


					// No existing files to be inserted, just insert this one
					$processed = "0";
					$stmt = $db->prepare("INSERT INTO gantt_files_pending_insert(programme_id,task_guid,encoded_files,processed) VALUES (?,?,?,?)");
					$stmt->bindParam(1, $programme_id);
					$stmt->bindParam(2, $_REQUEST['task_guid']);
					$stmt->bindParam(3, $encoded_files);
					$stmt->bindParam(4, $processed);
					$stmt->execute();
				} else {
					// already files to insert, lengthen array
					$file = $stmt->fetch(PDO::FETCH_ASSOC);


					$file_id_local = $file['id'];
					$files = json_decode($file['encoded_files']);

					array_push($files, $file_id);

					$encoded_files = json_encode($files);

					$stmt = $db->prepare("UPDATE gantt_files_pending_insert SET encoded_files='$encoded_files' WHERE id='$file_id_local'");
					$stmt->execute();
				}
			} else {

				$task = $stmt->fetch(PDO::FETCH_ASSOC);
				$task_id = $task['id'];


				$files = json_decode($task['files']);
				if ($files == NULL) {
					$files = array();
					array_push($files, $file_id);
					$encoded_files = json_encode($files);
				} else {
					array_push($files, $file_id);
				}

				$encoded_files = json_encode($files);


				$stmt = $db->prepare("UPDATE gantt_tasks SET files='$encoded_files' WHERE id='$task_id' AND programme_id='$programme_id'");
				$stmt->execute();
			}


			// Get files
			$files_array = array();
			foreach ($files as $file) {

				$stmt = $db->prepare("SELECT * FROM gantt_files WHERE programme_id='$programme_id' AND id= :id");
				$stmt->bindValue(':id', $file);
				$stmt->execute();
				$file_object = $stmt->fetch(PDO::FETCH_ASSOC);
				array_push($files_array, $file_object);
			}


			$payload = array("processed" => "true", "files" => $files_array, "encoded_files" => $encoded_files);
			echo json_encode($payload);


			break;
			die();

		case "add_file_to_programme":

			$filename_existing = $_FILES['file']['name'];

			$extension = pathinfo($filename_existing, PATHINFO_EXTENSION);
			$filename = hash('sha256', time() + md5(rand(0, 999999999999))) . "." . $extension;
			move_uploaded_file($_FILES['file']['tmp_name'], 'files/' . $filename);

			$url = "https://beta.ibex.software/mmb-basic/files/" . $filename;

			$created = time();

			$stmt = $db->prepare("INSERT INTO gantt_files(programme_id,name,hashed_name,extension,uploaded,url,uploaded_by) VALUES (?,?,?,?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $filename_existing);
			$stmt->bindParam(3, $filename);
			$stmt->bindParam(4, $extension);
			$stmt->bindParam(5, $created);
			$stmt->bindParam(6, $url);
			$stmt->bindParam(7, $_SESSION['user']['id']);
			$stmt->execute();
			$file_id = $db->lastInsertId();

			$stmt = $db->prepare("SELECT t1.*,t2.first_name AS user_first_name,t2.last_name AS user_last_name FROM gantt_files t1 LEFT JOIN users t2 on t2.id = t1.uploaded_by WHERE t1.programme_id = '$programme_id' ORDER by t1.uploaded DESC ");

			$stmt->execute();
			$files = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$payload = array("processed" => true, "files" => $files, "self_id" => $_SESSION['user']['id']);

			echo json_encode($payload);

			break;
			die();

		case "load_gantt":
			$programme_id = $_SESSION['gantt_id'];
			$task_id = 0;
			// Resources
			$sql = "SELECT * FROM gantt_resources WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resources = $stmt->fetchAll(PDO::FETCH_ASSOC) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : null;

			$sql = "SELECT * FROM gantt_resource_groups WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resource_groups = $stmt->fetchAll(PDO::FETCH_ASSOC) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : null;

			//Added by RB
			$sql = "SELECT * FROM gantt_tasks WHERE id= '$task_id' AND programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resource_cost = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$sql = "SELECT * FROM gantt_tasks WHERE id= '$task_id' AND programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$links = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($links as $link) {
				$resource_id = $link['resource_id'];
				$sql = "SELECT * FROM gantt_resources WHERE id='$resource_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$resource = $stmt->fetch(PDO::FETCH_ASSOC);
				$task_object['resources'][] = $resource;
			}

			$sql = "SELECT * FROM gantt_calendars WHERE programme_id='$programme_id' ORDER BY name ASC";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$calendars = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$sql = "SELECT * FROM gantt_calendar_overrides WHERE programme_id='$programme_id' ORDER BY start_date ASC";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$calendar_overrides = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$sql = "SELECT * FROM gantt_config WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch(PDO::FETCH_ASSOC);

			$sql = "SELECT * FROM gantt_settings WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$settings = $stmt->fetch(PDO::FETCH_ASSOC);

			$sql = "SELECT * FROM gantt_files WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$files = $stmt->fetchAll(PDO::FETCH_ASSOC);


			$user_id = $_SESSION['user']['id'];
			$sql = "SELECT * FROM gantt_columns WHERE programme_id='$programme_id' AND user_id='$user_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$columns = $stmt->fetch(PDO::FETCH_ASSOC);

			$user_id = $_SESSION['user']['id'];
			$sql = "SELECT user_group_id AS id FROM gantt_user_groups_links WHERE user_id='$user_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$user_groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$user_id = $_SESSION['user']['id'];
			$sql = "SELECT * FROM gantt_user_groups WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

			// Get summary tasks
			$summary_tasks_array = array();
			$sql = "SELECT id FROM gantt_tasks WHERE programme_id='$programme_id' AND is_summary = '1'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$summary_tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$start_date = date('Y-m-d', strtotime('-30 days'));
			$end_date = date('Y-m-d', strtotime('-30 days'));

			$count = 0;
			
			foreach ($summary_tasks as $summary_task) {
				$summary_task_id = $summary_task['id'];
				$sql = "SELECT * FROM gantt_tasks WHERE parent='$summary_task_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($tasks as $task) {
					if ($count == 0) {
						$start_date = strtotime($task['start_date']);
						$end_date = strtotime($task['end_date']);
					} else {
						echo json_encode("123123");
						if (strtotime($task['start_date']) < $start_date) {
							$start_date = strtotime($task['start_date']);
						}
						if (strtotime($task['end_date']) > $end_date) {
							$end_date = strtotime($task['end_date']);
						}
					}
				}

				$summary_task['start_date'] = $start_date;
				$summary_task['end_date'] = $end_date;
				// $summary_tasks_array[] = array("id" => $summary_task_id, "start_date" => date("Y-m-d H:i:s", $start_date), "end_date" => date("Y-m-d H:i:s", $end_date));
				$summary_tasks_array[] = array("id" => $summary_task_id, "start_date" => $start_date, "end_date" => $end_date);
			}

			$payload = array("groups" => $groups, "user_groups" => $user_groups, "resources" => $resources, "resource_groups" => $resource_groups, "resource_cost" => $resource_cost,  "calendars" => $calendars, "calendar_overrides" => $calendar_overrides, "settings" => $settings, "columns" => $columns, "summary_tasks" => $summary_tasks_array, "files" => $files);
			echo json_encode($payload);

			break;
			die();

		case "add_group_permission_to_task":
			$task_id = $_REQUEST['task_id'];
			$group_id = $_REQUEST['group_id'];

			$groups = array();

			$sql = "SELECT custom_permission_groups FROM gantt_tasks WHERE id='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$groups_current = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($groups_current['custom_permission_groups'] == NULL) {

				array_push($groups, $group_id);
			}

			$groups_updated = json_encode($groups);

			$sql = "UPDATE gantt_tasks SET custom_permission_groups='$groups_updated' WHERE id='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			break;
			die();


		case "add_resource_group":


			$group_id = $_REQUEST['id'];

			if ($group_id == "0") {
				$group_name = $_REQUEST['name'];
				$contains_human_resources = "0";
				if ($_REQUEST['contains_human_resources'] == true) {
					$contains_human_resources = "1";
				}
				$contains_consumable_resources = "0";
				if ($_REQUEST['contains_consumable_resources'] == true) {
					$contains_consumable_resources = "1";
				}
				$outputs_unit = $_REQUEST['unit'];
				$stmt = $db->prepare("INSERT INTO gantt_resource_groups(programme_id,name,contains_human_resources,contains_consumable_resources,outputs_unit,min_output_value,max_output_value,calendar_id,period,period_minutes,output_per_minute) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
				$stmt->bindParam(1, $programme_id);
				$stmt->bindParam(2, $group_name);
				$stmt->bindParam(3, $contains_human_resources);
				$stmt->bindParam(4, $contains_consumable_resources);
				$stmt->bindParam(5, $outputs_unit);
				$stmt->bindParam(6, $_REQUEST['min_output']);
				$stmt->bindParam(7, $_REQUEST['max_output']);
				$stmt->bindParam(8, $_REQUEST['calendar_id']);
				$stmt->bindParam(9, $_REQUEST['period']);
				$stmt->bindParam(10, $_REQUEST['period_minutes']);
				$stmt->bindParam(11, $_REQUEST['output_per_minute']);
				$stmt->execute();
			} else {

				$group_name = $_REQUEST['name'];
				$contains_human_resources = "0";
				if ($_REQUEST['contains_human_resources'] == "true") {
					$contains_human_resources = "1";
				}
				$contains_consumable_resources = "0";
				if ($_REQUEST['contains_consumable_resources'] == "true") {
					$contains_consumable_resources = "1";
				}
				$outputs_unit = $_REQUEST['unit'];
				$period = $_REQUEST['period'];
				$calendar_id = $_REQUEST['calendar_id'];
				$min_output = $_REQUEST['min_output'];
				$max_output = $_REQUEST['max_output'];
				$period_minutes = $_REQUEST['period_minutes'];
				$output_per_minute = $_REQUEST['output_per_minute'];

				$sql = "UPDATE gantt_resource_groups SET name='$group_name',contains_human_resources='contains_human_resources', contains_consumable_resources = '$contains_consumable_resources',outputs_unit='$outputs_unit',period='$period',calendar_id='$calendar_id',min_output_value='$min_output',max_output_value='$max_output',period_minutes='$period_minutes',output_per_minute='$output_per_minute' WHERE id='$group_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
			}


			$sql = "SELECT * FROM gantt_resource_groups WHERE programme_id='$programme_id' ORDER BY name ASC";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resource_groups = $stmt->fetchAll(PDO::FETCH_ASSOC);


			$payload = array("processed" => true, "resource_groups" => $resource_groups);
			echo json_encode($payload);



			break;
			die();

		case "dismiss_broadcasts":

			$_SESSION['user']['last_login'] = time();

			$payload = array("processed" => true);
			echo json_encode($payload);
			break;
			die();

		case "send_broadcast_message":

			$time = time();
			$stmt = $db->prepare("INSERT INTO gantt_broadcasts(author_id,message,created,programme_id) VALUES (?,?,?,?)");
			$stmt->bindParam(1, $_SESSION['user']['id']);
			$stmt->bindParam(2, $_REQUEST['message']);
			$stmt->bindParam(3, $time);
			$stmt->bindParam(4, $programme_id);
			$stmt->execute();




			$sql = "SELECT t1.*,t2.first_name,t2.last_name FROM gantt_broadcasts t1 LEFT JOIN users t2 ON t2.id = t1.author_id WHERE t1.programme_id='$programme_id' ORDER BY t1.created DESC";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$broadcasts = $stmt->fetchAll(PDO::FETCH_ASSOC);


			$payload = array("processed" => true, "broadcasts" => $broadcasts);
			echo json_encode($payload);


			break;
			die();





		case "get_activity_broadcasts":
			$sql = "SELECT t1.*,t2.first_name,t2.last_name FROM gantt_broadcasts t1 LEFT JOIN users t2 ON t2.id = t1.author_id WHERE t1.programme_id='$programme_id' ORDER BY t1.created DESC";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$broadcasts = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$payload = array("processed" => true, "broadcasts" => $broadcasts);
			echo json_encode($payload);
			break;
			die();


		case "update_task_workload_quantity":

			$id = $_REQUEST['id'];
			$quantity = $_REQUEST['value'];
			$sql = "UPDATE gantt_workload_links SET quantity='$quantity' WHERE id='$id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();


			$payload = array("processed" => true);
			echo json_encode($payload);



			break;
			die();

		case "delete_resource_group":

			$group_id = $_REQUEST['id'];
			$sql = "DELETE FROM gantt_resource_groups WHERE programme_id='$programme_id' and id='$group_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$sql = "SELECT * FROM gantt_resource_groups WHERE programme_id='$programme_id' ORDER BY name ASC";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resource_groups = $stmt->fetchAll(PDO::FETCH_ASSOC);


			$payload = array("processed" => true, "resource_groups" => $resource_groups);
			echo json_encode($payload);




			break;
			die();


		case "update_settings":


			if ($_REQUEST['aspect'] == "new_task") {
				$value = $_REQUEST['value'];
				$sql = "UPDATE gantt_settings SET task_insertion_mode='$value' WHERE programme_id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
			}


			if ($_REQUEST['aspect'] == "timing_unit") {
				$value = $_REQUEST['value'];
				$sql = "UPDATE gantt_settings SET timing_unit='$value' WHERE programme_id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
			}


			if ($_REQUEST['aspect'] == "period_descriptor") {
				$value = $_REQUEST['value'];
				$singular = $_REQUEST['singular'];
				$plural = $_REQUEST['plural'];
				$sql = "UPDATE gantt_settings SET period_descriptor='$value',period_descriptor_text_singular='$singular',period_descriptor_text_plural='$plural' WHERE programme_id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
			}

			if ($_REQUEST['aspect'] == "automatic_scheduling") {
				$value = $_REQUEST['value'];

				$sql = "UPDATE gantt_settings SET automatic_scheduling_enabled='$value' WHERE programme_id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
			}


			$payload = array("processed" => true);
			echo json_encode($payload);

			break;
			die();



		case "update_group_permissions":

			$group_id = $_REQUEST['id'];
			$groups = json_decode($_REQUEST['data'], true);
			$sql_update = "UPDATE gantt_user_groups SET ";

			foreach ($groups as $key => $value) {
				$sql_update .= $key . "=" . $value . ", ";
			}
			$sql_update = rtrim($sql_update, ', ');
			$sql_update .= " WHERE programme_id='$programme_id' AND id='$group_id'";


			$stmt = $db->prepare($sql_update);
			$stmt->execute();


			$payload = array("processed" => true);
			echo json_encode($payload);

			break;
			die();






		case "update_permission_groups":

			$task_id = $_REQUEST['task_id'];
			$groups = $_REQUEST['data'];



			$sql = "UPDATE gantt_tasks SET custom_permission_groups='$groups' WHERE id='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();


			$payload = array("processed" => true);
			echo json_encode($payload);

			break;
			die();





		case "update_default_permission_sets":

			// Get admin group and add this to start

			$sql = "SELECT id FROM gantt_user_groups WHERE programme_id='$programme_id' AND is_admin_group='1'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$group = $stmt->fetch(PDO::FETCH_ASSOC);
			$user_group_id = $group['id'];
			$permission_sets = '{"group_' . $user_group_id . '_set_1":true,"group_' . $user_group_id . '_set_2":true,"group_' . $user_group_id . '_set_3":true,"group_' . $user_group_id . '_set_4":true,"group_' . $user_group_id . '_set_5":true,"group_' . $user_group_id . '_set_6":true,"group_' . $user_group_id . '_set_7":true,';
			$sets = $_REQUEST['data'];
			$sets = $permission_sets . substr($sets, 1);

			$sql = "UPDATE gantt_settings SET default_permission_sets='$sets' WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$payload = array("processed" => true);
			echo json_encode($payload);

			break;
			die();





		case "update_default_task_editor_permission_sets":

			// Get admin group and add this to start

			$sql = "SELECT id FROM gantt_user_groups WHERE programme_id='$programme_id' AND is_admin_group='1'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$group = $stmt->fetch(PDO::FETCH_ASSOC);
			$user_group_id = $group['id'];
			$task_editor_permission_sets = '{"group_' . $user_group_id . '_set_1":true,"group_' . $user_group_id . '_set_2":true,"group_' . $user_group_id . '_set_3":true,"group_' . $user_group_id . '_set_4":true,"group_' . $user_group_id . '_set_5":true,"group_' . $user_group_id . '_set_6":true,"group_' . $user_group_id . '_set_7":true,' . $user_group_id . '_set_8":true,';
			$sets = $_REQUEST['data'];
			$sets = $task_editor_permission_sets . substr($sets, 1);

			$sql = "UPDATE gantt_settings SET default_task_editor_permission_sets='$sets' WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$payload = array("processed" => true);
			echo json_encode($payload);

			break;
			die();








		case "update_default_permission_groups":

			$groups = $_REQUEST['data'];

			$sql = "UPDATE gantt_settings SET default_permission_groups='$groups' WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$payload = array("processed" => true);
			echo json_encode($payload);

			break;
			die();

		case "reload_gantt":
			$programme_id = $_SESSION['gantt_id'];

			// Resources
			$sql = "SELECT * FROM gantt_resources WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$sql = "SELECT * FROM gantt_calendars WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$calendars = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$sql = "SELECT * FROM gantt_config WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$settings = $stmt->fetchAll(PDO::FETCH_ASSOC);



			$user_id = $_SESSION['user']['id'];
			$sql = "SELECT * FROM gantt_columns WHERE programme_id='$programme_id' AND user_id='$user_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$columns = $stmt->fetch(PDO::FETCH_ASSOC);


			$payload = array("resources" => $resources,  "calendars" => $calendars, "settings" => $settings, "columns" => $columns);
			echo json_encode($payload);
			break;



		case "send_message":



			$time = time();
			$unread = "1";
			$stmt = $db->prepare("INSERT INTO gantt_messages(programme_id,sender_id,recipient_id,created,unread,`text`) VALUES (?,?,?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $_SESSION['user']['id']);
			$stmt->bindParam(3, $_REQUEST['recipient_id']);
			$stmt->bindParam(4, $time);
			$stmt->bindParam(5, $unread);
			$stmt->bindParam(6, $_REQUEST['message']);
			$stmt->execute();


			$message_array = array();
			$my_id = $_SESSION['user']['id'];
			$recipient_id = $_REQUEST['recipient_id'];
			$sql = "SELECT * FROM gantt_messages WHERE programme_id='$programme_id' AND (sender_id='$my_id' AND recipient_id='$recipient_id' OR sender_id='$recipient_id' AND recipient_id='$my_id') ORDER BY created DESC";

			$stmt = $db->prepare($sql);
			$stmt->execute();
			$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($messages as $message) {
				$message_sender = $message['sender_id'];
				if ($message_sender == $_SESSION['user']['id']) {
					$message['sender_name'] = "You";
				} else {

					$sql = "SELECT first_name,last_name FROM users WHERE id='$message_sender'";

					$stmt = $db->prepare($sql);
					$stmt->execute();
					$user = $stmt->fetch(PDO::FETCH_ASSOC);
					$message['sender_name'] = $user['first_name'];
				}

				$message_array[] = $message;
			}

			// Mark any unread messages
			$sql = "UPDATE gantt_messages SET unread='0' WHERE recipient_id='$my_id' AND sender_id='$recipient_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$payload = array("thread" => $message_array, "self_id" => $_SESSION['user']['id'], "contact_name" => $user['first_name'] . " " . $user['last_name']);
			echo json_encode($payload);
			break;
			die();


		case "get_thread":
			$message_array = array();
			$my_id = $_SESSION['user']['id'];
			$recipient_id = $_REQUEST['recipient_id'];
			$sql = "SELECT first_name,last_name FROM users WHERE id='$recipient_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$user2 = $stmt->fetch(PDO::FETCH_ASSOC);
			$sql = "SELECT * FROM gantt_messages WHERE programme_id='$programme_id' AND (sender_id='$my_id' AND recipient_id='$recipient_id' OR sender_id='$recipient_id' AND recipient_id='$my_id') ORDER BY created DESC";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($messages as $message) {
				$message_sender = $message['sender_id'];
				if ($message_sender == $_SESSION['user']['id']) {
					$message['sender_name'] = "You";
				} else {
					if ($message_sender == "0") {
						$message['sender_name'] = "Ibex Support";
						$message['contact_name'] = "Ibex Support";
						$counterparty_url = "https://beta.ibex.software/img/logo.png";
					} else {
						$sql = "SELECT first_name,last_name,avatar_url FROM users WHERE id='$message_sender'";
						$stmt = $db->prepare($sql);
						$stmt->execute();
						$user = $stmt->fetch(PDO::FETCH_ASSOC);
						$counterparty_url = $user['avatar_url'];
						$message['sender_name'] = $user['first_name'];
						$message['contact_name'] = $user['first_name'] . " " . $user['last_name'];
					}
				}
				$message_array[] = $message;
			}
			// Mark any unread messages
			$sql = "UPDATE gantt_messages SET unread='0' WHERE recipient_id='$my_id' AND sender_id='$recipient_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$payload = array("thread" => $message_array, "self_id" => $_SESSION['user']['id'], "contact_name" => $user2['first_name'] . " " . $user2['last_name'], "counterparty_image_url" => $counterparty_url);
			echo json_encode($payload);
			break;
			die();


		case "get_message_states":
			$contacts = array();
			$sql = "SELECT t1.*,t2.first_name,t2.last_name,t2.avatar_url FROM gantt_user_programme_links t1 LEFT JOIN users t2 on t2.id = t1.user_id WHERE t1.programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$links = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$support_user = array("id" => "0", "user_id" => "0", "programme_id" => $programme_id, "first_name" => "Ibex-pph", "last_name" => "Support", "avatar_url" => 'https://beta.ibex.software/img/logo.png');
			array_push($links, $support_user);
			foreach ($links as $link) {
				// Get unread message count from this sender
				$contact_id = $link['user_id'];
				$my_id = $_SESSION['user']['id'];
				$sql = "SELECT id FROM gantt_messages WHERE programme_id='$programme_id' AND sender_id='$contact_id' AND recipient_id='$my_id' AND unread='1'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$unread_count = $stmt->rowCount();
				if ($contact_id != $_SESSION['user']['id']) {
					$local_object = array("contact_id" => $contact_id, "contact_name" => $link['first_name'] . " " . $link['last_name'], "unread_messages" => $unread_count, "avatar_url" => $link['avatar_url']);
					$contacts[] = $local_object;
				}
			}
			$payload = array("contacts" => $contacts);
			echo json_encode($payload);
			break;
			die();


















		case "get_ibex_support_messages":
			$ibex_support_contact = array();
			$contact_id = "0";
			$my_id = $_SESSION['user']['id'];
			$sql = "SELECT id FROM gantt_messages WHERE programme_id='$programme_id' AND sender_id='$contact_id' AND recipient_id='$my_id' AND unread='1'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($messages as $message) {
				$message_sender = $message['sender_id'];
				$sql = "SELECT first_name FROM users WHERE id='$message_sender'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_ASSOC);
				$message['sender_name'] = $user['first_name'];
				$message_array[] = $message;
			}
			$unread_count = $stmt->rowCount();
			$unread_count = $stmt->rowCount();
			if ($contact_id != $_SESSION['user']['id']) {
				$local_object1 = array("contact_id" => $contact_id, "contact_name" => "Ibex Support", "unread_messages" => $unread_count);
				$ibex_support_contact[] = $local_object1;
			}
			$payload = array("ibex_support_contact" => $ibex_support_contact);
			echo json_encode($payload);
			break;
			die();



















		case "get_accounts":


			if ($_REQUEST['token'] == "DJ16VFUN18F8YGQ5T!1UF8718Y7FT!") {
				//$stmt = $db->prepare("SELECT t1.user_id, t1.permission_type, t2.id, t2.first_name, t2.last_name, t2.email_address, t2.user_group_id, t2.account_setup FROM gantt_user_programme_links t1 LEFT JOIN gantt_users t2 on t2.id = t1.user_id 


				$sql = "SELECT t1.*,t2.id,t2.first_name,t2.last_name,t2.email_address,t2.last_programme_id FROM accounts t1 LEFT JOIN users t2 ON t2.id = t1.administrator_id ORDER BY t1.name ASC";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			$payload = array("accounts" => $accounts);
			echo json_encode($payload);



			break;
			die();




		case "update_profile":
			$first_name = $_REQUEST['first_name'];
			$last_name = $_REQUEST['last_name'];
			$telephone_number = $_REQUEST['telephone_number'];
			$user_id = $_SESSION['user']['id'];
			$sql = "UPDATE users SET first_name='$first_name',last_name='$last_name',telephone_number='$telephone_number',account_setup='1' WHERE id='$user_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$sql = "SELECT * FROM users WHERE id='$user_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
			unset($user['hash']);
			$_SESSION['user'] = $user;
			$payload = array("processed" => true);
			echo json_encode($payload);
			break;
			die();

		case "update_account":
			$account_first_name = $_REQUEST['account_first_name'];
			$sql = "INSERT INTO user_account_links (account_first_name) VALUES ('$account_first_name') WHERE id='$account_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$account_id = $programme['account_id'];
			$payload = array("processed" => true);
			echo json_encode($payload);
			break;
			die();














		case "update_pricing_detail":
			$id = $_REQUEST['id'];
			if ($id == "0") // NEW PRICING ITEM
			{
				$pricing_section = $_REQUEST['pricing_section'];
				$pricing_reference = $_REQUEST['pricing_reference'];
				$pricing_description = $_REQUEST['pricing_description'];
				$pricing_quantity = $_REQUEST['pricing_quantity'];
				$pricing_unit = $_REQUEST['pricing_unit'];
				$pricing_rate = $_REQUEST['pricing_rate'];
				$pricing_sum = $_REQUEST['pricing_sum'];
				$stmt = $db->prepare("INSERT INTO gantt_pricing(programme_id,section,ref,description,quantity,unit,rate,sum) VALUES (?,?,?,?,?,?,?,?)");
				$stmt->bindParam(1, $programme_id);
				$stmt->bindParam(2, $pricing_section);
				$stmt->bindParam(3, $pricing_reference);
				$stmt->bindParam(4, $pricing_description);
				$stmt->bindParam(5, $pricing_quantity);
				$stmt->bindParam(6, $pricing_unit);
				$stmt->bindParam(7, $pricing_rate);
				$stmt->bindParam(8, $pricing_sum);
				$stmt->execute();

				$select_SOR_item = $_REQUEST['select_SOR_item'];
				$stmt = $db->prepare("INSERT INTO gantt_SOR(programme_id,sor_item,unit,rate) VALUES (?,?,?,?)");
				$stmt->bindParam(1, $programme_id);
				$stmt->bindParam(2, $select_SOR_item);
				$stmt->bindParam(3, $pricing_unit);
				$stmt->bindParam(4, $pricing_rate);
				$stmt->execute();
			} else {

				// EXISTING PRCING ITEM
				$pricing_section = $_REQUEST['pricing_section'];
				$pricing_reference = $_REQUEST['pricing_reference'];
				$pricing_description = $_REQUEST['pricing_description'];
				$pricing_quantity = $_REQUEST['pricing_quantity'];
				$pricing_unit = $_REQUEST['pricing_unit'];
				$pricing_rate = $_REQUEST['pricing_rate'];
				$pricing_sum = $_REQUEST['pricing_sum'];
				$sql = "UPDATE gantt_pricing SET section='$pricing_section',ref='$pricing_reference',description='$pricing_description',quantity='$pricing_quantity',unit='$pricing_unit',rate='$pricing_rate',sum='$pricing_sum' WHERE id='$id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();

				// Now send back all items to allow table to be repopulated on UI
				$sql = "SELECT * FROM gantt_pricing WHERE programme_id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$items = $stmt->fetchAll(PDO::FETCH_ASSOC);


				$select_SOR_item = $_REQUEST['select_SOR_item'];
				$sql = "UPDATE gantt_SOR SET sor_item='$select_SOR_item',unit='$pricing_unit',rate='$pricing_rate' WHERE id='$id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();

				$sql = "SELECT * FROM gantt_SOR WHERE programme_id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}

			$payload = array("pricing_items" => $items);
			echo json_encode($payload);
			break;
			die();


		case "get_pricing_detail":
			$id = $_REQUEST['id'];
			$sql = "SELECT * FROM gantt_pricing WHERE id = '$id' AND programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			/*
		$SORitem = $_REQUEST['SORitem'];
		$sql= "SELECT * FROM gantt_SOR WHERE id = '$id' AND programme_id='$programme_id'";
		$stmt = $db->prepare($sql);
		$stmt->execute();
		*/
			$pricing_item = $stmt->fetch(PDO::FETCH_ASSOC);
			$payload = array("pricing_item" => $pricing_item);
			echo json_encode($payload);
			break;
			die();


		case "get_pricing_items":

			// Get all
			$sql = "SELECT * FROM gantt_pricing WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$items = $stmt->fetchAll(PDO::FETCH_ASSOC);


			$payload = array("pricing_items" => $items);
			echo json_encode($payload);
			break;
			die();



		case "update_task_price":
			$id = $_REQUEST['id'];
			$total_price = $_REQUEST['total_price'];
			$sql = "UPDATE gantt_tasks SET price='$total_price' WHERE id='$id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$payload = array("pricing_items" => $items);
			echo json_encode($payload);
			break;
			die();


























		case "update_SOR_detail":
			$id = $_REQUEST['id'];
			if ($id == "0") // NEW SOR ITEM
			{
				$SOR_name = $_REQUEST['SOR_name'];
				$SOR_people = $_REQUEST['SOR_people'];
				$SOR_equipment = $_REQUEST['SOR_equipment'];
				$SOR_plant = $_REQUEST['SOR_plant'];
				$SOR_materials = $_REQUEST['SOR_materials'];
				$SOR_unit = $_REQUEST['SOR_unit'];
				$SOR_rate = $_REQUEST['SOR_rate'];
				$stmt = $db->prepare("INSERT INTO gantt_SOR(programme_id,name,people,equipment,plant,materials,unit,rate) VALUES (?,?,?,?,?,?,?,?)");
				$stmt->bindParam(1, $programme_id);
				$stmt->bindParam(2, $SOR_name);
				$stmt->bindParam(3, $SOR_people);
				$stmt->bindParam(4, $SOR_equipment);
				$stmt->bindParam(5, $SOR_plant);
				$stmt->bindParam(6, $SOR_materials);
				$stmt->bindParam(7, $SOR_unit);
				$stmt->bindParam(8, $SOR_rate);
				$stmt->execute();
			} else {

				// EXISTING PRCING ITEM
				$sql = "UPDATE gantt_SOR SET name='$SOR_name',people='$SOR_people',equipment='$SOR_equipment',plant='$SOR_plant',materials='$SOR_materials',unit='$SOR_unit',rate='$SOR_rate' WHERE id='$id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();



				// Now send back all items to allow table to be repopulated on UI
				$sql = "SELECT * FROM gantt_SOR WHERE programme_id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}

			$payload = array("SOR_items" => $items);
			echo json_encode($payload);
			break;
			die();


			// SOR ITEMS TABLE
		case "get_SOR_detail":
			$id = $_REQUEST['id'];
			$sql = "SELECT * FROM gantt_SOR WHERE id = '$id' AND programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$SOR_item = $stmt->fetch(PDO::FETCH_ASSOC);
			$payload = array("SOR_item" => $SOR_item);
			echo json_encode($payload);
			break;
			die();


		case "get_SOR_items":

			// Get all
			$sql = "SELECT * FROM gantt_SOR WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$items = $stmt->fetchAll(PDO::FETCH_ASSOC);


			$payload = array("SOR_items" => $items);
			echo json_encode($payload);
			break;
			die();






























































		case "redo_version":

			$sql = "SELECT * FROM gantt_programmes WHERE id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$programme = $stmt->fetch(PDO::FETCH_ASSOC);
			$current_version = $programme['undo_redo_version_id'];

			if ($programme['undo_redo_version_id'] == NULL) {
				$payload = array("processed" => false);
				echo json_encode($payload);
			} else {

				$sql = "SELECT * FROM gantt_versions WHERE programme_id='$programme_id' AND id > '$current_version' ORDER BY id ASC LIMIT 1";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$version = $stmt->fetch(PDO::FETCH_ASSOC);
				$id = $version['id'];



				$stmt = $db->prepare("UPDATE gantt_programmes SET undo_redo_version_id='$id' WHERE id='$programme_id'");
				$stmt->execute();

				$stmt = $db->prepare("UPDATE gantt_versions SET active='0' WHERE programme_id='$programme_id' AND id > '$id'");
				$stmt->bindValue(':version_id', $_REQUEST['version']);
				$stmt->execute();

				$stmt = $db->prepare("UPDATE gantt_versions SET active='1' WHERE programme_id='$programme_id' AND id <= '$id'");
				$stmt->bindValue(':version_id', $_REQUEST['version']);
				$stmt->execute();

				// Delete existing
				$stmt = $db->prepare("DELETE FROM gantt_tasks WHERE programme_id='$programme_id'");
				$stmt->execute();

				$stmt = $db->prepare("DELETE FROM gantt_links WHERE programme_id='$programme_id'");
				$stmt->execute();

				// Reconstruct from version
				$data = json_decode($version['gantt_data'], true);

				$tasks = $data['data'];
				$links = $data['links'];




				// Insert tasks
				foreach ($tasks as $task) {
					if (isset($task['is_summary'])) {
						unset($task['id']);
						$table = "gantt_tasks";
						$sql = 'INSERT INTO ' . $table . '(`' . implode('`,`', array_keys($task)) . '`) values (:' . implode(',:', array_keys($task)) . ');';
						$stmt = $db->prepare($sql);
						foreach ($task as $field => $value) {
							$params[":{$field}"] = $value;
						}
						$stmt->execute($params);
					}
				}

				$sql = "";
				$params = "";
				// Insert links
				foreach ($links as $link) {
					unset($link['id']);

					$table = "gantt_links";
					$sql = 'INSERT INTO ' . $table . '(`' . implode('`,`', array_keys($link)) . '`) values (:' . implode(',:', array_keys($link)) . ');';
					$stmt = $db->prepare($sql);
					foreach ($link as $field => $value) {
						$params[":{$field}"] = $value;
					}


					$stmt->execute($params);
				}

				// Re-link
				$stmt = $db->prepare("SELECT id,guid,parent,parent_guid FROM gantt_tasks WHERE programme_id = '$programme_id'");
				$stmt->execute();
				$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$tasks_copy = $tasks;

				foreach ($tasks as $task) {
					$task_id = $task['id'];
					if ($task['parent'] != "0") {
						$parent_guid = $task['parent_guid'];

						foreach ($tasks_copy as $task_copy) {
							if ($task_copy['guid'] == $parent_guid) {
								$parent_found = $task_copy['id'];
								$sql = "UPDATE gantt_tasks SET parent='$parent_found' WHERE id='$task_id' AND programme_id='$programme_id'";
								$stmt = $db->prepare($sql);
								$stmt->execute();
							}
						}
					}
				}

				$stmt = $db->prepare("SELECT id,guid FROM gantt_tasks WHERE programme_id = '$programme_id'");
				$stmt->execute();
				$tasks_new = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$tasks_new_copy = $tasks_new;
				$order = 0;
				foreach ($tasks_new_copy as $task_new_copy) {
					$task_id = $task_new_copy['id'];
					$sql = "UPDATE gantt_tasks SET order_ui='$order' WHERE id='$task_id' AND programme_id='$programme_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$order++;
				}


				$stmt = $db->prepare("SELECT * FROM gantt_links WHERE programme_id = '$programme_id'");
				$stmt->execute();
				$links = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($links as $link) {

					$link_id = $link['id'];

					$source_id = 0;
					$target_id = 0;

					$source_guid = $link['source_guid'];
					$target_guid = $link['target_guid'];
					foreach ($tasks_new as $task_new) {
						if ($task_new['guid'] == $source_guid) {
							$source_id = $task_new['id'];
						}
						if ($task_new['guid'] == $target_guid) {
							$target_id = $task_new['id'];
						}
					}

					$sql = "UPDATE gantt_links SET source='$source_id',target='$target_id' WHERE id='$link_id' AND programme_id='$programme_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
				}

				$payload = array("processed" => true);
				echo json_encode($payload);
			}






			break;
			die();




		case "undo_version":

			$sql = "SELECT * FROM gantt_programmes WHERE id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$programme = $stmt->fetch(PDO::FETCH_ASSOC);
			$current_version = $programme['undo_redo_version_id'];


			if ($programme['undo_redo_version_id'] == NULL) {

				// Get last version
				$sql = "SELECT * FROM gantt_versions WHERE programme_id='$programme_id' ORDER BY id DESC LIMIT 1";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$version = $stmt->fetch(PDO::FETCH_ASSOC);
				$id = $version['id'];
			} else {

				$sql = "SELECT * FROM gantt_versions WHERE programme_id='$programme_id' AND id < '$current_version' ORDER BY id DESC LIMIT 1";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$version = $stmt->fetch(PDO::FETCH_ASSOC);
				$id = $version['id'];
			}


			$stmt = $db->prepare("UPDATE gantt_programmes SET undo_redo_version_id='$id' WHERE id='$programme_id'");
			$stmt->execute();

			$stmt = $db->prepare("UPDATE gantt_versions SET active='0' WHERE programme_id='$programme_id' AND id > '$id'");
			$stmt->bindValue(':version_id', $_REQUEST['version']);
			$stmt->execute();

			$stmt = $db->prepare("UPDATE gantt_versions SET active='1' WHERE programme_id='$programme_id' AND id <= '$id'");
			$stmt->bindValue(':version_id', $_REQUEST['version']);
			$stmt->execute();

			// Delete existing
			$stmt = $db->prepare("DELETE FROM gantt_tasks WHERE programme_id='$programme_id'");
			$stmt->execute();

			$stmt = $db->prepare("DELETE FROM gantt_links WHERE programme_id='$programme_id'");
			$stmt->execute();

			// Reconstruct from version
			$data = json_decode($version['gantt_data'], true);

			$tasks = $data['data'];
			$links = $data['links'];




			// Insert tasks
			foreach ($tasks as $task) {
				if (isset($task['is_summary'])) {
					unset($task['id']);
					$table = "gantt_tasks";
					$sql = 'INSERT INTO ' . $table . '(`' . implode('`,`', array_keys($task)) . '`) values (:' . implode(',:', array_keys($task)) . ');';
					$stmt = $db->prepare($sql);
					foreach ($task as $field => $value) {
						$params[":{$field}"] = $value;
					}
					$stmt->execute($params);
				}
			}

			$sql = "";
			$params = "";
			// Insert links
			foreach ($links as $link) {
				unset($link['id']);

				$table = "gantt_links";
				$sql = 'INSERT INTO ' . $table . '(`' . implode('`,`', array_keys($link)) . '`) values (:' . implode(',:', array_keys($link)) . ');';
				$stmt = $db->prepare($sql);
				foreach ($link as $field => $value) {
					$params[":{$field}"] = $value;
				}


				$stmt->execute($params);
			}

			// Re-link
			$stmt = $db->prepare("SELECT id,guid,parent,parent_guid FROM gantt_tasks WHERE programme_id = '$programme_id'");
			$stmt->execute();
			$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$tasks_copy = $tasks;

			foreach ($tasks as $task) {
				$task_id = $task['id'];
				if ($task['parent'] != "0") {
					$parent_guid = $task['parent_guid'];

					foreach ($tasks_copy as $task_copy) {
						if ($task_copy['guid'] == $parent_guid) {
							$parent_found = $task_copy['id'];
							$sql = "UPDATE gantt_tasks SET parent='$parent_found' WHERE id='$task_id' AND programme_id='$programme_id'";
							$stmt = $db->prepare($sql);
							$stmt->execute();
						}
					}
				}
			}

			$stmt = $db->prepare("SELECT id,guid FROM gantt_tasks WHERE programme_id = '$programme_id'");
			$stmt->execute();
			$tasks_new = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$tasks_new_copy = $tasks_new;
			$order = 0;
			foreach ($tasks_new_copy as $task_new_copy) {
				$task_id = $task_new_copy['id'];
				$sql = "UPDATE gantt_tasks SET order_ui='$order' WHERE id='$task_id' AND programme_id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$order++;
			}


			$stmt = $db->prepare("SELECT * FROM gantt_links WHERE programme_id = '$programme_id'");
			$stmt->execute();
			$links = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($links as $link) {

				$link_id = $link['id'];

				$source_id = 0;
				$target_id = 0;

				$source_guid = $link['source_guid'];
				$target_guid = $link['target_guid'];
				foreach ($tasks_new as $task_new) {
					if ($task_new['guid'] == $source_guid) {
						$source_id = $task_new['id'];
					}
					if ($task_new['guid'] == $target_guid) {
						$target_id = $task_new['id'];
					}
				}

				$sql = "UPDATE gantt_links SET source='$source_id',target='$target_id' WHERE id='$link_id' AND programme_id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
			}

			$payload = array("processed" => true);
			echo json_encode($payload);




			break;
			die();




		case "add_team_batch":


			$group_name = "Team members";
			$admin_group = "0";
			$stmt = $db->prepare("INSERT INTO gantt_user_groups(programme_id,name,is_admin_group) VALUES (?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $group_name);
			$stmt->bindParam(3, $admin_group);
			$stmt->execute();
			$user_group_id = $db->lastInsertId();


			$stmt = $db->prepare("SELECT default_permission_sets FROM gantt_settings WHERE programme_id='$programme_id'");
			$stmt->execute();

			$settings = $stmt->fetch(PDO::FETCH_ASSOC);
			$sets = $settings['default_permission_sets'];


			$sets2 = substr($sets, 0, -1);



			// Set permissions
			$permission_sets = ',"group_' . $user_group_id . '_set_1":true,"group_' . $user_group_id . '_set_2":true,"group_' . $user_group_id . '_set_3":false,"group_' . $user_group_id . '_set_4":false,"group_' . $user_group_id . '_set_5":false,"group_' . $user_group_id . '_set_6":false,"group_' . $user_group_id . '_set_7":false}';





			$sets = $sets2 . $permission_sets;



			$sql = "UPDATE gantt_settings SET default_permission_sets='$sets' WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();




			// Get current groups
			$stmt = $db->prepare("SELECT * FROM gantt_settings WHERE programme_id='$programme_id'");
			$stmt->execute();
			$settings = $stmt->fetch(PDO::FETCH_ASSOC);
			$pgroups = json_decode($settings['default_permission_groups'], true);


			$pgroups[$user_group_id] = false;


			$new_groups = json_encode($pgroups);
			$stmt = $db->prepare("UPDATE gantt_settings SET default_permission_groups='$new_groups' WHERE programme_id='$programme_id'");
			$stmt->execute();







			// Now add any team
			$team_members = $_REQUEST['team'];
			$team_members = json_decode($team_members, true);
			foreach ($team_members as $team_member) {


				// Check if they exist
				$stmt = $db->prepare("SELECT id FROM users WHERE email_address='$team_member'");
				$stmt->execute();
				if ($stmt->rowCount() > 0) {

					$user = $stmt->fetch(PDO::FETCH_ASSOC);
					// Exist - just link
					$permission_type = "2";
					$stmt = $db->prepare("INSERT INTO gantt_user_programme_links(user_id,programme_id,permission_type) VALUES (?,?,?)");
					$stmt->bindParam(1, $user['id']);
					$stmt->bindParam(2, $programme_id);
					$stmt->bindParam(3, $permission_type);

					$stmt->execute();

					// Exist - just link to group
					$stmt = $db->prepare("INSERT INTO gantt_user_groups_links(user_id,user_group_id) VALUES (?,?)");
					$stmt->bindParam(1, $user['id']);
					$stmt->bindParam(2, $user_group_id);
					$stmt->execute();

					// Set up columns on this prog
					$stmt = $db->prepare("INSERT INTO gantt_columns(programme_id,user_id) VALUES (?,?)");
					$stmt->bindParam(1, $programme_id);
					$stmt->bindParam(2, $user['id']);

					$stmt->execute();
				} else {


			
			
			
			
					// Set up columns on this prog
					$stmt = $db->prepare("INSERT INTO gantt_columns(programme_id,user_id) VALUES (?,?)");
					$stmt->bindParam(1, $programme_id);
					$stmt->bindParam(2, $user_id);

					$stmt->execute();


					// Exist - just link to group
					$stmt = $db->prepare("INSERT INTO gantt_user_groups_links(user_id,user_group_id) VALUES (?,?)");
					$stmt->bindParam(1, $user_id);
					$stmt->bindParam(2, $user_group_id);
					$stmt->execute();


					$permission_type = "2";
					// Exist - just link
					$permission_type = "2";
					$stmt = $db->prepare("INSERT INTO gantt_user_programme_links(user_id,programme_id,permission_type) VALUES (?,?,?)");
					$stmt->bindParam(1, $user_id);
					$stmt->bindParam(2, $programme_id);
					$stmt->bindParam(3, $permission_type);

					$stmt->execute();


					// Get subdomain
					$stmt = $db->prepare("SELECT subdomain FROM accounts WHERE id='$account_id'");
					$stmt->execute();
					$account = $stmt->fetch(PDO::FETCH_ASSOC);
					$subdomain = $account['subdomain'];


					// Send email to user
			$code = rand(1000, 9999);
			$link = "beta.ibex.software/mmb-basic/auth-email.php?email_address=" . $_REQUEST['email_address'] . "&code="
	. $code;
	$time = time();
			
			$stmt = $db->prepare("INSERT INTO users(first_name,last_name,email_address,invite_code,created) VALUES (?,?,?,?,?)");
			$stmt->bindParam(1, $_REQUEST['first_name']);
			$stmt->bindParam(2, $_REQUEST['last_name']);
			$stmt->bindParam(3, $_REQUEST['email_address']);
			$stmt->bindParam(4, $code);
			$stmt->bindParam(5, $time);
			$stmt->execute();
			
			// Email admin
			$config = array();
			$config['api_key'] = "key-86929a614db601844f2de754bf641f80";
			$config['api_url'] = "https://api.mailgun.net/v3/ibex.software/messages";
						
			$message = array();
			$message['from'] = "Ibex <support@ibex.software>";
			$message['to'] = $_REQUEST['email_address'];
			$message['subject'] = "Welcome to Ibex";
							
			$content = file_get_contents('email-templates/auth_link_signup.html');
			$content = str_replace("{{AUTH_URL}}", $link, $content);
				
			$message['html'] = $content;
						
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $config['api_url']);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "api:{$config['api_key']}");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_POST, true); 
			curl_setopt($ch, CURLOPT_POSTFIELDS,$message);
						
			$result = curl_exec($ch);
			curl_close($ch);
				}
			}

			$payload = array("processed" => true);
			echo json_encode($payload);

			break;


		case "add_team_member":


			$email_address = $_REQUEST['email_address'];
			$user_group_id = $_REQUEST['group_id'];


			// Check if they exist
			$stmt = $db->prepare("SELECT id FROM users WHERE email_address='$email_address'");
			$stmt->execute();
			if ($stmt->rowCount() > 0) {
				$user = $stmt->fetch(PDO::FETCH_ASSOC);
				$user_id = $user['id'];
				// Exist - just link
				$permission_type = "2";
				$stmt = $db->prepare("INSERT INTO gantt_user_programme_links(user_id,programme_id,permission_type) VALUES (?,?,?)");
				$stmt->bindParam(1, $user['id']);
				$stmt->bindParam(2, $programme_id);
				$stmt->bindParam(3, $permission_type);
				$stmt->execute();



				// Link user to group
				$stmt = $db->prepare("INSERT INTO gantt_user_groups_links(user_id,user_group_id) VALUES (?,?)");
				$stmt->bindParam(1, $user_id);
				$stmt->bindParam(2, $user_group_id);
				$stmt->execute();







				// Set up columns on this prog

				$stmt = $db->prepare("INSERT INTO gantt_columns(programme_id,user_id) VALUES (?,?)");
				$stmt->bindParam(1, $programme_id);
				$stmt->bindParam(2, $user['id']);

				$stmt->execute();



				$members = array();
				// Get all users in prog
				$stmt = $db->prepare("SELECT t1.user_id, t1.permission_type, t2.id, t2.first_name, t2.last_name, t2.email_address, t2.state FROM gantt_user_programme_links t1 LEFT JOIN users t2 on t2.id = t1.user_id 
		WHERE t1.programme_id='$programme_id'");
				$stmt->execute();
				$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

				foreach ($users as $user) {

					$user_id = $user['id'];


					$sql = "SELECT * FROM gantt_user_groups_links WHERE user_id='$user_id' AND user_group_id='$user_group_id'";

					$stmt = $db->prepare($sql);
					$stmt->execute();
					if ($stmt->rowCount() > 0) {
						$user['within_group'] = "true";
					} else {
						$user['within_group'] = "false";
					}



					if ($user['state'] == "1") {
						$user['name'] = $user['first_name'] . " " . $user['last_name'];
					} else {
						$user['name'] = $user['email_address'];
					}

					$members[] = $user;
				}

				$payload = array("members" => $members);
				echo json_encode($payload);
			} else {
				// Invite
				// Insert new user into db
				$invite_token = hash('sha512', $email_address . time() . mt_rand(10000000000, 999999999999));
				$type = "1";

				$user_guid = generateGUID();
				$created = time();
				$invite_code = rand(1000, 9999);

				// Insert new user 
				$stmt = $db->prepare("INSERT INTO users(email_address,invite_code,created,last_programme_id) VALUES (?,?,?,?)");
				$stmt->bindParam(1, $email_address);
				$stmt->bindParam(2, $invite_code);
				$stmt->bindParam(3, $created);
				$stmt->bindParam(4, $programme_id);
				$stmt->execute();
				$user_id = $db->lastInsertId();



				// Link user to group
				$stmt = $db->prepare("INSERT INTO gantt_user_groups_links(user_id,user_group_id) VALUES (?,?)");
				$stmt->bindParam(1, $user_id);
				$stmt->bindParam(2, $user_group_id);
				$stmt->execute();



				// Set up columns on this prog
				$stmt = $db->prepare("INSERT INTO gantt_columns(programme_id,user_id) VALUES (?,?)");
				$stmt->bindParam(1, $programme_id);
				$stmt->bindParam(2, $user_id);

				$stmt->execute();


				$permission_type = "2";
				// Exist - just link
				$permission_type = "2";
				$stmt = $db->prepare("INSERT INTO gantt_user_programme_links(user_id,programme_id,permission_type) VALUES (?,?,?)");
				$stmt->bindParam(1, $user_id);
				$stmt->bindParam(2, $programme_id);
				$stmt->bindParam(3, $permission_type);

				$stmt->execute();


				// Get subdomain
				$stmt = $db->prepare("SELECT subdomain FROM accounts WHERE id='$account_id'");
				$stmt->execute();
				$account = $stmt->fetch(PDO::FETCH_ASSOC);
				$subdomain = $account['subdomain'];


				$auth_url = "https://beta.ibex.software/auth.php?email_address=" . $_REQUEST['email_address'] . "&code=" . $invite_code;
				// Email admin
				$config = array();
				$config['api_key'] = "key-86929a614db601844f2de754bf641f80";
				$config['api_url'] = "https://api.mailgun.net/v3/ibex.software/messages";

				$message = array();
				$message['from'] = "Ibex <support@ibex.software>";
				$message['to'] = $_REQUEST['email_address'];
				$message['subject'] = "Welcome to Ibex";

				$content = file_get_contents('../email-templates/auth_link.html');
				$content = str_replace("{{FIRST_NAME}}", $_REQUEST['first_name'], $content);
				$content = str_replace("{{AUTH_URL}}", $auth_url, $content);

				$message['html'] = $content;

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $config['api_url']);
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($ch, CURLOPT_USERPWD, "api:{$config['api_key']}");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $message);

				$result = curl_exec($ch);
				var_dump($result);
				curl_close($ch);


				$members = array();
				// Get all users in prog
				$stmt = $db->prepare("SELECT t1.user_id, t1.permission_type, t2.id, t2.first_name, t2.last_name, t2.email_address, t2.state FROM gantt_user_programme_links t1 LEFT JOIN users t2 on t2.id = t1.user_id 
		WHERE t1.programme_id='$programme_id'");
				$stmt->execute();
				$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

				foreach ($users as $user) {

					$user_id = $user['id'];


					$sql = "SELECT * FROM gantt_user_groups_links WHERE user_id='$user_id' AND user_group_id='$user_group_id'";

					$stmt = $db->prepare($sql);
					$stmt->execute();
					if ($stmt->rowCount() > 0) {
						$user['within_group'] = "true";
					} else {
						$user['within_group'] = "false";
					}



					if ($user['state'] == "1") {
						$user['name'] = $user['first_name'] . " " . $user['last_name'];
					} else {
						$user['name'] = $user['email_address'];
					}

					$members[] = $user;
				}

				$payload = array("members" => $members);
				echo json_encode($payload);
			}


			break;
			die();


		case "delete_group":



			$group_id = $_REQUEST['id'];


			$stmt = $db->prepare("SELECT * FROM gantt_user_groups WHERE id='$group_id'");
			$stmt->execute();
			$group = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($group['is_admin_group'] == "0") {



				$stmt = $db->prepare("DELETE FROM gantt_user_groups WHERE id='$group_id'");

				$stmt->execute();
			}
			// Get user groups linked to this programme
			$stmt = $db->prepare("SELECT * FROM gantt_user_groups WHERE programme_id='$programme_id' ORDER BY name ASC ");
			$stmt->execute();
			$user_groups = $stmt->fetchAll(PDO::FETCH_ASSOC);


			$payload = array("user_groups" => $user_groups);
			echo json_encode($payload);

			break;
			die();



		case "add_user_group":

			error_reporting(E_ALL);
			ini_set('display_errors', 1);
			$stmt = $db->prepare("INSERT INTO gantt_user_groups(programme_id,name) VALUES (?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $_REQUEST['name']);
			$stmt->execute();
			$new_group_id = $db->lastInsertId();

			// Get current groups
			$stmt = $db->prepare("SELECT * FROM gantt_settings WHERE programme_id='$programme_id'");
			$stmt->execute();
			$settings = $stmt->fetch(PDO::FETCH_ASSOC);
			$pgroups = json_decode($settings['default_permission_groups'], true);


			$pgroups[$new_group_id] = false;


			$new_groups = json_encode($pgroups);
			$stmt = $db->prepare("UPDATE gantt_settings SET default_permission_groups='$new_groups' WHERE programme_id='$programme_id'");
			$stmt->execute();



			///
			$sets1 = $settings['default_permission_sets'];
			$sets1a = rtrim($sets1, "}");
			$permission_sets_new = ',"group_' . $new_group_id . '_set_1":true,"group_' . $new_group_id . '_set_2":true,"group_' . $new_group_id . '_set_3":false,"group_' . $new_group_id . '_set_4":false,"group_' . $new_group_id . '_set_5":false,"group_' . $new_group_id . '_set_6":false,"group_' . $new_group_id . '_set_7":false}';




			$sets2 = $sets1a . $permission_sets_new;



			$sql = "UPDATE gantt_settings SET default_permission_sets='$sets2' WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			///






			// Get user groups linked to this programme
			$stmt = $db->prepare("SELECT * FROM gantt_user_groups WHERE programme_id='$programme_id' ORDER BY name ASC ");
			$stmt->execute();
			$user_groups = $stmt->fetchAll(PDO::FETCH_ASSOC);


			$payload = array("user_groups" => $user_groups);
			echo json_encode($payload);


			break;
			die();


		case "toggle_team_member_inclusion":

			error_reporting(E_ALL);
			ini_set('display_errors', 1);
			$group_id = $_REQUEST['group_id'];
			$user_id = $_REQUEST['id'];

			// Already assigned, unassign
			$sql = "SELECT id FROM gantt_user_groups_links WHERE user_id='$user_id' AND user_group_id='$group_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			if ($stmt->rowCount() == 0) {
				// Link user to group
				$stmt = $db->prepare("INSERT INTO gantt_user_groups_links(user_id,user_group_id) VALUES (?,?)");
				$stmt->bindParam(1, $user_id);
				$stmt->bindParam(2, $group_id);
				$stmt->execute();
			} else {
				// Check we're not deleting self from admin group!
				$sql = "SELECT is_admin_group FROM gantt_user_groups WHERE id='$group_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$group = $stmt->fetch(PDO::FETCH_ASSOC);
				$admin_check = $group['is_admin_group'];


				if ($user_id == $_SESSION['user']['id'] && $admin_check == "1") {
				} else {


					// Link user to group
					$stmt = $db->prepare("DELETE FROM gantt_user_groups_links WHERE user_id='$user_id' AND user_group_id='$group_id'");
					$stmt->execute();
				}
			}




			$members = array();
			// Get all users in prog
			$stmt = $db->prepare("SELECT t1.user_id, t1.permission_type, t2.id, t2.first_name, t2.last_name, t2.email_address, t2.state FROM gantt_user_programme_links t1 LEFT JOIN users t2 on t2.id = t1.user_id 
		WHERE t1.programme_id='$programme_id'");
			$stmt->execute();
			$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($users as $user) {
				$user_id = $user['id'];


				$sql = "SELECT * FROM gantt_user_groups_links WHERE user_id='$user_id' AND user_group_id='$group_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				if ($stmt->rowCount() > 0) {
					$user['within_group'] = "true";
				} else {
					$user['within_group'] = "false";
				}



				if ($user['state'] == "2") {
					$user['name'] = $user['first_name'] . " " . $user['last_name'];
				} else {
					$user['name'] = $user['email_address'];
				}

				$members[] = $user;
			}

			$payload = array("members" => $members);
			echo json_encode($payload);



			break;
			die();

		case "view_group_members":

			error_reporting(E_ALL);
			ini_set('display_errors', 1);
			$group_id = $_REQUEST['id'];
			$members = array();
			// Get all users in prog
			$stmt = $db->prepare("SELECT t1.user_id, t1.permission_type, t2.id, t2.first_name, t2.last_name, t2.email_address, t2.state FROM gantt_user_programme_links t1 LEFT JOIN users t2 on t2.id = t1.user_id WHERE t1.programme_id='$programme_id'");
			$stmt->execute();
			$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($users as $user) {
				$user_id = $user['id'];


				$sql = "SELECT * FROM gantt_user_groups_links WHERE user_id='$user_id' AND user_group_id='$group_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				if ($stmt->rowCount() > 0) {
					$user['within_group'] = "true";
				} else {
					$user['within_group'] = "false";
				}




				if ($user['state'] == "2") {
					$user['name'] = $user['first_name'] . " " . $user['last_name'];
				} else {
					$user['name'] = $user['email_address'];
				}

				$members[] = $user;
			}

			$payload = array("members" => $members);
			echo json_encode($payload);






			break;
			die();


		case "get_template_data":

			$template_id = $_REQUEST['id'];

			$sql = "SELECT * FROM gantt_templates WHERE id='$template_id' AND account_id='$account_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$template = $stmt->fetch(PDO::FETCH_ASSOC);
			$gantt_data = $template['gantt_data'];


			echo $gantt_data;



			break;
			die();

		case "add_task_calendar_override":


			$programme_id = $_REQUEST['programme_id'];
			$calendar_id = $_REQUEST['calendar_id'];
			$start_date = $_REQUEST['start_date'];
			$end_date = $_REQUEST['end_date'];

			$stmt = $db->prepare("INSERT INTO gantt_calendar_overrides(programme_id,calendar_id,start_date,end_date) VALUES (?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $calendar_id);
			$stmt->bindParam(3, $start_date);
			$stmt->bindParam(4, $end_date);
			$stmt->execute();

			$stmt = $db->prepare("SELECT * FROM gantt_calendar_overrides WHERE programme_id='$programme_id' AND calendar_id='$calendar_id' ORDER BY start_date ASC");
			$stmt->execute();
			$overrides = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$payload = array("created" => true, "calendar_overrides" => $overrides);
			echo json_encode($payload);

			die();


		case "add_resource":

			$group_id = $_REQUEST['parent'];
			$calendar_id = $_REQUEST['calendar_id'];
			$name = $_REQUEST['name'];
			$cost_rate = $_REQUEST['cost_rate'];
			$notes = $_REQUEST['notes'];
			$company = $_REQUEST['company'];
			$unit_of_measure = $_REQUEST['unit_of_measure'];
			$guid = $_REQUEST['guid'];
			$created = time();
			$stmt = $db->prepare("INSERT INTO gantt_resources(programme_id,group_id,calendar_id,name,notes,cost_rate,created,company,unit_of_measure,guid) VALUES (?,?,?,?,?,?,?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $group_id);
			$stmt->bindParam(3, $calendar_id);
			$stmt->bindParam(4, $name);
			$stmt->bindParam(5, $notes);
			$stmt->bindParam(6, $cost_rate);
			$stmt->bindParam(7, $created);
			$stmt->bindParam(8, $company);
			$stmt->bindParam(9, $unit_of_measure);
			$stmt->bindParam(10, $guid);
			$stmt->execute();

			// Resources
			$sql = "SELECT * FROM gantt_resources WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$sql = "SELECT * FROM gantt_resource_groups WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resource_groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$payload = array("created" => true, "resources" => $resources, "resource_groups" => $resource_groups);
			echo json_encode($payload);

			break;
			die();



		case "add_link":

			// New or existing
			if ($_REQUEST['new'] == "true") {
				$type = $_REQUEST['type'];
				$offset_type = $_REQUEST['offset_type'];
				$offset_minutes = $_REQUEST['offset'];

				$source_id = $_REQUEST['source_id'];
				$source_guid = $_REQUEST['source_guid'];

				$target_id = $_REQUEST['target_id'];
				$target_guid = $_REQUEST['target_guid'];

				$programme_id = $_REQUEST['programme_id'];

				$stmt = $db->prepare("INSERT INTO gantt_links(programme_id,source,source_guid,target,target_guid,type,offset_minutes,offset_type) VALUES (?,?,?,?,?,?,?,?)");
				$stmt->bindParam(1, $programme_id);
				$stmt->bindParam(2, $source_id);
				$stmt->bindParam(3, $source_guid);
				$stmt->bindParam(4, $target_id);
				$stmt->bindParam(5, $target_guid);
				$stmt->bindParam(6, $type);
				$stmt->bindParam(7, $offset_minutes);
				$stmt->bindParam(8, $offset_type);
				$stmt->execute();
				$link_id = $db->lastInsertId();
				$payload = array("created" => true, "link_id" => $link_id);
				echo json_encode($payload);
			} else {

				$link_id = $_REQUEST['id'];
				$type = $_REQUEST['type'];
				$offset_type = $_REQUEST['offset_type'];
				$offset_minutes = $_REQUEST['offset'];

				$source_id = $_REQUEST['source_id'];
				$source_guid = $_REQUEST['source_guid'];

				$target_id = $_REQUEST['target_id'];
				$target_guid = $_REQUEST['target_guid'];

				$programme_id = $_REQUEST['programme_id'];

				$stmt = $db->prepare("UPDATE gantt_links SET source='$source_id',source_guid='$source_guid',target='$target_id',target_guid='$target_guid',type='$type',offset_type='$offset_type',offset_minutes='$offset_minutes' WHERE id='$link_id'");

				$stmt->execute();
				$payload = array("updated" => true, "link_id" => $link_id);
				echo json_encode($payload);
			}


			break;
			die();



		case "get_task_workload":

			$guid = $_REQUEST['guid'];
			$sql = "SELECT id,work_time, CONVERT(work_time, TIME) as modDate,work_date,quantity,is_root,parent FROM gantt_workload_links WHERE task_guid='$guid' ORDER by work_date ASC,modDate ASC";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$workload = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$payload = array("workloads" => $workload);
			echo json_encode($payload);

			break;



		case "get_workload_root":

			$root = $_REQUEST['id'];
			$sql = "SELECT * FROM gantt_workload_links WHERE id='$root'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$workload = $stmt->fetch(PDO::FETCH_ASSOC);
			$payload = array("workload" => $workload);
			echo json_encode($payload);


			break;
			die();


		case "add_to_workload_root":

			$root_id = $_REQUEST['root'];
			$sql = "SELECT id,task_guid,work_date FROM gantt_workload_links WHERE id='$root_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$root = $stmt->fetch(PDO::FETCH_ASSOC);

			$guid = $root['task_guid'];
			$date = $root['work_date'];
			$time = $_REQUEST['target'];
			$quantity = $_REQUEST['quantity'];

			$is_root = "0";
			$stmt = $db->prepare("INSERT INTO gantt_workload_links(task_guid,work_date,is_root,work_time,quantity,parent) VALUES (?,?,?,?,?,?)");
			$stmt->bindParam(1, $guid);
			$stmt->bindParam(2, $date);
			$stmt->bindParam(3, $is_root);
			$stmt->bindParam(4, $time);
			$stmt->bindParam(5, $quantity);
			$stmt->bindParam(6, $root_id);
			$stmt->execute();


			$sql = "SELECT work_time, CONVERT(work_time, TIME) as modDate,work_date,quantity,is_root,parent FROM gantt_workload_links WHERE task_guid='$guid' ORDER by work_date ASC,modDate ASC";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$workload = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$payload = array("created" => true, "workloads" => $workload);
			echo json_encode($payload);

			break;
			die();




		case "remove_from_workload_root":

			$id = $_REQUEST['id'];
			$sql = "DELETE FROM gantt_workload_links WHERE id='$id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$payload = array("removed" => true);
			echo json_encode($payload);

			break;

		case "update_task_workload_dates":

			$dates = json_decode($_REQUEST['range'], true);
			$guid = $_REQUEST['guid'];
			foreach ($dates as $date) {

				$sql = "SELECT id FROM gantt_workload_links WHERE task_guid='$guid' AND work_date='$date'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				if ($stmt->rowCount() == 0) {
					$is_root = "1";
					$stmt = $db->prepare("INSERT INTO gantt_workload_links(task_guid,work_date,is_root) VALUES (?,?,?)");
					$stmt->bindParam(1, $guid);
					$stmt->bindParam(2, $date);
					$stmt->bindParam(3, $is_root);
					$stmt->execute();
					$programme_id = $db->lastInsertId();
				}
			}

			$sql = "SELECT id,work_time, CONVERT(work_time, TIME) as modDate,work_date,quantity,is_root,parent FROM gantt_workload_links WHERE task_guid='$guid' ORDER by work_date ASC,modDate ASC";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$workload = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$payload = array("created" => true, "workloads" => $workload);
			echo json_encode($payload);


			break;
			die();

		




		case "get_root_elements":

			$sql = "SELECT id,text FROM gantt_tasks WHERE type <> 'milestone' AND parent='0' AND programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$elements = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$payload = array("elements" => $elements);
			echo json_encode($payload);
			break;
			die();



		case "set_reset_baselines_true":

			$task_id = $_REQUEST['id'];
			$sql = "UPDATE gantt_tasks SET reset_baselines_to_dates='1' WHERE programme_id='$programme_id' AND id='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			break;
			die();

		case "auto_schedule":

			// Need to schedule the programme!

			// 1. Get calendars and overrides
			$sql = "SELECT * FROM gantt_calendars WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			global $global_calendars;
			$global_calendars = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$sql = "SELECT * FROM gantt_calendar_overrides WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			global $global_calendar_overrides;
			$global_calendar_overrides = $stmt->fetchAll(PDO::FETCH_ASSOC);

			// 2. Get links
			$sql = "SELECT * FROM gantt_links WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			global $global_links;
			$global_links = $stmt->fetchAll(PDO::FETCH_ASSOC);

			// 3. Get all tasks
			$sql = "SELECT * FROM gantt_tasks WHERE type <> 'project' AND programme_id='$programme_id' ORDER by id";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			global $global_tasks;
			$global_tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);


			for ($i = 0; $i < count($global_tasks); $i++) {
				$task_id = $global_tasks[$i]['id'];

				if (linkInToTaskExists($task_id)) {
					$last_link_date = calculateLinkLastDate($task_id, $global_tasks[$i]['start_date'], $global_tasks[$i]['duration_worked'], $global_tasks[$i]['calendar_id']);

					$task_scheduled_start_date = $last_link_date;

					//$task_scheduled_end_date = getAdjustedEndDate($global_tasks[$i]['start_date'], $global_tasks[$i]['end_date'], $task_scheduled_start_date);

					$task_scheduled_end_date = getTaskEndDateFromMinutesv2($task_scheduled_start_date, $global_tasks[$i]['duration_worked'], $global_tasks[$i]['calendar_id']);

					if ($global_tasks[$i]['reset_baselines_to_dates'] == "1") {
						// Reset baselines as this task is recently scheduled!
						$sql = "UPDATE gantt_tasks SET start_date='$task_scheduled_start_date',end_date='$task_scheduled_end_date',baseline_start='$task_scheduled_start_date',baseline_end='$task_scheduled_end_date',reset_baselines_to_dates='0' WHERE id='$task_id'";
						$stmt = $db->prepare($sql);
						$stmt->execute();

						$global_tasks[$i]['start_date'] = $task_scheduled_start_date;
						$global_tasks[$i]['end_date'] = $task_scheduled_end_date;
						$global_tasks[$i]['baseline_start'] = $task_scheduled_start_date;
						$global_tasks[$i]['baseline_end'] = $task_scheduled_end_date;
					} else {
						// No - just update
						$sql = "UPDATE gantt_tasks SET start_date='$task_scheduled_start_date',end_date='$task_scheduled_end_date' WHERE id='$task_id'";
						$stmt = $db->prepare($sql);
						$stmt->execute();

						$global_tasks[$i]['start_date'] = $task_scheduled_start_date;
						$global_tasks[$i]['end_date'] = $task_scheduled_end_date;
					}
				}
			}


			$payload = array("schedule_updated" => true);
			echo json_encode($payload);



			break;
			die();



		case "build_programme_from_template":

			error_reporting(E_ALL);
			ini_set('display_errors', 1);



			break;
			die();




		case "set_demo_name":


			$user_id = $_COOKIE['ibex_demo_user_id'];
			$name = $_REQUEST['name'];

			$sql = "UPDATE gantt_users SET first_name='$name' WHERE id='$user_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$payload = array("name_set" => true);
			echo json_encode($payload);



			break;
			die();


		case "get_dashboard_tasks_by_status":

			$status = $_REQUEST['status'];
			$tasks_array = array("tasks");


			// Get my porgrammes
			$sql = "SELECT * FROM gantt_programmes WHERE account_id='$account_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$programmes = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($programmes as $programme) {
				$programme_id = $programme['id'];

				if ($status == "1") {
					$sql = "SELECT text,start_date,end_date,baseline_start,baseline_end,progress FROM gantt_tasks WHERE programme_id='$programme_id' AND type='task' AND progress='0'";
				} else if ($status == "3") {
					$sql = "SELECT text,start_date,end_date,baseline_start,baseline_end,progress FROM gantt_tasks WHERE programme_id='$programme_id' AND type='task'AND progress='100'";
				} else if ($status == "2") {
					$sql = "SELECT text,start_date,end_date,baseline_start,baseline_end,progress FROM gantt_tasks WHERE programme_id='$programme_id' AND type='task' AND progress <> '1' AND progress <> '0'";
				} else if ($status == "4") {
					$sql = "SELECT text,start_date,end_date,baseline_start,baseline_end,progress FROM gantt_tasks WHERE programme_id='$programme_id' AND type='task' AND end_date > deadline";
				}
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($tasks as $task_local) {
					array_push($tasks_array, $task_local);
				}
			}

			$payload = array($tasks_array);
			echo json_encode($payload);


			break;
			die();




		case "get_support_ticket":

			$guid = $_REQUEST['guid'];


			$sql = "SELECT * FROM gantt_support_tickets WHERE account_id='$account_id' AND guid='$guid'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

			$ticket_id = $ticket['id'];

			// Build ticket string for status bar
			$category = "Unassigned";
			if ($ticket['category'] == "1") {
				$category = "Technical support";
			}
			if ($ticket['category'] == "2") {
				$category = "Billing";
			}
			if ($ticket['category'] == "3") {
				$category = "Account management";
			}

			if ($ticket['status'] == "1") {
				$status = "Open";
			}
			if ($ticket['status'] == "2") {
				$status = "On hold";
			}
			if ($ticket['status'] == "3") {
				$status = "Closed";
			}

			$assigned_user = $ticket['assigned_to'];
			$sql = "SELECT first_name,last_name FROM gantt_users WHERE id='$assigned_user'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$user_assigned = $stmt->fetch(PDO::FETCH_ASSOC);

			$assigned_to_name = $user_assigned['first_name'] . " " . substr($user_assigned['last_name'], 0, 1) . " [Ibex Support]";


			$status_string = "Category: " . $category . " | Status: " . $status . " | Assigned: " . $assigned_to_name;

			$replies_array = array();

			$sql = "SELECT * FROM gantt_support_ticket_replies WHERE ticket_id='$ticket_id' ORDER BY created DESC";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$replies = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($replies as $reply) {
				$reply_creator = $reply['creator'];
				$sql = "SELECT first_name,last_name,account_id FROM gantt_users WHERE id='$reply_creator'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$creator = $stmt->fetch(PDO::FETCH_ASSOC);
				if ($creator['account_id'] == "0") {
					$reply['creator'] = $creator['first_name'] . " " . substr($creator['last_name'], 0, 1) . " [Ibex Support]";
				}
				if ($reply_creator == $_SESSION['user']['id']) {
					$reply['creator'] = "You";
				}

				$reply_text_decoded = json_decode($reply['text']);
				$reply['text'] = nl2br($reply_text_decoded);

				$replies_array[] = $reply;
			}
			$ticket['replies'] = $replies_array;
			$ticket['status_bar_string'] = $status_string;

			$sql = "UPDATE gantt_support_tickets SET unread='0' WHERE id='$ticket_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();




			$payload = array($ticket);
			echo json_encode($payload);
			break;
			die();


		case "get_support_tickets":

			$tickets_array = array();

			$sql = "SELECT * FROM gantt_support_tickets WHERE account_id='$account_id' AND status='1' ORDER BY last_reply DESC";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

			foreach ($tickets as $ticket) {
				$ticket_id = $ticket['id'];

				$sql = "SELECT * FROM gantt_support_ticket_replies WHERE ticket_id='$ticket_id' ORDER BY created DESC";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$replies = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($replies as $reply) {

					$ticket['replies'][] = $reply;
				}

				$tickets_arrays[] = $ticket;
			}

			$payload = array("support_tickets" => $tickets_arrays);
			echo json_encode($payload);
			break;
			die();



		case "update_ticket":

			// NEw or existing?
			if ($_REQUEST['initialised'] == "0") {

				// New
				$subject = $_REQUEST['subject'];
				$details = $_REQUEST['details'];
				$category = $_REQUEST['category'];
				$creator = $_SESSION['user']['id'];
				$creator_email = $_SESSION['user']['id'];
				$created = time();
				$guid = $_REQUEST['guid'];
				$short_identifier = uniqid();

				$stmt = $db->prepare("INSERT INTO gantt_support_tickets(account_id,guid,subject,creator,created,last_reply,category,short_identifier) VALUES (?,?,?,?,?,?,?,?)");
				$stmt->bindParam(1, $account_id);
				$stmt->bindParam(2, $guid);
				$stmt->bindParam(3, $subject);
				$stmt->bindParam(4, $creator);
				$stmt->bindParam(5, $created);
				$stmt->bindParam(6, $created);
				$stmt->bindParam(7, $category);
				$stmt->bindParam(8, $short_identifier);
				$stmt->execute();
				$ticket_id = $db->lastInsertId();

				// Now add the first reply
				$stmt = $db->prepare("INSERT INTO gantt_support_ticket_replies(ticket_id,text,creator,created) VALUES (?,?,?,?)");
				$stmt->bindParam(1, $ticket_id);
				$stmt->bindParam(2, $details);
				$stmt->bindParam(3, $creator);
				$stmt->bindParam(4, $created);
				$stmt->execute();
				$response_id = $db->lastInsertId();

				//


				$sql = "SELECT * FROM gantt_support_tickets WHERE id='$ticket_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

				$ticket_id = $ticket['id'];

				$replies_array = array();

				$sql = "SELECT * FROM gantt_support_ticket_replies WHERE ticket_id='$ticket_id' ORDER BY created DESC";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$replies = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($replies as $reply) {
					$reply_creator = $reply['creator'];
					$sql = "SELECT first_name,last_name,account_id FROM gantt_users WHERE id='$reply_creator'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$creator = $stmt->fetch(PDO::FETCH_ASSOC);
					if ($creator['account_id'] == "0") {
						$reply['creator'] = $creator['first_name'] . " " . substr($creator['last_name'], 0, 1) . " [Ibex Support]";
					} else {
						$reply['creator'] = $creator['first_name'] . " " . substr($creator['last_name'], 0, 1);
					}

					$replies_array[] = $reply;
				}
				$ticket['replies'] = $replies_array;


				// Mailgun

				// User who needs to be emailed
				$sql = "SELECT email_address FROM gantt_users WHERE id='$creator_email'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$creator = $stmt->fetch(PDO::FETCH_ASSOC);

				$auth_link = "auth.php?email_address=" . $email_address . "&token=" . $invite_token;


				// Email admin
				$config = array();
				$config['api_key'] = "key-86929a614db601844f2de754bf641f80";
				$config['api_url'] = "https://api.mailgun.net/v3/ibex.software/messages";

				$message = array();
				$message['from'] = "Ibex Support <support-tickets@ibex.software>";
				$message['to'] = $creator['email_address'];
				$message['subject'] = "Support ticket created: " . $subject . " [#" . $short_identifier . "]";

				$content = file_get_contents('http://dashboard.ibex.software/assets/email-templates/support_ticket_created.html');
				$content = str_replace("{{SUBJECT}}", $subject, $content);
				$content = str_replace("{{AUTH_LINK}}", $auth_link, $content);

				$message['html'] = $content;

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $config['api_url']);
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($ch, CURLOPT_USERPWD, "api:{$config['api_key']}");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $message);

				$result = curl_exec($ch);
				curl_close($ch);







				$payload = array("ticket_action" => "created", "ticket" => $ticket);
				echo json_encode($payload);
			} else {
				$details = $_REQUEST['details'];
				$creator = $_SESSION['user']['id'];
				$created = time();

				// existing
				$guid = $_REQUEST['guid'];
				$sql = "SELECT id FROM gantt_support_tickets WHERE guid ='$guid' AND account_id='$account_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$ticket = $stmt->fetch(PDO::FETCH_ASSOC);
				$ticket_id = $ticket['id'];

				$stmt = $db->prepare("INSERT INTO gantt_support_ticket_replies(ticket_id,text,creator,created) VALUES (?,?,?,?)");
				$stmt->bindParam(1, $ticket_id);
				$stmt->bindParam(2, $details);
				$stmt->bindParam(3, $creator);
				$stmt->bindParam(4, $created);
				$stmt->execute();
				$response_id = $db->lastInsertId();

				$stmt = $db->prepare("UPDATE gantt_support_tickets SET last_reply='$created' WHERE id='$ticket_id'");
				$stmt->execute();

				$payload = array("ticket_updated" => true, "ticket_id" => $ticket_id);
				echo json_encode($payload);
			}



			break;
			die();


		case "cascade_permissions":

			error_reporting(E_ALL);
			ini_set('display_errors', 1);
			$task_guid = $_REQUEST['task_guid'];
			$sql = "SELECT id,programme_id FROM gantt_tasks WHERE guid ='$task_guid'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$task_id = $task['id'];
			$programme_id = $task['programme_id'];

			echo "task ID is " . $task_id;

			$sql = "SELECT account_id FROM gantt_programmes WHERE id ='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$programme = $stmt->fetch(PDO::FETCH_ASSOC);
			$account_id = $programme['account_id'];

			echo "account id is " . $account_id;


			// Get users
			$sql = "SELECT id FROM gantt_users WHERE active='1' AND account_id ='$account_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

			var_dump($users);
			foreach ($users as $user) {
				$user_id_local = $user['id'];
				$stmt = $db->prepare("INSERT INTO gantt_user_project_permissions(user_id,project_id) VALUES (?,?)");
				$stmt->bindParam(1, $user_id_local);
				$stmt->bindParam(2, $task_id);
				$stmt->execute();
			}


			$payload = array("cascaded" => true);
			echo json_encode($payload);

			break;
			die();





		case "determine_can_edit":

			$can_edit = false;
			$user_id = $_SESSION['user']['id'];
			$task_id = $_REQUEST['task_id'];

			// Admin?
			$sql = "SELECT type FROM gantt_users WHERE id ='$user_id' AND account_id='$account_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($user['type'] == "2") {
				$can_edit = true;
			} else if ($_SESSION['user']['type'] == "0") {
				$can_edit = true;
			} else if ($user['type'] == "1") {
				// Is this task a parent?
				$sql = "SELECT parent FROM gantt_tasks WHERE id ='$task_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$task = $stmt->fetch(PDO::FETCH_ASSOC);
				if ($task['parent'] == "0") {
					// Root - check permission
					$sql = "SELECT id FROM gantt_user_project_permissions WHERE user_id ='$user_id' AND project_id='$task_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					if ($stmt->rowCount() != 0) {
						$can_edit = true;
					}
				} else {
					// Get parent permission
					$parent_id = $task['parent'];
					$sql = "SELECT id FROM gantt_user_project_permissions WHERE user_id ='$user_id' AND project_id='$parent_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					if ($stmt->rowCount() != 0) {
						$can_edit = true;
					}
				}
			}

			$payload = array("can_edit" => $can_edit);
			echo json_encode($payload);


			break;
			die();

		case "toggle_user_project_permission":

			$user_id = $_REQUEST['user_id'];
			$project_id = $_REQUEST['project_id'];
			$type = $_REQUEST['type'];

			if ($type == "1") {
				$sql = "DELETE FROM gantt_user_project_permissions WHERE user_id ='$user_id' AND project_id='$project_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
			} else if ($type == "2") {
				$stmt = $db->prepare("INSERT INTO gantt_user_project_permissions(user_id,project_id) VALUES (?,?)");
				$stmt->bindParam(1, $user_id);
				$stmt->bindParam(2, $project_id);
				$stmt->execute();
			}


			break;
			die();


		case "get_user_details":



			$user_id = $_REQUEST['user_id'];
			$sql = "SELECT id,first_name,last_name,email_address,type,avatar_url,background_url,last_login,last_heartbeat,type FROM gantt_users WHERE id ='$user_id' AND account_id='$account_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_ASSOC);

			$self_id = $_SESSION['user']['id'];
			$sql = "SELECT type FROM gantt_users WHERE id ='$self_id' AND account_id='$account_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$self = $stmt->fetch(PDO::FETCH_ASSOC);
			$self_is_administrator = false;
			if ($self['type'] == "2") {
				$self_is_administrator = true;
			}

			$sql = "SELECT project_id FROM gantt_user_project_permissions WHERE user_id ='$user_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);


			$payload = array("user" => $user, "self_is_administrator" => $self_is_administrator, "permissions" => $permissions);
			echo json_encode($payload);
			break;
			die();

		case "set_auto_schedule_on_next_load":

			$_SESSION['auto_schedule_on_load'] = "1";
			$payload = array("actioned" => true);
			echo json_encode($payload);
			break;
			die();


		case "get_task_comments":

			$task_id = $_REQUEST['task_id'];
			$sql = "SELECT comments_activity FROM gantt_tasks WHERE id ='$task_id' AND programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$comments_existing = $task['comments_activity'];

			echo $comments_existing;

			break;
			die();



		case "add_task_comment":

			$task_id = $_REQUEST['id'];
			$comment = $_REQUEST['comment'];
			$delayed = $_REQUEST['delayed'];
			$author = $_SESSION['user']['first_name'] . " " . $_SESSION['user']['last_name'];

			// Update task manual delay 
			$manual_delay = "0";
			if ($delayed == "true") {
				$manual_delay = "1";
			}

			$sql = "UPDATE gantt_tasks SET manually_delayed='$manual_delay' WHERE id='$task_id' AND programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();



			// Get existing comments
			$sql = "SELECT comments_activity FROM gantt_tasks WHERE id ='$task_id' AND programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$comments_existing = $task['comments_activity'];

			$json_comments_existing;
			if ($comments_existing != "") {

				$json_comments_existing = json_decode($comments_existing, true);
				$inner_json_comments_existing = $json_comments_existing['comments'];
				$comment_array = array("author" => $author, "comment" => $comment, "delayed" => $delayed, "created" => time());
				array_push($inner_json_comments_existing, $comment_array);
				$parsed_array = array("comments" => $inner_json_comments_existing);
			} else {

				$comment_array = array("author" => $author, "comment" => $comment, "delayed" => $delayed, "created" => time());
				$json_comments_existing[] = $comment_array;
				$parsed_array = array("comments" => $json_comments_existing);
			}


			$updated_comments = json_encode($parsed_array);



			$sql = "UPDATE gantt_tasks SET comments_activity='$updated_comments' WHERE programme_id='$programme_id' AND id='$task_id'";

			$stmt = $db->prepare($sql);
			$test = $stmt->execute();


			$payload = array("comment_added" => true);
			echo json_encode($payload);

			break;
			die();

		case "mark_task_closed":

			$task_id = $_REQUEST['id'];
			$sql = "UPDATE gantt_tasks SET opened='0' WHERE programme_id='$programme_id' AND id='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			break;
			die();


		case "mark_task_opened":

			$task_id = $_REQUEST['id'];
			$sql = "UPDATE gantt_tasks SET opened='1' WHERE programme_id='$programme_id' AND id='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			break;
			die();


		case "reassign_task_resource_links":

			$task_guid = $_REQUEST['guid'];
			$sql = "SELECT id FROM gantt_tasks WHERE guid ='$task_guid'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$task_id = $task['id'];

			$sql = "UPDATE gantt_task_resource_links SET task_id='$task_id' WHERE task_guid='$task_guid' AND programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			break;
			die();


		case "create_demo_resources":


			$task_1_guid = $_REQUEST['task_1_guid'];
			$task_2_guid = $_REQUEST['task_2_guid'];
			$task_2a_guid = $_REQUEST['task_2a_guid'];
			$task_3_guid = $_REQUEST['task_3_guid'];
			$task_4_guid = $_REQUEST['task_4_guid'];

			$sql = "SELECT id FROM gantt_tasks WHERE guid ='$task_1_guid'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$task_1_id = $task['id'];

			$sql = "SELECT id FROM gantt_tasks WHERE guid ='$task_2_guid'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$task_2_id = $task['id'];

			$sql = "SELECT id FROM gantt_tasks WHERE guid ='$task_2a_guid'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$task_2a_id = $task['id'];

			$sql = "SELECT id FROM gantt_tasks WHERE guid ='$task_3_guid'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$task_3_id = $task['id'];

			$sql = "SELECT id FROM gantt_tasks WHERE guid ='$task_4_guid'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$task_4_id = $task['id'];

			$created = time();

			$type = "1";
			$name = "Dave Smith";
			$role = "Labourer";
			$supplier = "Labour Unlimited Ltd";
			$unit = "2";
			$unit_cost = "100";

			$stmt = $db->prepare("INSERT INTO gantt_resources(programme_id,type,name,role,supplier,unit,unit_cost,created) VALUES (?,?,?,?,?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $type);
			$stmt->bindParam(3, $name);
			$stmt->bindParam(4, $role);
			$stmt->bindParam(5, $supplier);
			$stmt->bindParam(6, $unit);
			$stmt->bindParam(7, $unit_cost);
			$stmt->bindParam(8, $created);
			$stmt->execute();
			$resource_1_id = $db->lastInsertId();

			$stmt = $db->prepare("INSERT INTO gantt_task_resource_links(programme_id,task_id,task_guid,resource_id) VALUES (?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $task_1_id);
			$stmt->bindParam(3, $task_1_guid);
			$stmt->bindParam(4, $resource_1_id);
			$stmt->execute();

			$stmt = $db->prepare("INSERT INTO gantt_task_resource_links(programme_id,task_id,task_guid,resource_id) VALUES (?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $task_2a_id);
			$stmt->bindParam(3, $task_2a_guid);
			$stmt->bindParam(4, $resource_1_id);
			$stmt->execute();



			$type = "1";
			$name = "Aiden Crawley";
			$role = "Labourer";
			$supplier = "Labour Unlimited Ltd";
			$unit = "2";
			$unit_cost = "100";

			$stmt = $db->prepare("INSERT INTO gantt_resources(programme_id,type,name,role,supplier,unit,unit_cost,created) VALUES (?,?,?,?,?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $type);
			$stmt->bindParam(3, $name);
			$stmt->bindParam(4, $role);
			$stmt->bindParam(5, $supplier);
			$stmt->bindParam(6, $unit);
			$stmt->bindParam(7, $unit_cost);
			$stmt->bindParam(8, $created);
			$stmt->execute();
			$resource_2_id = $db->lastInsertId();

			$stmt = $db->prepare("INSERT INTO gantt_task_resource_links(programme_id,task_id,task_guid,resource_id) VALUES (?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $task_1_id);
			$stmt->bindParam(3, $task_1_guid);
			$stmt->bindParam(4, $resource_2_id);
			$stmt->execute();

			$stmt = $db->prepare("INSERT INTO gantt_task_resource_links(programme_id,task_id,task_guid,resource_id) VALUES (?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $task_2_id);
			$stmt->bindParam(3, $task_2_guid);
			$stmt->bindParam(4, $resource_2_id);
			$stmt->execute();


			$type = "1";
			$name = "Darren Axwell";
			$role = "Labourer";
			$supplier = "Labour Unlimited Ltd";
			$unit = "2";
			$unit_cost = "120";

			$stmt = $db->prepare("INSERT INTO gantt_resources(programme_id,type,name,role,supplier,unit,unit_cost,created) VALUES (?,?,?,?,?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $type);
			$stmt->bindParam(3, $name);
			$stmt->bindParam(4, $role);
			$stmt->bindParam(5, $supplier);
			$stmt->bindParam(6, $unit);
			$stmt->bindParam(7, $unit_cost);
			$stmt->bindParam(8, $created);
			$stmt->execute();
			$resource_3_id = $db->lastInsertId();

			$stmt = $db->prepare("INSERT INTO gantt_task_resource_links(programme_id,task_id,task_guid,resource_id) VALUES (?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $task_1_id);
			$stmt->bindParam(3, $task_1_guid);
			$stmt->bindParam(4, $resource_3_id);
			$stmt->execute();

			$stmt = $db->prepare("INSERT INTO gantt_task_resource_links(programme_id,task_id,task_guid,resource_id) VALUES (?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $task_3_id);
			$stmt->bindParam(3, $task_3_guid);
			$stmt->bindParam(4, $resource_3_id);
			$stmt->execute();


			$type = "2";
			$name = "Harry Stand";
			$role = "Supervisor";
			$supplier = "Contractors Corner Ltd";
			$unit = "2";
			$unit_cost = "250";

			$stmt = $db->prepare("INSERT INTO gantt_resources(programme_id,type,name,role,supplier,unit,unit_cost,created) VALUES (?,?,?,?,?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $type);
			$stmt->bindParam(3, $name);
			$stmt->bindParam(4, $role);
			$stmt->bindParam(5, $supplier);
			$stmt->bindParam(6, $unit);
			$stmt->bindParam(7, $unit_cost);
			$stmt->bindParam(8, $created);
			$stmt->execute();
			$resource_4_id = $db->lastInsertId();

			$stmt = $db->prepare("INSERT INTO gantt_task_resource_links(programme_id,task_id,task_guid,resource_id) VALUES (?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $task_1_id);
			$stmt->bindParam(3, $task_1_guid);
			$stmt->bindParam(4, $resource_4_id);
			$stmt->execute();

			$stmt = $db->prepare("INSERT INTO gantt_task_resource_links(programme_id,task_id,task_guid,resource_id) VALUES (?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $task_2_id);
			$stmt->bindParam(3, $task_2_guid);
			$stmt->bindParam(4, $resource_4_id);
			$stmt->execute();

			$stmt = $db->prepare("INSERT INTO gantt_task_resource_links(programme_id,task_id,task_guid,resource_id) VALUES (?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $task_3_id);
			$stmt->bindParam(3, $task_3_guid);
			$stmt->bindParam(4, $resource_4_id);
			$stmt->execute();


			$payload = array("resources_assigned" => true);
			echo json_encode($payload);



			break;
			die();


		case "get_listed_resources_of_task":

			$task_id = $_REQUEST['task_id'];

			$resources_array = array();

			$sql = "SELECT resource_id FROM gantt_task_resource_links WHERE programme_id ='$programme_id' AND task_id='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$count = 0;
			$resource_string = "";

			foreach ($resources as $resource) {
				$count++;
				$resource_id = $resource['resource_id'];
				$sql = "SELECT name FROM gantt_resources WHERE id ='$resource_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$resource_object = $stmt->fetch(PDO::FETCH_ASSOC);
				$resource_string .= $resource_object['name'] . ", ";
			}
			$resource_string = rtrim($resource_string, ', ');

			$payload = array("count" => $count, "listed_resources" => $resource_string);
			echo json_encode($payload);

			break;
			die();



		case "get_linked_resources":

			$task_id = $_REQUEST['task_id'];

			$resources_array = array();

			$sql = "SELECT resource_id FROM gantt_task_resource_links WHERE programme_id ='$programme_id' AND task_id='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$count = 0;
			$resource_string = "";

			foreach ($resources as $resource) {
				$count++;
				$resource_id = $resource['resource_id'];
				$sql = "SELECT name FROM gantt_resources WHERE id ='$resource_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$resource_object = $stmt->fetch(PDO::FETCH_ASSOC);
				$resource_string .= $resource_object['name'] . ", ";
			}
			$resource_string = rtrim($resource_string, ', ');

			$payload = array("linked_resources" => $resource_string);
			echo json_encode($payload);

			break;
			die();




		case "get_resources_of_task":
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
			$task_id = $_REQUEST['task_id'];

			$resources_array = array();
			$sql = "SELECT resource_id FROM gantt_task_resource_links WHERE programme_id ='$programme_id' AND task_id='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($resources as $resource) {
				array_push($resources_array, $resource['resource_id']);
			}

			$payload = array("resources" => $resources_array);
			echo json_encode($payload);

			break;
			die();



		case "get_detailed_resources_of_task":

			$task_id = $_REQUEST['task_id'];

			$resources_array = array();
			$sql = "SELECT resource_id FROM gantt_task_resource_links WHERE programme_id ='$programme_id' AND task_id='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($resources as $resource) {
				$resource_id = $resource['resource_id'];
				$sql = "SELECT * FROM gantt_resources WHERE id ='$resource_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$resource_object = $stmt->fetch(PDO::FETCH_ASSOC);

				array_push($resources_array, $resource_object);
			}

			$payload = array("resources" => $resources_array);
			echo json_encode($payload);

			break;
			die();





		case "get_resources_of_task_editor":

			$task_id = $_REQUEST['task_id'];

			$resources_array = array();


			$sql = "SELECT id,name FROM gantt_resources WHERE programme_id ='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($resources as $resource) {
				$resource_id = $resource['id'];
				$resource_name = $resource['name'];
				$sql_link = "SELECT id FROM gantt_task_resource_links WHERE resource_id ='$resource_id' AND task_id='$task_id'";
				$stmt_link = $db->prepare($sql_link);
				$stmt_link->execute();
				if ($stmt_link->rowCount() == 0) {
					$resources_array[] = array("id" => $resource_id, "name" => $resource_name, "assigned" => false);
				} else if ($stmt_link->rowCount() > 0) {
					$resources_array[] = array("id" => $resource_id, "name" => $resource_name, "assigned" => true);
				}
			}

			$payload = array("resources" => $resources_array);
			echo json_encode($payload);

			break;
			die();





		case "toggle_task_resource_link":

			$linked = $_REQUEST['linked'];
			$task_id = $_REQUEST['task_id'];
			$resource_id = $_REQUEST['resource_id'];

			if ($linked == "true") {
				$task_guid = $_REQUEST['task_guid'];
				$stmt = $db->prepare("INSERT INTO gantt_task_resource_links(programme_id,task_id,task_guid,resource_id) VALUES (?,?,?,?)");
				$stmt->bindParam(1, $programme_id);
				$stmt->bindParam(2, $task_id);
				$stmt->bindParam(3, $task_guid);
				$stmt->bindParam(4, $resource_id);
				$stmt->execute();

				// Update task object to show overview

				$sql = "SELECT resource_id FROM gantt_task_resource_links WHERE programme_id ='$programme_id' AND task_id='$task_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$count = 0;
				$resource_string = "";

				foreach ($resources as $resource) {
					$count++;
					$resource_id = $resource['resource_id'];
					$sql = "SELECT name FROM gantt_resources WHERE id ='$resource_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$resource_object = $stmt->fetch(PDO::FETCH_ASSOC);
					$resource_string .= $resource_object['name'] . ", ";
				}
				$resource_string = rtrim($resource_string, ', ');

				$sql = "UPDATE gantt_tasks SET resource_count=resource_count+1,resources_listed='$resource_string' WHERE programme_id='$programme_id' AND id='$task_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
			} else {
				$stmt = $db->prepare("DELETE FROM gantt_task_resource_links WHERE programme_id='$programme_id' AND task_id='$task_id' AND resource_id='$resource_id'");
				$stmt->execute();

				// Update task object to show overview

				$sql = "SELECT resource_id FROM gantt_task_resource_links WHERE programme_id ='$programme_id' AND task_id='$task_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$count = 0;
				$resource_string = "";

				foreach ($resources as $resource) {
					$count++;
					$resource_id = $resource['resource_id'];
					$sql = "SELECT name FROM gantt_resources WHERE id ='$resource_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$resource_object = $stmt->fetch(PDO::FETCH_ASSOC);
					$resource_string .= $resource_object['name'] . ", ";
				}
				$resource_string = rtrim($resource_string, ', ');

				$sql = "UPDATE gantt_tasks SET resource_count=resource_count-1,resources_listed='$resource_string' WHERE programme_id='$programme_id' AND id='$task_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
			}

			$payload = array("assignment_updated" => true);
			echo json_encode($payload);


			break;
			die();



		case "search_tasks_to_assign_resource":

			$search = strtolower($_REQUEST['q']);
			$resource_id = $_REQUEST['resource_id'];

			// Check existing tasks
			$sql = "SELECT id,text FROM gantt_tasks WHERE programme_id ='$programme_id' AND type='task'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

			/*
		$existing_tasks_array = array();
		
		$sql = "SELECT * FROM gantt_task_resource_links WHERE resource_id ='$resource_id'";
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$resource_links = $stmt->fetchAll(PDO::FETCH_ASSOC);

		foreach ($tasks as $existing_link)
		{
			array_push($existing_links_array, $existing_link['source']);
		}

		$sql= "SELECT id,text,reference_number FROM gantt_tasks WHERE type='task' AND LOWER(text) LIKE '%$search%' AND programme_id='$programme_id' AND id <> '$task_id' AND id NOT IN ( '" . implode($existing_links_array, "', '") . "' ) LIMIT 50";
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$list = $stmt->fetchall(PDO::FETCH_ASSOC);

		if(count($list) > 0)
		{
			foreach ($list as $key => $value) 
			{
				
						$data[] = array('id' => $value['id'], 'text' => $value['text']);			 	
					
			} 
		} 
		else 
		{
			$data[] = array('id' => '0', 'text' => 'No records found');
		}
			

			echo json_encode($data);
		*/
			die();
			break;





		case "get_resource":

			$resource_id = $_REQUEST['id'];

			$sql = "SELECT * FROM gantt_resources WHERE id= '$resource_id' AND programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resource = $stmt->fetch(PDO::FETCH_ASSOC);

			// Get linked task
			$sql = "SELECT id,`text`,programme_id,resource_id FROM gantt_tasks WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$linked_tasks = array();
			foreach ($tasks as $task) {
				$resources_task = $task['resource_id'];
				$array_tasks_res = explode(",", $resources_task);
				if (in_array($resource_id, $array_tasks_res)) {
					$linked_tasks[] = array("task_text" => $task['text']);
				}
			}




			$payload = array("resource" => $resource, "linked_tasks" => $linked_tasks);
			echo json_encode($payload);


			break;
			die();




		case "get_resource_groups":


			$sql = "SELECT * FROM gantt_resource_groups WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resource_groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$payload = array("resource_groups" => $resource_groups);
			echo json_encode($payload);


			break;
			die();





		case "get_resource_group":

			$resource_id = $_REQUEST['id'];

			$sql = "SELECT * FROM gantt_resource_groups WHERE id= '$resource_id' AND programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resource_group = $stmt->fetch(PDO::FETCH_ASSOC);
			$payload = array("resource_group" => $resource_group);
			echo json_encode($payload);


			break;
			die();






		case "get_resource_assignments":

			$sql = "SELECT * FROM gantt_resources WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$resources_array = array();

			foreach ($resources as $resource) {

				$dates_array = array();

				$resource_id = $resource['id'];
				$sql = "SELECT task_id FROM gantt_task_resource_links WHERE programme_id ='$programme_id' AND resource_id='$resource_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

				$tasks_array = array();

				foreach ($tasks as $task) {
					$task_id = $task['task_id'];

					$tasks_array[] = $task_id;

					$sql_task = "SELECT id,start_date,end_date,calendar_id FROM gantt_tasks WHERE id ='$task_id'";
					$stmt_task = $db->prepare($sql_task);
					$stmt_task->execute();
					$task_object = $stmt_task->fetch(PDO::FETCH_ASSOC);

					$calendar_id = $task_object['calendar_id'];

					$sql_calendar = "SELECT start_hour,end_hour FROM gantt_calendars WHERE id ='$calendar_id'";
					$stmt_calendar = $db->prepare($sql_calendar);
					$stmt_calendar->execute();
					$calendar_object = $stmt_calendar->fetch(PDO::FETCH_ASSOC);

					// Get hours in this task shift
					$task_length_hours = abs($calendar_object['end_hour'] - $calendar_object['start_hour']);

					// Get days in this task range (start <-> end)
					$begin = new DateTime($task_object['start_date']);
					$end = new DateTime($task_object['end_date']);

					$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);

					foreach ($daterange as $date) {
						$date_string = $date->format("Y-m-d");

						$count_check = 0;
						foreach ($dates_array as $identifier => $subarray) {
							if ($subarray['date'] == $date_string) {
								$count_check++;
								$dates_array[$identifier]['hours'] = $dates_array[$identifier]['hours'] + $task_length_hours;
							}
						}

						if ($count_check == 0) {
							$dates_array[] = array("resource_id" => $resource_id, "date" => $date_string, "hours" => $task_length_hours, "task_id" => $task_object['id']);
						}
					}
				}
				$resources_array[] = array("resource_id" => $resource_id, "assignments" => $dates_array);
			}



			$payload = array("resources" => $resources_array);
			echo json_encode($payload);


			break;
			die();






		case "get_resources":

			$sql = "SELECT * FROM gantt_resources WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);

			// Cache tasks
			$sql_task = "SELECT id,start_date,end_date,calendar_id,text,type FROM gantt_tasks WHERE programme_id='$programme_id'";
			$stmt_task = $db->prepare($sql_task);
			$stmt_task->execute();
			$cached_tasks = $stmt_task->fetchAll(PDO::FETCH_ASSOC);

			// Cache calendars
			$sql_calendar = "SELECT start_hour,end_hour FROM gantt_calendars WHERE programme_id ='$programme_id'";
			$stmt_calendar = $db->prepare($sql_calendar);
			$stmt_calendar->execute();
			$cached_calendars = $stmt_calendar->fetchAll(PDO::FETCH_ASSOC);

			$resources_array = array();

			foreach ($resources as $resource) {
				$dates_array = array();
				$resource_id = $resource['id'];

				$sql = "SELECT task_id FROM gantt_task_resource_links WHERE programme_id ='$programme_id' AND resource_id='$resource_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();

				$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

				$tasks_array = array();

				foreach ($tasks as $task) {
					$task_id = $task['task_id'];

					$tasks_array[] = $task_id;

					foreach ($cached_tasks as $cached_task) {
						if ($cached_task['id'] == $task_id) {
							$task_object = $cached_task;
							break;
						}
					}

					$calendar_id = $task_object['calendar_id'];

					foreach ($cached_calendars as $cached_calendar) {
						if ($cached_calendar['id'] == $calendar_id) {
							$calendar_object = $cached_calendar;
							break;
						}
					}

					// Get hours in this task shift
					if ($task_object['type'] == "task") {
						$task_length_hours = abs($calendar_object['end_hour'] - $calendar_object['start_hour']);
					} else {
						$task_length_hours = 1;
					}

					// Get days in this task range (start <-> end)
					$begin = new DateTime($task_object['start_date']);
					$end = new DateTime($task_object['end_date']);


					$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);

					foreach ($daterange as $date) {

						$date_string = $date->format("Y-m-d");

						$count_check = 0;
						foreach ($dates_array as $identifier => $subarray) {
							if ($subarray['date'] == $date_string) {
								$count_check++;
								$dates_array[$identifier]['hours'] = $dates_array[$identifier]['hours'] + $task_length_hours;
							}
						}

						if ($count_check == 0) {
							$dates_array[] = array("date" => $date_string, "hours" => $task_length_hours, "task_id" => $task_object['id']);
						}
					}
				}
				$resources_array[] = array("resource" => $resource);
				$assignments_array[] = array("resource_id" => $resource_id, "assignments" => $dates_array);
			}

			// Task links
			$task_link_array = array();


			foreach ($cached_tasks as $task) {

				$resource_string = "";

				$task_id = $task['id'];
				$sql = "SELECT resource_id FROM gantt_task_resource_links WHERE programme_id ='$programme_id' AND task_id='$task_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$count_resources = 0;
				foreach ($resources as $resource) {
					$resource_id = $resource['resource_id'];
					$sql = "SELECT name FROM gantt_resources WHERE id='$resource_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$resource_object = $stmt->fetch(PDO::FETCH_ASSOC);
					$resource_string .= $resource_object['name'] . ", ";
					$count_resources++;
				}
				$resource_string = rtrim($resource_string, ', ');

				$task_link_array[] = array("task_id" => $task_id, "resource_string" => $resource_string, "resource_count" => $count_resources);
			}

			$payload = array("resources" => $resources_array, "assignments" => $assignments_array, "task_resource_links" => $task_link_array);
			echo json_encode($payload);


			break;
			die();










		case "create_resource":

			$created = time();

			$resource_data = $_REQUEST['data'];

			$stmt = $db->prepare("INSERT INTO gantt_resources(programme_id,type,name,role,supplier,unit,unit_cost,unit_conversion,unit_conversion_type,created) VALUES (?,?,?,?,?,?,?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $resource_data['type']);
			$stmt->bindParam(3, $resource_data['name']);
			$stmt->bindParam(4, $resource_data['role']);
			$stmt->bindParam(5, $resource_data['supplier']);
			$stmt->bindParam(6, $resource_data['unit']);
			$stmt->bindParam(7, $resource_data['unit_cost']);
			$stmt->bindParam(8, $resource_data['unit_conversion']);
			$stmt->bindParam(9, $resource_data['unit_conversion_type']);
			$stmt->bindParam(10, $created);
			$stmt->execute();

			$resource_inserted = $db->lastInsertId();
			$payload = array("resource_created" => true, "resource_id" => $resource_inserted);
			echo json_encode($payload);




			break;
			die();


		case "delete_resource":

			$resource_id = $_REQUEST['id'];

			$sql = "DELETE FROM gantt_resources WHERE id = '$resource_id' AND programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			// Resources
			$sql = "SELECT * FROM gantt_resources WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$sql = "SELECT * FROM gantt_resource_groups WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resource_groups = $stmt->fetchAll(PDO::FETCH_ASSOC);



			$payload = array("processed" => true, "resources" => $resources, "resource_groups" => $resource_groups);
			echo json_encode($payload);
			die();

			break;





		case "update_resource":
			$created = time();
			$resource_id = $_REQUEST['id'];
			$name = $_REQUEST['name'];
			$group_id = $_REQUEST['parent'];
			$calendar_id = $_REQUEST['calendar_id'];
			$notes = $_REQUEST['notes'];
			$company = $_REQUEST['company'];
			$cost_rate = $_REQUEST['cost_rate'];
			$unit_of_measure = $_REQUEST['unit_of_measure'];
			$stmt = $db->prepare("UPDATE gantt_resources SET name='$name',group_id='$group_id',company='$company',notes='$notes',cost_rate='$cost_rate',calendar_id='$calendar_id',unit_of_measure='$unit_of_measure' WHERE id='$resource_id' AND programme_id='$programme_id'");
			$stmt->execute();
			$payload = array("resource_updated" => true);
			echo json_encode($payload);
			break;
			die();

























		case "determine_existing_links":

			$source_task = $_REQUEST['source'];
			$target_task = $_REQUEST['target'];

			$link_valid = true;

			$sql = "SELECT id FROM gantt_links WHERE source='$source_task'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			if ($stmt->rowCount() > 0) {
				$link_valid = false;
			}

			$sql = "SELECT id FROM gantt_links WHERE target='$target_task'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			if ($stmt->rowCount() > 0) {
				$link_valid = false;
			}


			$payload = array("link_can_be_created" => $link_valid);
			echo json_encode($payload);

			break;
			die();

		case "get_programme_contributors":



			$token = $_REQUEST['token'];
			if ($token == $admin_auth_token) {
				$sql = "SELECT * FROM gantt_contributor_history WHERE programme_id='$programme_id' ORDER BY last_heartbeat DESC";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$contributors = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$payload = array("contributors" => $contributors);
				echo json_encode($payload);
			} else {
				die();
			}


			break;
			die();



		case "set_save_time":

			$time = $_REQUEST['time'];
			$sql = "UPDATE gantt_programmes SET last_save_time='$time' WHERE id='$programme_id'";

			$stmt = $db->prepare($sql);
			$stmt->execute();
			$payload = array("save_time_set" => $time);
			echo json_encode($payload);

			break;
			die();


		case "set_demo_user_name":

			if ($_REQUEST['guid']) {
				$guid = $_REQUEST['guid'];
				$sql = "SELECT * FROM gantt_programmes WHERE sharing_identifier='$guid'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$programme = $stmt->fetch(PDO::FETCH_ASSOC);
				$programme_id = $programme['identifier'];
			}

			$time = time();
			$name = $_REQUEST['name'];
			$user_id = $_COOKIE['ibex_demo_user_id'];
			$guid = generateGUID();
			$stmt = $db->prepare("INSERT INTO gantt_contributors(programme_id,user_id,name,guid) VALUES (?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $user_id);
			$stmt->bindParam(3, $name);
			$stmt->bindParam(4, $guid);
			$stmt->execute();

			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
			$stmt = $db->prepare("INSERT INTO gantt_contributor_history(programme_id,contributor_id,contributor_name,ip_address,guid,created) VALUES (?,?,?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $user_id);
			$stmt->bindParam(3, $name);
			$stmt->bindParam(4, $ip_address);
			$stmt->bindParam(5, $guid);
			$stmt->bindParam(6, $time);
			$stmt->execute();

			setcookie('ibex_demo_first_time_login', 0, strtotime('today 23:59'), '/');
			setcookie('ibex_demo_name', $name, strtotime('today 23:59'), '/');
			setcookie('ibex_demo_contributor_guid', $guid, strtotime('today 23:59'), '/');

			$payload = array("demo_name_set" => true);


			break;
			die();




		case "get_dashboard_resource_calculations":

			$task_id = $_REQUEST['task_id'];

			// Check within my prog

			$task_object = array();

			$sql = "SELECT * FROM gantt_tasks WHERE id='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task_object['task'] = $stmt->fetch(PDO::FETCH_ASSOC);

			$task_calendar_id = $task_object['task']['calendar_id'];
			$sql = "SELECT * FROM gantt_calendars WHERE id='$task_calendar_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$calendar = $stmt->fetch(PDO::FETCH_ASSOC);
			$task_object['calendar'] = $calendar;

			$sql = "SELECT * FROM gantt_calendar_overrides WHERE calendar_id='$task_calendar_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$calendar_overrides = $stmt->fetch(PDO::FETCH_ASSOC);
			$task_object['calendar_overrides'] = $calendar_overrides;

			$sql = "SELECT * FROM gantt_resources WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$sql = "SELECT * FROM gantt_resource_groups WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resource_groups = $stmt->fetchAll(PDO::FETCH_ASSOC);



			$sql = "SELECT duration_worked FROM gantt_tasks WHERE type='task' AND programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$duration_worked = $stmt->fetch(PDO::FETCH_ASSOC);
			$task_object['duration_worked'] = $duration_worked;




			$payload = array($task_object);
			echo json_encode($payload);

			break;
			die();




		case "get_schedule_history":

			$history_array = array();
			$stmt = $db->prepare("SELECT * FROM gantt_scheduler_history WHERE programme_id='$programme_id' ORDER by id DESC");
			$stmt->execute();
			$histories = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($histories as $history) {

				$user = $history['author'];

				if ($history['object_type'] == "1") {
					// link
					$link_id = $history['link_id'];
					$sql = "select * FROM gantt_links WHERE id='$link_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$link = $stmt->fetch(PDO::FETCH_ASSOC);

					$source_task_id = $link['source'];
					$target_task_id = $link['target'];

					$sql = "select text FROM gantt_tasks WHERE id='$source_task_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$task = $stmt->fetch(PDO::FETCH_ASSOC);
					$task_1_text = $task['text'];


					$sql = "select text FROM gantt_tasks WHERE id='$target_task_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$task = $stmt->fetch(PDO::FETCH_ASSOC);
					$task_2_text = $task['text'];



					$history_array_local = array("detail" => "<span>'" . $user . "'<span> edited the link between <span>'" . $task_1_text . "'<span> and <span>'" . $task_2_text . "'<span>.<br> Lag is " . $history['lag'] . " and Lead is " . $history['lead'] . ".<br>Notes: <span>'" . $history['details']  . "'<span>", "created" => $history['created']);

					$history_array[] = $history_array_local;
				}

				if ($history['object_type'] == "2") {
					// task

					$task_id = $history['task_id'];
					$sql = "select text FROM gantt_tasks WHERE id='$task_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$task = $stmt->fetch(PDO::FETCH_ASSOC);
					$task_text = $task['text'];


					$history_array_local = array("detail" => "<span>'" . $user . "'<span> edited the length of task <span>'" . $task_text . ".'<span><br> Duration was " . $history['duration_before'] . " and is now " . $history['duration_after'] . ".<br>Notes: <span>'" . $history['details'] . "'<span>", "created" => $history['created']);

					$history_array[] = $history_array_local;
				}

				if ($history['object_type'] == "3") {
					// task

					$task_id = $history['task_id'];
					$sql = "select text FROM gantt_tasks WHERE id='$task_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$task = $stmt->fetch(PDO::FETCH_ASSOC);
					$task_text = $task['text'];


					$history_array_local = array("detail" => "<span>'" . $user . "'<span> edited the start of task <span>'" . $task_text . ".'<span><br> Start was " . $history['date_before'] . " and is now " . $history['date_after'] . ".<br>Notes: <span>'" . $history['details'] . "'<span>", "created" => $history['created']);

					$history_array[] = $history_array_local;
				}
			}
			$payload = array("history" => $history_array);

			echo json_encode($payload);

			break;
			die();



		case "add_to_schedule_history":

			$object_type = $_REQUEST['object_type'];
			$details = $_REQUEST['details'];
			$author = $_COOKIE['ibex_demo_name'];
			$created = time();

			if ($object_type == 1) {
				$link_id = $_REQUEST['link_id'];
				$lag = $_REQUEST['lag'];
				$lead = $_REQUEST['lead'];

				$stmt = $db->prepare("INSERT INTO gantt_scheduler_history(programme_id,object_type,link_id,details,lag,lead,author,created) VALUES (?,?,?,?,?,?,?,?)");
				$stmt->bindParam(1, $programme_id);
				$stmt->bindParam(2, $object_type);
				$stmt->bindParam(3, $link_id);
				$stmt->bindParam(4, $details);
				$stmt->bindParam(5, $lag);
				$stmt->bindParam(6, $lead);
				$stmt->bindParam(7, $author);
				$stmt->bindParam(8, $created);
			}

			if ($object_type == 2) {
				$task_id = $_REQUEST['task_id'];
				$duration_before = $_REQUEST['duration_before'];
				$duration_after = $_REQUEST['duration_after'];

				$stmt = $db->prepare("INSERT INTO gantt_scheduler_history(programme_id,object_type,task_id,details,duration_before,duration_after,author,created) VALUES (?,?,?,?,?,?,?,?)");
				$stmt->bindParam(1, $programme_id);
				$stmt->bindParam(2, $object_type);
				$stmt->bindParam(3, $task_id);
				$stmt->bindParam(4, $details);
				$stmt->bindParam(5, $duration_before);
				$stmt->bindParam(6, $duration_after);
				$stmt->bindParam(7, $author);
				$stmt->bindParam(8, $created);
			}


			if ($object_type == 3) {
				$task_id = $_REQUEST['task_id'];
				$date_before = $_REQUEST['date_before'];
				$date_after = $_REQUEST['date_after'];

				$stmt = $db->prepare("INSERT INTO gantt_scheduler_history(programme_id,object_type,task_id,details,date_before,date_after,author,created) VALUES (?,?,?,?,?,?,?,?)");
				$stmt->bindParam(1, $programme_id);
				$stmt->bindParam(2, $object_type);
				$stmt->bindParam(3, $task_id);
				$stmt->bindParam(4, $details);
				$stmt->bindParam(5, $date_before);
				$stmt->bindParam(6, $date_after);
				$stmt->bindParam(7, $author);
				$stmt->bindParam(8, $created);
			}


			$stmt->execute();

			$payload = array("update_added" => true);
			echo json_encode($payload);

			break;
			die();




		case "set_ui_order":


			$data = json_decode($_REQUEST['data'], true);

			foreach ($data as $order) {
				$index = $order['index'];
				$id = $order['id'];
				$sql = "UPDATE gantt_tasks SET order_ui='$index' WHERE programme_id='$programme_id' AND id='$id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
			}



			break;
			die();

		case "get_columns":
			$user_id = $_SESSION['user']['id'];
			$stmt = $db->prepare("SELECT column_data FROM gantt_columns WHERE user_id='$user_id'");
			$stmt->execute();
			$column_data = $stmt->fetch(PDO::FETCH_ASSOC);

			$payload = array("columns" => $column_data);

			echo json_encode($payload);

			break;
			die();



		case "get_activities2":


			$activities_array = array();
			$stmt = $db->prepare("SELECT * FROM gantt_activities WHERE programme_id='$programme_id' AND active='1' ORDER by id DESC LIMIT 100");
			$stmt->execute();
			$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($activities as $activity) {
				$user_id = $activity['user_id'];
				$stmt = $db->prepare("SELECT first_name,last_name FROM gantt_users WHERE account_id='$account_id' AND id='$user_id' AND active = '1'");
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_ASSOC);


				$activity['user_name'] = $user['first_name'] . " " . $user['last_name'];
				$activities_array[] = $activity;
			}


			$payload = array("activities" => $activities_array);

			echo json_encode($payload);

			break;
			die();


		case "get_activities_admin":

			$programme_id_local = $_REQUEST['programme_id'];
			$activities_array = array();
			$stmt = $db->prepare("SELECT * FROM gantt_activities WHERE programme_id='$programme_id_local' AND active='1' ORDER by id DESC LIMIT 20");
			$stmt->execute();
			$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($activities as $activity) {
				$user_id = $activity['user_id'];
				$stmt = $db->prepare("SELECT first_name,last_name FROM gantt_users WHERE id='$user_id' AND active = '1'");
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_ASSOC);


				$activity['user_name'] = $user['first_name'] . " " . $user['last_name'];
				$activities_array[] = $activity;
			}
			$payload = array("activities" => $activities_array);

			echo json_encode($payload);

			break;
			die();



		case "delete_support_attachment":
			$attachment_id = $_REQUEST['id'];
			$stmt = $db->prepare("SELECT account_id FROM gantt_support_ticket_attachments WHERE id='$attachment_id'");
			$stmt->execute();
			$attachment = $stmt->fetch(PDO::FETCH_ASSOC);
			$account_id_attachment = $attachment['account_id'];

			if ($_SESSION['user']['account_id'] == $account_id_attachment) {
				$stmt = $db->prepare("DELETE FROM gantt_support_ticket_attachments WHERE id='$attachment_id'");
				$stmt->execute();
			}

			$payload = array("attachment_deleted" => true);
			echo json_encode($payload);


			break;
			die();


		case "get_support_attachments":


			// Get download URL 
			$stmt = $db->prepare("SELECT subdomain FROM gantt_accounts WHERE id='$account_id'");
			$stmt->execute();
			$account = $stmt->fetch(PDO::FETCH_ASSOC);
			$subdomain = $account['subdomain'];

			$guid = $_REQUEST['guid'];

			$attachments_array = array();
			$stmt = $db->prepare("SELECT * FROM gantt_support_ticket_attachments WHERE account_id='$account_id' AND ticket_guid='$guid' ORDER by uploaded DESC");
			$stmt->execute();
			$attachments = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($attachments as $attachment) {
				$attachment['url'] = "https://" . $subdomain . ".ibex.software/programmes/support-attachments/" . $attachment['attachment_hash'];
				$attachments_array[] = $attachment;
			}
			$payload = array("support_attachments" => $attachments_array);

			echo json_encode($payload);

			break;
			die();


		case "upload_support_attachment":


			$guid = $_REQUEST['guid'];

			$filename_existing = $_FILES['attachment']['name'];

			$extension = pathinfo($filename_existing, PATHINFO_EXTENSION);
			$filename = hash('sha256', time() + md5(rand(0, 999999999999))) . "." . $extension;
			move_uploaded_file($_FILES['attachment']['tmp_name'], '/var/www/html/ibex-production-mmb-basic/programmes/support-attachments/' . $filename);

			$uploaded = time();

			$stmt = $db->prepare("INSERT INTO gantt_support_ticket_attachments(account_id,ticket_guid,attachment_name,attachment_hash,uploaded) VALUES (?,?,?,?,?)");
			$stmt->bindParam(1, $account_id);
			$stmt->bindParam(2, $guid);
			$stmt->bindParam(3, $filename_existing);
			$stmt->bindParam(4, $filename);
			$stmt->bindParam(5, $uploaded);

			$stmt->execute();
			$attachment_id = $db->lastInsertId();

			// Get download URL 
			$stmt = $db->prepare("SELECT subdomain FROM gantt_accounts WHERE id='$account_id'");
			$stmt->execute();
			$account = $stmt->fetch(PDO::FETCH_ASSOC);
			$subdomain = $account['subdomain'];


			$payload = array("attachment_uploaded" => true, "attachment_id" => $attachment_id, "attachment_name" => $filename_existing, "attachment_url" => "https://" . $subdomain . ".ibex.software/programmes/support-attachments/" . $filename);
			echo json_encode($payload);



			break;
			die();





		case "upload_conversation_attachment":


			$recipient_id = $_REQUEST['recipient_id'];
			$sender_id = $_SESSION['user']['id'];
			$created = time();



			$filename_existing = $_FILES['attachment']['name'];
			$text = $filename_existing;
			$type = "2";

			$extension = pathinfo($filename_existing, PATHINFO_EXTENSION);
			$filename = hash('sha256', time() + md5(rand(0, 999999999999))) . "." . $extension;
			move_uploaded_file($_FILES['attachment']['tmp_name'], '/var/www/html/ibex-production-mmb-basic/assets/conversation-attachments/' . $filename);

			$sql = "SELECT account_id FROM gantt_users WHERE id='$recipient_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_ASSOC);

			$account_id = $user['account_id'];
			$sql = "SELECT subdomain FROM gantt_accounts WHERE id='$account_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$account = $stmt->fetch(PDO::FETCH_ASSOC);



			$url = "https://" . $account['subdomain'] . ".ibex.software/assets/conversation-attachments/" . $filename;




			if ($user['account_id'] == $_SESSION['user']['account_id']) {




				$stmt = $db->prepare("INSERT INTO gantt_conversations(programme_id,sender_id,recipient_id,text,created,type,url,name) VALUES (?,?,?,?,?,?,?,?)");
				$stmt->bindParam(1, $programme_id);
				$stmt->bindParam(2, $sender_id);
				$stmt->bindParam(3, $recipient_id);
				$stmt->bindParam(4, $text);
				$stmt->bindParam(5, $created);
				$stmt->bindParam(6, $type);
				$stmt->bindParam(7, $url);
				$stmt->bindParam(8, $filename_existing);

				$stmt->execute();

				$payload = array("attachment_url" => $url);
				echo json_encode($payload);
			} else {
				$payload = array("attachment_url" => null);
				echo json_encode($payload);
			}

			break;
			die();


		case "update_conversation_as_read":

			$sender_id = $_REQUEST['recipient_id'];
			if ($_SESSION['demo_session'] == "true") {
				$recipient_id = $_COOKIE['ibex_demo_user_id'];
			} else {
				$recipient_id = $_SESSION['user']['id'];
			}


			$stmt = $db->prepare("UPDATE gantt_conversations SET is_read='1' WHERE recipient_id='$recipient_id' AND sender_id='$sender_id'");
			$stmt->execute();



			break;
			die();


		case "set_sortorder_as_id":

			$id = $_REQUEST['id'];

			$stmt = $db->prepare("UPDATE gantt_tasks SET sortorder='$id' WHERE id='$id'");
			$stmt->execute();
			$payload = array("sortorder_updated" => true);
			echo json_encode($payload);
			break;
			die();


		case "get_conversations":

			$recipient_id_global = $_REQUEST['recipient_id'];

			if ($_SESSION['demo_session'] == true) {
				$sender_id = $_COOKIE['ibex_demo_user_id'];
			} else {
				$sender_id = $_SESSION['user']['id'];
			}

			$conversations_array = array();

			$stmt = $db->prepare("SELECT * FROM gantt_conversations WHERE programme_id='$programme_id' AND (sender_id='$sender_id' AND recipient_id='$recipient_id_global') OR (sender_id='$recipient_id_global' AND recipient_id='$sender_id') ORDER by id ASC");
			$stmt->execute();
			$conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($conversations as $conversation) {

				$datetime_created = date('d/m/Y H:i', $conversation['created']);
				$date_created = date('d/m/Y', $conversation['created']);
				$time_created = date('H:i', $conversation['created']);
				$created_today = false;
				if (date('d/m/Y', $conversation['created']) == date('d/m/Y')) {
					$created_today = true;
				}

				//$dt = new DateTime($conversation['created'], new DateTimeZone('UTC'));
				// change the timezone of the object without changing it's time
				//$dt->setTimezone(new DateTimeZone('Europe/London'));
				// format the datetime
				//$conversation['created_time'] = $dt->format('H:i');




				$conversation['created_datetime'] = $datetime_created;
				$conversation['created_date'] = $date_created;
				$conversation['created_time'] = $time_created;
				$conversation['created_today'] = $created_today;

				$sender_id = $conversation['sender_id'];
				$sql = "SELECT id,first_name,last_name,guid FROM gantt_users WHERE id='$sender_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$sender = $stmt->fetch(PDO::FETCH_ASSOC);
				$sender['avatar_url'] = "https://demo.ibex.software/img/user.png";

				$conversation['sender_is_self'] = false;
				if ($_SESSION['demo_session'] == true) {
					if ($sender_id == $_COOKIE['ibex_demo_user_id']) {
						$conversation['sender_is_self'] = true;
					}
				} else {

					if ($sender_id == $_SESSION['user']['id']) {
						$conversation['sender_is_self'] = true;
					}
				}

				$recipient_id = $conversation['recipient_id'];
				$sql = "SELECT id,first_name,last_name,guid FROM gantt_users WHERE id='$recipient_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$recipient = $stmt->fetch(PDO::FETCH_ASSOC);
				$recipient['avatar_url'] = "https://demo.ibex.software/img/user.png";

				$conversation['sender'] = $sender;
				$conversation['recipient'] = $recipient;

				$conversations_array[] = $conversation;


				// Update so we now have no new convos


			}
			$payload = array("conversations" => $conversations_array);
			echo json_encode($payload);



			break;
			die();



		

		case "add_new_link":

			$source_id = $_REQUEST['source'];
			$target_id = $_REQUEST['target'];

			$sql = "SELECT guid FROM gantt_tasks WHERE id='$source_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$source_guid = $task['guid'];

			$sql = "SELECT guid FROM gantt_tasks WHERE id='$target_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$target_guid = $task['guid'];

			$type = "0";

			$stmt = $db->prepare("INSERT INTO gantt_links (programme_id,source,source_guid,target,target_guid,type) VALUES  (?,?,?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $source_id);
			$stmt->bindParam(3, $source_guid);
			$stmt->bindParam(4, $target_id);
			$stmt->bindParam(5, $target_guid);
			$stmt->bindParam(6, $type);
			$stmt->execute();

			$payload = array("link_inserted" => true);
			echo json_encode($payload);




			break;
			die();

		case "reassign_task_links":

			$source_guid = $_REQUEST['source'];
			$target_guid = $_REQUEST['target'];
			$type = $_REQUEST['type'];

			$sql = "SELECT id FROM gantt_tasks WHERE guid='$source_guid'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$source_task_id = $task['id'];

			$sql = "SELECT id FROM gantt_tasks WHERE guid='$target_guid'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$target_task_id = $task['id'];


			$stmt = $db->prepare("UPDATE gantt_links SET source='$source_task_id',target='$target_task_id' WHERE programme_id='$programme_id' AND source_guid='$source_guid' AND target_guid='$target_guid'");
			$stmt->execute();




			$payload = array("source_task_id" => $source_task_id, "target_task_id" => $target_task_id, "source_task_guid" => $source_guid, "target_task_guid" => $target_guid);
			echo json_encode($payload);


			break;
			die();



		case "get_team_member":


			$users_array = array();
			$id = $_REQUEST['id'];
			$sql = "SELECT id,first_name,last_name,email_address,last_programme_id,heartbeat_time FROM users WHERE id='$id' AND last_programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_ASSOC);


			$user_id = $user['id'];

			$sql = "SELECT * FROM gantt_user_groups_links WHERE user_id='$user_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$links = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$groups = array();
			foreach ($links as $link) {
				$link_id = $link['user_group_id'];
				$sql = "SELECT * FROM gantt_user_groups WHERE id='$link_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$group = $stmt->fetch(PDO::FETCH_ASSOC);

				$groups[] = array("id" => $link_id, "name" => $group['name']);
			}
			$user['groups'] = $groups;


			// Get all groups
			$sql = "SELECT * FROM gantt_user_groups WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);






			$payload = array("team_member" => $user, "available_groups" => $groups, "self_id" => $_SESSION['user']['id']);
			echo json_encode($payload);

			die();

		case "get_user_groups":


			// Get all groups
			$groups_array = [];
			$sql = "SELECT * FROM gantt_user_groups WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($groups as $group) {
				$members = [];
				// Get member string 
				$group_id = $group['id'];
				$sql = "SELECT user_id FROM gantt_user_groups_links WHERE user_group_id='$group_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$links = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($links as $link) {

					$user_id = $link['user_id'];
					$sql = "SELECT id,first_name FROM users WHERE id='$user_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$user = $stmt->fetch(PDO::FETCH_ASSOC);
					if ($user['id'] == $_SESSION['user']['id']) {
						$user_name = "You";
					} else {
						$user_name = $user['first_name'];
					}
					if ($user_name != "" && $user_name != null) {
						$members[] = $user_name;
					}
				}
				$group['members'] = $members;
				$groups_array[] = $group;
			}

			// Now get the groups I am a member of and get highest permission

			$links_array = [];
			$array_key_maps;
			$sql = "SELECT user_group_id FROM gantt_user_groups_links WHERE user_id= :user_id";
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':user_id', $_SESSION['user']['id']);
			$stmt->execute();
			$links = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($links as $link) {
				$sql = "SELECT * FROM gantt_user_groups WHERE id= :id";
				$stmt = $db->prepare($sql);
				$stmt->bindValue(':id', $link['user_group_id']);
				$stmt->execute();
				$link_object = $stmt->fetch(PDO::FETCH_ASSOC);
				unset($link_object['id']);
				unset($link_object['programme_id']);
				unset($link_object['name']);
				unset($link_object['is_admin_group']);

				$links_array[] = $link_object;
				$array_key_maps = array_keys($link_object);
			}

			$order_array = [];
			// Push zero index to order array
			foreach ($array_key_maps as $key) {
				$order_array[$key] = 0;
			}

			// Loop through key maps and get highest
			foreach ($links_array as $link_array) {
				foreach ($array_key_maps as $key) {
					$value_get = intval($link_array[$key]);
					$value_compare = intval($order_array[$key]);
					if ($value_get > $value_compare) {
						$order_array[$key] = (string)$value_get;
					}
				}
			}

			$payload = array("groups" => $groups_array, "self_groups" => $order_array);
			echo json_encode($payload);
			die();


		case "get_group_permission":

			// Get all groups
			$sql = "SELECT * FROM gantt_user_groups WHERE programme_id='$programme_id' AND id= :id";
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':id', $_REQUEST['id']);
			$stmt->execute();
			$group = $stmt->fetch(PDO::FETCH_ASSOC);
			$payload = array("group" => $group);
			echo json_encode($payload);
			die();

		case "add_user_to_group":


			$user_id = $_REQUEST['user_id'];
			$group_id = $_REQUEST['group_id'];

			$stmt = $db->prepare("INSERT INTO gantt_user_groups_links (user_id,user_group_id) VALUES  (?,?)");
			$stmt->bindParam(1, $user_id);
			$stmt->bindParam(2, $group_id);
			$stmt->execute();




			break;
			die();


		case "remove_user_from_group":


			$user_id = $_REQUEST['user_id'];
			$group_id = $_REQUEST['group_id'];

			$stmt = $db->prepare("DELETE FROM gantt_user_groups_links WHERE user_id='$user_id' AND user_group_id = '$group_id'");
			$stmt->execute();




			break;
			die();

		case "save_user":
			$id = $_REQUEST['id'];
			$first_name = $_REQUEST['first_name'];
			$last_name = $_REQUEST['last_name'];
			$sql = "UPDATE users SET first_name='$first_name',last_name='$last_name' WHERE id='$id' AND last_programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$payload = array("processed" => true);
			echo json_encode($payload);

			break;
			die();

		case "add_user":


			$groups = json_decode($_REQUEST['user_groups']);


			// Insert user
			// Insert new user into db
			$invite_code = mt_rand(1000, 9999);



			$created = time();

			// Insert new user 
			$stmt = $db->prepare("INSERT INTO users(first_name,last_name,email_address,invite_code,created,last_programme_id) VALUES (?,?,?,?,?,?)");
			$stmt->bindParam(1, $_REQUEST['first_name']);
			$stmt->bindParam(2, $_REQUEST['last_name']);
			$stmt->bindParam(3, $_REQUEST['email_address']);
			$stmt->bindParam(4, $invite_code);
			$stmt->bindParam(5, $created);
			$stmt->bindParam(6, $programme_id);
			$stmt->execute();
			$user_id_new = $db->lastInsertId();

			// Set up user prog links
			// Based on mine
			$user_id = $_SESSION['user']['id'];
			$stmt = $db->prepare("SELECT * FROM gantt_user_programme_links WHERE user_id='$user_id' AND programme_id = '$programme_id'");
			$stmt->execute();
			$link = $stmt->fetch(PDO::FETCH_ASSOC);
			$date_start = $link['date_range_start'];
			$date_end = $link['date_range_end'];

			$type = "1";
			$stmt = $db->prepare("INSERT INTO gantt_user_programme_links(user_id,programme_id,permission_type,date_range_start,date_range_end) VALUES (?,?,?,?,?)");
			$stmt->bindParam(1, $user_id_new);
			$stmt->bindParam(2, $programme_id);
			$stmt->bindParam(3, $type);
			$stmt->bindParam(4, $date_start);
			$stmt->bindParam(5, $date_end);

			$stmt->execute();





			// Set up columns on this prog
			$stmt = $db->prepare("INSERT INTO gantt_columns(programme_id,user_id) VALUES (?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $user_id_new);

			$stmt->execute();





			// Set up columns on this prog
			$stmt = $db->prepare("INSERT INTO gantt_columns(programme_id,user_id) VALUES (?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $user_id_new);

			$stmt->execute();


			// Link to groups
			foreach ($groups as $group) {
				$stmt = $db->prepare("INSERT INTO gantt_user_groups_links(user_id,user_group_id) VALUES (?,?)");
				$stmt->bindParam(1, $user_id_new);
				$stmt->bindParam(2, $group);
				$stmt->execute();
			}

			// Get subdomain
			$stmt = $db->prepare("SELECT subdomain FROM accounts WHERE id='$account_id'");
			$stmt->execute();
			$account = $stmt->fetch(PDO::FETCH_ASSOC);
			$subdomain = $account['subdomain'];


			// Auth
			$auth_link = "auth.php?email_address=" . $email_address . "&token=" . $invite_token;


			// Email admin
			$config = array();
			$config['api_key'] = "key-86929a614db601844f2de754bf641f80";
			$config['api_url'] = "https://api.mailgun.net/v3/ibex.software/messages";

			$message = array();
			$message['from'] = "Ibex <support@ibex.software>";
			$message['to'] = $_REQUEST['email_address'];
			$message['subject'] = "Welcome to Ibex";

			$content = file_get_contents('../email-templates/auth_link.html');
			$content = str_replace("{{INVITED_BY_NAME}}", $_SESSION['user']['first_name'] . " " . $_SESSION['user']['last_name'], $content);
			$content = str_replace("{{AUTH_URL}}", $auth_link, $content);

			$message['html'] = $content;

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $config['api_url']);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "api:{$config['api_key']}");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $message);

			$result = curl_exec($ch);


			curl_close($ch);


			$payload = array("processed" => true);
			echo json_encode($payload);



			break;
			die();


		case "delete_user":

			$stmt = $db->prepare("DELETE FROM users WHERE last_programme_id='$programme_id' AND id= :id");
			$stmt->bindValue(':id', $_REQUEST['id']);
			$stmt->execute();
			$payload = array("processed" => true);
			echo json_encode($payload);

			break;
			die();



		case "get_team_members":


			$users_array = array();

			$sql = "SELECT id,first_name,last_name,email_address,last_programme_id,heartbeat_time FROM users WHERE last_programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

			foreach ($users as $user) {
				$user_id = $user['id'];

				$sql = "SELECT * FROM gantt_user_groups_links WHERE user_id='$user_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$links = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$groups = array();
				foreach ($links as $link) {
					$link_id = $link['user_group_id'];
					$sql = "SELECT * FROM gantt_user_groups WHERE id='$link_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$group = $stmt->fetch(PDO::FETCH_ASSOC);

					$groups[] = array("id" => $link_id, "name" => $group['name']);
				}

				$user['groups'] = $groups;
				$users_array[] = $user;
			}
			$payload = array("team_members" => $users_array);
			echo json_encode($payload);

			die();

		case "heartbeat":

			// Update user heartbeat
			$time = time();

			if (!isset($_SESSION['user']['id'])) {
				$payload = array("heartbeat" => false);
				echo json_encode($payload);
				die();
			} else {

				$data = json_decode($_REQUEST['data'], true);

				foreach ($data as $order) {
					$index = $order['index'];
					$id = $order['id'];
					$sql = "UPDATE gantt_tasks SET order_ui='$index' WHERE programme_id='$programme_id' AND id='$id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
				}




				// Update pending file inserts

				$sql = "SELECT * FROM gantt_files_pending_insert WHERE programme_id='$programme_id' AND processed='0'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$to_process = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($to_process as $process) {
					$task_guid = $process['task_guid'];
					$this_id = $process['id'];
					$files = $process['encoded_files'];
					// Got task?
					$sql = "SELECT * FROM gantt_tasks WHERE guid='$task_guid'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$count_task = $stmt->rowCount();
					if ($count_task > 0) {
						$task = $stmt->fetch(PDO::FETCH_ASSOC);
						$task_id = $task['id'];
						// Update
						$sql = "UPDATE gantt_tasks SET files='$files' WHERE id='$task_id'";
						$stmt = $db->prepare($sql);
						$stmt->execute();

						$sql = "UPDATE gantt_files_pending_insert SET processed='1' WHERE id='$this_id'";
						$stmt = $db->prepare($sql);
						$stmt->execute();
					}
				}





				// Update duplicates?
				$sql = "DELETE u1 FROM gantt_versions u1, gantt_versions u2 WHERE u1.id < u2.id AND u1.created = u2.created AND u1.programme_id = u2.programme_id AND u1.primary_object_guid = u2.primary_object_guid";
				$stmt = $db->prepare($sql);
				$stmt->execute();



				// Update prog versions that need finalising
				$sql = "SELECT id,ui_string,primary_object_guid FROM gantt_versions WHERE programme_id='$programme_id' AND to_finalise='1'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$to_finalises = $stmt->fetchAll(PDO::FETCH_ASSOC);


				foreach ($to_finalises as $item) {
					$id = $item['id'];
					$current_text = $item['ui_string'];
					$guid = $item['primary_object_guid'];
					$sql = "SELECT `text` FROM gantt_tasks WHERE guid ='$guid'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$task = $stmt->fetch(PDO::FETCH_ASSOC);
					$task_name = $task['text'];

					$ui_string_new = $current_text . "<span>'" . $task_name . "'<span>";
					$sql = "UPDATE gantt_versions SET to_finalise='0' WHERE id='$id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
				}


				$user_id = $_SESSION['user']['id'];
				$unread_messages = false;

				$sql = "UPDATE users SET heartbeat_time='$time' WHERE id='$user_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();

				$sql = "SELECT t1.*,t2.first_name,t2.last_name FROM gantt_programmes t1 LEFT JOIN users t2 on t1.last_save_author_id = t2.id WHERE t1.id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$programme = $stmt->fetch(PDO::FETCH_ASSOC);

				// Messages
				$sql = "SELECT id FROM gantt_messages WHERE programme_id='$programme_id' AND recipient_id='$user_id' AND unread='1'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$count_messages = $stmt->rowCount();
				if ($count_messages > 0) {
					$unread_messages = true;
				}




				$contacts = array();
				$sql = "SELECT t1.*,t2.first_name,t2.last_name,t2.avatar_url FROM gantt_user_programme_links t1 LEFT JOIN users t2 on t2.id = t1.user_id WHERE t1.programme_id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$links = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$support_user = array("id" => "0", "user_id" => "0", "programme_id" => $programme_id, "first_name" => "Ibex-pph", "last_name" => "Support", "avatar_url" => 'https://beta.ibex.software/img/logo.png');
				array_push($links, $support_user);
				foreach ($links as $link) {
					// Get unread message count from this sender
					$contact_id = $link['user_id'];
					$my_id = $_SESSION['user']['id'];
					$sql = "SELECT id FROM gantt_messages WHERE programme_id='$programme_id' AND sender_id='$contact_id' AND recipient_id='$my_id' AND unread='1'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$unread_count = $stmt->rowCount();
					if ($contact_id != $_SESSION['user']['id']) {
						$local_object = array("contact_id" => $contact_id, "contact_name" => $link['first_name'] . " " . $link['last_name'], "unread_messages" => $unread_count, "avatar_url" => $link['avatar_url']);
						$contacts[] = $local_object;
					}
				}


				if ($programme['last_save_author_id'] != $_SESSION['user']['id']) {

					$diff = true;
				} else {
					$diff = false;
				}



				$payload = array("heartbeat" => true, "undo_redo_version" => $programme['undo_redo_version_id'], "current_version" => $programme['current_version_id'], "last_save_time" => $programme['last_save_time'], "last_save_author" => $programme['first_name'] . " " . $programme['last_name'], "last_save_author_id_diff" => $diff, "unread_messages" => $unread_messages, "contacts" => $contacts);
				echo json_encode($payload);
				die();
			}
			die();
			$user_id = $_SESSION['user']['id'];


			$unread_messages = false;

			$sql = "UPDATE users SET heartbeat_time='$time' WHERE id='$user_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();



			// Is there anyone else in the gantt - we best warn if so!
			$sql = "SELECT id,first_name,last_name,heartbeat_time,guid FROM users WHERE last_programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			if ($stmt->rowCount() > 0) {
				// Get times of others and details of everyone
				$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

				foreach ($users as $user) {
					$last_heartbeat_seconds = time() - $user['last_heartbeat'];
					if ($last_heartbeat_seconds < 120) {
						$users_array[] = $user;
					}
				}

				// are there background changes that this user has not seen?
				$sql_changes = "select last_save_time FROM gantt_programmes WHERE id='$programme_id'";
				$stmt_changes = $db->prepare($sql_changes);
				$stmt_changes->execute();
				$programme = $stmt_changes->fetch(PDO::FETCH_ASSOC);
				$last_save_time = $programme['last_save_time'];
			}


			// Any unread messages
			if ($_SESSION['demo_session'] == "true") {
				$user_id = $_COOKIE['ibex_demo_user_id'];
			} else {
				$user_id = $_SESSION['user']['id'];
			}

			$senders_array = array();
			$sql_messages = "select sender_id FROM gantt_conversations WHERE recipient_id ='$user_id' AND is_read='0'";
			$stmt_messages = $db->prepare($sql_messages);
			$stmt_messages->execute();
			if ($stmt_messages->rowCount() > 0) {
				$senders = $stmt_messages->fetchAll(PDO::FETCH_ASSOC);

				$unread_messages = true;
			} else {
				$senders = null;
			}




			if ($stmt->rowCount() > 0) {
				$payload = array("additional_users_count" => $count_users, "additional_users" => $users_array, 'last_save_time' => $last_save_time, 'unread_messages' => $unread_messages, "sender_ids" => $senders);
				echo json_encode($payload);
				die();
			} else {
				$payload = array("additional_users_count" => 0, 'unread_messages' => $unread_messages, "sender_ids" => $senders);
				echo json_encode($payload);
				die();
			}







			break;
			die();


		case "generate_share_link":


			$sharing_identifier = generateGUID();
			$sql = "UPDATE programmes SET sharing_identifier='$sharing_identifier' WHERE id='$programme_id'";
			$stmt = $DB_con->prepare($sql);
			$stmt->execute();

			$payload = array("share_link" => "https://share.ibex.software?id=" . $sharing_identifier);
			echo json_encode($payload);



			break;
			die();


		case "report_error":




			$sql = "SELECT * FROM gantt_calendars WHERE programme_id ='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$calendars = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$payload_calendars = array("calendars" => $calendars);
			$calendar_data = json_encode($payload_calendars);

			//2. Settings
			$sql = "SELECT * FROM gantt_settings WHERE programme_id ='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$settings = $stmt->fetch(PDO::FETCH_ASSOC);

			$settings_only = $settings['settings_json'];
			$columns_only = $settings['columns_json'];
			$columns_resources_only = $settings['columns_resources_json'];


			$account_id = $_SESSION['user']['account_id'];
			$user_id = $_SESSION['user']['id'];
			$error_message = $_REQUEST['error_message'];
			$gantt_data = $_REQUEST['data'];

			$config_data = $_REQUEST['configData'];


			$created = time();

			$stmt = $db->prepare("INSERT INTO gantt_errors (account_id,programme_id,user_id,error_message,gantt_data,config_data,calendar_data,settings_data,columns_data,columns_resources_data,created) VALUES  (?,?,?,?,?,?,?,?,?,?,?)");
			$stmt->bindParam(1, $account_id);
			$stmt->bindParam(2, $programme_id);
			$stmt->bindParam(3, $user_id);
			$stmt->bindParam(4, $error_message);
			$stmt->bindParam(5, $gantt_data);
			$stmt->bindParam(6, $config_data);
			$stmt->bindParam(7, $calendar_data);
			$stmt->bindParam(8, $settings_only);
			$stmt->bindParam(9, $columns_only);
			$stmt->bindParam(10, $columns_resources_only);
			$stmt->bindParam(11, $created);
			$stmt->execute();

			$payload = array("error_reported" => true);
			echo json_encode($payload);


			break;
			die();

		case "calculate_remaining_projects":

			$programme_id = $_SESSION['gantt_current_programme_id'];
			$sql = "select id FROM gantt_tasks WHERE type='project' AND programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$count = $stmt->rowCount();

			$payload = array("remaining_projects" => $count);
			echo json_encode($payload);

			break;
			die();


		case "get_gantt_data_for_error_user";

			$user_id = $_REQUEST['id'];
			$sql = "select * FROM gantt_errors WHERE user_id='$user_id' ORDER BY id DESC";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$global_errors_array = array();

			foreach ($data as $error) {
				$account_id = $error['account_id'];
				$sql = "SELECT name FROM gantt_accounts WHERE id='$account_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$account = $stmt->fetch(PDO::FETCH_ASSOC);
				$error['account_name'] = $account['name'];

				$programme_id = $error['programme_id'];
				$sql = "SELECT name FROM gantt_programmes WHERE id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$programme = $stmt->fetch(PDO::FETCH_ASSOC);
				$error['programme_name'] = $programme['name'];

				$user_id = $error['user_id'];
				$sql = "SELECT first_name,last_name FROM gantt_users WHERE id='$user_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_ASSOC);
				$error['user_name'] = $user['first_name'] . " " . $user['last_name'];

				$error['created_datetime'] = date('d/m/Y H:i:s', $error['created']);

				array_push($global_errors_array, $error);
			}




			$payload = array("data" => $global_errors_array);
			echo json_encode($payload);

			break;
			die();

		case "get_gantt_data_for_error_programme";

			$programme_id = $_REQUEST['id'];
			$sql = "select * FROM gantt_errors WHERE programme_id='$programme_id' ORDER BY id DESC";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$global_errors_array = array();

			foreach ($data as $error) {
				$account_id = $error['account_id'];
				$sql = "SELECT name FROM gantt_accounts WHERE id='$account_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$account = $stmt->fetch(PDO::FETCH_ASSOC);
				$error['account_name'] = $account['name'];

				$programme_id = $error['programme_id'];
				$sql = "SELECT name FROM gantt_programmes WHERE id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$programme = $stmt->fetch(PDO::FETCH_ASSOC);
				$error['programme_name'] = $programme['name'];

				$user_id = $error['user_id'];
				$sql = "SELECT first_name,last_name FROM gantt_users WHERE id='$user_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_ASSOC);
				$error['user_name'] = $user['first_name'] . " " . $user['last_name'];

				$error['created_datetime'] = date('d/m/Y H:i:s', $error['created']);

				array_push($global_errors_array, $error);
			}




			$payload = array("data" => $global_errors_array);
			echo json_encode($payload);


			break;
			die();



		case "get_gantt_data_for_error_all";

			$programme_id = $_REQUEST['id'];
			$sql = "select * FROM gantt_errors ORDER BY id DESC";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$global_errors_array = array();

			foreach ($data as $error) {
				$account_id = $error['account_id'];
				$sql = "SELECT name FROM gantt_accounts WHERE id='$account_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$account = $stmt->fetch(PDO::FETCH_ASSOC);
				$error['account_name'] = $account['name'];

				$programme_id = $error['programme_id'];
				$sql = "SELECT name FROM gantt_programmes WHERE id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$programme = $stmt->fetch(PDO::FETCH_ASSOC);
				$error['programme_name'] = $programme['name'];

				$user_id = $error['user_id'];
				$sql = "SELECT first_name,last_name FROM gantt_users WHERE id='$user_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_ASSOC);
				$error['user_name'] = $user['first_name'] . " " . $user['last_name'];

				$error['created_datetime'] = date('d/m/Y H:i:s', $error['created']);

				array_push($global_errors_array, $error);
			}




			$payload = array("data" => $global_errors_array);
			echo json_encode($payload);


			break;
			die();



		case "get_gantt_data_for_error";

			$error_id = $_REQUEST['id'];
			$sql = "select * FROM gantt_errors WHERE id='$error_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_ASSOC);

			$payload = array("data" => $data);
			echo json_encode($payload);

			break;
			die();

		case "get_lead_output":

			$link_id = $_REQUEST['id'];
			$value = $_REQUEST['value'];
			$type = $_REQUEST['type'];

			$sql = "select * FROM gantt_links WHERE id='$link_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$link = $stmt->fetch(PDO::FETCH_ASSOC);

			$source_task_id = $link['source'];
			$target_task_id = $link['target'];

			$sql = "select text,start_date,end_date,calendar_id,duration_worked FROM gantt_tasks WHERE id='$source_task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$start_date = $task['start_date'];
			$calendar_id = $task['calendar_id'];
			$duration_worked = intval($task['duration_worked']) - (60 * intval($value));

			$source_text = $task['text'];

			$source_end_date = DateTime::createFromFormat('Y-m-d H:i:s', $task['end_date'])->format("d/m/Y");
			$source_end_time = DateTime::createFromFormat('Y-m-d H:i:s', $task['end_date'])->format("H:i");


			$sql = "select text FROM gantt_tasks WHERE id='$target_task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$target_text = $task['text'];



			$output_date = getTaskEndDateFromMinutesv2Output($start_date, $duration_worked, $calendar_id, $db);
			$date_output = DateTime::createFromFormat('Y-m-d H:i:s', $output_date)->format("d/m/Y");
			$time_output = DateTime::createFromFormat('Y-m-d H:i:s', $output_date)->format("H:i");



			$payload = array("source_date" => $source_end_date, "source_time" => $source_end_time, "output_date" => $date_output, "output_time" => $time_output, "source_text" => $source_text, "target_text" => $target_text);
			echo json_encode($payload);



			break;
			die();





		case "get_lag_output":

			$link_id = $_REQUEST['id'];
			$value = $_REQUEST['value'];
			$type = $_REQUEST['type'];

			$sql = "select * FROM gantt_links WHERE id='$link_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$link = $stmt->fetch(PDO::FETCH_ASSOC);

			$source_task_id = $link['source'];
			$target_task_id = $link['target'];

			$sql = "select text,start_date,end_date,calendar_id,duration_worked FROM gantt_tasks WHERE id='$source_task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$start_date = $task['start_date'];
			$calendar_id = $task['calendar_id'];
			$duration_worked = intval($task['duration_worked']) + (60 * intval($value));
			$source_text = $task['text'];

			$source_end_date = DateTime::createFromFormat('Y-m-d H:i:s', $task['end_date'])->format("d/m/Y");
			$source_end_time = DateTime::createFromFormat('Y-m-d H:i:s', $task['end_date'])->format("H:i");

			$sql = "select text FROM gantt_tasks WHERE id='$target_task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$target_text = $task['text'];

			$output_date = getTaskEndDateFromMinutesv2Output($start_date, $duration_worked, $calendar_id, $db);
			$date_output = DateTime::createFromFormat('Y-m-d H:i:s', $output_date)->format("d/m/Y");
			$time_output = DateTime::createFromFormat('Y-m-d H:i:s', $output_date)->format("H:i");



			$payload = array("source_date" => $source_end_date, "source_time" => $source_end_time, "output_date" => $date_output, "output_time" => $time_output, "source_text" => $source_text, "target_text" => $target_text);
			echo json_encode($payload);



			break;
			die();


		case "unset_user_custom_date_range":
			$_SESSION['user']['custom_date_range'] = false;
			$_SESSION['user']['custom_date_range_start'] = null;
			$_SESSION['user']['custom_date_range_end'] = null;
			$payload = array("range_unset" => true);
			echo json_encode($payload);
			break;
			die();


		case "set_user_custom_date_range":
			$_SESSION['user']['custom_date_range'] = true;
			$_SESSION['user']['custom_date_range_start'] = $_REQUEST['start'];
			$_SESSION['user']['custom_date_range_end'] = $_REQUEST['end'];
			$payload = array("range_set" => true);
			echo json_encode($payload);
			break;
			die();

		case "get_link_task_names":

			$link_id = $_REQUEST['id'];
			$sql = "select * FROM gantt_links WHERE id='$link_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$link = $stmt->fetch(PDO::FETCH_ASSOC);

			$source_task_id = $link['source'];
			$target_task_id = $link['target'];

			$sql = "select text,duration_unit FROM gantt_tasks WHERE id='$source_task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$task_1_text = $task['text'];


			$sql = "select text,duration_unit FROM gantt_tasks WHERE id='$target_task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$task_2_text = $task['text'];


			$payload = array("task_1_text" => $task_1_text, "task_2_text" => $task_2_text);
			echo json_encode($payload);


			break;
			die();


		case "convert_user_to_administrator":

			$user_id = $_REQUEST['user_id'];

			if ($_SESSION['user']['type'] == "2" && $user_id != $_SESSION['user']['id']) {
				$sql = "UPDATE gantt_users SET type='2' WHERE id='$user_id' AND account_id='$account_id' AND active='1'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$payload = array("user_converted" => true);
			} else {
				$payload = array("user_converted" => false);
			}



			echo json_encode($payload);
			break;
			die();


		case "convert_administrator_to_user":

			$user_id = $_REQUEST['user_id'];

			if ($_SESSION['user']['type'] == "2" && $user_id != $_SESSION['user']['id']) {
				$sql = "UPDATE gantt_users SET type='1' WHERE id='$user_id' AND account_id='$account_id' AND active='1'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$payload = array("user_converted" => true);
			} else {
				$payload = array("user_converted" => false);
			}



			echo json_encode($payload);
			break;
			die();


		case "delete_user":

			$user_id = $_REQUEST['user_id'];
			if ($_SESSION['user']['type'] == "2" && $user_id != $_SESSION['user']['id']) {
				$deletion_time = time();

				$sql = "SELECT email_address FROM gantt_users WHERE id='$user_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_ASSOC);
				$email_address = $user['email_address'];
				$email_address_updated = $user_id . "-" . $deletion_time . "-" . $email_address;

				$sql = "UPDATE gantt_users SET email_address='$email_address_updated',active='0',deleted='$deletion_time' WHERE id='$user_id' AND account_id='$account_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$payload = array("user_deleted" => true);
			} else {
				$payload = array("user_deleted" => false);
			}



			echo json_encode($payload);
			break;
			die();




		case "reset_user_password":

			$user_id = $_REQUEST['user_id'];

			$created = time();
			$expires = ($created + 86400);
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
			$token = hash('sha256', $user_id .  time() + md5(rand(0, 999999999999)));

			$sql = "select first_name,email_address FROM users WHERE id='$user_id' AND account_id='$account_id' AND active='1'";
			$stmt = $DB_con->prepare($sql);
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($user) {
				$email_address = $user['email_address'];
				$first_name = $user['first_name'];

				$stmt = $DB_con->prepare("INSERT INTO password_resets(user_id,email_address,ip_address,token,expires,created) VALUES (?,?,?,?,?,?)");
				$stmt->bindParam(1, $user_id);
				$stmt->bindParam(2, $email_address);
				$stmt->bindParam(3, $ip_address);
				$stmt->bindParam(4, $token);
				$stmt->bindParam(5, $expires);
				$stmt->bindParam(6, $created);
				$stmt->execute();

				HELPERS::sendPasswordResetEmail($DB_con, $user_id, $email_address, $first_name, $token);


				$_SESSION['last_action'] = "An email with instructions on resetting their password has been sent to " . $email_address;
				$payload = array("password_reset_initiated" => true);
			} else {
				$payload = array("password_reset_initiated" => false);
			}
			echo json_encode($payload);
			break;
			die();






		case "get_contributor_details":

			$user_id = $_REQUEST['user_id'];
			$stmt = $db->prepare("SELECT name FROM gantt_contributors WHERE guid='$user_id' AND programme_id='$programme_id'");
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_ASSOC);

			$payload = array("user" => $user);

			echo json_encode($payload);

			break;






		case "invite_new_user":

			if ($_REQUEST['email_address'] != "" && $_REQUEST['email_address'] != NULL) {
				$stmt = $db->prepare("SELECT id FROM gantt_users WHERE email_address= :email_address");
				$stmt->bindParam(':email_address', $_REQUEST['email_address']);
				$stmt->execute();
				if ($stmt->rowCount() > 0) {
					$payload = array("user_invited" => false);
				} else {

					$programme_id = $_SESSION['current_programme_id'];
					$author_id = $_SESSION['user']['first_name'] . " " . $_SESSION['user']['last_name'];
					$action_type = "invited";
					$action_object = "new team member to Ibex";
					$action_id = $_REQUEST['email_address'];
					$created = time();

					// Activities
					/*$author_id = $_SESSION['user']['id'];
					$stmt = $db->prepare("INSERT INTO gantt_activities (programme_id,user_id,action,object_type,object_id,created) VALUES  (?,?,?,?,?,?)");
				$stmt->bindParam(1, $programme_id);
				$stmt->bindParam(2, $author_id);
				$stmt->bindParam(3, $action_type);
				$stmt->bindParam(4, $action_object);
				$stmt->bindParam(5, $action_id);
				$stmt->bindParam(6, $created);
				$stmt->execute();
				*/


					$user_id = $_SESSION['user']['id'];
					$last_programme_id = $_SESSION['user']['last_programme_id'];

					// Insert new user into db
					$invite_token = hash('sha512', $_REQUEST['email_address'] . time() . mt_rand(10000000000, 999999999999));
					$type = "1";
					if ($_REQUEST['admin'] == "1") {
						$type = "2";
					}
					$user_guid = generateGUID();
					$created = time();

					// Insert new user 
					$stmt = $db->prepare("INSERT INTO gantt_users(type,account_id,email_address,invite_token,created,guid,last_programme_id) VALUES (?,?,?,?,?,?,?)");
					$stmt->bindParam(1, $type);
					$stmt->bindParam(2, $account_id);
					$stmt->bindParam(3, $_REQUEST['email_address']);
					$stmt->bindParam(4, $invite_token);
					$stmt->bindParam(5, $created);
					$stmt->bindParam(6, $user_guid);
					$stmt->bindParam(7, $last_programme_id);
					$stmt->execute();
					$user_id = $db->lastInsertId();

					// Get permissions
					if ($type == "1") {
						$permissions = explode(",", $_REQUEST['permissions']);
						foreach ($permissions as $permission) {
							if ($permission != "") {
								$stmt = $db->prepare("INSERT INTO gantt_user_project_permissions(user_id,project_id) VALUES (?,?)");
								$stmt->bindParam(1, $user_id);
								$stmt->bindParam(2, $permission);
								$stmt->execute();
							}
						}
					}

					// Get subdomain
					$stmt = $db->prepare("SELECT subdomain FROM gantt_accounts WHERE id='$account_id'");
					$stmt->execute();
					$account = $stmt->fetch(PDO::FETCH_ASSOC);
					$subdomain = $account['subdomain'];


					// Auth
					$auth_link = "auth.php?email_address=" . $email_address . "&token=" . $invite_token;


					// Email admin
					$config = array();
					$config['api_key'] = "key-86929a614db601844f2de754bf641f80";
					$config['api_url'] = "https://api.mailgun.net/v3/ibex.software/messages";

					$message = array();
					$message['from'] = "Ibex <support@ibex.software>";
					$message['to'] = $_REQUEST['email_address'];
					$message['subject'] = "Welcome to Ibex";

					$content = file_get_contents('http://dashboard.ibex.software/assets/email-templates/new_user_invite.html');
					$content = str_replace("{{INVITED_BY_NAME}}", $_SESSION['user']['first_name'] . " " . $_SESSION['user']['last_name'], $content);
					$content = str_replace("{{AUTH_LINK}}", $auth_link, $content);

					$message['html'] = $content;

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $config['api_url']);
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
					curl_setopt($ch, CURLOPT_USERPWD, "api:{$config['api_key']}");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $message);

					$result = curl_exec($ch);
					curl_close($ch);






					$payload = array("user_invited" => true, "user_id" => $user_id);
					$_SESSION['last_action'] = "Invitation sent to " . $_REQUEST['email_address'];
				}
			} else {
				$payload = array("user_invited" => false);
			}
			echo json_encode($payload);



			break;





		case "delete_attachment":
			$attachment_id = $_REQUEST['id'];

			$sql = "select programme_id FROM gantt_attachments WHERE id='$attachment_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$attachment = $stmt->fetch(PDO::FETCH_ASSOC);


			$gantt_mode = $_SESSION['gantt_load_mode'];
			$gantt_load_mode_whatif = $_SESSION['gantt_load_mode_whatif'];

			if ($gantt_mode == "live" && $gantt_load_mode_whatif == "false") {
				$sql = "DELETE FROM gantt_attachments WHERE id = '$attachment_id' AND gantt_mode='live' AND gantt_whatif_mode='false'";
			} else {
				$sql = "DELETE FROM gantt_attachments WHERE id = '$attachment_id' AND gantt_mode='live' AND gantt_whatif_mode='true'";
			}
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$payload = array("attachment_deleted" => true);
			echo json_encode($payload);

			die();
			break;


		case "get_attachments":

			$task_id = $_REQUEST['task_id'];

			$sql = "select programme_id FROM gantt_tasks WHERE id='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);

			$gantt_mode = $_SESSION['gantt_load_mode'];
			$gantt_load_mode_whatif = $_SESSION['gantt_load_mode_whatif'];
			if ($gantt_mode == "live" && $gantt_load_mode_whatif == "true") {
				$sql = "SELECT id,description,url FROM gantt_attachments WHERE task_id='$task_id' AND gantt_mode='live'";
			} else if ($gantt_mode == "live" && $gantt_load_mode_whatif == "false") {
				$sql = "SELECT id,description,url FROM gantt_attachments WHERE task_id='$task_id' AND gantt_mode='live' AND gantt_whatif_mode='false'";
			} else {
				$sql = "SELECT id,description,url FROM gantt_attachments WHERE task_id='$task_id' AND gantt_mode='live' AND gantt_whatif_mode='true'";
			}



			$stmt = $db->prepare($sql);
			$stmt->execute();
			$attachments = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$payload = array("attachments" => $attachments);
			echo json_encode($payload);

			break;
			die();



		case "upload_task_attachment":

			$task_id = $_REQUEST['task_id'];
			$programme_id = $_REQUEST['programme_id'];



			$filename_existing = $_FILES['file']['name'];
			$filename_existing = str_replace(' ', '-', $filename_existing);
			$extension = pathinfo($filename_existing, PATHINFO_EXTENSION);
			$filename_isolated = pathinfo($filename_existing, PATHINFO_FILENAME) . "." . pathinfo($filename_existing, PATHINFO_EXTENSION);
			$filename_parsed = pathinfo($filename_existing, PATHINFO_FILENAME) . "-" . $task_id . mt_rand(100000, 999999);
			$filename = $filename_parsed . "." . $extension;
			$destination_url = "https://dashboard.ibex.software/assets/" . $filename;
			move_uploaded_file($_FILES['file']['tmp_name'], '/var/www/surveyorportal.com/public_html/assets/' . $filename);


			$author = $_SESSION['user']['first_name'] . " " . $_SESSION['user']['last_name'];
			$gantt_mode = $_SESSION['gantt_load_mode'];
			$gantt_load_mode_whatif = $_SESSION['gantt_load_mode_whatif'];
			$created = time();


			$stmt = $db->prepare("INSERT INTO gantt_attachments (programme_id,task_id,description,url,author,gantt_mode,gantt_whatif_mode,created) VALUES  (?,?,?,?,?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $task_id);
			$stmt->bindParam(3, $filename_isolated);
			$stmt->bindParam(4, $destination_url);
			$stmt->bindParam(5, $author);
			$stmt->bindParam(6, $gantt_mode);
			$stmt->bindParam(7, $gantt_load_mode_whatif);
			$stmt->bindParam(8, $created);
			$stmt->execute();

			$payload = array("attachment_uploaded" => true);
			echo json_encode($payload);

			break;
			die();


		case "move_task":

			$programme_id = $_REQUEST['programme_id'];


			$author_id = $_SESSION['user']['id'];
			$action_type = "task-was-moved";
			$action_object = "task was moved";
			$action_id = $description;
			$created = time();

			// Activities
			$author_id = $_COOKIE['ibex_demo_name'];
			$stmt = $db->prepare("INSERT INTO gantt_activities (programme_id,user_id,action,object_type,object_id,created) VALUES  (?,?,?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $author_id);
			$stmt->bindParam(3, $action_type);
			$stmt->bindParam(4, $action_object);
			$stmt->bindParam(5, $action_id);
			$stmt->bindParam(6, $created);
			$stmt->execute();

			$destination_task_id = $_REQUEST['destination_task_id'];
			$sql = "SELECT sortorder FROM gantt_tasks WHERE id='$destination_task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$destination_sortorder = (int)$task['sortorder'];

			// Source task sortorder will be one more than this
			$source_task_id = $_REQUEST['source_task_id'];
			$source_sortorder = $destination_sortorder + 1;

			// Update all sortorders higher than destination sortorder to be +1
			$sql = "UPDATE gantt_tasks SET sortorder = sortorder + 1 WHERE programme_id='$programme_id' AND sortorder > '$destination_sortorder'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			// Now update the source task ID with the new sortorder
			$sql = "UPDATE gantt_tasks SET sortorder = '$source_sortorder' WHERE id='$source_task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$payload = array("task_moved" => true);
			echo json_encode($payload);



			break;
			die();




		case "create_default_project":
			$programme_id = $_REQUEST['programme_id'];
			$sql = "SELECT * FROM gantt_calendars WHERE programme_id='$programme_id' AND is_default_task_calendar='1'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$calendar = $stmt->fetch(PDO::FETCH_ASSOC);
			$start_time_hm = $calendar['start_time'];
			$calendar_id = $calendar['id'];
			$type = "project";
			$text = "Default Project";
			$duration = "1";
			$progress = "0";
			$parent = "0";
			$reference_number = "";
			$start_date = date("Y-m-d") . " " . $start_time_hm . ":00";
			$stmt = $db->prepare("INSERT INTO gantt_tasks (programme_id,text,reference_number,start_date,duration,duration_worked,progress,parent,calendar_id,type) VALUES  (?,?,?,?,?,?,?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $text);
			$stmt->bindParam(3, $reference_number);
			$stmt->bindParam(4, $start_date);
			$stmt->bindParam(5, $duration);
			$stmt->bindParam(6, $duration);
			$stmt->bindParam(7, $progress);
			$stmt->bindParam(8, $parent);
			$stmt->bindParam(9, $calendar_id);
			$stmt->bindParam(10, $type);
			$stmt->execute();
			$inserted_id = $db->lastInsertId();
			$stmt = $db->prepare("UPDATE gantt_tasks SET sortorder='$inserted_id' WHERE id='$inserted_id'");
			$stmt->execute();
			$payload = array("project_created" => $inserted_id);
			echo json_encode($payload);
			break;
			die();



		case "get_gantt_settings":


			if ($_SESSION['demo_session'] == true) {
				$user_id = $_COOKIE['ibex_demo_user_id'];
			} else {
				$user_id = $_SESSION['user']['id'];
			}
			$sql = "SELECT * FROM gantt_columns WHERE programme_id='$programme_id' AND user_id='$user_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$column_widths = $stmt->fetch(PDO::FETCH_ASSOC);


			$sql = "SELECT * FROM gantt_settings WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$settings = $stmt->fetch(PDO::FETCH_ASSOC);
			$columns = $settings['columns_json'];
			$resource_columns = $settings['columns_resources_json'];
			$payload = array("account_id" => $_SESSION['user']['account_id'], "custom_date_range" => $_SESSION['user']['custom_date_range'], "custom_date_range_start" => $_SESSION['user']['custom_date_range_start'], "custom_date_range_end" => $_SESSION['user']['custom_date_range_end'], "settings" => $settings, "columns" => $columns, "resource_columns" => $resource_columns, "column_widths" => $column_widths);
			echo json_encode($payload);

			break;
			die();

		case "update_gantt_settings":

			if ($_SESSION['user']['type'] == "2" || $_SESSION['demo_session'] == true) {

				$data = $_REQUEST['data'];


				$sql = "UPDATE gantt_settings SET settings_json='$data' WHERE programme_id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$_SESSION['last_action'] = "Settings updated succesfully";
				$payload = array("settings_updated" => true);
				echo json_encode($payload);
			} else {
				$payload = array("settings_updated" => false);
				echo json_encode($payload);
			}

			break;
			die();




		case "update_column_width":


			$column_name = $_REQUEST['column_name'];
			$column_width = $_REQUEST['new_width'];
			$user_id = $_SESSION['user']['id'];



			$sql = "UPDATE gantt_columns SET " . $column_name . "='$column_width' WHERE programme_id='$programme_id' AND user_id='$user_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$payload = array("column_updated" => true);
			echo json_encode($payload);

			break;
			die();





		case "update_gantt_columns":
			$task_data = $_REQUEST['task_data'];
			$resource_data = $_REQUEST['resource_data'];
			$programme_id = $_REQUEST['programme_id'];




			$sql = "UPDATE gantt_settings SET columns_json='$task_data',columns_resources_json='$resource_data' WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$_SESSION['last_action'] = "Columns updated succesfully";
			$payload = array("columns_updated" => true);
			echo json_encode($payload);

			break;
			die();



		case "get_resource":
			$resource_id = $_REQUEST['id'];
			$sql = "SELECT * FROM gantt_resources WHERE id='$resource_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resource = $stmt->fetch(PDO::FETCH_ASSOC);
			$payload = array("resource" => $resource);
			echo json_encode($payload);

			break;
			die();









		

		case "retrieve_exported_file":

			$file_url = $_REQUEST['url'];

			$extension = $_REQUEST['type'];
			$description = $_REQUEST['description'];
			$programme_id = $_REQUEST['programme_id'];

			$author_id = $_SESSION['user']['id'];
			$action_type = "exported";
			if ($extension == "pdf") {
				$action_object = " PDF copy of";
			}
			if ($extension == "png") {
				$action_object = " PNG copy of";
			}
			$action_id = $description;
			$created = time();

			// Activities
			$author_id = $_SESSION['user']['id'];
			$stmt = $db->prepare("INSERT INTO gantt_activities (programme_id,user_id,action,object_type,object_id,created) VALUES  (?,?,?,?,?,?)");
			$stmt->bindParam(1, $programme_id);
			$stmt->bindParam(2, $author_id);
			$stmt->bindParam(3, $action_type);
			$stmt->bindParam(4, $action_object);
			$stmt->bindParam(5, $action_id);
			$stmt->bindParam(6, $created);
			$stmt->execute();





			$filename = hash('sha256', time() + md5(rand(0, 999999999999))) . "." . $extension;
			$destination = "/var/www/html/ibex-production-mmb-basic/assets/pdf/" .  $filename;

			$sql = "SELECT * FROM gantt_accounts WHERE id='$account_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$account = $stmt->fetch(PDO::FETCH_ASSOC);

			$destination_url = "https://" . $account['subdomain'] . ".ibex.software/assets/pdf/" .  $filename;

			if (copy($file_url, $destination)) {
				if ($extension == "pdf") {
					$icon_url = "https://dashboard.ibex.software/img/icon_pdf.png";
					$type = "pdf";
				}
				if ($extension == "png") {
					$icon_url = "https://dashboard.ibex.software/img/icon_png.png";
					$type = "png";
				}
				$author = $_SESSION['user']['first_name'] . " " . $_SESSION['user']['last_name'];
				$created = time();
				$stmt = $db->prepare("INSERT INTO gantt_documents (programme_id,icon_url,description,url,author,type,created) VALUES  (?,?,?,?,?,?,?)");
				$stmt->bindParam(1, $programme_id);
				$stmt->bindParam(2, $icon_url);
				$stmt->bindParam(3, $description);
				$stmt->bindParam(4, $destination_url);
				$stmt->bindParam(5, $author);
				$stmt->bindParam(6, $type);
				$stmt->bindParam(7, $created);
				$stmt->execute();
			}

			$payload = array("file_url" => $destination_url);
			echo json_encode($payload);

			break;
			die();

		case "get_links":
			$task_id = $_REQUEST['id'];
			$sql = "SELECT programme_id FROM gantt_tasks WHERE id='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);
			$sql = "SELECT id FROM gantt_links WHERE source = '$task_id' OR target='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$links = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$links_array = array();
			foreach ($links as $link) {
				$links_array[] = $link['id'];
			}
			$payload = array("links" => $links_array);
			echo json_encode($payload);
			die();
			break;

		case "delete_link":
			$link_id = $_REQUEST['id'];
			$sql = "SELECT programme_id FROM gantt_links WHERE id='$link_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$link = $stmt->fetch(PDO::FETCH_ASSOC);

			$sql = "DELETE FROM gantt_links WHERE id = '$link_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$_SESSION['last_action'] = "Link deleted successfully";
			$payload = array("link_deleted" => true);
			echo json_encode($payload);
			die();
			break;


		case "get_predecessors":

			$task_id = $_REQUEST['task_id'];


			$sql = "SELECT programme_id FROM gantt_tasks WHERE id='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);


			$sql = "SELECT id,type,source,lag,lead FROM gantt_links WHERE target='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$predecessors = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$predecessors_array = array();
			foreach ($predecessors as $predecessor) {
				$source_task_id = $predecessor['source'];
				$sql = "SELECT id,text,reference_number FROM gantt_tasks WHERE id='$source_task_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$task = $stmt->fetch(PDO::FETCH_ASSOC);
				$task['link_id'] = $predecessor['id'];
				$task['link_type'] = $predecessor['type'];
				if ($task['link_type'] == "0") {
					$task['link_type'] = "Finish to Start";
				}
				if ($task['link_type'] == "1") {
					$task['link_type'] = "Start to Start";
				}
				if ($task['link_type'] == "2") {
					$task['link_type'] = "Finish to Finish";
				}
				if ($task['link_type'] == "3") {
					$task['link_type'] = "Start to Finish";
				}

				$task['lag'] = $predecessor['lag'];
				$task['lead'] = $predecessor['lead'];

				$predecessors_array[] = $task;
			}
			$payload = array("predecessors" => $predecessors_array);
			echo json_encode($payload);

			die();
			break;



		case "get_successors":

			$task_id = $_REQUEST['task_id'];


			$sql = "SELECT programme_id FROM gantt_tasks WHERE id='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$task = $stmt->fetch(PDO::FETCH_ASSOC);


			$sql = "SELECT id,type,source,target,lag,lead FROM gantt_links WHERE source='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$successors = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$successors_array = array();
			foreach ($successors as $successor) {
				$source_task_id = $successor['target'];
				$sql = "SELECT id,text,reference_number FROM gantt_tasks WHERE id='$source_task_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$task = $stmt->fetch(PDO::FETCH_ASSOC);
				$task['link_id'] = $successor['id'];
				$task['link_type'] = $successor['type'];
				if ($task['link_type'] == "0") {
					$task['link_type'] = "Finish to Start";
				}
				if ($task['link_type'] == "1") {
					$task['link_type'] = "Start to Start";
				}
				if ($task['link_type'] == "2") {
					$task['link_type'] = "Finish to Finish";
				}
				if ($task['link_type'] == "3") {
					$task['link_type'] = "Start to Finish";
				}

				$task['lag'] = $successor['lag'];
				$task['lead'] = $successor['lead'];


				$successors_array[] = $task;
			}
			$payload = array("successors" => $successors_array);
			echo json_encode($payload);

			die();
			break;




		case "get_resources_of_task":

			$task_id = $_REQUEST['task_id'];
			$sql = "SELECT resource_id FROM gantt_task_resource_links WHERE task_id='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resource_links = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$resources_array = array();
			foreach ($resource_links as $resource_link) {
				$resource_id = $resource_link['resource_id'];
				$sql = "SELECT * FROM gantt_resources WHERE id='$resource_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$resource = $stmt->fetch(PDO::FETCH_ASSOC);
				$resources_array[] = $resource;
			}
			$payload = array("resources" => $resources_array);
			echo json_encode($payload);

			die();
			break;






		case "search_resources":

			$search = strtolower($_REQUEST['q']);
			$task_id = strtolower($_REQUEST['task_id']);
			$programme_id = $_SESSION['gantt_current_programme_id'];

			// Check existing links
			$sql = "SELECT resource_id FROM gantt_task_resource_links WHERE programme_id ='$programme_id' AND task_id='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$existing_links = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$existing_links_array = array();
			foreach ($existing_links as $existing_link) {
				array_push($existing_links_array, $existing_link['resource_id']);
			}


			$sql = "SELECT id,name FROM gantt_resources WHERE LOWER(name) LIKE '%$search%' AND programme_id='$programme_id' AND id <> '$resource_id' AND id NOT IN ( '" . implode($existing_links_array, "', '") . "' ) LIMIT 50";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$list = $stmt->fetchall(PDO::FETCH_ASSOC);

			if (count($list) > 0) {
				foreach ($list as $key => $value) {

					$data[] = array('id' => $value['id'], 'text' => $value['name']);
				}
			} else {
				$data[] = array('id' => '0', 'text' => 'No records found');
			}


			echo json_encode($data);

			die();
			break;



		case "search_tasks_positions":

			$search = strtolower($_REQUEST['q']);
			$task_id = strtolower($_REQUEST['task_id']);
			$programme_id = $_SESSION['gantt_current_programme_id'];




			$sql = "SELECT id,text,reference_number FROM gantt_tasks WHERE type='task' AND LOWER(text) LIKE '%$search%' AND programme_id='$programme_id' AND id <> '$task_id' LIMIT 50";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$list = $stmt->fetchall(PDO::FETCH_ASSOC);

			if (count($list) > 0) {
				foreach ($list as $key => $value) {

					$data[] = array('id' => $value['id'], 'text' => $value['text']);
				}
			} else {
				$data[] = array('id' => '0', 'text' => 'No records found');
			}


			echo json_encode($data);

			die();
			break;







		case "search_tasks_predecessors":

			$search = strtolower($_REQUEST['q']);
			$task_id = strtolower($_REQUEST['task_id']);
			$programme_id = $_SESSION['gantt_current_programme_id'];


			// Check existing links
			$sql = "SELECT source FROM gantt_links WHERE programme_id ='$programme_id' AND target='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$existing_links = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$existing_links_array = array();
			foreach ($existing_links as $existing_link) {
				array_push($existing_links_array, $existing_link['source']);
			}


			$sql = "SELECT id,text,reference_number FROM gantt_tasks WHERE type='task' AND LOWER(text) LIKE '%$search%' AND programme_id='$programme_id' AND id <> '$task_id' AND id NOT IN ( '" . implode($existing_links_array, "', '") . "' ) LIMIT 50";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$list = $stmt->fetchall(PDO::FETCH_ASSOC);

			if (count($list) > 0) {
				foreach ($list as $key => $value) {

					$data[] = array('id' => $value['id'], 'text' => $value['text']);
				}
			} else {
				$data[] = array('id' => '0', 'text' => 'No records found');
			}


			echo json_encode($data);

			die();
			break;





		case "search_tasks_successors":

			$search = strtolower($_REQUEST['q']);
			$task_id = strtolower($_REQUEST['task_id']);
			$programme_id = $_SESSION['gantt_current_programme_id'];



			// Check existing links
			$sql = "SELECT target FROM gantt_links WHERE programme_id ='$programme_id' AND source='$task_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$existing_links = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$existing_links_array = array();
			foreach ($existing_links as $existing_link) {
				array_push($existing_links_array, $existing_link['target']);
			}


			$sql = "SELECT id,text,reference_number FROM gantt_tasks WHERE type='task' AND LOWER(text) LIKE '%$search%' AND programme_id='$programme_id' AND id <> '$task_id' AND id NOT IN ( '" . implode($existing_links_array, "', '") . "' ) LIMIT 50";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$list = $stmt->fetchall(PDO::FETCH_ASSOC);

			if (count($list) > 0) {
				foreach ($list as $key => $value) {

					$data[] = array('id' => $value['id'], 'text' => $value['text']);
				}
			} else {
				$data[] = array('id' => '0', 'text' => 'No records found');
			}


			echo json_encode($data);

			die();
			break;





		case "initialise_demo_programme":


			$payload = "data:[{id:1, text:'Project #1',start_date:'01-04-2013', duration:11,progress: 0.6, open: true},{id:2, text:'Task #1', start_date:'03-04-2013', duration:5, progress: 1,   open: true, parent:1},{id:3, text:'Task #2',   start_date:'02-04-2013', duration:7, progress: 0.5, open: true, parent:1},{id:4, text:'Task #2.1', start_date:'03-04-2013', duration:2, progress: 1,   open: true, parent:3},{id:5, text:'Task #2.2', start_date:'04-04-2013', duration:3, progress: 0.8, open: true, parent:3},{id:6, text:'Task #2.3', start_date:'05-04-2013', duration:4, progress: 0.2, open: true, parent:3}],links:[{id:1, source:1, target:2, type:'1'},{id:2, source:1, target:3, type:'1'},{id:3, source:3, target:4, type:'1'},{id:4, source:4, target:5, type:'0'},{id:5, source:5, target:6, type:'0'}]";
			echo json_encode($payload);
			die();
			break;


		case "delete_calendar":

			$calendar_id = $_REQUEST['id'];

			$sql = "SELECT id FROM gantt_tasks WHERE programme_id='$programme_id' AND calendar_id='$calendar_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			if ($stmt->rowCount() == 0) {



				$sql = "DELETE FROM gantt_calendars WHERE id = '$calendar_id' AND programme_id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$payload = array("calendar_deleted" => true);
				echo json_encode($payload);
				$_SESSION['last_action'] = "Calendar deleted successfully";
				die();
			} else {
				$payload = array("calendar_deleted" => false);
				echo json_encode($payload);
			}

			break;


		case "save_settings":
			$programme_id = $_SESSION['gantt_id'];
			$settings_array_json = json_encode(array("task_duration_unit" => $_REQUEST['settings_task_duration_unit']));
			$sql = "UPDATE gantt_config SET settings='$settings_array_json' WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			break;
			die();






		case "save_task_calendar":
			$calendar_id = $_REQUEST['id'];
			$type = 1;
			$name = $_REQUEST['name'];
			$start_time = $_REQUEST['start_time'];
			$start_time_array = explode(":", $_REQUEST['start_time']);
			$start_hour = $start_time_array[0];
			$start_minute = $start_time_array[1];
			$end_time = $_REQUEST['end_time'];
			$end_time_array = explode(":", $_REQUEST['end_time']);
			$end_hour = $end_time_array[0];
			$end_minute = $end_time_array[1];
			$is_default_task_calendar = $_REQUEST['task_calendar_edit_default'];
			$working_day_monday = 0;
			if ($_REQUEST['working_day_monday'] == "true") {
				$working_day_monday = 1;
			}
			$working_day_tuesday = 0;
			if ($_REQUEST['working_day_tuesday'] == "true") {
				$working_day_tuesday = 1;
			}
			$working_day_wednesday = 0;
			if ($_REQUEST['working_day_wednesday'] == "true") {
				$working_day_wednesday = 1;
			}
			$working_day_thursday = 0;
			if ($_REQUEST['working_day_thursday'] == "true") {
				$working_day_thursday = 1;
			}
			$working_day_friday = 0;
			if ($_REQUEST['working_day_friday'] == "true") {
				$working_day_friday = 1;
			}
			$working_day_saturday = 0;
			if ($_REQUEST['working_day_saturday'] == "true") {
				$working_day_saturday = 1;
			}
			$working_day_sunday = 0;
			if ($_REQUEST['working_day_sunday'] == "true") {
				$working_day_sunday = 1;
			}
			if ($_REQUEST['default'] == "true") {
				$default = 1;
			} else {
				$default = 0;
			}
			// Update all other calendars to non-default if this is default
			if ($_REQUEST['default'] == "true") {
				$sql = "UPDATE gantt_calendars SET is_default_task_calendar='0' WHERE programme_id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
			}
			if ($_REQUEST['id'] == 0) {
				$stmt = $db->prepare("INSERT INTO gantt_calendars (programme_id,name,start_hour,start_minute,end_hour,end_minute,working_day_monday,working_day_tuesday,working_day_wednesday,working_day_thursday,working_day_friday,working_day_saturday,working_day_sunday,is_default_task_calendar,type) VALUES  (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
				$stmt->bindParam(1, $programme_id);
				$stmt->bindParam(2, $name);
				$stmt->bindParam(3, $start_hour);
				$stmt->bindParam(4, $start_minute);
				$stmt->bindParam(5, $end_hour);
				$stmt->bindParam(6, $end_minute);
				$stmt->bindParam(7, $working_day_monday);
				$stmt->bindParam(8, $working_day_tuesday);
				$stmt->bindParam(9, $working_day_wednesday);
				$stmt->bindParam(10, $working_day_thursday);
				$stmt->bindParam(11, $working_day_friday);
				$stmt->bindParam(12, $working_day_saturday);
				$stmt->bindParam(13, $working_day_sunday);
				$stmt->bindParam(14, $default);
				$stmt->bindParam(15, $type);
				$stmt->execute();
				$calendar_id = $db->lastInsertId();
			} else {
				$sql = "UPDATE gantt_calendars SET name='$name',start_hour='$start_hour',start_minute='$start_minute',end_hour='$end_hour',end_minute='$end_minute',working_day_monday='$working_day_monday',working_day_tuesday='$working_day_tuesday',working_day_wednesday='$working_day_wednesday',working_day_thursday='$working_day_thursday',working_day_friday='$working_day_friday',working_day_saturday='$working_day_saturday',working_day_sunday='$working_day_sunday',is_default_task_calendar='$default',type='$type' WHERE id='$calendar_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
			}

			// Add overrides
			$overrides = json_decode($_REQUEST['overrides'], true);

			foreach ($overrides as $override) {

				$stmt = $db->prepare("INSERT INTO gantt_calendar_overrides (programme_id,calendar_id,start_date,end_date) VALUES  (?,?,?,?)");
				$stmt->bindParam(1, $programme_id);
				$stmt->bindParam(2, $calendar_id);
				$stmt->bindParam(3, $override['startDate']);
				$stmt->bindParam(4, $override['endDate']);

				$stmt->execute();
			}


			$payload = array("processed" => true);
			echo json_encode($payload);
			die();
			break;

	// ok
		case "save_resource_calendar":
			$calendar_id = $_REQUEST['id'];
			$type = 2;
			$name = $_REQUEST['name'];
			$start_time = $_REQUEST['start_time'];
			$start_time_array = explode(":", $_REQUEST['start_time']);
			$start_hour = $start_time_array[0];
			$start_minute = $start_time_array[1];
			$end_time = $_REQUEST['end_time'];
			$end_time_array = explode(":", $_REQUEST['end_time']);
			$end_hour = $end_time_array[0];
			$end_minute = $end_time_array[1];
			$is_default_resource_calendar = $_REQUEST['resource_calendar_edit_default'];
			$working_day_monday = 0;
			if ($_REQUEST['working_day_monday'] == "true") {
				$working_day_monday = 1;
			}
			$working_day_tuesday = 0;
			if ($_REQUEST['working_day_tuesday'] == "true") {
				$working_day_tuesday = 1;
			}
			$working_day_wednesday = 0;
			if ($_REQUEST['working_day_wednesday'] == "true") {
				$working_day_wednesday = 1;
			}
			$working_day_thursday = 0;
			if ($_REQUEST['working_day_thursday'] == "true") {
				$working_day_thursday = 1;
			}
			$working_day_friday = 0;
			if ($_REQUEST['working_day_friday'] == "true") {
				$working_day_friday = 1;
			}
			$working_day_saturday = 0;
			if ($_REQUEST['working_day_saturday'] == "true") {
				$working_day_saturday = 1;
			}
			$working_day_sunday = 0;
			if ($_REQUEST['working_day_sunday'] == "true") {
				$working_day_sunday = 1;
			}
			if ($_REQUEST['default'] == "true") {
				$default = 1;
			} else {
				$default = 0;
			}
			// Update all other calendars to non-default if this is default
			if ($_REQUEST['default'] == "true") {
				$sql = "UPDATE gantt_calendars SET is_default_resource_calendar='0' WHERE programme_id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
			}
			if ($_REQUEST['id'] == 0) {
				$stmt = $db->prepare("INSERT INTO gantt_calendars (programme_id,name,start_hour,start_minute,end_hour,end_minute,working_day_monday,working_day_tuesday,working_day_wednesday,working_day_thursday,working_day_friday,working_day_saturday,working_day_sunday,is_default_resource_calendar,type) VALUES  (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
				$stmt->bindParam(1, $programme_id);
				$stmt->bindParam(2, $name);
				$stmt->bindParam(3, $start_hour);
				$stmt->bindParam(4, $start_minute);
				$stmt->bindParam(5, $end_hour);
				$stmt->bindParam(6, $end_minute);
				$stmt->bindParam(7, $working_day_monday);
				$stmt->bindParam(8, $working_day_tuesday);
				$stmt->bindParam(9, $working_day_wednesday);
				$stmt->bindParam(10, $working_day_thursday);
				$stmt->bindParam(11, $working_day_friday);
				$stmt->bindParam(12, $working_day_saturday);
				$stmt->bindParam(13, $working_day_sunday);
				$stmt->bindParam(14, $default);
				$stmt->bindParam(15, $type);
				$stmt->execute();
				$calendar_id = $db->lastInsertId();
			} else {
				$sql = "UPDATE gantt_calendars SET name='$name',start_hour='$start_hour',start_minute='$start_minute',end_hour='$end_hour',end_minute='$end_minute',working_day_monday='$working_day_monday',working_day_tuesday='$working_day_tuesday',working_day_wednesday='$working_day_wednesday',working_day_thursday='$working_day_thursday',working_day_friday='$working_day_friday',working_day_saturday='$working_day_saturday',working_day_sunday='$working_day_sunday',is_default_resource_calendar='$default',type='$type' WHERE id='$calendar_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
			}
			$payload = array("processed" => true);
			echo json_encode($payload);
			die();
			break;









		
		case "undo":
			error_reporting(E_ALL);
			ini_set('display_errors', 1);

			$sql = "SELECT current_snapshot FROM gantt_programmes WHERE id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$programme = $stmt->fetch(PDO::FETCH_ASSOC);
			$snapshot_id = $programme['current_snapshot'];

			// Are we at the top of the stack?
			$sql = "SELECT id FROM gantt_snapshots WHERE programme_id='$programme_id' AND id > '$snapshot_id' ORDER BY created DESC LIMIT 1";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			if ($stmt->rowCount() == 0) {
				// Top of the stack, stick another snapshot at the top to come back to the top if needed
				$data = $_REQUEST['data'];
				$action_type = "REFERENCE_SNAPSHOT";
				$action_object = "REFERENCE_SNAPSHOT";
				$action_id = "REFERENCE_SNAPSHOT";
				$action_additional_information = "REFERENCE_SNAPSHOT";
				$author = "REFERENCE_SNAPSHOT";
				$type = "0";
				$guid = generateGUID();
				$created = time();

				$stmt = $db->prepare("INSERT INTO gantt_snapshots (programme_id,action_type,action_object,action_text,action_additional_information,author,type,data,guid,created) VALUES  (?,?,?,?,?,?,?,?,?,?)");
				$stmt->bindParam(1, $programme_id);
				$stmt->bindParam(2, $action_type);
				$stmt->bindParam(3, $action_object);
				$stmt->bindParam(4, $action_id);
				$stmt->bindParam(5, $action_additional_information);
				$stmt->bindParam(6, $author);
				$stmt->bindParam(7, $type);
				$stmt->bindParam(8, $data);
				$stmt->bindParam(9, $guid);
				$stmt->bindParam(10, $created);
				$stmt->execute();
				$snapshot_id = $db->lastInsertId();

				$sql = "UPDATE gantt_programmes SET current_snapshot='$snapshot_id' WHERE id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
			}

			// Get current snapshot
			$sql = "SELECT current_snapshot FROM gantt_programmes WHERE id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$programme = $stmt->fetch(PDO::FETCH_ASSOC);
			$snapshot_id = $programme['current_snapshot'];


			if ($snapshot_id == "0") {
				$payload = array("undo_processed" => false);
				echo json_encode($payload);
			} else {
				// Get last snapshot (one further back) after this one
				$sql = "SELECT id,data,type FROM gantt_snapshots WHERE programme_id='$programme_id' AND id < '$snapshot_id' AND type='1' ORDER BY created DESC LIMIT 1";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$snapshot = $stmt->fetch(PDO::FETCH_ASSOC);
				$snapshot_id = $snapshot['id'];

				if ($snapshot) {

					$sql = "UPDATE gantt_programmes SET current_snapshot='$snapshot_id' WHERE id='$programme_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();

					// Delete any activities past this point
					$sql = "UPDATE gantt_activities SET active='0' WHERE programme_id='$programme_id' AND snapshot_id >= '$snapshot_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					// We now need to take this data and iterate through it to insert it again to the gantt
					$data = json_decode($snapshot['data'], true);
					$tasks = $data['data'];
					$links = $data['links'];
					$sql = "";
					$params = "";


					// Delete existing programme data
					$sql = "DELETE FROM gantt_tasks WHERE programme_id='$programme_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();

					$sql = "DELETE FROM gantt_links WHERE programme_id='$programme_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();

					foreach ($tasks as $task) {
						$guid_set = false;
						foreach ($task as $key => $value) {
							if (isset($task['guid'])) {
								$guid_set = true;
							}
							unset($task['id']);
							$table = "gantt_tasks";
							$sql = 'INSERT into ' . $table . '(`' . implode('`,`', array_keys($task)) . '`) values (:' . implode(',:', array_keys($task)) . ');';
							foreach ($task as $field => $value) {
								$params[":{$field}"] = $value;
							}
						}

						if ($guid_set == true) {
							$stmt = $db->prepare($sql);
							$stmt->execute($params);
						}
					}


					// Reassign parents by lookng up each client task
					$sql = "SELECT id,parent_guid FROM gantt_tasks WHERE programme_id='$programme_id' AND parent_guid <> ''";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$tasks_update = $stmt->fetchAll(PDO::FETCH_ASSOC);
					foreach ($tasks_update as $task_update) {
						// Get parent GUID
						$task_update_id = $task_update['id'];
						$parent_guid = $task_update['parent_guid'];

						$sql = "SELECT id FROM gantt_tasks WHERE guid='$parent_guid'";
						$stmt = $db->prepare($sql);
						$stmt->execute();
						$parent_update = $stmt->fetch(PDO::FETCH_ASSOC);
						$parent_update_id = $parent_update['id'];

						$sql = "UPDATE gantt_tasks SET parent='$parent_update_id' WHERE id='$task_update_id'";
						$stmt = $db->prepare($sql);
						$stmt->execute();
					}

					// Update links
					foreach ($links as $link) {
						$source_guid = $link['source_guid'];
						$target_guid = $link['target_guid'];

						$sql = "SELECT id FROM gantt_tasks WHERE guid='$source_guid'";
						$stmt = $db->prepare($sql);
						$stmt->execute();
						$parent_update = $stmt->fetch(PDO::FETCH_ASSOC);
						$source_id = $parent_update['id'];

						$sql = "SELECT id FROM gantt_tasks WHERE guid='$target_guid'";
						$stmt = $db->prepare($sql);
						$stmt->execute();
						$parent_update = $stmt->fetch(PDO::FETCH_ASSOC);
						$target_id = $parent_update['id'];

						$stmt = $db->prepare("INSERT INTO gantt_links (programme_id,source,source_guid,target,target_guid,type,lag,lead,respected,color) VALUES  (?,?,?,?,?,?,?,?,?,?)");
						$stmt->bindParam(1, $programme_id);
						$stmt->bindParam(2, $source_id);
						$stmt->bindParam(3, $source_guid);
						$stmt->bindParam(4, $target_id);
						$stmt->bindParam(5, $target_guid);
						$stmt->bindParam(6, $link['type']);
						$stmt->bindParam(7, $link['lag']);
						$stmt->bindParam(8, $link['lead']);
						$stmt->bindParam(9, $link['respected']);
						$stmt->bindParam(10, $link['color']);
						$stmt->execute();
					}






					$payload = array("undo_processed" => true);
					echo json_encode($payload);
				}
			}


			break;
			die();



		case "redo":


			// Get current snapshot
			$sql = "SELECT current_snapshot FROM gantt_programmes WHERE id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$programme = $stmt->fetch(PDO::FETCH_ASSOC);
			$snapshot_id = $programme['current_snapshot'];

			// Are there any more snapshots after this or are we at the top of the stack?
			$sql = "SELECT id FROM gantt_snapshots WHERE programme_id='$programme_id' AND id > '$snapshot_id' ORDER BY created DESC LIMIT 1";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			if ($stmt->rowCount() == 0) {
				// No more, do nothing
				$payload = array("redo_processed" => false);
				echo json_encode($payload);
			} else {

				// Get last snapshot (one further forward) after this one
				$sql = "SELECT id,data FROM gantt_snapshots WHERE programme_id='$programme_id' AND id > '$snapshot_id' ORDER BY created ASC LIMIT 1";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$snapshot = $stmt->fetch(PDO::FETCH_ASSOC);
				if ($snapshot) {
					$snapshot_id = $snapshot['id'];

					$sql = "UPDATE gantt_programmes SET current_snapshot='$snapshot_id' WHERE id='$programme_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();


					// We now need to take this data and iterate through it to insert it again to the gantt
					$data = json_decode($snapshot['data'], true);
					$tasks = $data['data'];
					$links = $data['links'];
					$sql = "";
					$params = "";

					// Delete existing programme data
					$sql = "DELETE FROM gantt_tasks WHERE programme_id='$programme_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();


					// Enable any activities past this point
					$sql = "UPDATE gantt_activities SET active='1' WHERE programme_id='$programme_id' AND snapshot_id <= '$snapshot_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();


					foreach ($tasks as $task) {
						$guid_set = false;
						foreach ($task as $key => $value) {
							if (isset($task['guid'])) {
								$guid_set = true;
							}
							unset($task['id']);
							$table = "gantt_tasks";
							$sql = 'INSERT into ' . $table . '(`' . implode('`,`', array_keys($task)) . '`) values (:' . implode(',:', array_keys($task)) . ');';
							foreach ($task as $field => $value) {
								$params[":{$field}"] = $value;
							}
						}

						if ($guid_set == true) {
							$stmt = $db->prepare($sql);
							$stmt->execute($params);
						}

						// Reassign parents by lookng up each client task
						$sql = "SELECT id,parent_guid FROM gantt_tasks WHERE programme_id='$programme_id' AND parent_guid <> ''";
						$stmt = $db->prepare($sql);
						$stmt->execute();
						$tasks_update = $stmt->fetchAll(PDO::FETCH_ASSOC);
						foreach ($tasks_update as $task_update) {
							// Get parent GUID
							$task_update_id = $task_update['id'];
							$parent_guid = $task_update['parent_guid'];

							$sql = "SELECT id FROM gantt_tasks WHERE guid='$parent_guid'";
							$stmt = $db->prepare($sql);
							$stmt->execute();
							$parent_update = $stmt->fetch(PDO::FETCH_ASSOC);
							$parent_update_id = $parent_update['id'];

							$sql = "UPDATE gantt_tasks SET parent='$parent_update_id' WHERE id='$task_update_id'";
							$stmt = $db->prepare($sql);
							$stmt->execute();
						}
					}

					// Update links
					foreach ($links as $link) {
						$source_guid = $link['source_guid'];
						$target_guid = $link['target_guid'];

						$sql = "SELECT id FROM gantt_tasks WHERE guid='$source_guid'";
						$stmt = $db->prepare($sql);
						$stmt->execute();
						$parent_update = $stmt->fetch(PDO::FETCH_ASSOC);
						$source_id = $parent_update['id'];

						$sql = "SELECT id FROM gantt_tasks WHERE guid='$target_guid'";
						$stmt = $db->prepare($sql);
						$stmt->execute();
						$parent_update = $stmt->fetch(PDO::FETCH_ASSOC);
						$target_id = $parent_update['id'];

						$stmt = $db->prepare("INSERT INTO gantt_links (programme_id,source,source_guid,target,target_guid,type,lag,lead,respected,color) VALUES  (?,?,?,?,?,?,?,?,?,?)");
						$stmt->bindParam(1, $programme_id);
						$stmt->bindParam(2, $source_id);
						$stmt->bindParam(3, $source_guid);
						$stmt->bindParam(4, $target_id);
						$stmt->bindParam(5, $target_guid);
						$stmt->bindParam(6, $link['type']);
						$stmt->bindParam(7, $link['lag']);
						$stmt->bindParam(8, $link['lead']);
						$stmt->bindParam(9, $link['respected']);
						$stmt->bindParam(10, $link['color']);
						$stmt->execute();
					}


					$payload = array("redo_processed" => true);
					echo json_encode($payload);
				} else {
					$payload = array("redo_processed" => false);
					echo json_encode($payload);
				}
			}




			break;
			die();




		case "revert_activity":

			$snapshot_id = $_REQUEST['snapshot_id'];

			// Get last snapshot (one further back) after this one
			$sql = "SELECT id,data,type FROM gantt_snapshots WHERE id='$snapshot_id' AND programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$snapshot = $stmt->fetch(PDO::FETCH_ASSOC);



			$decoded = json_decode($snapshot['data'], true);




			$tasks = $decoded['data'];
			$links = $decoded['links'];



			$snapshot_id = $snapshot['id'];


			if ($snapshot) {
				$sql = "UPDATE gantt_programmes SET current_snapshot='$snapshot_id' WHERE id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();

				// Delete any activities past this point
				$sql = "UPDATE gantt_activities SET active='0' WHERE programme_id='$programme_id' AND snapshot_id >= '$snapshot_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();

				$sql = "";
				$params = "";


				// Delete existing programme data
				$sql = "DELETE FROM gantt_tasks WHERE programme_id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();

				$sql = "DELETE FROM gantt_links WHERE programme_id='$programme_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();

				foreach ($tasks as $task) {


					$guid_set = false;
					foreach ($task as $key => $value) {
						if (isset($task['guid'])) {
							$guid_set = true;
						}
						unset($task['id']);
						$table = "gantt_tasks";
						$sql = 'INSERT into ' . $table . '(`' . implode('`,`', array_keys($task)) . '`) values (:' . implode(',:', array_keys($task)) . ');';
						foreach ($task as $field => $value) {
							$params[":{$field}"] = $value;
						}
					}

					if ($guid_set == true) {
						$stmt = $db->prepare($sql);
						$stmt->execute($params);
					}
				}


				// Reassign parents by lookng up each client task
				$sql = "SELECT id,parent_guid FROM gantt_tasks WHERE programme_id='$programme_id' AND parent_guid <> ''";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$tasks_update = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($tasks_update as $task_update) {
					// Get parent GUID
					$task_update_id = $task_update['id'];
					$parent_guid = $task_update['parent_guid'];

					$sql = "SELECT id FROM gantt_tasks WHERE guid='$parent_guid'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$parent_update = $stmt->fetch(PDO::FETCH_ASSOC);
					$parent_update_id = $parent_update['id'];

					$sql = "UPDATE gantt_tasks SET parent='$parent_update_id' WHERE id='$task_update_id'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
				}

				// Update links
				foreach ($links as $link) {
					$source_guid = $link['source_guid'];
					$target_guid = $link['target_guid'];

					$sql = "SELECT id FROM gantt_tasks WHERE guid='$source_guid'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$parent_update = $stmt->fetch(PDO::FETCH_ASSOC);
					$source_id = $parent_update['id'];

					$sql = "SELECT id FROM gantt_tasks WHERE guid='$target_guid'";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$parent_update = $stmt->fetch(PDO::FETCH_ASSOC);
					$target_id = $parent_update['id'];

					$stmt = $db->prepare("INSERT INTO gantt_links (programme_id,source,source_guid,target,target_guid,type,lag,lead,respected,color) VALUES  (?,?,?,?,?,?,?,?,?,?)");
					$stmt->bindParam(1, $programme_id);
					$stmt->bindParam(2, $source_id);
					$stmt->bindParam(3, $source_guid);
					$stmt->bindParam(4, $target_id);
					$stmt->bindParam(5, $target_guid);
					$stmt->bindParam(6, $link['type']);
					$stmt->bindParam(7, $link['lag']);
					$stmt->bindParam(8, $link['lead']);
					$stmt->bindParam(9, $link['respected']);
					$stmt->bindParam(10, $link['color']);
					$stmt->execute();
				}
			}

			$payload = array("snapshot_restored" => true);
			echo json_encode($payload);

			die();

			break;





		case "restore_snapshot":


			$snapshot_id = $_REQUEST['snapshot_id'];

			// Get programme ID for this snapshot
			$sql = "SELECT programme_id FROM gantt_snapshots WHERE id ='$snapshot_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$snapshot = $stmt->fetch(PDO::FETCH_ASSOC);
			$programme_id = $snapshot['programme_id'];



			/*$stmt = $db->prepare("DELETE FROM gantt_tasks WHERE programme_id='$programme_id'");
			$stmt->execute();
			
			$stmt = $db->prepare("DELETE FROM gantt_links WHERE programme_id='$programme_id'");
			$stmt->execute();
			
			$sql= "INSERT INTO gantt_tasks (id,programme_id,text,reference_number,start_date,baseline_start,baseline_end,deadline,duration,progress,sortorder,color,parent,calendar_id,type,duration_worked) SELECT original_id,'$programme_id', text,reference_number,start_date,baseline_start,baseline_end,deadline,duration,progress,sortorder,color,parent,calendar_id,type,duration_worked FROM gantt_tasks_snapshots WHERE snapshot_id='$snapshot_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			
			$sql= "INSERT INTO gantt_links (id,programme_id,source,target,type) SELECT original_id,'$programme_id',source,target,type FROM gantt_links_snapshots WHERE snapshot_id='$snapshot_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();*/

			$_SESSION['gantt_load_mode'] = "snapshot";
			$_SESSION['gantt_load_snapshot_id'] = $snapshot_id;


			$sql = "SELECT * FROM gantt_snapshots WHERE id='$snapshot_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$snapshot = $stmt->fetch(PDO::FETCH_ASSOC);
			$snapshot_data = $snapshot['data'];



			$payload = array("gantt_load_mode" => "live", "gantt_data" => $snapshot_data);
			echo json_encode($payload);
			die();

			break;


		case "restore_live_version":


			$_SESSION['gantt_load_mode'] = "live";
			$_SESSION['gantt_load_snapshot_id'] = null;
			$_SESSION['gantt_load_mode_whatif'] = false;
			$payload = array("gantt_load_mode" => "live");
			echo json_encode($payload);
			die();

			break;


		case "mode_live":


			$_SESSION['gantt_load_mode'] = "live";
			$_SESSION['gantt_load_snapshot_id'] = null;
			$payload = array("gantt_load_mode" => "live");
			echo json_encode($payload);
			die();

			break;



		case "get_calendar":

			$calendar_id = $_REQUEST['calendar_id'];

			$sql = "SELECT programme_id FROM gantt_calendars WHERE id='$calendar_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$calendar = $stmt->fetch(PDO::FETCH_ASSOC);




			$sql = "SELECT * FROM gantt_calendars WHERE id ='$calendar_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$calendar = $stmt->fetch(PDO::FETCH_ASSOC);

			$payload = array("calendar" => $calendar);
			echo json_encode($payload);
			die();

			break;




		case "get_calendars":



			$sql = "SELECT * FROM gantt_calendars WHERE programme_id ='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$calendars = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$sql = "SELECT * FROM gantt_calendar_overrides WHERE programme_id ='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$calendar_overrides = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$payload = array("calendars" => $calendars, "calendar_overrides" => $calendar_overrides);
			echo json_encode($payload);
			die();

			break;



		case "upload_snapshot_image":

			$data = $_REQUEST['imgBase64'];

			list($type, $data) = explode(';', $data);
			list(, $data)      = explode(',', $data);
			$data = base64_decode($data);

			$filename = hash('sha256', time() + md5(rand(0, 999999999999))) . "." . $extension;
			$destination = "/var/www/surveyorportal.com/public_html/assets/" .  $filename;
			$destination_url = "https://dashboard.ibex.software/assets/" .  $filename;




			$test = file_put_contents('imageXX.png', $data);

			echo $test;

			break;
			die();


		case "convert_whatif_to_contingency":

			$snapshot_id = $_REQUEST['snapshot_id'];
			$sql = "UPDATE gantt_snapshots SET type='3' WHERE id='$snapshot_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$payload = array("snapshot_converted" => true);
			echo json_encode($payload);

			break;
			die();

		case "get_snapshots":

			$programme_id = $_REQUEST['programme_id'];



			if ($_SESSION['gantt_load_mode_whatif'] == "true") {
				$sql = "SELECT * FROM gantt_snapshots WHERE programme_id ='$programme_id' AND type <> '1' ORDER BY created DESC LIMIT 15";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$snapshots_array = $stmt->fetchAll(PDO::FETCH_ASSOC);

				// Get current snapshot showing, if there is one
				$load_mode = "live";
				$snapshot_id = null;
				if ($_SESSION['gantt_load_mode'] == "snapshot") {
					$load_mode = "snapshot";
					$snapshot_id = $_SESSION['gantt_load_snapshot_id'];
				}
			} else {
				$snapshots_array = array();
				$sql = "SELECT * FROM gantt_snapshots WHERE programme_id ='$programme_id' AND type <> '2' ORDER BY created DESC LIMIT 15";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$snapshots = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($snapshots as $snapshot) {
					if ($snapshot['action_object'] == "task") {
						$task_id = $snapshot['action_id'];
						$sql = "SELECT text FROM gantt_tasks WHERE id ='$task_id'";
						$stmt = $db->prepare($sql);
						$stmt->execute();
						$result = $stmt->fetch(PDO::FETCH_ASSOC);
						$snapshot['descriptor'] = $result['text'];
					}
					array_push($snapshots_array, $snapshot);
				}

				// Get current snapshot showing, if there is one
				$load_mode = "live";
				$snapshot_id = null;
				if ($_SESSION['gantt_load_mode'] == "snapshot") {
					$load_mode = "snapshot";
					$snapshot_id = $_SESSION['gantt_load_snapshot_id'];
				}
			}


			$payload = array("snapshots" => $snapshots_array, "load_mode" => $load_mode, "snapshot_id" => $snapshot_id);
			echo json_encode($payload);
			die();

			break;


		case "reset_gantt":

			$sql = "DELETE FROM gantt_activities WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$sql = "DELETE FROM gantt_attachments WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$sql = "DELETE FROM gantt_calendars WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$sql = "DELETE FROM gantt_calendar_overrides WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$sql = "DELETE FROM gantt_columns WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();


			$sql = "DELETE FROM gantt_documents WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$sql = "DELETE FROM gantt_links WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$sql = "DELETE FROM gantt_scheduler_history WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$sql = "DELETE FROM gantt_settings WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$sql = "DELETE FROM gantt_snapshots WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$sql = "DELETE FROM gantt_tasks WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$sql = "DELETE FROM gantt_programmes WHERE identifier='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$sql = "DELETE FROM gantt_sessions WHERE identifier='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$sql = "DELETE FROM gantt_resources WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();

			$sql = "DELETE FROM gantt_task_resource_links WHERE programme_id='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();




			$payload = array("gantt_reset" => true);
			echo json_encode($payload);

			break;

			die();




		case "save_snapshot_image":

			$data = $_REQUEST['imgBase64'];
			$snapshot_id = $_REQUEST['snapshot_id'];

			$filename = hash('sha256', time() + md5(rand(0, 999999999999))) . ".png";
			$destination = "/var/www/surveyorportal.com/public_html/assets/" .  $filename;
			$destination_url = "https://dashboard.ibex.software/assets/" .  $filename;

			list($type, $data) = explode(';', $data);
			list(, $data)      = explode(',', $data);
			$data = base64_decode($data);

			file_put_contents($destination, $data);

			$sql = "UPDATE gantt_snapshots SET screenshot_url='$destination_url' WHERE id='$snapshot_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();


			break;
			die();


		case "get_templates":

			$templates_array = array();
			$sql = "SELECT id,name,author,created FROM gantt_templates WHERE account_id ='$account_id' ORDER BY name";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$templates = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($templates as $template) {
				$author_id = $template['author'];

				$sql = "SELECT first_name,last_name FROM gantt_users WHERE id ='$author_id'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_ASSOC);
				$template['created_by'] = $user['first_name'] . " " . $user['last_name'];
				$template['created'] = date('m/d/Y H:i', $template['created']);

				array_push($templates_array, $template);
			}

			$payload = array("templates" => $templates_array);
			echo json_encode($payload);
			die();



		case "create_template":


			$template_name = $_REQUEST['template_name'];
			$data = $_REQUEST['data'];
			$aux_data = "aux";


			$sql = "SELECT * FROM gantt_calendars WHERE programme_id ='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$calendars = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$payload_calendars = array("calendars" => $calendars);
			$calendar_data = json_encode($payload_calendars);

			//2. Settings
			$sql = "SELECT * FROM gantt_settings WHERE programme_id ='$programme_id'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$settings = $stmt->fetch(PDO::FETCH_ASSOC);

			$settings_only = $settings['settings_json'];
			$columns_only = $settings['columns_json'];
			$columns_resources_only = $settings['columns_resources_json'];






			$created = time();
			$stmt = $db->prepare("INSERT INTO gantt_templates (account_id,name,gantt_data,calendar_data,settings_data,columns_data,columns_resources_data,created,author) VALUES  (?,?,?,?,?,?,?,?,?)");
			$stmt->bindParam(1, $account_id);
			$stmt->bindParam(2, $template_name);
			$stmt->bindParam(3, $data);
			$stmt->bindParam(4, $calendar_data);
			$stmt->bindParam(5, $settings_only);
			$stmt->bindParam(6, $columns_only);
			$stmt->bindParam(7, $columns_resources_only);
			$stmt->bindParam(8, $created);
			$stmt->bindParam(9, $_SESSION['user']['id']);
			$stmt->execute();
			$snapshot_created = true;
			break;

		case "create_snapshot":
			if ($_SESSION['gantt_load_mode_whatif'] != "true") {
				// Check last snapshot time to ensure we can buffer multiple request
				$snapshot_created = false;
				// Markers
				$tasks_snapshotted = false;
				$guid = $_REQUEST['guid'];
				$time = time();
				$sql = "SELECT id FROM gantt_snapshots WHERE guid ='$guid'";
				$stmt = $db->prepare($sql);
				$stmt->execute();

				if ($stmt->rowCount() == 0) {
					$time = time();
					// Update db to show last save ref
					// Create new snapshot reference
					if ($_SESSION['demo_session'] == true) {
						$author_id = $_COOKIE['ibex_demo_user_id'];

						$sql = "SELECT first_name FROM gantt_users WHERE id ='$author_id'";
						$stmt = $db->prepare($sql);
						$stmt->execute();
						$author = $stmt->fetch(PDO::FETCH_ASSOC);

						$author = $author['first_name'];
					} else {
						$author = $_SESSION['user']['first_name'] . " " . $_SESSION['user']['last_name'];
					}

					$created = time();
					$type = "1";

					$action_type = $_REQUEST['action_type'];
					$action_object = $_REQUEST['action_object'];
					$action_id = $_REQUEST['action_id'];
					$action_additional_information = $_REQUEST['action_aux_data'];

					if ($action_type != "" && $action_object != "" && $action_id != "") {

						$data = json_decode($_REQUEST['data'], true);
						$data = $_REQUEST['data'];

						// Get new snapshot data
						// 1. Calendar
						$sql = "SELECT * FROM gantt_calendars WHERE programme_id ='$programme_id'";
						$stmt = $db->prepare($sql);
						$stmt->execute();
						$calendars = $stmt->fetchAll(PDO::FETCH_ASSOC);

						$payload_calendars = array("calendars" => $calendars);
						$encoded_payload_calendars = json_encode($payload_calendars);

						//2. Settings
						$sql = "SELECT * FROM gantt_settings WHERE programme_id ='$programme_id'";
						$stmt = $db->prepare($sql);
						$stmt->execute();
						$settings = $stmt->fetch(PDO::FETCH_ASSOC);


						$payload_settings = array("settings" => $settings);
						$encoded_payload_settings = json_encode($payload_settings);

						$stmt = $db->prepare("INSERT INTO gantt_snapshots (programme_id,action_type,action_object,action_text,action_additional_information,author,type,data,guid,created,calendar_data,settings_data) VALUES  (?,?,?,?,?,?,?,?,?,?,?,?)");
						$stmt->bindParam(1, $programme_id);
						$stmt->bindParam(2, $action_type);
						$stmt->bindParam(3, $action_object);
						$stmt->bindParam(4, $action_id);
						$stmt->bindParam(5, $action_additional_information);
						$stmt->bindParam(6, $author);
						$stmt->bindParam(7, $type);
						$stmt->bindParam(8, $data);
						$stmt->bindParam(9, $guid);
						$stmt->bindParam(10, $created);
						$stmt->bindParam(11, $encoded_payload_calendars);
						$stmt->bindParam(12, $encoded_payload_settings);
						$stmt->execute();
						$snapshot_created = true;
						$snapshot_id = $db->lastInsertId();

						$sql = "UPDATE gantt_programmes SET current_snapshot='$snapshot_id' WHERE id='$programme_id'";
						$stmt = $db->prepare($sql);
						$stmt->execute();

						// Activities
						if ($_SESSION['demo_session'] == true) {
							$author_id = $_COOKIE['ibex_demo_user_id'];
						} else {
							$author_id = $_SESSION['user']['id'];
						}

						$stmt = $db->prepare("INSERT INTO gantt_activities (programme_id,user_id,action,object_type,object_id,object_additional_information,snapshot_id,created) VALUES  (?,?,?,?,?,?,?,?)");
						$stmt->bindParam(1, $programme_id);
						$stmt->bindParam(2, $author_id);
						$stmt->bindParam(3, $action_type);
						$stmt->bindParam(4, $action_object);
						$stmt->bindParam(5, $action_id);
						$stmt->bindParam(6, $action_additional_information);
						$stmt->bindParam(7, $snapshot_id);
						$stmt->bindParam(8, $created);

						$stmt->execute();
						$snapshot_created = true;

						$payload = array("snapshot_created" => $snapshot_created, "snapshot_id" => $snapshot_id);
						echo json_encode($payload);
					}
				}
			}
			break;
	}

	//updateParentTaskIDs($tasks_array, $task['id'], $inserted_id);


	function updateParentTaskIDs($task_id, $inserted_id, $array)
	{
		echo "sent " . $task_id . " and " . $inserted_id;
		print_r($array);

		for ($i = 0; $i < count($array); $i++) {
			if ($array[$i]['parent'] == $task_id) {
				echo "found match, was " . $array[$i]['parent'];
				$array[$i]['parent'] = $inserted_id;
				echo "and is " . $array[$i]['parent'];
			}
		}
		echo "after";
		print_r($array);
		die();
		return true;
	}
