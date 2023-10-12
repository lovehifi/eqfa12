<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoEq Settings</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #343a40;
            color: white;
            padding: 20px;
        }

        .band-info {
            margin-bottom: 10px;
        }

        .table-container {
            max-width: 600px;
        }

        .table th,
        .table td {
            padding: 0.2rem;
        }
        .table td, .table th {
            border-top: 1px solid #7a7a7a;
        }

        .table input {
            width: 70px;
            height: 25px;
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="container table-container">
        <h1>Parametric Eqfa12 Settings</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Band</th>
                    <th>Enabled</th>
                    <th>Freq (Hz)</th>
                    <th>Q</th>
                    <th>Gain (dB)</th>
                </tr>
            </thead>
            <tbody id="eqInfo">
            </tbody>
        </table>
        <label for="volumeInput">Master Gain</label>
        <input type="text" id="volumeInput">
        
        <div id="savesuccessMessage" class="mt-3" style="display: none;">
            <div class="alert alert-success" role="alert">
                EQ settings have been saved successfully!
            </div>
        </div>
        <div id="successMessage" class="mt-3" style="display: none;">
             <div class="alert alert-success" role="alert">
               Load successfully!
            </div>
        </div>


        <div class="mt-3">
             <button id="applyChanges" class="btn btn-primary">Apply</button>             
            <button id="saveToDefault" class="btn btn-success">Save to default</button>
            <button id="resetToDefault" class="btn btn-info">Load default</button>
            <button id="backupToJson" class="btn btn-secondary">Download parametric</button>
         </div>


         <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Function to get EQ parameters
    function getEQParams() {
        return $.ajax({
            url: 'get_eq_params.php',
            method: 'GET',
            dataType: 'json'
        });
    }

    // Function to update EQ parameters
    function updateEQParams(updatedParams) {
        // Code to update EQ parameters goes here
        console.log('Updating EQ params:', updatedParams);
    }

    // Display EQ information
    function displayEQInfo(eqParams) {
        const eqInfoTbody = $('#eqInfo');
        eqInfoTbody.empty();

        // Loop through each band and display the information
        for (let i = 1; i <= 12; i++) {
            const bandInfo = eqParams['band' + i];

            // Check if bandInfo and enabled property exist
            if (bandInfo && bandInfo.hasOwnProperty('enabled')) {
                const isEnabled = parseInt(bandInfo.enabled) === 1;
                eqInfoTbody.append(`
                    <tr class="band-info">
                        <td>${i}</td>
                        <td><input type="checkbox" ${isEnabled ? 'checked' : ''}></td>
                        <td><input type="text" class="form-control" value="${bandInfo.freq}"></td>
                        <td><input type="text" class="form-control" value="${bandInfo.q}"></td>
                        <td><input type="text" class="form-control" value="${bandInfo.gain}"></td>
                    </tr>
                `);
            }
        }
    }

    // Function to update EQ parameters from the user interface
    function updateEQParamsFromUI() {
        const updatedEQParams = {};

        // Loop through each band and retrieve information from the user interface
        for (let i = 1; i <= 12; i++) {
            updatedEQParams[`band${i}`] = {
                enabled: $(`#eqInfo tr:nth-child(${i}) td:nth-child(2) input`).prop('checked') ? "1" : "0",
                freq: $(`#eqInfo tr:nth-child(${i}) td:nth-child(3) input`).val(),
                q: $(`#eqInfo tr:nth-child(${i}) td:nth-child(4) input`).val(),
                gain: $(`#eqInfo tr:nth-child(${i}) td:nth-child(5) input`).val()
            };
        }

        return updatedEQParams;
    }

    // Function to update EQ parameters in the configuration file
    function updateEQParamsInConfigFile(eqParams) {
        const configContent = [];
        for (let i = 1; i <= 12; i++) {
            const bandInfo = eqParams[`band${i}`];
            const enabled = parseInt(bandInfo.enabled) === 1 ? '1' : '0';
            const line = `${enabled},${bandInfo.freq},${bandInfo.q},${bandInfo.gain}`;
            configContent.push(line);
        }

        const configString = configContent.join('\n');
        // Call the function to save to the configuration file
        saveConfigToFile(configString);
    }

    // Function to save EQ parameters to the configuration file
    function saveConfigToFile(updatedParams) {
        const controlsArray = Object.keys(updatedParams).map(key => {
            const bandInfo = updatedParams[key];
            return `${bandInfo.enabled} ${bandInfo.freq} ${bandInfo.q} ${bandInfo.gain}`;
        });

        // Create the controls string
        const controlsString = '[' + controlsArray.join(' ') + ']';

        // Call AJAX to save EQ parameters to the file
        $.ajax({
            url: 'save_to_config.php',
            method: 'POST',
            data: { controls: controlsString },
            success: function (response) {
                console.log('Successfully updated EQ params:', response);
            },
            error: function (xhr, status, error) {
                console.error('Error updating EQ params:', error);
            }
        });
    }

    // Event handling when "Apply" button is clicked
    $('#applyChanges').click(function () {
        const updatedEQParams = updateEQParamsFromUI();

        // Call the function to update EQ parameters
        updateEQParams(updatedEQParams);

        // Log the updated EQ parameters to the console
        console.log('Updated EQ params:', updatedEQParams);

        // Call the function to save EQ parameters to the file
        saveConfigToFile(updatedEQParams);

        showSuccessMessage();
    });

    // Function to show success message
    function showSuccessMessage() {
        // Display the success message
        $('#successMessage').show();

        // Hide the message after 3 seconds
        setTimeout(function () {
            $('#successMessage').fadeOut();
        }, 2000);
    }

    // Event handling when the document has finished loading
    $(document).ready(function () {
        // Get EQ parameters and display them
        getEQParams().done(function (eqParams) {
            displayEQInfo(eqParams);
        });

        // Log EQ parameters to the console
        getEQParams().done(function (eqParams) {
            console.log('EQ parameters:', eqParams);
        });

        // Event handling when "Backup to Json" button is clicked
        $('#backupToJson').click(function () {
    const updatedEQParams = updateEQParamsFromUI();

    // Create JSON data from EQ parameters
    const eqParamsJson = JSON.stringify(updatedEQParams, null, 2);

    // Create a blob from the JSON data
    const blob = new Blob([eqParamsJson], { type: 'application/json' });

    // Create a URL to download the JSON file
    const url = URL.createObjectURL(blob);

    // Create an <a> element to download the JSON file
    const a = document.createElement('a');
    a.href = url;
    a.download = 'eq_params_new.json';
    a.click();

    // Revoke the created URL
    URL.revokeObjectURL(url);
});

        // Event handling when "Save to Default" button is clicked
        $('#saveToDefault').click(function () {
            const updatedEQParams = updateEQParamsFromUI();

            // Call the function to update EQ parameters
            updateEQParams(updatedEQParams);

            // Log the updated EQ parameters to the console
            console.log('Updated EQ params:', updatedEQParams);

            // Call the function to save EQ parameters to the file
            saveConfigToFile(updatedEQParams);

            const eqParamsJson = JSON.stringify(updatedEQParams, null, 2);

            // Save the data to eq_params_default.json
            $.ajax({
                url: 'save_eq_params_default.php',
                method: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: eqParamsJson,
                success: function (response) {
                    console.log('Saved to default:', response);

                    // Display the success message
                    $('#savesuccessMessage').show();

                    // Hide the message after 3 seconds
                    setTimeout(function () {
                        $('#savesuccessMessage').fadeOut();
                    }, 2000);
                },
                error: function (xhr, status, error) {
                    console.error('Error saving to default:', error);
                }
            });
        });

        // Event handling when "Reset to Default" button is clicked
        $('#resetToDefault').click(function () {
            // Get default EQ parameters from the JSON file
            $.ajax({
                url: '/mnt/MPD/SD/eq_params_default.json',
                method: 'GET',
                dataType: 'json'
            }).done(function (defaultEQParams) {
                // Display the default EQ information on the interface
                displayEQInfo(defaultEQParams);

                const updatedEQParams = updateEQParamsFromUI();

                // Call the function to update EQ parameters
                updateEQParams(updatedEQParams);

                // Log the updated EQ parameters to the console
                console.log('Updated EQ params:', updatedEQParams);

                // Call the function to save EQ parameters to the file
                saveConfigToFile(updatedEQParams);
                showSuccessMessage();
            });
        });

        $(document).ready(function () {
            // Function to get EQ parameters and volume value
            function getEQParamsAndVolumeValue() {
                return $.ajax({
                    url: 'get_eq_params.php',
                    method: 'GET',
                    dataType: 'json'
                });
            }

            // Call the function to get EQ parameters and volume value
            getEQParamsAndVolumeValue().done(function (data) {
                // Update EQ params
                displayEQInfo(data.eqParams);

                // Update volume value
                $('#volumeInput').val(data.volumeValue);
            });
        });
    });
</script>


</body>

</html>
