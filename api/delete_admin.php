<?php
header("Content-Type: application/json");
include '../components/connection.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    echo json_encode(["status"=>false,"msg"=>"Unauthorized"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;
$admin_id = $_SESSION['user_id'];

if(!$id){
    echo json_encode(["status"=>false,"msg"=>"Thiếu ID"]);
    exit();
}

if($id == $admin_id){
    echo json_encode([
        "status"=>false,
        "msg"=>"Không thể xóa tài khoản đang đăng nhập"
    ]);
    exit();
}

$delete = $conn->prepare("DELETE FROM admin WHERE id=?");
$delete->execute([$id]);

echo json_encode([
    "status"=>true,
    "msg"=>"Xóa thành công"
]);