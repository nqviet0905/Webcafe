<?php
include '../components/connection.php';
session_start();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? '';

if(empty($user_id)){
    echo json_encode([
        'status' => 'error',
        'message' => 'Người dùng chưa đăng nhập',
        'data' => []
    ]);
    exit;
}

// Lấy tất cả sản phẩm trong giỏ hàng
$select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
$select_cart->execute([$user_id]);

$cart_items = [];
$grand_total = 0;

if ($select_cart->rowCount() > 0) {
    while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
        
        // Lấy thông tin sản phẩm
        $select_products = $conn->prepare("SELECT * FROM `sanpham` WHERE id = ?");
        $select_products->execute([$fetch_cart['product_id']]);
        
        if ($select_products->rowCount() > 0) {
            $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
            
            $sub_total = $fetch_cart['qty'] * $fetch_products['Giaban'];
            $grand_total += $sub_total;
            
            $cart_items[] = [
                'product_id' => $fetch_cart['product_id'],
                'name' => $fetch_products['Name'],
                'price' => $fetch_products['Giaban'],
                'image' => $fetch_products['image'],
                'qty' => $fetch_cart['qty'],
                'sub_total' => $sub_total
            ];
        }
    }
}

echo json_encode([
    'status' => 'success',
    'data' => $cart_items,
    'grand_total' => $grand_total
]);
?>
