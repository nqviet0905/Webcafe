<?php
include '../components/connection.php';
session_start();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? '';
$product_id = $_POST['product_id'] ?? '';

if(empty($user_id)){
    echo json_encode([
        'status' => 'error',
        'message' => 'Người dùng chưa đăng nhập'
    ]);
    exit;
}

if(!$product_id){
    echo json_encode([
        'status' => 'error',
        'message' => 'Thiếu product_id'
    ]);
    exit;
}

// CHECK tồn tại
$check = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
$check->execute([$user_id, $product_id]);

if($check->rowCount()>0){

    // XÓA
    $delete = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $delete->execute([$user_id, $product_id]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Đã xóa'
    ]);

}else{
    echo json_encode([
        'status' => 'error',
        'message' => 'Không tìm thấy'
    ]);
}
?>