

const motorConfig = {
    count: 6, // Total number of motors
    metrics: [
        // Left column metrics
        {
            id: "voltage",
            icon: "bi-lightning-charge-fill",
            title: "Line Voltage",
            valueId: "voltage",
            unit: "V"
        },
        {
            id: "current",
            icon: "bi-plug-fill",
            title: "Motor Current",
            valueId: "current",
            unit: "A"
        },
        {
            id: "refVoltage",
            icon: "bi-lightning-fill",
            title: "Motor Voltage",
            valueId: "motor_voltage",
            unit: "V"
        },
        {
            id: "energy",
            icon: "bi-battery-half",
            title: "Energy (kWh)",
            valueId: "kwh",
            unit: ""
        },

        // Right column metrics
        {
            id: "refFrequency",
            icon: "bi-broadcast-pin",
            title: "Reference Frequency",
            valueId: "ref_frequency",
            unit: "Hz"
        },
        {
            id: "frequency",
            icon: "bi-activity",
            title: "Frequency",
            valueId: "frequency",
            unit: "Hz"
        },
        {
            id: "speed",
            icon: "bi-speedometer2",
            title: "Speed",
            valueId: "speed",
            unit: "RPM"
        },
        {
            id: "hours",
            icon: "bi-clock-history",
            title: "Running Hours",
            valueId: "hours",
            unit: "hrs"
        }
    ],
    // Admin-only metric
    adminMetric: {
        id: "admin-only",
        icon: "bi-shield-lock-fill",
        title: " Drive Status",
        valueId: "admin-status",
        unit: ""
    }
};

// Global variable for MQTT client
let mqttClient = null;
let mqttConnected = false;

// Generate HTML for motor tabs and content
function generateMotorTabsAndContent() {
    const tabsContainer = document.getElementById('motor-tabs');
    const tabContentsContainer = document.getElementById('motor-tab-contents');
    const userRole = document.getElementById('user-role').value;
    const isSuperAdmin = userRole === 'SUPERADMIN';

    // Clear existing content
    tabsContainer.innerHTML = '';
    tabContentsContainer.innerHTML = '';

    // Generate tabs and content for each motor
    for (let i = 1; i <= motorConfig.count; i++) {
        // Create tab trigger
        const tabTrigger = document.createElement('div');
        tabTrigger.className = 'tab-trigger' + (i === 1 ? ' active' : '');
        tabTrigger.setAttribute('data-tab', `motor_${i}`);
        tabTrigger.textContent = `Motor ${i}`;
        tabsContainer.appendChild(tabTrigger);

        // Create tab content
        const tabContent = document.createElement('div');
        tabContent.id = `motor_${i}`;
        tabContent.className = 'tab-content' + (i === 1 ? ' active' : '');

        // Create card
        tabContent.innerHTML = `
<div class="card">
<div class="card-header amber-gradient">
  <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center w-100">
    <h3 class="mb-2 mb-md-0 d-flex align-items-center gap-2">
      <i class="bi bi-cpu-fill fs-4"></i> Motor ${i} Electrical Details
    </h3>
    <div>
      <h3 class="m-0 fs-6 fs-md-5" id="update_time">
        <span class="timestamp-value">Updated On: </span>
        <span id="motor-${i}motor_update_date_time"></span>
      </h3>
    </div>
  </div>
</div>
<div class="card-body">
  <div class="grid-2-cols">
    <!-- Left Side -->
    <div id="motor-${i}-left-metrics">
      ${generateMetrics(i, 0, 4, isSuperAdmin)}
      ${isSuperAdmin ? generateAdminMetric(i) : ''}
    </div>
    
    <!-- Right Side -->
    <div id="motor-${i}-right-metrics">
      ${generateMetrics(i, 4, 8, isSuperAdmin)}
    </div>
  </div>
</div>
</div>
`;

        tabContentsContainer.appendChild(tabContent);
    }

    // Add event listeners for tab changes
    addTabEventListeners();
}

// Generate HTML for a set of metrics
function generateMetrics(motorId, startIndex, endIndex, isSuperAdmin) {
    let metricsHtml = '';

    for (let i = startIndex; i < endIndex; i++) {
        const metric = motorConfig.metrics[i];

        metricsHtml += `
<div class="metric-row ${metric.id}">
<div class="d-flex align-items-center">
  <div class="metric-icon icon-${metric.id}">
    <i class="bi ${metric.icon} fs-5"></i>
  </div>
  <h5 class="section-subtitle">${metric.title}</h5>
</div>
<span id="motor-${motorId}-${metric.valueId}" class="small-card-value">0 ${metric.unit}</span>
</div>
`;
    }

    return metricsHtml;
}

// Generate HTML for admin-only metric
function generateAdminMetric(motorId) {
    const metric = motorConfig.adminMetric;

    return `
<div class="metric-row ${metric.id} admin-only-metric">
<div class="d-flex align-items-center">
<div class="metric-icon icon-${metric.id}">
  <i class="bi ${metric.icon} fs-5"></i>
</div>
<h5 class="section-subtitle">${metric.title}</h5>
</div>
<span id="motor-${motorId}-${metric.valueId}" class="small-card-value">Not Available</span>
</div>
`;
}

// Add event listeners to the tabs
function addTabEventListeners() {
    document.querySelectorAll('.tab-trigger').forEach(trigger => {
        trigger.addEventListener('click', () => {
            const activeMotorTab = trigger.getAttribute('data-tab');
            const motorNumber = activeMotorTab.replace('motor_', '');

            // Remove 'active' from all tab triggers
            document.querySelectorAll('.tab-trigger').forEach(tab => {
                tab.classList.remove('active');
            });

            // Add 'active' class to the clicked tab trigger
            trigger.classList.add('active');

            // Remove 'active' from all tab contents
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });

            // Add 'active' to the selected tab content
            document.getElementById(activeMotorTab).classList.add('active');

            // Update electrical details for the active motor tab
            fetchAndUpdateMotorDetails(activeMotorTab, motorNumber);
        });
    });
}

// Helper function to update electrical details for a specific motor
function updateElectricalDetails(motorNumber, details) {
    motorConfig.metrics.forEach(metric => {
        const elementId = `motor-${motorNumber}-${metric.valueId}`;
        const element = document.getElementById(elementId);

        if (element) {
            const valueMap = {
                'voltage': details.lineVoltage,
                'current': details.motorCurrent,
                'kwh': details.energyKwh,
                'frequency': details.frequency,
                'speed': details.speed,
                'hours': details.runningHours,
                'motor_voltage': details.motor_voltage,
                'ref_frequency': details.referencefrequency,
            };

            // Set update date time
            document.getElementById(`motor-${motorNumber}motor_update_date_time`).innerHTML = details.date_time;

            // Ensure 0 is not treated as "no value"
            const rawValue = valueMap[metric.valueId];
            const value = rawValue !== null && rawValue !== undefined ? rawValue : '0';
            element.textContent = metric.unit ? `${value} ${metric.unit}` : value;
        }
    });

    // Update admin metric if user is superadmin
    const userRole = document.getElementById('user-role').value;
    if (userRole === 'SUPERADMIN') {
        const adminMetricElement = document.getElementById(`motor-${motorNumber}-${motorConfig.adminMetric.valueId}`);
        const a = details.adminStatus;

        if (adminMetricElement && details.adminStatus != null && details.adminStatus !== undefined) {
            adminMetricElement.textContent = details.adminStatus;
        }
    }
}

// Function to fetch motor details and update the electrical details
function fetchAndUpdateMotorDetails(motor_id, motor_number) {
    const userRole = document.getElementById('user-role').value;

    // Fetch latest data for the selected motor from database
    $.ajax({
        type: 'POST',
        url: '../dashboard/code/update_motortab_data.php',
        data: {
            motor: motor_id,
            role: userRole // Pass the role to the server
        },
        dataType: 'json',
        success: function (data) {
            updateElectricalDetails(motor_number, data);
        },
        error: function (xhr, status, error) {
            console.error(`Error fetching motor ${motor_id} details:`, error);
        }
    });
}

// Function to update the dashboard data from database
// function updateDashboardData() {
//     if (mqttConnected) {
//         // Skip AJAX update if MQTT is connected
//         console.log('Skipping AJAX update as MQTT is connected');
//         return;
//     }

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
//             const operationMode = data.operation_mode.toLowerCase();
//             const modeTextMap = {
//                 'auto': 'Automatic Operation',
//                 'oem': 'OEM Operation',
//                 'off': 'NO Operation'
//             };

//             $('#operation-mode-subtitle').text(modeTextMap[operationMode] || data.operation_mode);

//             // Update Inlet Pressure/Level
//             updateInletPressure(parseInt(data.inlet_pressure) === 1);

//             // Update Outlet Pressure
//             $('#outlet-pressure-1').text(data.outlet_pressure_1 + ' kg/m²');
//             $('#outlet-pressure-2').text(data.outlet_pressure_2 + ' kg/m²');

//             // Update Motor Details
//             updateMotorStatuses(data);

//             // Update Platform Details
//             updatePlatformStatuses(data);

//             document.getElementById('auto_update_date_time').innerHTML = data.date_time;

//             // Update Electrical Details for active tab
//             // const activeMotorTab = $('.tab-content.active').attr('id');
//             // const motorNumber = activeMotorTab.replace('motor_', '');
//             // fetchAndUpdateMotorDetails(activeMotorTab, motorNumber);
//         },
//         error: function (xhr, status, error) {
//             console.error('Error fetching dashboard data:', error);
//         }
//     });
// }

// Helper function to update inlet pressure display
function updateInletPressure(isActive) {
    const inletPressureEl = $('#inlet-pressure-status');

    // Update classes
    inletPressureEl
        .removeClass(isActive ? 'badge-outline-danger' : 'badge-outline-success')
        .addClass(isActive ? 'badge-outline-success' : 'badge-outline-danger');

    // Remove existing text nodes without affecting child elements
    inletPressureEl.contents().filter(function () {
        return this.nodeType === 3; // Text nodes
    }).remove();

    // Insert the new text at the beginning
    inletPressureEl.prepend(isActive ? 'Yes' : 'No');

    // Add the pulse dot if it doesn't exist
    if (inletPressureEl.find('.pulse-dot').length === 0) {
        inletPressureEl.append('<span class="pulse-dot"></span>');
    }

    // Show/hide the dot based on status
    inletPressureEl.find('.pulse-dot')[isActive ? 'show' : 'hide']();
}

// Helper function to update motor statuses
function updateMotorStatuses(data) {
    for (let i = 1; i <= motorConfig.count; i++) {
        const statusKey = `m${i}_on_of_status`;
        const flowRateKey = `m${i}_flow_rate`;
        const runtimeKey = `m${i}_last_time_running`;

        const motorStatus = data[statusKey] === '1' ? 'ON' : 'OFF';
        const flowRate = data[flowRateKey];
        const runningTime = data[runtimeKey];

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
}

// Helper function to update platform statuses
function updatePlatformStatuses(data) {
    const platforms = [{
        id: 'platform-1-2',
        statusKey: 'p1_p2_on_off_status',
        timeKey: 'p1_p2_last_open_time'
    },
    {
        id: 'platform-3-4',
        statusKey: 'p3_p4_on_off_status',
        timeKey: 'p3_p4_last_open_time'
    },
    {
        id: 'platform-5-6',
        statusKey: 'p5_p6_on_off_status',
        timeKey: 'p5_p6_last_open_time'
    },
    {
        id: 'platform-7',
        statusKey: 'p7_on_off_status',
        timeKey: 'p7_last_open_time'
    },
    {
        id: 'platform-8',
        statusKey: 'p8_on_off_status',
        timeKey: 'p8_last_open_time'
    },
    {
        id: 'platform-9-10',
        statusKey: 'p9_p10_on_off_status',
        timeKey: 'p9_p10_last_open_time'
    }
    ];

    platforms.forEach(({
        id,
        statusKey,
        timeKey
    }) => {
        const status = parseInt(data[statusKey]) === 1 ? 'Open' : 'Closed';
        const time = data[timeKey] ?? 0;

        const platformElement = $(`#${id}-status`);
        platformElement.empty(); // Clear previous contents

        // Apply new class based on status
        platformElement
            .attr('class', status === 'Open' ? 'badge-success' : 'badge-danger')
            .append(document.createTextNode(status));

        // Append pulse-dot if open
        if (status === 'Open' && platformElement.find('.pulse-dot').length === 0) {
            platformElement.append('<span class="pulse-dot"></span>');
        }

        // Update open time
        $(`#${id}-time`).text(`${time} min`);
    });
}

// MQTT connection and message handling
function requestMqttUpdate() {
    if (mqttClient && mqttConnected) {
        console.log('Requesting data update via MQTT');
        // Publish a request message to a request topic that the publisher is listening to
        mqttClient.publish('test/request', 'data_request');
    }
}

// Modify the MQTT connection function to include the request topic subscription
// function connectMqtt() {
//     $.ajax({
//         type: 'POST',
//         url: '../common-files/get_mqtt_credentials.php',
//         dataType: 'json',
//         success: function (data) {
//             // Connect client

//             console.log(data);
//             const options = {
//                 username: data.username,
//                 password: data.password,
//                 reconnectPeriod: data.reconnectPeriod,
//                 connectTimeout: data.connectTimeout,
//                 clean: data.clean,
//             };

//             console.log('MQTT connection options:', options);

//             const brokerUrl = 'ws://95.111.238.141:8083/mqtt';
//             mqttClient = mqtt.connect(brokerUrl, options);
//             const topic = 'PUB/SCRSC/VFD_STATUS';
//             const requestTopic = 'test/request_response'; // Topic to receive confirmation

//             mqttClient.on('connect', function () {
//                 console.log('MQTT Connected');
//                 mqttConnected = true;

//                 // Subscribe to main data topic
//                 mqttClient.subscribe(topic, function (err) {
//                     if (err) {
//                         console.error('Subscribe error:', err);
//                     } else {
//                         console.log('Subscribed to', topic);
//                     }
//                 });

//                 // Subscribe to request response topic
//                 mqttClient.subscribe(requestTopic, function (err) {
//                     if (err) {
//                         console.error('Subscribe error for request topic:', err);
//                     } else {
//                         console.log('Subscribed to', requestTopic);
//                     }
//                 });

//                 // Request data immediately after connection is established
//                 requestMqttUpdate();
//             });

//             mqttClient.on('message', function (topic, message) {
//                 console.log('Received MQTT message on topic:', topic);
//                 console.log('Message content:', message.toString());

//                 if (topic === 'PUB/SCRSC/VFD_STATUS') {
//                     // Process data message
//                     let data = message.toString();
//                     let fields = data.split(';').map(item => item.trim()); // Split and trim

//                     // Now join all fields with space ' '
//                     // let joinedData = fields.join(' ');

//                     // console.log('Joined Data:', joinedData);


//                     const mqttData = {
//                         mode: fields[0],
//                         inPressure: fields[1],
//                         outPressure1: fields[2],
//                         outPressure2: fields[3],
//                         motors: [
//                             { status: fields[4], flowRate: fields[5], time: fields[6] },
//                             { status: fields[7], flowRate: fields[8], time: fields[9] },
//                             { status: fields[10], flowRate: fields[11], time: fields[12] },
//                             { status: fields[13], flowRate: fields[14], time: fields[15] },
//                             { status: fields[16], flowRate: fields[17], time: fields[18] },
//                             { status: fields[19], flowRate: fields[20], time: fields[21] }
//                         ],
//                         platforms: [
//                             { status: fields[22], time: fields[23] },
//                             { status: fields[24], time: fields[25] },
//                             { status: fields[26], time: fields[27] },
//                             { status: fields[28], time: fields[29] },
//                             { status: fields[30], time: fields[31] },
//                             { status: fields[32], time: fields[33] }
//                         ],
//                         dateTime: fields[34],
//                         crc: fields[35]
//                     };

//                     updateDashboardWithMqttData(mqttData);
//                 } 
//                 else if (topic === 'test/request_response') {
//                     // Handle response to our request if needed
//                     console.log('Request acknowledged by publisher');
//                 }
//             });

//             mqttClient.on('close', function () {
//                 console.log('MQTT Connection lost');
//                 mqttConnected = false;
//             });

//             mqttClient.on('error', function (error) {
//                 console.error('MQTT Error:', error);
//                 mqttConnected = false;
//             });
//         },
//         error: function (xhr, status, error) {
//             console.error(`Error fetching MQTT credentials:`, error);
//             mqttConnected = false;
//         }
//     });
// }

// First, add the CryptoJS library to your HTML page
// <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
function xor_decrypt(encoded, key) {
    try {
        const decoded = atob(encoded);
        let result = '';

        for (let i = 0; i < decoded.length; i++) {
            result += String.fromCharCode(decoded.charCodeAt(i) ^ key.charCodeAt(i % key.length));
        }

        return JSON.parse(result);
    } catch (error) {
        console.error('Decryption failed:', error);
        return null;
    }
}


document.addEventListener('DOMContentLoaded', function () {
    mqttReconnect();
});


let vfdMqttClient;
let isVfdMqttConnected = false;
let isVfdMqttIntervalSet = false;

function mqttReconnect() {
    $.ajax({
        type: 'POST',
        url: '../common-files/get_mqtt_credentials.php',
        dataType: 'json',
        success: function (response) {
            const consoledata = 'consoledata';
            const decryptedData = xor_decrypt(response.data, consoledata);

            if (!decryptedData) {
                console.error('Failed to decrypt MQTT credentials');
                return;
            }

            const options = {
                username: decryptedData.username,
                password: decryptedData.password,
                reconnectPeriod: decryptedData.reconnectPeriod,
                connectTimeout: decryptedData.connectTimeout,
                clean: decryptedData.clean,
            };

            const brokerUrl = decryptedData.brokerUrl;
            vfdMqttClient = mqtt.connect(brokerUrl, options);

            const publishTopic = 'SUB/SCRSC/VFD_SETTING';
            const publishMessage = 'APP;CONNECTED';

            vfdMqttClient.on('connect', () => {
                console.log('Connected to MQTT broker');
                isVfdMqttConnected = true;

                document.getElementById("connection_status").innerHTML = "Connected";
                document.getElementById("connection_status").style.color = "green";

                // Hide reconnect button if it exists
                const btn = document.getElementById("reconnectBtn");
                if (btn) btn.remove();

                vfdMqttClient.publish(publishTopic, publishMessage, (err) => {
                    if (!err) {
                        console.log("Published message after connecting.");
                    } else {
                        console.error('Publish error:', err);
                    }
                });
            });

            vfdMqttClient.on('close', function () {
                console.log('MQTT Connection lost');
                isVfdMqttConnected = false;
                showDisconnectedStatus();
            });

            vfdMqttClient.on('error', function (error) {
                console.error('MQTT Error:', error);
                isVfdMqttConnected = false;
                showDisconnectedStatus();
            });

            if (!isVfdMqttIntervalSet) {
                setInterval(() => {
                    if (vfdMqttClient && vfdMqttClient.connected) {
                        document.getElementById("connection_status").innerHTML = "Connected";
                        document.getElementById("connection_status").style.color = "green";
                    } else {
                        showDisconnectedStatus();
                        console.warn("MQTT disconnected. Waiting for manual reconnect...");
                    }
                }, 30000); 
                isVfdMqttIntervalSet = true;
            }
        },
        error: function (xhr, status, error) {
            console.error(`Error fetching MQTT credentials:`, error);
            isVfdMqttConnected = false;
            showDisconnectedStatus();
        }
    });
}

function showDisconnectedStatus() {
    const statusElement = document.getElementById("connection_status");

    statusElement.innerHTML = `
        Disconnected 
        <button id="reconnectBtn" class="btn btn-sm btn-outline-danger ms-2">
            Reconnect
        </button>
    `;
    statusElement.style.color = "red";

    // Bind the reconnect button event
    const btn = document.getElementById("reconnectBtn");
    if (btn) {
        btn.addEventListener("click", () => {
            mqttReconnect();
        });
    }
}

function connectMqtt() {
    $.ajax({
        type: 'POST',
        url: '../common-files/get_mqtt_credentials.php',
        dataType: 'json',
        success: function (response) {
            const consoledata = 'consoledata';
            const decryptedData = xor_decrypt(response.data, consoledata);

            if (!decryptedData) {
                console.error('Failed to decrypt MQTT credentials');
                return;
            }

            //  console.log('Decrypted data:', decryptedData);
            const options = {
                username: decryptedData.username,
                password: decryptedData.password,
                reconnectPeriod: decryptedData.reconnectPeriod,
                connectTimeout: decryptedData.connectTimeout,
                clean: decryptedData.clean,
            };

            // Get broker URL and topics from PHP response instead of hardcoding
            const brokerUrl = decryptedData.brokerUrl;
            const topic = decryptedData.mainTopic;
            const requestTopic = decryptedData.requestTopic;

            mqttClient = mqtt.connect(brokerUrl, options);


            mqttClient.on('connect', function () {
                console.log('MQTT Connected');
                mqttConnected = true;

                // Subscribe to main data topic
                mqttClient.subscribe(topic, function (err) {
                    if (err) {
                        console.error('Subscribe error:', err);
                    } else {
                        console.log('Subscribed to', topic);
                    }
                });

                // Subscribe to request response topic
                mqttClient.subscribe(requestTopic, function (err) {
                    if (err) {
                        console.error('Subscribe error for request topic:', err);
                    } else {
                        console.log('Subscribed to', requestTopic);
                    }
                });

                // Request data immediately after connection is established
                requestMqttUpdate();
            });

            mqttClient.on('message', function (topic, message) {

                // console.log("message: " + message);
                localStorage.setItem("localMessageData", message);


                // console.log('Received MQTT message on topic:', topic);
                // console.log('Message content:', message.toString());

                if (topic === decryptedData.mainTopic) {
                    // Process data message
                    let data = message.toString();
                    let fields = data.split(';').map(item => item.trim()); // Split and trim

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
                        platforms: [
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

                    updateDashboardWithMqttData(mqttData);
                }
                else if (topic === decryptedData.requestTopic) {
                    // Handle response to our request if needed
                    console.log('Request acknowledged by publisher');
                }
            });

            mqttClient.on('close', function () {
                console.log('MQTT Connection lost');
                mqttConnected = false;
            });

            mqttClient.on('error', function (error) {
                console.error('MQTT Error:', error);
                mqttConnected = false;
            });
        },
        error: function (xhr, status, error) {
            console.error(`Error fetching MQTT credentials:`, error);
            mqttConnected = false;
        }
    });

}
// Modify DOMContentLoaded event handler to detect page refresh
document.addEventListener('DOMContentLoaded', function () {
    // Generate tabs and content
    generateMotorTabsAndContent();

    // Initial data load from database if MQTT is not connected
    // updateDashboardData();

    // Connect to MQTT
    connectMqtt();
    const activeMotorTab = $('.tab-content.active').attr('id');
    const motorNumber = activeMotorTab.replace('motor_', '');
    fetchAndUpdateMotorDetails(activeMotorTab, motorNumber);

    // Setup reconnection mechanism
    setInterval(function () {
        if (!mqttConnected && mqttClient) {
            console.log('Attempting to reconnect MQTT...');
            mqttClient.end(true);
            connectMqtt();
        }
    }, 60000); // Try to reconnect every minute if disconnected
});

// Add event listener for page visibility changes to detect when user returns to the page
document.addEventListener('visibilitychange', function () {
    if (document.visibilityState === 'visible') {
        console.log('Page is now visible, requesting data update');
        requestMqttUpdate();
    }
});

// Listen for page refresh events using beforeunload and performance navigation
window.addEventListener('beforeunload', function () {
    // Store timestamp to detect page refresh
    sessionStorage.setItem('lastUnload', Date.now());
});



// Check if this is a page refresh by comparing timestamps
if (performance.navigation && performance.navigation.type === 1 ||
    (sessionStorage.getItem('lastUnload') &&
        Date.now() - sessionStorage.getItem('lastUnload') < 3000)) {
    console.log('Page was refreshed');

}

// Update dashboard with MQTT data
function updateDashboardWithMqttData(data) {
    // console.log(data);
    // Update Operation Mode
    var mode = "";
    if (data.mode === '2') {
        mode = 'auto';

    }
    else if (data.mode === '1') {
        mode = 'oem';

    }
    else if (data.mode === '0') {
        mode = 'off';

    }
    $('#operation-mode-display').text(mode.toUpperCase());

    if (!$('#operation-mode-display .pulse-dot').length) {
        $('#operation-mode-display').append('<span class="pulse-dot"></span>');
    }

    const operationMode = data.mode.toLowerCase();
    const modeTextMap = {
        '2': 'Automatic Operation',
        '1': 'OEM Operation',
        '0': 'NO Operation'
    };
    $('#operation-mode-subtitle').text(modeTextMap[operationMode] || data.mode);

    // Update Inlet Pressure/Level
    updateInletPressure(parseFloat(data.inPressure) >= 0.1);

    // Update Outlet Pressure
    $('#outlet-pressure-1').text(data.outPressure1 + ' kg/cm²');
    $('#outlet-pressure-2').text(data.outPressure2 + ' kg/cm²');

    // Update Motors
    for (let i = 0; i < motorConfig.count; i++) {
        const motorData = data.motors[i];
        const motorNumber = i + 1;

        const motorStatus = motorData.status === '1' ? 'ON' : 'OFF';
        const flowRate = motorData.flowRate;
        const runningTime = motorData.time;

        const motorStatusElement = $(`#motor-${motorNumber}-status`);
        motorStatusElement.empty();
        motorStatusElement
            .attr('class', motorStatus === 'ON' ? 'badge-success' : 'badge-danger')
            .append('<i class="bi bi-power"></i> ')
            .append(document.createTextNode(motorStatus));

        if (motorStatus === 'ON' && motorStatusElement.find('.pulse-dot').length === 0) {
            motorStatusElement.append('<span class="pulse-dot"></span>');
        }

        $(`#motor-${motorNumber}-flow`)
            .text(`Flow Rate: ${flowRate} L/min`)
            .attr('class', motorStatus === 'ON' ? 'flow-value' : 'flow-value flow-inactive');

        $(`#motor-${motorNumber}-runtime`).text(`${runningTime} min`);
    }

    // Update Platforms - mapping from array to dashboard format
    const platformMappings = [
        { index: 0, id: 'platform-1-2' },
        { index: 1, id: 'platform-3-4' },
        { index: 2, id: 'platform-5-6' },
        { index: 3, id: 'platform-7' },
        { index: 4, id: 'platform-8' },
        { index: 5, id: 'platform-9-10' }
    ];

    platformMappings.forEach(({ index, id }) => {
        const platformData = data.platforms[index];
        const status = platformData.status === '1' ? 'Open' : 'Closed';
        const time = platformData.time;

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

    // Note: Electrical details are still fetched from the database when tabs are clicked
}

// Set up refresh interval for the database (will be skipped if MQTT is connected)
// const refreshInterval = 100000; // Same as original
// let dashboardUpdateInterval = setInterval(updateDashboardData, refreshInterval);

// Initialize the tabs, content, and MQTT on page load
// document.addEventListener('DOMContentLoaded', function () {
//     // Generate tabs and content
//     generateMotorTabsAndContent();

//     // Initial data load from database
//     // updateDashboardData();

//     // Connect to MQTT
//     connectMqtt();
//     const activeMotorTab = $('.tab-content.active').attr('id');
//     const motorNumber = activeMotorTab.replace('motor_', '');
//     fetchAndUpdateMotorDetails(activeMotorTab, motorNumber);
//     // Setup reconnection mechanism
//     setInterval(function () {
//         if (!mqttConnected && mqttClient) {
//             console.log('Attempting to reconnect MQTT...');
//             mqttClient.end(true);
//             connectMqtt();
//         }
//     }, 60000); // Try to reconnect every minute if disconnected
// });