#!/bin/bash
input_file="/etc/alsa/conf.d/eqfa12p.conf"
output_file="/tmp/eqfa12p_modified.conf"
file="/srv/http/data/mpdconf/output.conf"

cd /tmp
wget https://raw.githubusercontent.com/lovehifi/eqfa12/main/eqfa12p.conf
cp -f /tmp/eqfa12p.conf /etc/alsa/conf.d/
chown http:http /etc/alsa/conf.d/eqfa12p.conf
cp -f /etc/alsa/conf.d/eqfa12p.conf /etc/alsa/conf.d/eqfa12p.conf.backup

wget https://raw.githubusercontent.com/lovehifi/eqfa12/main/eq12.conf
cp -f /tmp/eq12.conf /srv/http/data/mpdconf/
chown http:http /srv/http/data/mpdconf/eq12.conf

wget https://raw.githubusercontent.com/lovehifi/eqfa12/main/ladspa_share.tar.gz
wget https://raw.githubusercontent.com/lovehifi/eqfa12/main/ladspa_usr.tar.gz
tar -xzf /tmp/ladspa_share.tar.gz --overwrite -C /
tar -xzf /tmp/ladspa_usr.tar.gz --overwrite -C /

mkdir -p /srv/http/eqfa12
cd /srv/http/eqfa12
wget https://raw.githubusercontent.com/lovehifi/eqfa12/main/eqfa12/eq_params_default.json
wget https://raw.githubusercontent.com/lovehifi/eqfa12/main/eqfa12/get_eq_params.php
wget https://raw.githubusercontent.com/lovehifi/eqfa12/main/eqfa12/index.php
wget https://raw.githubusercontent.com/lovehifi/eqfa12/main/eqfa12/save_eq_params_default.php
wget https://raw.githubusercontent.com/lovehifi/eqfa12/main/eqfa12/save_to_config.php
chown http:http -Rv /srv/http/eqfa12
cd /tmp


# Check if the file exists
if [ -f "$file" ]; then
  # Extract the value after "device" and remove "hw:" prefix
  device=$(awk -F'"' '/device/ {gsub("hw:", "", $2); print $2; exit}' "$file")

  # Print the extracted device
  echo "Device: $device"
else
  echo "File not found: $file"
fi

# Specify the input file and output file


# Check if the input file exists
if [ -f "$input_file" ]; then
  # Read the content of the input file, replace plughw:X,Y with the device, and save to the output file
  sed "s/plughw:[0-9]\+,[0-9]\+/plughw:$device/g" "$input_file" > "$output_file"
  echo "Replacement completed. Modified configuration saved to $output_file"
else
  echo "Input file not found: $input_file"
fi


cp -f /tmp/eqfa12p_modified.conf /etc/alsa/conf.d/eqfa12p.conf
chown http:http /etc/alsa/conf.d/eqfa12p.conf

