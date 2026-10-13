<?php
include '../webcafe/components/connection.php';

header('Content-Type: application/json');

// lấy product_id từ request (GET hoặc POST đều được)
$product_id = $_GET['product_id'] ?? '';

if (empty($product_id)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Thiếu product_id'
    ]);
    exit;
}

// query sản phẩm
$select = $conn->prepare("SELECT * FROM sanpham WHERE id = ? LIMIT 1");
$select->execute([$product_id]);

$product = $select->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Không tìm thấy sản phẩm'
    ]);
    exit;
}

// trả về dữ liệu
echo json_encode([
    'status' => 'success',
    'data' => $product
]);