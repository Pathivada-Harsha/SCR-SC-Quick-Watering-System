<?php
require_once 'config-path.php';
require_once '../session/session-manager.php';
SessionManager::checkSession();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <title>Downloads</title>
    <?php include(BASE_PATH . "assets/html/start-page.php"); ?>

    <style>
        :root {
            --header-bg: #4a6da7;
            --phase-r: #e74c3c;
            --phase-y: #f39c12;
            --phase-b: #3498db;
            --phase-total: #27ae60;
            --energy: #9b59b6;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition-speed: 0.3s;
        }

        .header_card {
            background-color: var(--header-bg);
            color: white;
            border-radius: 8px 8px 0 0;
            border: none;
            box-shadow: var(--card-shadow);
        }

        .main-card {
            border-radius: 8px;
            box-shadow: var(--card-shadow);
            border: none;
            transition: transform var(--transition-speed);
        }




        .info-icon {
            color: rgba(255, 255, 255, 0.8);
            transition: color var(--transition-speed);
            display: flex;
            align-items: center;
        }

        .info-icon:hover {
            color: white;
        }

        .icon-sm {
            font-size: 0.875rem;
        }

        .icon-md {
            font-size: 1rem;
        }


        .card-footer {
            /* background-color: #f8f9fa; */
            border-top: 1px solid #eee;
            border-radius: 0 0 8px 8px;
        }

        .text-danger {
            font-size: 0.9rem;
        }

        .breadcrumb-text {
            /* color: #6c757d; */
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-card {
                margin: 0 10px;
            }
        }
    </style>
</head>

<body>
    <div class="d-flex flex-column flex-shrink-0 p-3 main-content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <p class="breadcrumb-text m-0">
                        <i class="bi bi-download"></i> Pages / <span class="fw-medium">Download Data</span>
                    </p>

                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-sm-10">
                    <form action="../downloads/data-download.php" method="post" id="download-form">
                        <div class="card main-card mt-3">
                            <div class="card-header header_card d-flex justify-content-between align-items-center py-3">
                                <h5 class="mb-0 fw-bold">Download Data</h5>
                                <a tabindex="0" role="button" class="info-icon"
                                    data-bs-toggle="popover"
                                    data-bs-trigger="focus"
                                    data-bs-title="Information"
                                    data-bs-content="Use this form to download device data. Select a motor and date range to proceed.">
                                    <i class="bi bi-info-circle icon-md"></i>
                                </a>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <label for="select-device" class="form-label">Select Motor:</label>
                                    <div class="input-group">
                                        <span class="input-group-text ">
                                            <i class="fas fa-cog text-primary"></i>
                                        </span>
                                        <?php include(BASE_PATH . "dropdown-selection/device_selection.php"); ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="date-range" class="form-label">Select Date Range:</label>
                                    <div class="input-group">
                                        <span class="input-group-text ">
                                            <i class="fas fa-calendar-alt text-primary"></i>
                                        </span>
                                        <input type="date" id="date-range" class="form-control" name="date-range" placeholder="Select Date Range">
                                    </div>
                                </div>

                                <div class="d-grid gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center">
                                        <i class="bi bi-download icon-sm me-2"></i>Download Data
                                    </button>
                                </div>
                            </div>
                            <div class="card-footer py-3">
                                <div class="alert alert-warning mb-0 py-2 d-flex align-items-center" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill icon-sm me-2"></i>
                                    <span><strong>Note:</strong> Maximum 30 days of data can be downloaded at once.</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?php echo BASE_PATH; ?>js_modal_scripts/popover.js"></script>
    <script src="<?php echo BASE_PATH; ?>assets/js/sidebar-menu.js"></script>
    <script src="<?php echo BASE_PATH; ?>assets/js/date-range-picker.min.js"></script>
    <script src="<?php echo BASE_PATH; ?>assets/js/date-range-picker.js"></script>

    <script type="text/javascript">
        // Initialize date picker with maximum 30 days range
        initializeDateRangePicker("#date-range", 29);

        // Function to get selected date range
        function getSelectedDateRange() {
            const selectedDates = window.fp.selectedDates;
            if (selectedDates.length === 2) {
                const [startDate, endDate] = selectedDates;
                return {
                    startDate: startDate,
                    endDate: endDate
                };
            } else {
                return null;
            }
        }

        // Form validation
        document.getElementById('download-form').addEventListener('submit', function(event) {
            const dateRange = document.getElementById('date-range').value;
            const deviceSelect = document.getElementById('select-device');

            if (!dateRange || deviceSelect.value === '') {
                event.preventDefault();
                alert('Please select both a motor and date range before downloading.');
            }
        });
    </script>

    <?php
    include(BASE_PATH . "assets/html/body-end.php");
    include(BASE_PATH . "assets/html/html-end.php");
    ?>
</body>

</html>