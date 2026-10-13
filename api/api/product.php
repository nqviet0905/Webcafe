<?php
include '../webcafe/components/connection.php';

header('Content-Type: application/json');

// nhận cả GET và POST
$product_id = $_GET['product_id'] ?? $_POST['product_id'] ?? '';

// trim cho sạch dữ liệu
$product_id = trim($product_id);

// kiểm tra dữ liệu
if (empty($product_id)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Thiếu product_id',
        'hint' => 'Ví dụ: ?product_id=13'
    ]);
    exit;
}

try {
    // query sản phẩm
    $select = $conn->prepare("SELECT * FROM sanpham WHERE id = ? LIMIT 1");
    $select->execute([$product_id]);

    $product = $select->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Không tìm thấy sản phẩm',
            'product_id' => $product_id
        ]);
        exit;
    }

    // success
    echo json_encode([
        'status' => 'success',
        'data' => $product
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Lỗi database',
        'error' => $e->getMessage()
    ]);
}