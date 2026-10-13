<?php
include '../webcafe/components/connection.php';
session_start();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? '';

if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit;
}

$select = $conn->prepare("
    SELECT c.id as cart_id, c.qty, sp.*
    FROM cart c
    JOIN sanpham sp ON c.product_id = sp.id
    WHERE c.user_id = ?
");
$select->execute([$user_id]);

$data = [];
$grand_total = 0;

while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
    $sub_total = $row['qty'] * $row['Giaban'];
    $grand_total += $sub_total;

    $row['sub_total'] = $sub_total;
    $data[] = $row;
}

echo json_encode([
    'status' => 'success',
    'data' => $data,
    'grand_total' => $grand_total
]);