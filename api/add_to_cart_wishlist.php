<?php
include '../components/connection.php';
session_start();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? '';
$product_id = $_POST['product_id'] ?? '';
$qty = $_POST['qty'] ?? 1;

// Validate
if(empty($user_id)){
    echo json_encode([
        'status' => 'error',
        'message' => 'Người dùng chưa đăng nhập'
    ]);
    exit;
}

if(empty($product_id)){
    echo json_encode([
        'status' => 'error',
        'message' => 'Thiếu product_id'
    ]);
    exit;
}

$qty = filter_var($qty, FILTER_VALIDATE_INT);
if($qty === false || $qty < 1) $qty = 1;

// Check sản phẩm đã có trong cart
$varify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");
$varify_cart->execute([$user_id, $product_id]);

// Check cart không vượt quá 20 items
$max_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
$max_cart_items->execute([$user_id]);

if ($varify_cart->rowCount() > 0) {
    echo json_encode([
        'status' => 'warning',
        'message' => 'Sản phẩm đã có trong giỏ hàng'
    ]);
} else if ($max_cart_items->rowCount() >= 20) {
    echo json_encode([
        'status' => 'warning',
        'message' => 'Giỏ hàng đã đầy (tối đa 20 sản phẩm)'
    ]);
} else {
    // Get product price
    $select_price = $conn->prepare("SELECT * FROM `sanpham` WHERE id = ? LIMIT 1");
    $select_price->execute([$product_id]);
    
    if($select_price->rowCount() == 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Sản phẩm không tồn tại'
        ]);
        exit;
    }
    
    $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);
    $price = $fetch_price['Giaban'] ?? 0;
    
    // Generate unique ID
    $id = bin2hex(random_bytes(16));
    
    // Insert to cart
    $insert_cart = $conn->prepare(
        "INSERT INTO cart (id, user_id, product_id, Giaban, qty) VALUES (?,?,?,?,?)"
    );
    $insert_cart->execute([$id, $user_id, $product_id, $price, $qty]);
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Sản phẩm đã được thêm vào giỏ hàng'
    ]);
}
?>
