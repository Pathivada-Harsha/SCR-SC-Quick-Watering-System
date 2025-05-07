<?php
require_once '../../base-path/config-path.php';
require_once BASE_PATH_1 . 'config_db/config.php';
require_once BASE_PATH_1 . 'session/session-manager.php';
SessionManager::checkSession();

$sessionVars = SessionManager::SessionVariables();
$mobile_no     = $sessionVars['mobile_no'];
$user_id       = $sessionVars['user_id'];
$role          = $sessionVars['role'];
$user_login_id = $sessionVars['user_login_id'];
$user_name     = $sessionVars['user_name'];
$user_email    = $sessionVars['user_email'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['D_ID']) && isset($_POST['RECORDS'])) {
	$motor_id = filter_input(INPUT_POST, 'D_ID', FILTER_SANITIZE_STRING);
	$records  = filter_input(INPUT_POST, 'RECORDS', FILTER_SANITIZE_STRING);
	$data     = "";

	$conn = mysqli_connect(HOST, USERNAME, PASSWORD, DB_ALL);
	if (!$conn) {
		echo '<tr><td class="text-danger" colspan="75">Connection failed: ' . mysqli_connect_error() . '</td></tr>';
		exit;
	}

	$stmt = null;

	if ($records === "LATEST") {
		$sql = "SELECT * FROM `motor_data` WHERE motor_id = ? ORDER BY id DESC LIMIT 20";
		$stmt = mysqli_prepare($conn, $sql);
		mysqli_stmt_bind_param($stmt, 's', $motor_id);
	} elseif ($records === "ADD" && isset($_POST['DATE_TIME'])) {
		$raw_date = filter_input(INPUT_POST, 'DATE_TIME', FILTER_SANITIZE_STRING);
		$formatted = str_replace('/', '-', $raw_date);
		$date_obj = date_create_from_format('Y-m-d H:i:s', $formatted);

		if ($date_obj) {
			$date = date_format($date_obj, "Y-m-d H:i:s");
			$sql = "SELECT * FROM `motor_data` WHERE date_time < ? AND motor_id = ? ORDER BY id DESC LIMIT 200";
			$stmt = mysqli_prepare($conn, $sql);
			mysqli_stmt_bind_param($stmt, 'ss', $date, $motor_id);
		} else {
			echo '<tr><td class="text-danger" colspan="75">Invalid Date-Time Format</td></tr>';
			exit;
		}
	} elseif ($records === "DATE" && isset($_POST['DATE'])) {
		$date = trim(filter_input(INPUT_POST, 'DATE', FILTER_SANITIZE_STRING));
		$date_formatted = date('Y-m-d', strtotime($date));
		$sql = "SELECT * FROM `motor_data` WHERE DATE(date_time) = ? AND motor_id = ? ORDER BY id DESC LIMIT 200";
		$stmt = mysqli_prepare($conn, $sql);
		mysqli_stmt_bind_param($stmt, 'ss', $date_formatted, $motor_id);
	} else {
		echo '<tr><td class="text-danger" colspan="75">Missing or invalid parameters.</td></tr>';
		exit;
	}

	if (isset($stmt) && mysqli_stmt_execute($stmt)) {
		$result = mysqli_stmt_get_result($stmt);
		if (mysqli_num_rows($result) > 0) {
			while ($r = mysqli_fetch_assoc($result)) {
				include("table_cells.php"); // Echoes each row directly
			}
		} else {
			echo '<tr><td class="text-danger" colspan="75">Records are not Found</td></tr>';
		}
		mysqli_stmt_close($stmt);
	} else {
		echo '<tr><td class="text-danger" colspan="75">Failed to fetch records.</td></tr>';
	}

	mysqli_close($conn);
}
?>
