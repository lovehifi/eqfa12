<?php
// save_to_config.php

// Đường dẫn đến tệp eqfa12p.conf
$configFilePath = '/etc/alsa/conf.d/eqfa12p.conf';

// Lấy thông tin từ POST request
$controls = $_POST['controls'];

// Tạo nội dung mới để ghi vào tệp
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

// Ghi nội dung mới vào tệp
file_put_contents($configFilePath, $newContent);

// Log thông báo thành công
error_log("Configuration updated successfully. New content: \n" . $newContent);

echo "Configuration updated successfully.";
?>
