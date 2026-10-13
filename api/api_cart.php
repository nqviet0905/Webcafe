<?php
include '../components/connection.php';
session_start();
$_SESSION['user_id'] = 1;
header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    echo json_encode([
        "status" => "error",
        "message" => "Chưa đăng nhập"
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];

$product_id = $_POST['product_id'] ?? '';
$qty = (int)($_POST['qty'] ?? 1);
if($qty <= 0) $qty = 1;

if(empty($product_id)){
    echo json_encode([
        "status" => "error",
        "message" => "Thiếu product_id"
    ]);
    exit();
}

// check sản phẩm
$get = $conn->prepare("SELECT Giaban FROM sanpham WHERE id=? LIMIT 1");
$get->execute([$product_id]);
$row = $get->fetch(PDO::FETCH_ASSOC);

if(!$row){
    echo json_encode([
        "status" => "error",
        "message" => "Sản phẩm không tồn tại"
    ]);
    exit();
}

$Giaban = $row['Giaban'];

// check cart
$check = $conn->prepare("SELECT qty FROM cart WHERE user_id=? AND product_id=?");
$check->execute([$user_id, $product_id]);

if($check->rowCount() > 0){

    $update = $conn->prepare("
        UPDATE cart 
        SET qty = qty + ? 
        WHERE user_id=? AND product_id=?
    ");
    $update->execute([$qty, $user_id, $product_id]);

} else {

    $count = $conn->prepare("SELECT COUNT(*) FROM cart WHERE user_id=?");
    $count->execute([$user_id]);
    $total = $count->fetchColumn();

    if($total >= 20){
        echo json_encode([
            "status" => "error",
            "message" => "Giỏ hàng đã đầy"
        ]);
        exit();
    }

    $insert = $conn->prepare("
        INSERT INTO cart (user_id, product_id, Giaban, qty)
        VALUES (?,?,?,?)
    ");

    $insert->execute([$user_id, $product_id, $Giaban, $qty]);
}

// 🔥 QUAN TRỌNG: lấy tổng số lượng cart
$count_cart = $conn->prepare("
    SELECT SUM(qty) FROM cart WHERE user_id=?
");
$count_cart->execute([$user_id]);
$cart_count = (int)$count_cart->fetchColumn();

echo json_encode([
    "status" => "success",
    "message" => "Đã thêm vào giỏ hàng",
    "cart_count" => $cart_count
]);