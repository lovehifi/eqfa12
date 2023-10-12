<?php
// Đường dẫn đến tệp eq_params_default.json
$file_path = '/mnt/MPD/SD/eq_params_default.json';

// Kiểm tra xem dữ liệu POST đã được gửi hay chưa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_data = file_get_contents('php://input');

    // Kiểm tra xem dữ liệu JSON hợp lệ hay không
    $decoded_data = json_decode($json_data, true);
    if ($decoded_data !== null) {
        // Chuyển dữ liệu JSON thành chuỗi đẹp
        $formatted_json = json_encode($decoded_data, JSON_PRETTY_PRINT);

        // Lưu dữ liệu vào tệp eq_params_default.json
        file_put_contents($file_path, $formatted_json);

        // Trả về thông báo thành công
        echo json_encode(['success' => true]);
        exit;
    }
}

// Trả về thông báo lỗi nếu dữ liệu không hợp lệ
echo json_encode(['success' => false, 'error' => 'Invalid data.']);
?>
