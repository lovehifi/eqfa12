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
        $numBands = min(count($controls) / 4, 12);  // Chỉ lấy 12 band
        for ($i = 0; $i < $numBands * 4; $i += 4) {
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

// Trích xuất giá trị volume từ chuỗi dữ liệu
preg_match('/\bcontrols\s+\[.*\s+([0-9.-]+)\]/', $configContent, $volumeMatches);
$volumeValue = isset($volumeMatches[1]) ? (float)$volumeMatches[1] : null;

// Ghi thông số EQ và giá trị volume vào một đối tượng JSON
$result = array(
    'eqParams' => $eqParameters,
    'volumeValue' => $volumeValue  // Đây là giá trị volume
);

// Ghi vào tệp eq_params.json
$jsonFilePath = '/mnt/MPD/SD/eq_params.json';
file_put_contents($jsonFilePath, json_encode($result, JSON_PRETTY_PRINT));

// Trả về thông số EQ và giá trị volume dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($result);
?>
