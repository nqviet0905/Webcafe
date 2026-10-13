<?php
include '../components/connection.php';
session_start();

// ❌ Tắt lỗi HTML (tránh phá JSON)
error_reporting(0);
ini_set('display_errors', 0);

header("Content-Type: application/json");

// Lấy JSON từ fetch
$data = json_decode(file_get_contents("php://input"), true);

// 👉 Check có nhận được JSON không
if (!$data) {
    echo json_encode([
        "status" => false,
        "message" => "Không nhận được dữ liệu JSON!"
    ]);
    exit;
}

// 👉 Validate dữ liệu
if (!isset($data['name'], $data['email'], $data['number'], $data['message'])) {
    echo json_encode([
        "status" => false,
        "message" => "Thiếu dữ liệu!"
    ]);
    exit;
}

// 👉 Lấy dữ liệu
$id = uniqid();
$user_id = $_SESSION['user_id'] ?? null;

$name   = filter_var($data['name'], FILTER_SANITIZE_SPECIAL_CHARS);
$email  = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
$number = filter_var($data['number'], FILTER_SANITIZE_SPECIAL_CHARS);
$msg    = filter_var($data['message'], FILTER_SANITIZE_SPECIAL_CHARS);

$subject = "Liên hệ từ web";

try {
    $sql = "INSERT INTO message (id, user_id, Name, Email, DienThoai, subject, message)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$id, $user_id, $name, $email, $number, $subject, $msg]);

    echo json_encode([
        "status" => true,
        "message" => "Gửi thành công!"
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "status" => false,
        "message" => "Lỗi server!",
        "error" => $e->getMessage() // 👉 debug nếu cần
    ]);
}
