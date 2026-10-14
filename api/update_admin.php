
<?php
header("Content-Type: application/json; charset=UTF-8");

session_start();
require_once '../components/connection.php';

function response($status, $msg) {
    echo json_encode([
        "status" => $status,
        "msg" => $msg
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// 1. Kiểm tra quyền admin
if (
    !isset($_SESSION['user_id'], $_SESSION['user_type']) ||
    $_SESSION['user_type'] !== 'admin'
) {
    http_response_code(403);
    response(false, "Bạn không có quyền thực hiện thao tác này.");
}

// 2. Chỉ chấp nhận phương thức POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    response(false, "Phương thức không được hỗ trợ.");
}

// 3. Đọc dữ liệu JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    http_response_code(400);
    response(false, "Dữ liệu gửi lên không hợp lệ.");
}

$id = trim((string)($data['id'] ?? ''));
$name = trim((string)($data['name'] ?? ''));
$email = trim((string)($data['email'] ?? ''));
$password = (string)($data['password'] ?? '');

// 4. Kiểm tra dữ liệu
if ($id === '' || $name === '' || $email === '') {
    http_response_code(400);
    response(false, "Vui lòng nhập đầy đủ ID, tên và email.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    response(false, "Email không hợp lệ.");
}

if (strlen($name) > 255 || strlen($email) > 255) {
    http_response_code(400);
    response(false, "Tên hoặc email quá dài.");
}

if ($password !== '' && strlen($password) < 8) {
    http_response_code(400);
    response(false, "Mật khẩu mới phải có ít nhất 8 ký tự.");
}

try {
    // 5. Kiểm tra tài khoản cần cập nhật
    $check = $conn->prepare(
        "SELECT id, user_type FROM users WHERE id = ? LIMIT 1"
    );
    $check->execute([$id]);
    $target = $check->fetch(PDO::FETCH_ASSOC);

    if (!$target || $target['user_type'] !== 'admin') {
        http_response_code(404);
        response(false, "Không tìm thấy tài khoản admin.");
    }

    // 6. Kiểm tra email có bị tài khoản khác sử dụng không
    $checkEmail = $conn->prepare(
        "SELECT id FROM users WHERE Email = ? AND id <> ? LIMIT 1"
    );
    $checkEmail->execute([$email, $id]);

    if ($checkEmail->fetch(PDO::FETCH_ASSOC)) {
        http_response_code(409);
        response(false, "Email này đã được tài khoản khác sử dụng.");
    }

    // 7. Cập nhật thông tin
    if ($password !== '') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $update = $conn->prepare(
            "UPDATE users
             SET Name = ?, Email = ?, Matkhau = ?
             WHERE id = ? AND user_type = 'admin'"
        );

        $update->execute([
            $name,
            $email,
            $hashedPassword,
            $id
        ]);
    } else {
        // Không nhập mật khẩu mới thì giữ nguyên mật khẩu hiện tại
        $update = $conn->prepare(
            "UPDATE users
             SET Name = ?, Email = ?
             WHERE id = ? AND user_type = 'admin'"
        );

        $update->execute([
            $name,
            $email,
            $id
        ]);
    }

    response(true, "Cập nhật tài khoản admin thành công.");

} catch (PDOException $e) {
    error_log("update_admin.php: " . $e->getMessage());

    http_response_code(500);
    response(false, "Có lỗi cơ sở dữ liệu. Vui lòng thử lại.");
}
