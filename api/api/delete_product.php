<?php

header("Content-Type: application/json");

include '../webcafe/components/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => false, "msg" => "Yêu cầu phương thức không hợp lệ"]);
    exit();
}


$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['product_id'])) {
    echo json_encode(["status" => false, "msg" => "Missing product_id"]);
    exit();
}

$p_id = filter_var($data['product_id'], FILTER_SANITIZE_NUMBER_INT);


$delete_product = $conn->prepare("DELETE FROM sanpham WHERE id=?");
$delete_product->execute([$p_id]);

echo json_encode(["status" => true, "msg" => "Xóa sản phẩm thành công"]);