<?php

$ip_address = $_SERVER['REMOTE_ADDR'];
$frame_data = "";
$payload = "";
GLOBAl $crc_status;
if ($ip_address == "::1") {
    define('HOST', 'localhost');
    define('USERNAME', 'root');
    define('PASSWORD', '123456');

    define('DB_USER', 'scr_user_db');
    define('DB_ALL', 'scr_secundrabad');
    $frame_data = "0;0.00;0.00;0;422.5;0.0;0.0;0.00;50.00;0;254.00;1678;51;0;0.00;0.00;0;425.6;0.0;0.0;0.00;50.00;0;308.00;2384;51;0;0.00;0.00;0;418.4;0.0;0.0;0.00;50.00;0;771.00;6;51;0;0.00;0.00;0;0.0;0.0;0.0;0.00;0.00;0;0.00;0;0;0;0.00;0.00;0;0.0;0.0;0.0;0.00;0.00;0;0.00;0;0;0;0.00;0.00;0;425.9;0.0;0.0;0.00;50.00;0;791.00;1373;51;0;0;0;0;0;0;1.13;1.12;0.01;2025-05-08 15:20:18;37140";
    $crc_status ='false';
    $payload = "1;1.00;0.00;0;422.5;0.0;0.0;0.00;50.00;0;254.00;1678;51;0;0.00;0.00;0;425.6;0.0;0.0;0.00;50.00;0;308.00;2384;51;0;0.00;0.00;0;418.4;0.0;0.0;0.00;50.00;0;771.00;6;51;0;0.00;0.00;0;0.0;0.0;0.0;0.00;0.00;0;0.00;0;0;0;0.00;0.00;0;0.0;0.0;0.0;0.00;0.00;0;0.00;0;0;0;0.00;0.00;0;425.9;0.0;0.0;0.00;50.00;0;791.00;1373;51;0;0;0;0;0;0;1.13;1.12;0.01;2025-05-08 15:20:18;37140";
} else {
    define('HOST', '103.101.59.93');
    define('USERNAME', 'istlabsonline_db_user');
    define('PASSWORD', 'istlabsonline_db_pass');
    define('DB_USER', 'scr_user_db');
    define('DB_ALL', 'scr_secundrabad');

    $frame_data = file_get_contents("php://input");
    $data_set = json_decode($frame_data, true);

    if (!isset($data_set['event']) || $data_set['event'] !== 'message.publish') {
        http_response_code(400);
        echo "⚠️ Not a valid message event.";
        exit;
    }

    $data = $data_set['payload'] ?? '';

    $crc_string = strrchr($data, ';'); // get the crc word from data
    $crc_compare = trim($crc_string, ";"); // getting crc value from the data
    $crc_len = strlen($crc_string) - 1; // getting the length of the crc word
    $data_crc = substr($data, 0, -$crc_len); // removing last crc word
    // $data_crc = "CCMS=" . $data_crc;
    $for_array = substr($data, 0, -strlen($crc_string)); // removing array crc from data
    $array_data = explode(';', $for_array); //converting data to array
    $crc = 0xFFFF;
    for ($i = 0; $i < strlen($data_crc); $i++) {
        $crc ^= ord($data_crc[$i]);
        for ($j = 8; $j != 0; $j--) {
            if (($crc & 0x0001) != 0) {
                $crc >>= 1;
                $crc ^= 0xA001;
            } else $crc >>= 1;
        }
    }
    $payload= $data_crc ;
    $crc_status = ($crc_compare == $crc) ? 'true' : 'false';
    
}


$central_db = DB_ALL;
$users_db = DB_USER;

if ($payload != "" && $crc_status === 'true' ) {

    //file_put_contents('log.txt',  $payload ."\n\n", FILE_APPEND);
    $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DB_ALL);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
        exit;
    }

    $frame_data = $payload;
    $array = array_map('trim', explode(";", $frame_data));
    date_default_timezone_set('Asia/Kolkata');
    $server_time = date("Y-m-d H:i:s");

    $pf_values = array_slice($array, -10);

    $pf_1_2 = $pf_values[0];
    $pf_3_4 = $pf_values[1];
    $pf_5_6 = $pf_values[2];
    $pf_7 = $pf_values[3];
    $pf_8 = $pf_values[4];
    $pf_9_10 = $pf_values[5];
    $inlet_pressure = $pf_values[6];
    $outlet_pressure_1 = $pf_values[7];
    $outlet_pressure_2 = $pf_values[8];
    $date_time = $pf_values[9];


    for ($i = 0; $i < 6; $i++) {
        $base_index = $i * 13;
        $motor_id = "MOTOR_" . ($i + 1);
        $on_off_status = $array[$base_index];
        $flow_rate = $array[$base_index + 1];
        $cumulative_flow = $array[$base_index + 2];
        $duration_run_time = $array[$base_index + 3];
        $line_voltage = $array[$base_index + 4];
        $motor_voltage = $array[$base_index + 5];
        $motor_current = $array[$base_index + 6];
        $frequency = $array[$base_index + 7];
        $reference_frequency = $array[$base_index + 8];
        $speed = $array[$base_index + 9];
        $energy_kwh = $array[$base_index + 10];
        $total_running_hours = $array[$base_index + 11];
        $drive_status = $array[$base_index + 12];
        $motor_query = "INSERT INTO motor_data(motor_id, on_off_status, flow_rate, cumulative_flow, duration_run_time, line_voltage, motor_voltage, motor_current, frequency, reference_frequency, speed, energy_kwh, total_running_hours, drive_status, pf_1_2,pf_3_4, pf_5_6, pf_7, pf_8, pf_9_10,inlet_pressure,outlet_pressure1,outlet_pressure2,date_time, server_date_time) VALUES ('$motor_id', '$on_off_status', '$flow_rate', '$cumulative_flow', '$duration_run_time', '$line_voltage', '$motor_voltage', '$motor_current', '$frequency', '$reference_frequency', '$speed', '$energy_kwh', '$total_running_hours', '$drive_status', '$pf_1_2', '$pf_3_4', '$pf_5_6', '$pf_7', '$pf_8', '$pf_9_10','$inlet_pressure','$outlet_pressure_1','$outlet_pressure_2','$date_time', '$server_time')";

        //file_put_contents('log.txt',  $motor_query."\n\n", FILE_APPEND);
        if (mysqli_query($conn, $motor_query)) {
            // file_put_contents('log.txt',  $motor_query."\n\n", FILE_APPEND);
            echo "updated";
        } else {
            mysqli_error($conn);
            // file_put_contents('log.txt',  mysqli_error($conn)."\n\n", FILE_APPEND);

        }
    }

    mysqli_close($conn);
}
else{
    $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DB_ALL);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
        exit;
    }
    date_default_timezone_set('Asia/Kolkata');
    $server_time = date("Y-m-d H:i:s");
    $motor_query = "INSERT INTO crc_fail_frame_data_table (frame,date_time) values ('$frame_data', '$server_time')";

    //file_put_contents('log.txt',  $motor_query."\n\n", FILE_APPEND);
    if (mysqli_query($conn, $motor_query)) {
        // file_put_contents('log.txt',  $motor_query."\n\n", FILE_APPEND);
        echo "updated";
    } else {
        mysqli_error($conn);
        // file_put_contents('log.txt',  mysqli_error($conn)."\n\n", FILE_APPEND);

    }


mysqli_close($conn);
}
