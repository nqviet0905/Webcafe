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
$name = $data['name'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if(!$id){
    echo json_encode(["status"=>false,"msg"=>"Thiếu ID"]);
    exit();
}

$update = $conn->prepare("UPDATE admin SET Name=?, Email=?, password=? WHERE id=?");
$update->execute([$name,$email,$password,$id]);

echo json_encode([
    "status"=>true,
    "msg"=>"Cập nhật thành công"
]);