<?php
include '../components/connection.php';
session_start();

// 👉 Luôn trả JSON
header("Content-Type: application/json; charset=utf-8");

// ❌ Tắt warning HTML phá JSON
error_reporting(0);
ini_set('display_errors', 0);

// 👉 Check admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    echo json_encode([
        "status" => false,
        "message" => "Không có quyền!"
    ]);
    exit;
}

// 👉 Lấy raw JSON
$raw = file_get_contents("php://input");

// 👉 DEBUG (ghi log để kiểm tra)
file_put_contents(__DIR__ . "/debug.txt", $raw . PHP_EOL, FILE_APPEND);

// 👉 Decode JSON
$data = json_decode($raw, true);

// 👉 FIX fallback nếu JSON lỗi
if (!is_array($data)) {
    $data = $_POST;
}

// 👉 FIX chuẩn check ID
if (!isset($data['id']) || trim($data['id']) === '') {
    echo json_encode([
        "status" => false,
        "message" => "Thiếu ID!"
    ]);
    exit;
}

$delete_id = trim($data['id']);

try {
    // 👉 Kiểm tra tồn tại
    $verify = $conn->prepare("SELECT id FROM message WHERE id = ?");
    $verify->execute([$delete_id]);

    if ($verify->rowCount() == 0) {
        echo json_encode([
            "status" => false,
            "message" => "Không tìm thấy!"
        ]);
        exit;
    }

    // 👉 Xóa
    $delete = $conn->prepare("DELETE FROM message WHERE id = ?");
    $delete->execute([$delete_id]);

    echo json_encode([
        "status" => true,
        "message" => "Xóa thành công!"
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "status" => false,
        "message" => "Lỗi server!",
        "error" => $e->getMessage()
    ]);
}
