<?php
include '../components/connection.php';
session_start();

header('Content-Type: application/json');

// Lấy product_id
$product_id = $_POST['product_id'] ?? '';
$product_id = filter_var($product_id, FILTER_SANITIZE_STRING);

// Kiểm tra
if(empty($product_id)){
    echo json_encode([
        'status' => 'error',
        'message' => 'Thiếu product_id'
    ]);
    exit;
}

// Check tồn tại
$check = $conn->prepare("SELECT * FROM cart WHERE product_id = ?");
$check->execute([$product_id]);

if ($check->rowCount() > 0) {

    // Xóa sản phẩm
    $delete = $conn->prepare("DELETE FROM cart WHERE product_id = ?");
    $delete->execute([$product_id]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Đã xóa sản phẩm'
    ]);

} else {

    echo json_encode([
        'status' => 'warning',
        'message' => 'Không tìm thấy sản phẩm'
    ]);
}
?>