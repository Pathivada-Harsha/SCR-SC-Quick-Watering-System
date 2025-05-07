// function updateDashboardData() {
//     $.ajax({
//         url: '../dashboard/code/update_switchpoints.php',
//         type: 'POST',
//         dataType: 'json',
//         success: function (data) {
//             // Update Operation Mode
//             $('#operation-mode-display').text(data.operation_mode.toUpperCase());

//             // Make sure the pulse dot is preserved by appending it after setting text
//             if (!$('#operation-mode-display .pulse-dot').length) {
//                 $('#operation-mode-display').append('<span class="pulse-dot"></span>');
//             }

//             // Check the operation mode subtitle and update accordingly
//             if (data.operation_mode.toLowerCase() == 'auto') {
//                 $('#operation-mode-subtitle').text('Automatic Operation');
//             } else if (data.operation_mode.toLowerCase() === 'oem') {
//                 $('#operation-mode-subtitle').text('OEM Operation');
//             }
//             else if (data.operation_mode.toLowerCase() === 'off') {
//                 $('#operation-mode-subtitle').text('NO Operation');
//             }
//             else {
//                 $('#operation-mode-subtitle').text(data.operation_mode); // Fallback for other values
//             }

//             // Update Inlet Pressure/Level
//             if (parseInt(data.inlet_pressure) === 1) {
//                 // First update the text and classes
//                 const inletPressureEl = $('#inlet-pressure-status');
//                 inletPressureEl
//                     .removeClass('badge-outline-danger')  // Remove any "No" related class
//                     .addClass('badge-outline-success');   // Add "Yes" related class

//                 // Remove existing text nodes without affecting child elements
//                 inletPressureEl.contents().filter(function () {
//                     return this.nodeType === 3; // Text nodes
//                 }).remove();

//                 // Insert the new text at the beginning
//                 inletPressureEl.prepend('Yes');

//                 // Add the pulse dot if it doesn't exist
//                 if (inletPressureEl.find('.pulse-dot').length === 0) {
//                     inletPressureEl.append('<span class="pulse-dot"></span>');
//                 }

//                 // Make sure the dot is visible
//                 inletPressureEl.find('.pulse-dot').show();
//             } else {
//                 // First update the text and classes
//                 const inletPressureEl = $('#inlet-pressure-status');
//                 inletPressureEl
//                     .removeClass('badge-outline-success')  // Remove any "Yes" related class
//                     .addClass('badge-outline-danger');     // Add "No" related class

//                 // Remove existing text nodes without affecting child elements
//                 inletPressureEl.contents().filter(function () {
//                     return this.nodeType === 3; // Text nodes
//                 }).remove();

//                 // Insert the new text at the beginning
//                 inletPressureEl.prepend('No');

//                 // Hide the pulse dot
//                 inletPressureEl.find('.pulse-dot').hide();
//             }

//             // Update Outlet Pressure
//             $('#outlet-pressure-1').text(data.outlet_pressure_1 + ' kg/m²');
//             $('#outlet-pressure-2').text(data.outlet_pressure_2 + ' kg/m²');

//             // Update Motor Details
//             for (let i = 1; i <= 6; i++) {
//                 const statusKey = `m${i}_on_of_status`;
//                 const flowRateKey = `m${i}_flow_rate`;
//                 const runtimeKey = `m${i}_last_time_running`;

//                 const motorStatus = data[statusKey] === '1' ? 'ON' : 'OFF';
//                 const flowRate = data[flowRateKey];
//                 const runningTime = data[runtimeKey];

//                 const motorStatusElement = $(`#motor-${i}-status`);
//                 motorStatusElement.empty();

//                 motorStatusElement
//                     .attr('class', motorStatus === 'ON' ? 'badge-success' : 'badge-danger')
//                     .append('<i class="bi bi-power"></i> ')
//                     .append(document.createTextNode(motorStatus));

//                 if (motorStatus === 'ON') {
//                     if (motorStatusElement.find('.pulse-dot').length === 0) {
//                         motorStatusElement.append('<span class="pulse-dot"></span>');
//                     }
//                 }

//                 $(`#motor-${i}-flow`)
//                     .text(`Flow Rate: ${flowRate} L/min`)
//                     .attr('class', motorStatus === 'ON' ? 'flow-value' : 'flow-value flow-inactive');

//                 $(`#motor-${i}-runtime`).text(`${runningTime} min`);
//             }

//             // Update Platform Details
//             const platforms = [
//                 { id: 'platform-1-2', statusKey: 'p1_p2_on_off_status', timeKey: 'p1_p2_last_open_time' },
//                 { id: 'platform-3-4', statusKey: 'p3_p4_on_off_status', timeKey: 'p3_p4_last_open_time' },
//                 { id: 'platform-5-6', statusKey: 'p5_p6_on_off_status', timeKey: 'p5_p6_last_open_time' },
//                 { id: 'platform-7', statusKey: 'p7_on_off_status', timeKey: 'p7_last_open_time' },
//                 { id: 'platform-8', statusKey: 'p8_on_off_status', timeKey: 'p8_last_open_time' },
//                 { id: 'platform-9-10', statusKey: 'p9_p10_on_off_status', timeKey: 'p9_p10_last_open_time' }
//             ];

//             platforms.forEach(({ id, statusKey, timeKey }) => {
//                 const status = parseInt(data[statusKey]) === 1 ? 'Open' : 'Closed';
//                 const time = data[timeKey] ?? 0;

//                 const platformElement = $(`#${id}-status`);
//                 platformElement.empty(); // Clear previous contents

//                 // Apply new class based on status
//                 platformElement
//                     .attr('class', status === 'Open' ? 'badge-success' : 'badge-danger')
//                     .append(document.createTextNode(status));

//                 // Append pulse-dot if open
//                 if (status === 'Open') {
//                     if (platformElement.find('.pulse-dot').length === 0) {
//                         platformElement.append('<span class="pulse-dot"></span>');
//                     }
//                 }

//                 // Update open time
//                 $(`#${id}-time`).text(`${time} min`);
//             });

//             document.getElementById('auto_update_date_time').innerHTML = data.date_time;
//             // Update Electrical Details for active tab
//             const activeMotorTab = $('.tab-content.active').attr('id');
//             const motorNumber = activeMotorTab.replace('motor_', '');
//             // console.log(activeMotorTab, motorNumber);

//             // Update electrical details for the active motor tab
//             fetchAndUpdateMotorDetails(activeMotorTab, motorNumber);
//         },
//         error: function (xhr, status, error) {
//             console.error('Error fetching dashboard data:', error);
//         }
//     });
// }

// // Helper function to update electrical details for a specific motor
// function updateElectricalDetails(motorNumber, details) {
//     document.getElementById(`motor-${motorNumber}-voltage`).textContent = details.lineVoltage + " " + 'V';
//     document.getElementById(`motor-${motorNumber}-current`).textContent = details.motorCurrent + " " + 'A';
//     document.getElementById(`motor-${motorNumber}-kwh`).textContent = details.energyKwh;
//     // document.getElementById(`motor-${motorNumber}-kvah`).textContent = details.energyKvah;
//     document.getElementById(`motor-${motorNumber}-frequency`).textContent = details.frequency + " " + 'Hz';
//     document.getElementById(`motor-${motorNumber}-speed`).textContent = details.speed + " " + 'RPM';
//     document.getElementById(`motor-${motorNumber}-hours`).textContent = details.runningHours + " " + 'hrs';
// }

// // Set up refresh interval (e.g., every 5 seconds)
// const refreshInterval = 100000; // 5 seconds
// setInterval(updateDashboardData, refreshInterval);

// // Initial data load
// document.addEventListener('DOMContentLoaded', updateDashboardData);

// // Add event listeners for tab changes to update electrical details
// // Function to fetch motor details and update the electrical details
// function fetchAndUpdateMotorDetails(motor_id, motor_number) {
//     // Fetch latest data for the selected motor
//     $.ajax({
//         type: 'POST',
//         url: '../dashboard/code/update_motortab_data.php',
//         data: { motor: motor_id },
//         dataType: 'json',
//         success: function (data) {

//             updateElectricalDetails(motor_number, data);
//         },
//         error: function (xhr, status, error) {
//             console.error(`Error fetching motor ${motor_id} details:`, error);
//         }
//     });

// }
// document.querySelectorAll('.tab-trigger').forEach(trigger => {
//     trigger.addEventListener('click', () => {
//         const activeMotorTab = trigger.getAttribute('data-tab'); // e.g., 'motor_1'
//         const motorNumber = activeMotorTab.replace('motor_', '');


//         // Remove 'active' from all tab triggers
//         document.querySelectorAll('.tab-trigger').forEach(tab => {
//             tab.classList.remove('active');
//         });

//         // Add 'active' class to the clicked tab trigger
//         trigger.classList.add('active');

//         // Remove 'active' from all tab contents
//         document.querySelectorAll('.tab-content').forEach(tab => {
//             tab.classList.remove('active');
//         });

//         // Add 'active' to the selected tab content
//         document.getElementById(activeMotorTab).classList.add('active');

//         // Update electrical details for the active motor tab
//         fetchAndUpdateMotorDetails(activeMotorTab, motorNumber);
//     });
// });



function getMqttData() {
    $.ajax({
        type: 'POST',
        url: '../common-files/get_mqtt_credentials.php',
        dataType: 'json',
        success: function (data) {
            // Connect client
            const options = {
                username: data.username,
                password: data.password,
                reconnectPeriod: data.reconnectPeriod,
                connectTimeout: data.connectTimeout,
                clean: data.clean,
            };

            console.log(options);

            const brokerUrl = 'ws://95.111.238.141:8083/mqtt';
            const client = mqtt.connect(brokerUrl, options);
            const topic = 'test/topic';

            client.on('connect', function () {
                console.log('Connected');
                client.subscribe(topic, function (err) {
                    if (err) {
                        console.error('Subscribe error:', err);
                    } else {
                        console.log('Subscribed to', topic);
                    }
                });
            });

            client.on('message', function (topic, message) {
                console.log('Received message:', message.toString());
                let data = message.toString();
                let fields = data.split(';').map(item => item.trim());
                console.log('Split fields:', fields);

                const mqttData = {
                    mode: fields[0],
                    inPressure: fields[1],
                    outPressure1: fields[2],
                    outPressure2: fields[3],
                    motors: [
                        { status: fields[4], flowRate: fields[5], time: fields[6] },
                        { status: fields[7], flowRate: fields[8], time: fields[9] },
                        { status: fields[10], flowRate: fields[11], time: fields[12] },
                        { status: fields[13], flowRate: fields[14], time: fields[15] },
                        { status: fields[16], flowRate: fields[17], time: fields[18] },
                        { status: fields[19], flowRate: fields[20], time: fields[21] }
                    ],
                    powerFailures: [
                        { status: fields[22], time: fields[23] },
                        { status: fields[24], time: fields[25] },
                        { status: fields[26], time: fields[27] },
                        { status: fields[28], time: fields[29] },
                        { status: fields[30], time: fields[31] },
                        { status: fields[32], time: fields[33] }
                    ],
                    dateTime: fields[34],
                    crc: fields[35]
                };

                updateDashboardAfterFrameLoad(mqttData);
            });

            client.on('close', function () {
                console.log('Connection lost');
            });
        },
        error: function (xhr, status, error) {
            console.error(`Error fetching motor details:`, error);
        }
    });
}



function updateDashboardAfterFrameLoad(data) {
    // Update Operation Mode
    $('#operation-mode-display').text(data.mode.toUpperCase());

    if (!$('#operation-mode-display .pulse-dot').length) {
        $('#operation-mode-display').append('<span class="pulse-dot"></span>');
    }

    const operationMode = data.mode.toLowerCase();
    const modeTextMap = {
        'auto': 'Automatic Operation',
        'oem': 'OEM Operation',
        'off': 'NO Operation'
    };
    $('#operation-mode-subtitle').text(modeTextMap[operationMode] || data.mode);

    // Update Inlet Pressure/Level
    const inletPressureEl = $('#inlet-pressure-status');
    const isInletActive = parseInt(data.inPressure) === 1;

    inletPressureEl
        .removeClass(isInletActive ? 'badge-outline-danger' : 'badge-outline-success')
        .addClass(isInletActive ? 'badge-outline-success' : 'badge-outline-danger')
        .contents().filter(function () { return this.nodeType === 3; }).remove();

    inletPressureEl.prepend(isInletActive ? 'Yes' : 'No');

    if (inletPressureEl.find('.pulse-dot').length === 0) {
        inletPressureEl.append('<span class="pulse-dot"></span>');
    }
    inletPressureEl.find('.pulse-dot')[isInletActive ? 'show' : 'hide']();

    // Update Outlet Pressure
    $('#outlet-pressure-1').text(data.outPressure1 + ' kg/m²');
    $('#outlet-pressure-2').text(data.outPressure2 + ' kg/m²');

    // Update Motors
    for (let i = 1; i <= 6; i++) {  // Adjusted for 6 motors
        const motorData = data.motors[i - 1];

        const motorStatus = motorData.status === '1' ? 'ON' : 'OFF';
        const flowRate = motorData.flowRate;
        const runningTime = motorData.time;

        const motorStatusElement = $(`#motor-${i}-status`);
        motorStatusElement.empty();
        motorStatusElement
            .attr('class', motorStatus === 'ON' ? 'badge-success' : 'badge-danger')
            .append('<i class="bi bi-power"></i> ')
            .append(document.createTextNode(motorStatus));

        if (motorStatus === 'ON' && motorStatusElement.find('.pulse-dot').length === 0) {
            motorStatusElement.append('<span class="pulse-dot"></span>');
        }

        $(`#motor-${i}-flow`)
            .text(`Flow Rate: ${flowRate} L/min`)
            .attr('class', motorStatus === 'ON' ? 'flow-value' : 'flow-value flow-inactive');

        $(`#motor-${i}-runtime`).text(`${runningTime} min`);
    }

    // Update Platforms
    const platforms = [
        { id: 'platform-1-2', statusKey: 'p1_p2_on_off_status', timeKey: 'p1_p2_last_open_time' },
        { id: 'platform-3-4', statusKey: 'p3_p4_on_off_status', timeKey: 'p3_p4_last_open_time' },
        { id: 'platform-5-6', statusKey: 'p5_p6_on_off_status', timeKey: 'p5_p6_last_open_time' },
        { id: 'platform-7', statusKey: 'p7_on_off_status', timeKey: 'p7_last_open_time' },
        { id: 'platform-8', statusKey: 'p8_on_off_status', timeKey: 'p8_last_open_time' },
        { id: 'platform-9-10', statusKey: 'p9_p10_on_off_status', timeKey: 'p9_p10_last_open_time' }
    ];

    platforms.forEach(({ id, statusKey, timeKey }) => {
        const status = parseInt(data[statusKey]) === 1 ? 'Open' : 'Closed';
        const time = data[timeKey] ?? 0;

        const platformElement = $(`#${id}-status`);
        platformElement.empty()
            .attr('class', status === 'Open' ? 'badge-success' : 'badge-danger')
            .append(document.createTextNode(status));

        if (status === 'Open' && platformElement.find('.pulse-dot').length === 0) {
            platformElement.append('<span class="pulse-dot"></span>');
        }

        $(`#${id}-time`).text(`${time} min`);
    });

    // Update Date-Time
    $('#auto_update_date_time').text(data.dateTime);

    // Update Electrical Details (you can call fetchAndUpdateMotorDetails if needed)
    const activeMotorTab = $('.tab-content.active').attr('id');
    // const motorNumber = activeMotorTab.replace('motor_', '');
    // fetchAndUpdateMotorDetails(activeMotorTab, motorNumber);
}