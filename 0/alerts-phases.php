<?php
require_once 'config-path.php';
require_once '../session/session-manager.php';
SessionManager::checkSession();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phase Alerts</title>
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
            padding: 0.35rem 0.85rem;
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: 50px;
            color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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

        .table-container {
            border-radius: 12px;
            overflow: hidden;
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
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
    </style>
</head>

<body>
    <?php
    include(BASE_PATH . "assets/html/start-page.php");
    ?>
    <div class="d-flex flex-column flex-shrink-0 p-4 main-content">
        <div class="container-fluid">
            <div class="row d-flex align-items-center mb-1">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <i class="fas fa-home me-1"></i>
                                <a href="#" class="text-decoration-none">Pages</a>
                            </li>
                            <li class="breadcrumb-item active fw-medium">Phase Alerts</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row mb-2 justify-content-end">
                <div class="col-md-6">
                    <div class="card " style="background-color: transparent; border: none; box-shadow: none;">
                        <div class="card-body">

                            <label class="form-label text-muted mb-2">Select Motor</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-cog text-primary"></i>
                                </span>
                                <select class="form-select" id="motorSelect">
                                    <option value="motor_1">Motor 1</option>
                                    <option value="motor_2">Motor 2</option>
                                    <option value="motor_3">Motor 3</option>
                                    <option value="motor_4">Motor 4</option>
                                    <option value="motor_5">Motor 5</option>
                                    <option value="motor_6">Motor 6</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label text-muted">Date Filter</label>
                                    <input type="date" id="dateFilter" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted">Alert Type</label>
                                    <select class="form-select" id="alertType">
                                        <option value="ALL">All Alerts</option>

                                        <option value="Voltage">Voltage</option>
                                        <option value="Current">OverLoad / Current</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary w-100" onclick="refreshData()">
                                        <i class="fas fa-sync-alt me-2"></i>Refresh Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-bordered motor-report-table mb-0">
                                <thead>
                                    <tr>
                                        <th class="table-header-row11" style="color:white;">Motor ID</th>
                                        <th class="table-header-row11" style="color:white;">Alerts</th>
                                        <th class="table-header-row11 phase-b" colspan="3">Phase status</th>

                                        <th class="table-header-row11 phase-r" colspan="3">Phase Voltages (V)</th>
                                        <th class="table-header-row11 phase-y" colspan="3">Phase Currents (A)</th>
                                        <th class="table-header-row11" style="color:white;">Date&Time</th>

                                    </tr>

                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th class="phase-r-bg">R</th>
                                        <th class="phase-r-bg">Y</th>
                                        <th class="phase-r-bg">B</th>
                                        <th class="phase-r-bg">R</th>
                                        <th class="phase-r-bg">Y</th>
                                        <th class="phase-r-bg">B</th>
                                        <th class="phase-y-bg">R</th>
                                        <th class="phase-y-bg">Y</th>
                                        <th class="phase-y-bg">B</th>
                                        <th></th>
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
        </div>

        <script>
            // Function to generate random number between min and max (inclusive)
            function getRandomNumber(min, max) {
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }

            // Function to generate random data for a single motor alert entry
            function generateRandomAlert(motorId, alertType) {
                // Define normal ranges
                const voltageNormalMin = 225;
                const voltageNormalMax = 245;
                const currentNormalMax = 35;

                // Generate random voltage values (220-250V range)
                const voltageR = getRandomNumber(220, 250);
                const voltageY = getRandomNumber(220, 250);
                const voltageB = getRandomNumber(220, 250);

                // Generate random current values (10-40A range)
                const currentR = getRandomNumber(10, 40);
                const currentY = getRandomNumber(10, 40);
                const currentB = getRandomNumber(10, 40);

                // Determine phase status (normal, low, high)
                const phaseRStatus = Math.random() > 0.9 ? "low" : (Math.random() > 0.8 ? "high" : "normal");
                const phaseYStatus = Math.random() > 0.9 ? "low" : (Math.random() > 0.8 ? "high" : "normal");
                const phaseBStatus = Math.random() > 0.9 ? "low" : (Math.random() > 0.8 ? "high" : "normal");

                // Determine voltage status for each phase
                const voltageRStatus = voltageR < voltageNormalMin ? "low" : (voltageR > voltageNormalMax ? "high" : "normal");
                const voltageYStatus = voltageY < voltageNormalMin ? "low" : (voltageY > voltageNormalMax ? "high" : "normal");
                const voltageBStatus = voltageB < voltageNormalMin ? "low" : (voltageB > voltageNormalMax ? "high" : "normal");

                // Determine current status for each phase
                const currentRStatus = currentR > currentNormalMax ? "high" : "normal";
                const currentYStatus = currentY > currentNormalMax ? "high" : "normal";
                const currentBStatus = currentB > currentNormalMax ? "high" : "normal";

                // Determine if there's an alert and what type
                let voltageAlert = false;
                let currentAlert = false;

                // Check for voltage alerts
                if ((alertType === "ALL" || alertType === "Voltage") &&
                    (voltageRStatus !== "normal" || voltageYStatus !== "normal" || voltageBStatus !== "normal" ||
                        phaseRStatus !== "normal" || phaseYStatus !== "normal" || phaseBStatus !== "normal")) {
                    voltageAlert = true;
                }

                // Check for current alerts
                if ((alertType === "ALL" || alertType === "Current") &&
                    (currentRStatus !== "normal" || currentYStatus !== "normal" || currentBStatus !== "normal")) {
                    currentAlert = true;
                }

                // For simplicity, we'll only have one type of alert per row randomly selected
                if (voltageAlert && currentAlert) {
                    if (Math.random() > 0.5) {
                        voltageAlert = false;
                    } else {
                        currentAlert = false;
                    }
                }

                // Generate a random datetime within the last 24 hours
                const now = new Date();
                const randomMinutesAgo = getRandomNumber(0, 1440); // Random minutes in 24 hours
                const alertTime = new Date(now - randomMinutesAgo * 60000);
                const formattedDateTime = alertTime.toLocaleString('en-GB', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                });

                return {
                    motorId: motorId,
                    voltageAlert: voltageAlert,
                    currentAlert: currentAlert,
                    phaseRStatus: phaseRStatus,
                    phaseYStatus: phaseYStatus,
                    phaseBStatus: phaseBStatus,
                    voltageR: voltageR,
                    voltageY: voltageY,
                    voltageB: voltageB,
                    voltageRStatus: voltageRStatus,
                    voltageYStatus: voltageYStatus,
                    voltageBStatus: voltageBStatus,
                    currentR: currentR,
                    currentY: currentY,
                    currentB: currentB,
                    currentRStatus: currentRStatus,
                    currentYStatus: currentYStatus,
                    currentBStatus: currentBStatus,
                    dateTime: formattedDateTime
                };
            }

            // Function to generate multiple random alert entries
            // Function to generate multiple random alert entries
            function generateRandomAlerts(motorId, alertType, count = 10) {
                const alerts = [];
                let attempts = 0;

                while (alerts.length < count && attempts < count * 5) {
                    const alert = generateRandomAlert(motorId, alertType);
                    if (alert.voltageAlert || alert.currentAlert) {
                        alerts.push(alert);
                    }
                    attempts++; // Prevent infinite loop in case of low alert probability
                }

                return alerts;
            }


            // Function to get status color class
            function getStatusColorClass(status) {
                switch (status) {
                    case "normal":
                        return "text-success";
                    case "low":
                        return "text-warning";
                    case "high":
                        return "text-danger";
                    default:
                        return "";
                }
            }

            // Function to get status icon
            function getStatusIcon(status) {
                switch (status) {
                    case "normal":
                        return '<i class="fas fa-check-circle text-success"></i>';
                    case "low":
                        return '<i class="fas fa-arrow-down text-warning"></i>';
                    case "high":
                        return '<i class="fas fa-arrow-up text-danger"></i>';
                    default:
                        return '';
                }
            }

            // Function to update the table with the generated data
            function updateTable(alerts) {
                const tableBody = document.getElementById('frame_data_table');
                tableBody.innerHTML = ''; // Clear existing rows

                if (alerts.length === 0) {
                    tableBody.innerHTML = `
            <tr>
                <td colspan="12" class="text-center py-4">
                    <p class="mt-2 text-muted">No alerts found for the selected criteria.</p>
                </td>
            </tr>
        `;
                    return;
                }

                // Add the new data
                alerts.forEach((alert, index) => {
                    const rowClass = index % 2 === 0 ? 'table-row-even' : 'table-row-odd';
                    const row = document.createElement('tr');
                    row.className = rowClass;

                    // Determine alert badge - just show the alert type
                    let alertBadge = '';
                    if (alert.voltageAlert) {
                        alertBadge = `<span class="badge bg-danger">Voltage</span>`;
                    } else if (alert.currentAlert) {
                        alertBadge = `<span class="badge bg-warning text-dark">Current</span>`;
                    } else {
                        alertBadge = `<span class="badge bg-success">Normal</span>`;
                    }

                    row.innerHTML = `
            <td>${alert.motorId}</td>
            <td>${alertBadge}</td>
            <td>${getStatusIcon(alert.phaseRStatus)} <span class="${getStatusColorClass(alert.phaseRStatus)}">${alert.phaseRStatus.toUpperCase()}</span></td>
            <td>${getStatusIcon(alert.phaseYStatus)} <span class="${getStatusColorClass(alert.phaseYStatus)}">${alert.phaseYStatus.toUpperCase()}</span></td>
            <td>${getStatusIcon(alert.phaseBStatus)} <span class="${getStatusColorClass(alert.phaseBStatus)}">${alert.phaseBStatus.toUpperCase()}</span></td>
            <td class="${getStatusColorClass(alert.voltageRStatus)}">${alert.voltageR}
                <div class="small ${getStatusColorClass(alert.voltageRStatus)}">${alert.voltageRStatus.toUpperCase()}</div>
            </td>
            <td class="${getStatusColorClass(alert.voltageYStatus)}">${alert.voltageY}
                <div class="small ${getStatusColorClass(alert.voltageYStatus)}">${alert.voltageYStatus.toUpperCase()}</div>
            </td>
            <td class="${getStatusColorClass(alert.voltageBStatus)}">${alert.voltageB}
                <div class="small ${getStatusColorClass(alert.voltageBStatus)}">${alert.voltageBStatus.toUpperCase()}</div>
            </td>
            <td class="${getStatusColorClass(alert.currentRStatus)}">${alert.currentR}
                <div class="small ${getStatusColorClass(alert.currentRStatus)}">${alert.currentRStatus.toUpperCase()}</div>
            </td>
            <td class="${getStatusColorClass(alert.currentYStatus)}">${alert.currentY}
                <div class="small ${getStatusColorClass(alert.currentYStatus)}">${alert.currentYStatus.toUpperCase()}</div>
            </td>
            <td class="${getStatusColorClass(alert.currentBStatus)}">${alert.currentB}
                <div class="small ${getStatusColorClass(alert.currentBStatus)}">${alert.currentBStatus.toUpperCase()}</div>
            </td>
            <td>${alert.dateTime}</td>
        `;

                    tableBody.appendChild(row);
                });
            }

            // Function to refresh data (called on button click or filter change)
            function refreshData() {
                const motorId = document.getElementById('motorSelect').value;
                const alertType = document.getElementById('alertType').value;
                const selectedDate = document.getElementById('dateFilter').value;

                // Generate between 5-15 random entries
                const count = getRandomNumber(5, 15);
                const alerts = generateRandomAlerts(motorId, alertType, count);

                // Filter by date if needed (this is simulated since we're using random data)
                // In a real application, you would filter based on the selected date

                // Update the table with the new data
                updateTable(alerts);
            }

            // Initialize data when the page loads
            document.addEventListener('DOMContentLoaded', function() {
                refreshData();

                // Add event listeners for filter changes
                document.getElementById('motorSelect').addEventListener('change', refreshData);
                document.getElementById('alertType').addEventListener('change', refreshData);
                document.getElementById('dateFilter').addEventListener('change', refreshData);
            });
        </script>
        <script src="<?php echo BASE_PATH; ?>assets/js/sidebar-menu.js"></script>
        <?php
        include(BASE_PATH . "assets/html/body-end.php");
        include(BASE_PATH . "assets/html/html-end.php");
        ?>
</body>

</html>