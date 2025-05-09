<?php

require_once '../base-path/config-path.php';
require_once BASE_PATH . 'config_db/config.php';
require_once BASE_PATH . 'session/session-manager.php';

// Verify user session and permissions
SessionManager::checkSession();
$sessionVars = SessionManager::SessionVariables();

$mobile_no = $sessionVars['mobile_no'];
$user_id = $sessionVars['user_id'];
$role = $sessionVars['role'];
$user_login_id = $sessionVars['user_login_id'];
$user_name = $sessionVars['user_name'];
$user_email = $sessionVars['user_email'];
$permission_check = 0;

// Set unlimited execution time for large exports
ini_set('max_execution_time', 0);

if (isset($_POST["date-range"]) && isset($_POST['device_id'])) {
	// Sanitize inputs
	$deviceId = trim($_POST['device_id']);
	$dateRange = trim($_POST['date-range']);

	// Check if the date range is empty
	if (empty($dateRange)) {
		echo "<script>alert('Please select a valid date range'); window.history.back();</script>";
		exit;
	}

	$id = $deviceId;
	// include_once("../common-files/fetch-device-phase.php");
	// $phase = $device_phase;

	// Parse and validate the date range
	if (strpos($dateRange, ' to ') === false) {
		echo "<script>alert('Invalid date range format'); window.history.back();</script>";
		exit;
	}

	list($from, $to) = explode(' to ', $dateRange);
	$fromDate = date("Y-m-d 00:00:01", strtotime($from));
	$toDate = date("Y-m-d 23:59:59", strtotime($to));

	// Validate date parsing
	if (!$fromDate || !$toDate) {
		echo "<script>alert('Invalid date format'); window.history.back();</script>";
		exit;
	}

	// Ensure date range does not exceed 30 days
	$dateDiff = (strtotime($toDate) - strtotime($fromDate)) / (60 * 60 * 24);
	if ($dateDiff > 30) {
		echo "<script>alert('Date range cannot exceed 30 days'); window.history.back();</script>";
		exit;
	}

	// Get the device name from session
	$device_name = $deviceId;
	$device_list = json_decode($_SESSION["DEVICES_LIST"] ?? '[]');
	foreach ($device_list as $key => $value) {
		if ($value->D_ID == strtoupper($deviceId)) {
			$device_name = $value->D_NAME;
			break;
		}
	}

	$filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $device_name) . '_' . date('Y-m-d');
	$db = strtolower($deviceId);

	try {
		$conn = mysqli_connect(HOST, USERNAME, PASSWORD, DB_ALL);
		if (!$conn) {
			throw new Exception("Connection failed: " . mysqli_connect_error());
		}

		// Prepare query with proper column names
		$params = "`id`, `motor_id`,`inlet_pressure`,`outlet_pressure1`, `outlet_pressure2`,`on_off_status`, `flow_rate`, 
                  `cumulative_flow`, `duration_run_time`, `line_voltage`, `motor_voltage`, `motor_current`, 
                  `frequency`, `reference_frequency`, `speed`, `energy_kwh`, `total_running_hours`, 
                  `drive_status`, `pf_1_2`, `pf_3_4`, `pf_5_6`, `pf_7`, `pf_8`, `pf_9_10`, 
                  `date_time`, `server_date_time`";

		// Use prepared statements to prevent SQL injection
		$sql = "SELECT $params FROM motor_data WHERE motor_id = ? AND date_time BETWEEN ? AND ? ORDER BY id ASC";
		$stmt = mysqli_prepare($conn, $sql);

		if (!$stmt) {
			throw new Exception("Query preparation failed: " . mysqli_error($conn));
		}

		mysqli_stmt_bind_param($stmt, "sss", $db, $fromDate, $toDate);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		if (!$result) {
			throw new Exception("Query execution failed: " . mysqli_error($conn));
		}

		if (mysqli_num_rows($result) == 0) {
			echo "<script>alert('No records found for the specified date range'); window.history.back();</script>";
			exit;
		}

		// Set headers for Excel download
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$filename.xls");
		header("Pragma: no-cache");
		header("Expires: 0");

		$sep = "\t";

		// Output column headers
		$col_headers = "id\tmotor_id\tinlet_pressure\toutlet_pressure1\toutlet_pressure2\ton_off_status\t" .
			"flow_rate\tcumulative_flow\tduration_run_time\tline_voltage\tmotor_voltage\t" .
			"motor_current\tfrequency\treference_frequency\tspeed\tenergy_kwh\ttotal_running_hours\t" .
			"drive_status\tpf_1_2\tpf_3_4\tpf_5_6\tpf_7\tpf_8\tpf_9_10\tdate_time\tserver_date_time";

		echo $col_headers . "\n";

		// Output data rows
		while ($row = mysqli_fetch_assoc($result)) {
			$line = '';
			foreach ($row as $value) {
				if (isset($value)) {
					// Clean the data to prevent Excel formula injection
					if (in_array(substr($value, 0, 1), ['=', '+', '-', '@'])) {
						$value = "'" . $value;
					}
					$line .= $value;
				} else {
					$line .= "NULL";
				}
				$line .= $sep;
			}
			echo rtrim($line, $sep) . "\n";
		}

		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	} catch (Exception $e) {
		echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
		exit;
	}
} else {
	echo "<script>alert('Invalid input. Please ensure all fields are filled out'); window.history.back();</script>";
	exit;
}
