<?php
include '../components/connection.php';
session_start();

error_reporting(0);
ini_set('display_errors', 0);

header("Content-Type: application/json; charset=utf-8");

// Check admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    echo json_encode([
        "status" => false,
        "message" => "Không có quyền!"
    ]);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT * FROM message ORDER BY id DESC");
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => true,
        "data" => $messages
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "status" => false,
        "message" => "Lỗi server!",
        "error" => $e->getMessage()
    ]);
}
