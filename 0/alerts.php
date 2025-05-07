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
    <title>Alerts</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --color-bg: #f8f9fa;
            --color-text: #212529;
            --color-primary: #4361ee;
            --color-secondary: #3f37c9;
            --color-success: #4cc9f0;
            --color-danger: #f72585;
            --color-warning: #f8961e;
            --color-info: #4895ef;
            --color-light: #f1faee;
            --color-dark: #1d3557;
            --color-white: #ffffff;
            --color-border: #dee2e6;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --transition: all 0.3s ease;
        }





        h1 {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: var(--color-dark);
            font-weight: 600;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        .btn-refresh:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .alerts-container {
            margin-top: 15px;
            border-radius: var(--radius-lg);
            overflow: hidden;
            /* background-color: var(--color-white); */
            box-shadow: var(--shadow-md);
            max-height: 600px;
            overflow-y: auto;
        }

        .alert-item {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            padding: 12px 15px;
            border-bottom: 1px solid var(--color-border);
            transition: var(--transition);
        }

        .alert-item:last-child {
            border-bottom: none;
        }

        .alert-item:hover {
            background-color: rgba(241, 250, 238, 0.5);
        }

        .motor-id {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--color-light);
            color: var(--color-dark);
            font-weight: 600;
            padding: 6px 10px;
            border-radius: var(--radius-md);
            font-size: 0.85rem;
            box-shadow: var(--shadow-sm);
            margin-right: 12px;
            min-width: 70px;
        }

        .alert-message {
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            font-weight: 500;
            flex: 1;
            margin: 6px 0;
        }

        .alert-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            margin-right: 12px;
            color: var(--color-white);
            flex-shrink: 0;
        }

        .alert-datetime {
            font-size: 0.8rem;
            /* color: #6c757d; */
            font-weight: 500;
            white-space: nowrap;
            margin-left: auto;
            padding-left: 10px;
        }

        /* Alert type styles */
        .alert-icon.power-restored {
            background-color: var(--color-success);
        }

        .alert-icon.power-disconnected {
            background-color: var(--color-danger);
        }

        .alert-icon.overload {
            background-color: var(--color-warning);
        }

        .alert-icon.drive-run {
            background-color: var(--color-info);
        }

        /* Loading spinner enhancement */
        .loading-spinner {
            color: #3b82f6;
            width: 1.5rem;
            height: 1.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 767px) {
            .alert-item {
                padding: 10px 12px;
            }

            .motor-id {
                min-width: 60px;
                font-size: 0.8rem;
                padding: 4px 8px;
            }

            .alert-message {
                font-size: 0.9rem;
            }

            .alert-icon {
                width: 24px;
                height: 24px;
                margin-right: 8px;
            }

            .alert-datetime {
                width: 100%;
                text-align: left;
                margin-top: 4px;
                margin-left: 82px;
                padding-left: 0;
            }
        }

        /* Empty state styling */
        .empty-state {
            padding: 30px;
            text-align: center;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        /* Loading state styling */
        .loading {
            padding: 30px;
            text-align: center;
            color: #6c757d;
        }

        .spinner {
            border: 3px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 3px solid var(--color-primary);
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <?php
    include(BASE_PATH . "assets/html/start-page.php");
    ?>
    <div class="d-flex flex-column flex-shrink-0 main-content mt-4">
        <div class="container-fluid">
            <!-- Breadcrumb -->
            <div class="row d-flex align-items-center mb-3">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <i class="bi bi-bell-fill"></i>
                                <a href="#" class="text-decoration-none text-dark text-muted">Pages</a>
                            </li>
                            <li class="breadcrumb-item active fw-medium">Alerts</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Controls -->
            <div class="row g-2">
                <!-- Motor Dropdown -->
                <div class="col-md-4 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body p-2">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-cog text-primary"></i>
                                </span>
                                <select class="form-select" id="motor-list">
                                    <option value="ALL" selected > All </option>
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

                <!-- Date Picker -->
                <div class="col-md-4 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body p-2">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-calendar-alt text-primary"></i>
                                </span>
                                <input type="date" id="dateFilter" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Refresh Button -->
                <div class="col-md-4 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body p-2">
                            <button type="button" class="btn btn-primary w-100" id="refreshBtn" onclick="refreshData()">
                                <i class="fas fa-sync-alt me-2"></i>Refresh Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alerts List -->
            <div class="card mt-3">
                <div class="card-header  py-2">
                    <h5 class="card-title mb-0">Alert History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="alerts-container" id="alertsList">
                        <!-- Alert items will be populated here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

 

    <script src="<?php echo BASE_PATH; ?>assets/js/sidebar-menu.js"></script>
    <script src="<?php echo BASE_PATH; ?>assets/js/project/motor-alerts.js"></script>
    <?php
    include(BASE_PATH . "assets/html/body-end.php");
    include(BASE_PATH . "assets/html/html-end.php");
    ?>
</body>

</html>