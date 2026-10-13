<?php
include '../components/connection.php';

session_start();
header('Content-Type: application/json; charset=utf-8');

// ===== CHỈ POST =====
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "status" => "error",
        "message" => "Chỉ hỗ trợ POST"
    ]);
    exit();
}

// ===== LẤY DATA =====
$name  = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$pass  = trim($_POST['pass'] ?? '');
$cpass = trim($_POST['cpass'] ?? '');

// ===== VALIDATE =====
if (empty($name) || empty($email) || empty($pass) || empty($cpass)) {
    echo json_encode([
        "status" => "error",
        "message" => "Vui lòng nhập đầy đủ"
    ]);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "status" => "error",
        "message" => "Email không hợp lệ"
    ]);
    exit();
}

if ($pass !== $cpass) {
    echo json_encode([
        "status" => "error",
        "message" => "Mật khẩu không khớp"
    ]);
    exit();
}

// ===== CHECK EMAIL =====
$stmt = $conn->prepare("SELECT id FROM users WHERE Email = ?");
$stmt->execute([$email]);

if ($stmt->rowCount() > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Email đã tồn tại"
    ]);
    exit();
}

// ===== HASH PASSWORD =====
$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

// ===== INSERT =====
$id = uniqid();

$stmt = $conn->prepare(
    "INSERT INTO users (id, Name, Email, Matkhau)
     VALUES (?, ?, ?, ?)"
);

$stmt->execute([$id, $name, $email, $hashed_pass]);

// ===== SESSION =====
$_SESSION['user_id']   = $id;
$_SESSION['user_name'] = $name;
$_SESSION['user_type'] = 'user';

// ===== RESPONSE =====
echo json_encode([
    "status" => "success",
    "message" => "Đăng ký thành công"
]);
