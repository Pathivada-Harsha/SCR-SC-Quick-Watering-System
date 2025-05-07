<?php
require_once 'config-path.php';
require_once '../session/session-manager.php';
SessionManager::checkSession();

$sessionVars = SessionManager::SessionVariables();
$devices = isset($_SESSION["DEVICES_LIST"]) ? json_decode($_SESSION["DEVICES_LIST"], true) : [];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Report </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --header-bg: #4a6da7;
            --phase-r: #e74c3c;
            --phase-y: #f39c12;
            --phase-b: #3498db;
            --phase-total: #27ae60;
            --energy: #9b59b6;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f8f9fa;
        }

        .breadcrumb-text {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .motor-report-table {
            font-size: 0.9rem;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .table-header-row11 {
            background-color: var(--header-bg) !important;

            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
        }

        .phase-r {
            background-color: var(--phase-r) !important;
            color: white !important;
            font-weight: 600;
        }

        .phase-y {
            background-color: var(--phase-y) !important;
            color: white !important;
            font-weight: 600;
        }

        .phase-b {
            background-color: var(--phase-b) !important;
            color: white !important;
            font-weight: 600;
        }

        .phase-total {
            background-color: var(--phase-total) !important;
            color: white !important;
            font-weight: 600;
        }

        .energy-cell {
            background-color: var(--energy) !important;
            color: white !important;
            font-weight: 600;
        }

        .table-row-even {
            background-color: rgba(248, 249, 250, 0.5);
        }

        .table-row-odd {
            background-color: rgba(255, 255, 255, 0.5);
        }

        .motor-report-table td,
        .motor-report-table th {
            padding: 0.75rem;
            text-align: center;
            vertical-align: middle;
            border-color: #eaeaea;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            /* spacing between icon and text */
            padding: 0.35rem 0.85rem;
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: 50px;
            color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            min-width: 80px;
            /* keeps width consistent for open/close */
        }


        .status-on {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .status-off {
            background: linear-gradient(135deg, #dc3545, #e74c3c);
        }

        .form-select,
        .form-control {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 0.6rem 1rem;
            box-shadow: none;
            transition: all 0.3s;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
            border-radius: 8px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(52, 152, 219, 0.2);
            transition: all 0.3s;
        }

        /* .btn-primary:hover {
            background: linear-gradient(135deg, #2980b9, #3498db);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(52, 152, 219, 0.3);
        } */
        .table-responsive {
            overflow: visible;
        }

        .table-container {
            border-radius: 12px;
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            max-height: 65vh;
            /* Control the height of the visible area */
            overflow: auto;
            /* Enable scrolling */
        }

        .phase-r-bg {
            background-color: rgba(231, 76, 60, 0.1);
        }

        .phase-y-bg {
            background-color: rgba(243, 156, 18, 0.1);
        }

        .phase-b-bg {
            background-color: rgba(52, 152, 219, 0.1);
        }

        .phase-total-bg {
            background-color: rgba(39, 174, 96, 0.1);
        }

        .energy-bg {
            background-color: rgba(155, 89, 182, 0.1);
        }

        @media (max-width: 768px) {
            .motor-report-table {
                font-size: 0.8rem;
            }

            .motor-report-table td,
            .motor-report-table th {
                padding: 0.5rem;
            }
        }

        .date-column {
            min-width: 150px;
            /* Adjust as needed for your date format */
            white-space: nowrap;
        }

        /* Alternative solution using relative sizing */
        .motor-report-table th:nth-child(2),
        .motor-report-table td:nth-child(2) {
            width: 150px;
            min-width: 150px;
            white-space: nowrap;
        }

        /* Enhanced sticky header styles */
        .motor-report-table thead {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .motor-report-table thead tr:first-child th {
            position: sticky;
            top: 0;
            z-index: 11;
        }

        .motor-report-table thead tr:nth-child(2) th {
            position: sticky;
            top: 43px;
            /* Adjusted based on first row height */
            z-index: 10;
            background-color: #f8f9fa;
            /* Default background */
        }

        /* For colored headers, we need to maintain their background colors */
        .motor-report-table thead tr:nth-child(2) th.phase-r-bg {
            background-color: rgba(231, 76, 60, 0.1);
        }

        .motor-report-table thead tr:nth-child(2) th.phase-y-bg {
            background-color: rgba(243, 156, 18, 0.1);
        }

        .motor-report-table thead tr:nth-child(2) th.phase-b-bg {
            background-color: rgba(52, 152, 219, 0.1);
        }

        .motor-report-table thead tr:nth-child(2) th.energy-bg {
            background-color: rgba(155, 89, 182, 0.1);
        }

        /* Ensure the table container has proper overflow settings */
    </style>
</head>

<body>
    <?php
    include(BASE_PATH . "assets/html/start-page.php");
    ?>
    <div class="d-flex flex-column flex-shrink-0 p-4 main-content">
        <div class="container-fluid">

            <div class="row mb-3">
                <div class="col-12">
                    <p class="breadcrumb-text m-0">
                        <i class="bi bi-clipboard-data"></i> Pages / <span class="fw-medium">Data Report</span>
                    </p>
                </div>
            </div>
            <!-- Add this empty container in your HTML just above the table -->
            <!-- <div id="notification-area"></div> -->

            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="card">
                        <div class="card-body">
                            <label class="form-label text-muted mb-2">Select Motor</label>
                            <div class="input-group">
                                <span class="input-group-text ">
                                    <i class="fas fa-cog text-primary"></i>
                                </span>
                                <select class="form-select" id="motor-list">
                                    <?php
                                    if (!empty($devices)) {
                                        foreach ($devices as $device) {
                                            $id = htmlspecialchars($device["D_ID"]);
                                            $name = htmlspecialchars($device["D_NAME"]);
                                            echo "<option value=\"$id\">$name</option>";
                                        }
                                    } else {
                                        echo '<option disabled>No devices available</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <label class="form-label text-muted mb-2">Select Date</label>
                            <div class="input-group">
                                <span class="input-group-text ">
                                    <i class="fas fa-calendar-alt text-primary"></i>
                                </span>
                                <input type="date" class="form-control" id="search_date">
                                <button class="btn btn-primary" type="button" id="search-button" onclick="search_records()">
                                    <i class="fas fa-search me-1"></i> Search
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="table-container">
                        <table class="table table-bordered motor-report-table mb-0">
                            <thead >
                                <tr>
                                    <th class="table-header-row11" style="color:white;">Motor ID</th>
                                    <th class="table-header-row11 date-column" style="color:white;">Updated at</th>
                                    <th class="table-header-row11" style="color:white;">On/Off</th>
                                    <th class="table-header-row11" style="color:white;">Line Voltage </th>
                                    <th class="table-header-row11 phase-r">Motor Voltage </th>

                                    <th class="table-header-row11 phase-y">Motor Current </th>

                                    <th class="table-header-row11 energy-cell">Energy</th>
                                    <th class="table-header-row11" style="color:white;">Flow Rate</th>

                                    <th class="table-header-row11" style="color:white;">Speed</th>
                                    <th class="table-header-row11" style="color:white;">Reference Frequency</th>

                                    <th class="table-header-row11" style="color:white;">Frequency</th>

                                    <th class="table-header-row11" style="color:white;">Running Hours </th>
                                    <th class="table-header-row11" style="color:white;">PF 1&2 </th>
                                    <th class="table-header-row11" style="color:white;">PF 3&4 </th>
                                    <th class="table-header-row11" style="color:white;">PF 5&6 </th>
                                    <th class="table-header-row11" style="color:white;">PF 7 </th>
                                    <th class="table-header-row11" style="color:white;">PF 8 </th>
                                    <th class="table-header-row11" style="color:white;">PF 9&10 </th>



                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Status</th>
                                    <th class="phase-r-bg">(V)</th>
                                    <th class="phase-r-bg">(V)</th>
                                    <th class="phase-y-bg">(A)</th>
                                    <th class="energy-bg">kWh</th>
                                    <th>Liters/Minute (LPM)</th>
                                    <th>(RPM)</th>
                                    <th>(Hz)</th>
                                    <th>(Hz)</th>
                                    <th>(hrs)</th>
                                    <th>Status</th>
                                    <th>Status</th>
                                    <th>Status</th>
                                    <th>Status</th>
                                    <th>Status</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="frame_data_table">
                                <tr>
                                    <td colspan="21" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2 text-muted">Loading motor data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 d-flex justify-content-end">
            <button class="btn btn-secondary btn-sm mt-2" id="btn_add_more" onclick="add_more_records()">+ More Records</button>
        </div>
    </div>


    <script src="<?php echo BASE_PATH; ?>assets/js/sidebar-menu.js"></script>
    <script src="<?php echo BASE_PATH; ?>assets/js/project/motor_data_report.js"></script>

    <?php
    include(BASE_PATH . "assets/html/body-end.php");
    include(BASE_PATH . "assets/html/html-end.php");
    ?>
</body>

</html>