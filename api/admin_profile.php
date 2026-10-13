<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

include '../webcafe/components/connection.php';
session_start();



$admin_id = null;

if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'admin') {
    $admin_id = $_SESSION['user_id']; // Dùng cho web
} else if (isset($_GET['id'])) {
    $admin_id = $_GET['id']; // Dùng cho Postman (GET)
} else if (isset($_POST['id'])) {
    $admin_id = $_POST['id']; // Dùng cho Postman (POST)
} else {
    echo json_encode([
        "status" => "error",
        "msg" => "Thiếu ID admin hoặc chưa đăng nhập"
    ]);
    exit();
}

// ========================
// 1. API LẤY THÔNG TIN ADMIN
// ========================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $query = $conn->prepare("SELECT id, Name, Email, Hoso FROM admin WHERE id = ?");
    $query->execute([$admin_id]);

    if ($query->rowCount() > 0) {
        echo json_encode([
            "status" => "",
            "data" => $query->fetch(PDO::FETCH_ASSOC)
        ]);
    } else {
        echo json_encode(["status" => "error", "msg" => "Không tìm thấy admin"]);
    }
    exit();
}


// ========================
// 2. API CẬP NHẬT THÔNG TIN ADMIN
// ========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = $_POST['name'] ?? "";
    $email    = $_POST['email'] ?? "";
    $password = $_POST['password'] ?? "";

    $update = $conn->prepare("UPDATE admin SET Name=?, Email=?, password=? WHERE id=?");
    $update->execute([$name, $email, $password, $admin_id]);

    // Upload ảnh
    if (!empty($_FILES['Hoso']['name'])) {

        $img_name = $_FILES['Hoso']['name'];
        $tmp = $_FILES['Hoso']['tmp_name'];
        $ext = pathinfo($img_name, PATHINFO_EXTENSION);

        $new_name = $admin_id . "_" . time() . "." . $ext;

        move_uploaded_file($tmp, "../img/" . $new_name);

        $update_img = $conn->prepare("UPDATE admin SET Hoso=? WHERE id=?");
        $update_img->execute([$new_name, $admin_id]);
    }

    echo json_encode(["status" => "true", "msg" => "Cập nhật thành công"]);
    exit();
}

echo json_encode(["status" => "true", "msg" => "Yêu cầu không hợp lệ"]);
exit();