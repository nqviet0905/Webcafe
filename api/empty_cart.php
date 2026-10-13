<?php
include '../components/connection.php';
session_start();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? '';

// Validate user_id
if(empty($user_id)){
    echo json_encode([
        'status' => 'error',
        'message' => 'Người dùng chưa đăng nhập'
    ]);
    exit;
}

// Check giỏ hàng có sản phẩm không
$check = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
$check->execute([$user_id]);

if ($check->rowCount() > 0) {

    // Xóa toàn bộ giỏ hàng
    $delete = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $delete->execute([$user_id]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Đã xóa toàn bộ giỏ hàng'
    ]);

} else {

    echo json_encode([
        'status' => 'warning',
        'message' => 'Giỏ hàng đang trống'
    ]);
}
?>
