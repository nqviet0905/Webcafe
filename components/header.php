<?php
// ================= BẮT SESSION =================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ================= KẾT NỐI DB =================
// LƯU Ý: file này ĐƯỢC include từ các trang đã include connection.php
// nên KHÔNG include lại connection.php ở đây

// ================= USER =================
$user_id = $_SESSION['user_id'] ?? '';

// ================= LOGOUT =================
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("location: login.php");
    exit();
}

// ================= ĐẾM WISHLIST & CART =================
$total_wishlist_items = 0;
$total_cart_items = 0;

if (isset($conn) && !empty($user_id)) {

    $wishlist_stmt = $conn->prepare(
        "SELECT COUNT(*) FROM wishlist WHERE user_id = ?"
    );
    $wishlist_stmt->execute([$user_id]);
    $total_wishlist_items = (int)$wishlist_stmt->fetchColumn();

    $cart_stmt = $conn->prepare(
        "SELECT SUM(qty) FROM cart WHERE user_id = ?"
    );
    $cart_stmt->execute([$user_id]);
    $total_cart_items = (int)$cart_stmt->fetchColumn();
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body>

<header class="header">
    <div class="flex">

        <a href="home.php" class="logo">
            <img src="img/logo.jpg" alt="logo">
        </a>

        <nav class="navbar">
            <a href="home.php">TRANG CHỦ</a>
            <a href="view_products.php">SẢN PHẨM</a>
            <a href="order.php">ĐẶT HÀNG</a>
            <a href="about.php">VỀ CHÚNG TÔI</a>
            <a href="contacts.php">LIÊN HỆ</a>
        </nav>

        <div class="icons">
            <i class="bx bxs-user" id="user-btn"></i>

            <a href="wishlist.php" class="cart-btn">
                <i class="bx bx-heart"></i>
                <sup><?= $total_wishlist_items ?></sup>
            </a>

            <a href="cart.php" class="cart-btn">
                <i class="bx bx-cart-download"></i>
                <sup><?= $total_cart_items ?></sup>
            </a>

            <i class="bx bx-list-plus" id="menu-btn" style="font-size: 2rem;"></i>
        </div>

        <div class="user-box">
            <?php if (!empty($user_id)) { ?>
                <p>Username :
                    <span><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                </p>

                <form method="post">
                    <button type="submit" name="logout" class="logout-btn">
                        Đăng xuất
                    </button>
                </form>
            <?php } else { ?>
                <a href="login.php" class="btn">Đăng nhập</a>
                <a href="register.php" class="btn">Đăng ký</a>
            <?php } ?>
        </div>

    </div>
</header>

</body>
</html>
