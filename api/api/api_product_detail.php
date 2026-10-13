<?php
header("Content-Type: application/json");
include '../webcafe/components/connection.php';

$id = $_GET['id'] ?? '';

if(empty($id)){
    echo json_encode([
        "status"=>false,
        "message"=>"Thiếu id"
    ]);
    exit();
}

$sql = $conn->prepare("SELECT * FROM sanpham WHERE id=?");
$sql->execute([$id]);

$product = $sql->fetch(PDO::FETCH_ASSOC);

if(!$product){
    echo json_encode([
        "status"=>false,
        "message"=>"Không tìm thấy sản phẩm"
    ]);
    exit();
}

echo json_encode([
    "status"=>true,
    "data"=>$product
]);