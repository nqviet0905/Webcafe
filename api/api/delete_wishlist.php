<?php
include '../webcafe/components/connection.php';

header('Content-Type: application/json');

// LẤY DATA
$wishlist_id = $_POST['wishlist_id'] ?? '';

if(!$wishlist_id){
    echo json_encode([
        "status"=>"error",
        "message"=>"Thiếu wishlist_id"
    ]);
    exit;
}

// CHECK tồn tại
$check = $conn->prepare("SELECT * FROM wishlist WHERE id=?");
$check->execute([$wishlist_id]);

if($check->rowCount()>0){

    // XÓA
    $delete = $conn->prepare("DELETE FROM wishlist WHERE id=?");
    $delete->execute([$wishlist_id]);

    echo json_encode([
        "status"=>"success",
        "message"=>"Đã xóa"
    ]);

}else{
    echo json_encode([
        "status"=>"error",
        "message"=>"Không tìm thấy"
    ]);
}