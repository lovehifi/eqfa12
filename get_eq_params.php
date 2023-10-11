<?php
// Đường dẫn đến tệp cấu hình EQ
$configFilePath = '/etc/alsa/conf.d/eqfa12p.conf';

// Hàm để trích xuất thông số EQ từ nội dung cấu hình
function extractEQParams($configContent) {
  $eqParameters = array();

  // Tìm các mẫu 'controls [ ... ]'
  preg_match('/controls \[(.*?)\]/', $configContent, $matches);

  // Lấy giá trị controls từ các mẫu tìm được
  if (count($matches) > 1) {
    $controls = preg_split('/\s+/', $matches[1], -1, PREG_SPLIT_NO_EMPTY);

    // Lặp qua từng control và lấy giá trị gain cho 12 band
    for ($i = 0; $i < count($controls); $i += 4) {
      $band = ($i / 4) + 1;
      $eqParameters['band' . $band] = array(
        'enabled' => $controls[$i],
        'freq' => $controls[$i + 1],
        'q' => $controls[$i + 2],
        'gain' => $controls[$i + 3]
      );
    }
  }

  return $eqParameters;
}

// Đọc nội dung tệp cấu hình EQ
$configContent = file_get_contents($configFilePath);

// Trích xuất thông số EQ từ nội dung cấu hình
$eqParameters = extractEQParams($configContent);

// Ghi thông số EQ và thông tin debug vào tệp JSON
$jsonFilePath = '/srv/http/eq_params.json';
file_put_contents($jsonFilePath, json_encode($eqParameters, JSON_PRETTY_PRINT));

// Trả về thông số EQ dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($eqParameters);
?>
