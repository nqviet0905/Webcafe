<?php
include '../components/connection.php';
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['user_type'] = 'admin';
header('Content-Type: application/json');

// kiểm tra quyền admin
if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    echo json_encode([
        "status" => "error",
        "message" => "Không có quyền truy cập"
    ]);
    exit();
}

// kiểm tra method
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo json_encode([
        "status" => "error",
        "message" => "Phải dùng POST"
    ]);
    exit();
}

// lấy dữ liệu
$name = filter_var($_POST['Name'] ?? '', FILTER_SANITIZE_STRING);
$price = filter_var($_POST['Giaban'] ?? '', FILTER_SANITIZE_STRING);
$content = filter_var($_POST['content'] ?? '', FILTER_SANITIZE_STRING);
$type = $_POST['type'] ?? 'publish'; // publish hoặc draft

$status = ($type === 'publish') ? 'Hoạt động' : 'Không hoạt động';

// upload ảnh
$image = $_FILES['image']['name'] ?? '';
$image_tmp = $_FILES['image']['tmp_name'] ?? '';
$image_size = $_FILES['image']['size'] ?? 0;

$image = filter_var($image, FILTER_SANITIZE_STRING);
$image_folder = '../img/'.$image;

// validate
if(empty($name) || empty($price) || empty($content)){
    echo json_encode([
        "status" => "error",
        "message" => "Thiếu dữ liệu"
    ]);
    exit();
}

// check trùng ảnh
$select = $conn->prepare("SELECT * FROM sanpham WHERE image = ?");
$select->execute([$image]);

if($select->rowCount() > 0){
    echo json_encode([
        "status" => "error",
        "message" => "Trùng tên ảnh"
    ]);
    exit();
}

// check size
if($image_size > 2000000){
    echo json_encode([
        "status" => "error",
        "message" => "Ảnh quá lớn"
    ]);
    exit();
}

// upload file
move_uploaded_file($image_tmp, $image_folder);

// insert DB
$id = uniqid();

$insert = $conn->prepare("
    INSERT INTO sanpham 
    (id, Name, Giaban, image, product_detail, Trangthai)
    VALUES (?, ?, ?, ?, ?, ?)
");

$insert->execute([$id, $name, $price, $image, $content, $status]);

echo json_encode([
    "status" => "success",
    "message" => "Thêm sản phẩm thành công"
]);