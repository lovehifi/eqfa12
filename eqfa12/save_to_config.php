<?php

$configFilePath = '/etc/alsa/conf.d/eqfa12p.conf';

// Lấy thông tin từ POST request
$controls = $_POST['controls'];
$volume = $_POST['volume'];  // Retrieve volume from POST data

// Append volume to the controls string
$controls .= " $volume";


$newContent = "pcm.eqfa12p {
    type plug
    slave.pcm \"plug_eqfa12p\"
}
pcm.plug_eqfa12p {
    type ladspa
    slave.pcm \"plughw:0,0\";
    path \"/usr/lib/ladspa\";
    plugins [ {
        id 2611
        label EqFA12p
        input {
            controls $controls
        }
    } ]
}";


file_put_contents($configFilePath, $newContent);

error_log("Configuration updated successfully. New content: \n" . $newContent);

echo "Configuration updated successfully.";
?>
