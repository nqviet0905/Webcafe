<?php
header("Content-Type: application/json");
include '../webcafe/components/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => false, "msg" => "Invalid request method"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ;
$name = $data['Name'];
$price = $data['Giaban'] ;
$content = $data['content'] ;
$status = $data['Trangthai'];

$update = $conn->prepare("UPDATE sanpham SET Name=?, Giaban=?, product_detail=?, Trangthai=? WHERE id=?");
$update->execute([$name, $price, $content, $status, $id]);

echo json_encode([
    "status" => true,
    "msg" => "Cập nhật sản phẩm thành công"
]);