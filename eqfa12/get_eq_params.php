<?php
// Đường dẫn đến tệp cấu hình EQ
$configFilePath = '/etc/alsa/conf.d/eqfa12p.conf';

// Hàm để trích xuất thông số EQ từ nội dung cấu hình
function extractEQParams($configContent) {
  $eqParameters = array();

  // Tìm mẫu 'controls [ ... ]'
  preg_match('/controls \[(.*?)\]/', $configContent, $matches);

  // Lấy giá trị controls từ mẫu tìm được
  if (count($matches) > 1) {
    $controls = preg_split('/\s+/', $matches[1], -1, PREG_SPLIT_NO_EMPTY);

    // Lặp qua từng control và lấy giá trị gain cho 12 band
    for ($i = 0; $i < 49; $i += 4) {  // Changed loop range to get 12 bands + volume
      if ($i == 48) {  // If it's the volume value
        $eqParameters['volume'] = $controls[$i];
      } else {
        $band = ($i / 4) + 1;
        $eqParameters['band' . $band] = array(
          'enabled' => $controls[$i],
          'freq' => $controls[$i + 1],
          'q' => $controls[$i + 2],
          'gain' => $controls[$i + 3]
        );
      }
    }
  }

  return $eqParameters;
}

// Đọc nội dung tệp cấu hình EQ
$configContent = file_get_contents($configFilePath);

// Trích xuất thông số EQ từ nội dung cấu hình
$eqParameters = extractEQParams($configContent);

// Ghi thông số EQ và thông tin debug vào tệp JSON
$jsonFilePath = '/mnt/MPD/SD/eq_params.json';
file_put_contents($jsonFilePath, json_encode($eqParameters, JSON_PRETTY_PRINT));

// Trả về thông số EQ và volume dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($eqParameters);
?>
