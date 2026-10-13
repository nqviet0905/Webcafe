<?php
include '../components/connection.php';
session_start();

header('Content-Type: application/json');

// Lấy dữ liệu
$product_id = $_POST['product_id'] ?? '';
$qty = $_POST['qty'] ?? 1;

// Làm sạch dữ liệu
$product_id = filter_var($product_id, FILTER_SANITIZE_STRING);
$qty = filter_var($qty, FILTER_VALIDATE_INT);

// Validate
if(empty($product_id) || $qty === false){
    echo json_encode([
        'status' => 'error',
        'message' => 'Thiếu product_id hoặc qty không hợp lệ'
    ]);
    exit;
}

if ($qty < 1) $qty = 1;

// Kiểm tra sản phẩm có trong cart không
$check = $conn->prepare("SELECT * FROM cart WHERE product_id = ?");
$check->execute([$product_id]);

if ($check->rowCount() > 0) {

    // Cập nhật số lượng
    $update = $conn->prepare("UPDATE cart SET qty = ? WHERE product_id = ?");
    $update->execute([$qty, $product_id]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Cập nhật số lượng thành công'
    ]);

} else {

    echo json_encode([
        'status' => 'warning',
        'message' => 'Sản phẩm không tồn tại trong giỏ'
    ]);
}
?>