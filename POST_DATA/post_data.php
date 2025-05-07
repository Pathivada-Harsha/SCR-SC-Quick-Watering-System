<?php

$ip_address = $_SERVER['REMOTE_ADDR'];
$frame_data = "";
$payload = "";
if($ip_address=="::1")
{
    define('HOST','localhost');
    define('USERNAME', 'root');
    define('PASSWORD','123456');
    
    define('DB_USER', 'scr_user_db');
    define('DB_ALL', 'scr_secundrabad');
    $frame_data = "1;123;0;0;230;0;45; 120; 0; 1500; 121; 123; 0;2; 130; 1; 0; 235; 0; 50; 125; 0; 1550; 125; 130; 1;0; 118; 0; 1; 228; 1; 40; 115; 1; 1450; 118; 119; 0;1; 125; 1; 1; 232; 0; 48; 122; 0; 1520; 124; 126; 1;2; 127; 0; 0; 231; 1; 46; 119; 1; 1490; 126; 127; 0;1; 122; 1; 0; 229; 0; 44; 117; 0; 1480; 121; 122; 1; 0; 0; 0; 1; 1; 0;2025-04-28 13:05:20";

}
else
{
    define('HOST','103.101.59.93');
    define('USERNAME', 'istlabsonline_db_user');
    define('PASSWORD','istlabsonline_db_pass');
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
    $data_crc = $data_crc;
    $payload = substr($data, 0, -strlen($crc_string)); // removing array crc from data
    $array_data = explode(';', $for_array); //converting data to array
    $crc = 0xFFFF;
    for ($i = 0;$i < strlen($data_crc);$i++)
    {
        $crc ^= ord($data_crc[$i]);

        for ($j = 8;$j != 0;$j--)
        {
            if (($crc & 0x0001) != 0)
            {
                $crc >>= 1;
                $crc ^= 0xA001;
            }
            else $crc >>= 1;
        }
    }
}
$central_db=DB_ALL;
$users_db=DB_USER;

if ($payload != "") 
{
echo "message";
file_put_contents('log.txt',  $payload ."\n\n", FILE_APPEND);
    $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DB_ALL);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
        exit;
    }

    $frame_data=$payload ;
    $array = array_map('trim', explode(";", $frame_data));
    date_default_timezone_set('Asia/Kolkata');
    $server_time = date("Y-m-d H:i:s");

    $pf_values = array_slice($array, -7);
    $pf_1_2 = $pf_values[0];
    $pf_3_4 = $pf_values[1];
    $pf_5_6 = $pf_values[2];
    $pf_7 = $pf_values[3];
    $pf_8 = $pf_values[4];
    $pf_9_10 = $pf_values[5];
    $date_time =$pf_values[6];

    for ($i = 0; $i <6; $i++) {
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
        $motor_query = "INSERT INTO motor_data(motor_id, on_off_status, flow_rate, cumulative_flow, duration_run_time, line_voltage, motor_voltage, motor_current, frequency, reference_frequency, speed, energy_kwh, total_running_hours, drive_status, pf_1_2,pf_3_4, pf_5_6, pf_7, pf_8, pf_9_10, date_time, server_date_time) VALUES ('$motor_id', '$on_off_status', '$flow_rate', '$cumulative_flow', '$duration_run_time', '$line_voltage', '$motor_voltage', '$motor_current', '$frequency', '$reference_frequency', '$speed', '$energy_kwh', '$total_running_hours', '$drive_status', '$pf_1_2', '$pf_3_4', '$pf_5_6', '$pf_7', '$pf_8', '$pf_9_10', '$date_time', '$server_time')";
        
         //file_put_contents('log.txt',  $motor_query."\n\n", FILE_APPEND);
    if (mysqli_query($conn, $motor_query))
        {
             file_put_contents('log.txt',  $motor_query."\n\n", FILE_APPEND);

          
        }
        else
        {
            mysqli_error($conn);
            // file_put_contents('log.txt',  mysqli_error($conn)."\n\n", FILE_APPEND);

        }
    }
    
    mysqli_close($conn);
}
?>