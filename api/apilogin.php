<?php
include '../components/connection.php';

session_start();
header('Content-Type: application/json; charset=utf-8');

// ===== CHỈ CHO POST =====
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "status" => "error",
        "message" => "Chỉ hỗ trợ POST"
    ]);
    exit();
}

// ===== LẤY DATA =====
$email = trim($_POST['email'] ?? '');
$pass  = trim($_POST['pass'] ?? '');

// ===== DEBUG (XEM CONSOLE) =====
error_log("INPUT PASS: " . $pass);

// ===== LẤY USER =====
$stmt = $conn->prepare("SELECT * FROM users WHERE Email = ?");
$stmt->execute([$email]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode([
        "status" => "error",
        "message" => "Email không tồn tại"
    ]);
    exit();
}

error_log("DB HASH: " . $user['Matkhau']);

// ===== CHECK PASSWORD =====
if (password_verify($pass, $user['Matkhau'])) {

    session_regenerate_id(true);

    $_SESSION['user_id']   = $user['id'];
    $_SESSION['user_name'] = $user['Name'];
    $_SESSION['user_type'] = $user['user_type'] ?? 'user';

    echo json_encode([
        "status" => "success",
        "message" => "Đăng nhập thành công",
        "role" => $user['user_type'] ?? 'user'
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => "Sai mật khẩu"
    ]);
}
?>