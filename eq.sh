#!/bin/bash
input_file="/etc/alsa/conf.d/eqfa12p.conf"
output_file="/tmp/eqfa12p_modified.conf"
file="/srv/http/data/mpdconf/output.conf"

echo "Auto config alsa for Eqfa12p"

# Check if the file exists
if [ -f "$file" ]; then
  # Extract the value after "device" and remove "hw:" prefix
  device=$(awk -F'"' '/device/ {gsub("hw:", "", $2); print $2; exit}' "$file")

  # Print the extracted device
  echo "Device: $device"
else
  echo "File not found: $file"
fi


# Check if the input file exists
if [ -f "$input_file" ]; then
  sed "s/plughw:[0-9]\+,[0-9]\+/plughw:$device/g" "$input_file" > "$output_file"
  echo "Replacement completed. Modified configuration saved to $output_file"
else
  echo "Input file not found: $input_file"
fi


cp -f /tmp/eqfa12p_modified.conf /etc/alsa/conf.d/eqfa12p.conf
chown http:http /etc/alsa/conf.d/eqfa12p.conf

echo "Config DAC Finished, please reboot"

