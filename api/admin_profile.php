
<?php
header("Content-Type: application/json; charset=UTF-8");

session_start();
require_once __DIR__ . '/../components/connection.php';

function respond($status, $msg, $data = null, $httpCode = 200)
{
    http_response_code($httpCode);

    $result = [
        "status" => $status,
        "msg" => $msg
    ];

    if ($data !== null) {
        $result["data"] = $data;
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    exit();
}

// 1. Kiểm tra quyền admin
if (
    !isset($_SESSION['user_id'], $_SESSION['user_type']) ||
    $_SESSION['user_type'] !== 'admin'
) {
    respond("error", "Bạn cần đăng nhập bằng tài khoản admin.", null, 403);
}

// 2. Chỉ cho phép GET và POST
$method = $_SERVER['REQUEST_METHOD'];

if (!in_array($method, ['GET', 'POST'], true)) {
    header("Allow: GET, POST");
    respond("error", "Phương thức không được hỗ trợ.", null, 405);
}

$adminId = (string) $_SESSION['user_id'];

try {
    // 3. Lấy thông tin admin đang đăng nhập
    if ($method === 'GET') {
        $query = $conn->prepare(
            "SELECT id, Name, Email
             FROM users
             WHERE id = ? AND user_type = 'admin'
             LIMIT 1"
        );

        $query->execute([$adminId]);
        $admin = $query->fetch(PDO::FETCH_ASSOC);

        if (!$admin) {
            respond("error", "Không tìm thấy tài khoản admin.", null, 404);
        }

        respond("success", "Lấy thông tin thành công.", $admin);
    }

    // 4. Nhận dữ liệu cập nhật
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!is_string($password)) {
        respond("error", "Mật khẩu không hợp lệ.", null, 400);
    }

    if ($name === '' || $email === '') {
        respond("error", "Vui lòng nhập tên và email.", null, 400);
    }

    if (mb_strlen($name, 'UTF-8') > 50) {
        respond("error", "Tên không được vượt quá 50 ký tự.", null, 400);
    }

    if (strlen($email) > 100 ||
        !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        respond("error", "Email không hợp lệ hoặc quá dài.", null, 400);
    }

    if ($password !== '' && strlen($password) < 8) {
        respond("error", "Mật khẩu mới phải có ít nhất 8 ký tự.", null, 400);
    }

    // 5. Kiểm tra email trùng với tài khoản khác
    $checkEmail = $conn->prepare(
        "SELECT id FROM users
         WHERE Email = ? AND id <> ?
         LIMIT 1"
    );

    $checkEmail->execute([$email, $adminId]);

    if ($checkEmail->fetch(PDO::FETCH_ASSOC)) {
        respond("error", "Email đã được tài khoản khác sử dụng.", null, 409);
    }

    // 6. Cập nhật thông tin
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
            $adminId
        ]);
    } else {
        // Không nhập mật khẩu mới thì giữ nguyên mật khẩu cũ
        $update = $conn->prepare(
            "UPDATE users
             SET Name = ?, Email = ?
             WHERE id = ? AND user_type = 'admin'"
        );

        $update->execute([
            $name,
            $email,
            $adminId
        ]);
    }

    respond("success", "Cập nhật hồ sơ admin thành công.");

} catch (PDOException $e) {
    error_log("admin_profile.php: " . $e->getMessage());
    respond("error", "Lỗi cơ sở dữ liệu. Vui lòng thử lại.", null, 500);
}
