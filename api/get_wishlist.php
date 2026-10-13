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

// Lấy tất cả wishlist items của user
$select_wishlist = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ?");
$select_wishlist->execute([$user_id]);

$wishlist_items = [];

if ($select_wishlist->rowCount() > 0) {
    while($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)){
        
        // Lấy thông tin sản phẩm
        $select_products = $conn->prepare("SELECT * FROM `sanpham` WHERE id = ?");
        $select_products->execute([$fetch_wishlist['product_id']]);
        
        if ($select_products->rowCount() > 0) {
            $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
            
            $wishlist_items[] = [
                'product_id' => $fetch_products['id'],
                'name' => $fetch_products['Name'],
                'price' => $fetch_products['Giaban'],
                'image' => $fetch_products['image']
            ];
        }
    }
}

echo json_encode([
    'status' => 'success',
    'data' => $wishlist_items
]);
?>
