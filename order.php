<?php
include 'components/connection.php';
session_start();

$user_id = $_SESSION['user_id'] ?? '';

if (isset($_POST['logout'])) {
    session_destroy();
    header("location: login.php");
    exit;
}
?>

<style type="text/css">
<?php include 'style.css'; ?>
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <title>Green Coffee - Order Page</title>
</head>
<body>

<?php include 'components/header.php'; ?>

<div class="main">
    <div class="banner">
        <h1>Đơn hàng của tôi</h1>
    </div>

    <div class="title2">
        <a href="home.php">Trang chủ</a><span> / Đơn hàng</span>
    </div>

    <section class="order">
        <div class="box-container">
            <div class="title">
                <img src="img/download.png" class="logo">
                <h1>Đơn hàng của tôi</h1>
            </div>

            <div class="box-container">

            <?php
            $select_dathang = $conn->prepare("
                SELECT * FROM dathang 
                WHERE user_id = ? 
                ORDER BY date DESC
            ");
            $select_dathang->execute([$user_id]);

            if ($select_dathang->rowCount() > 0) {

                while ($fetch_order = $select_dathang->fetch(PDO::FETCH_ASSOC)) {

                    $select_product = $conn->prepare("
                        SELECT * FROM sanpham WHERE id = ?
                    ");
                    $select_product->execute([$fetch_order['product_id']]);

                    if ($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)) {

                        // ✅ LẤY GIÁ AN TOÀN
                        $price = (!empty($fetch_order['Giaban']) && $fetch_order['Giaban'] > 0)
                            ? $fetch_order['Giaban']
                            : $fetch_product['Giaban'];
            ?>

                <div class="box" <?= ($fetch_order['Trangthai'] == 'hủy bỏ') ? 'style="border:2px solid red;"' : ''; ?>>
                    <a href="view_order.php?get_id=<?= $fetch_order['id']; ?>">

                        <p class="date">
                            <i class="bi bi-calendar-fill"></i>
                            <span><?= $fetch_order['date']; ?></span>
                        </p>

                        <img src="img/<?= $fetch_product['image']; ?>" class="image">

                        <div class="row">
                            <h3 class="name"><?= $fetch_product['Name']; ?></h3>

                            <p class="price">
                                Giá: <?= number_format($price, 0, ',', '.'); ?> nghìn đồng
                                x <?= $fetch_order['qty']; ?>
                            </p>

                            <p class="status" style="color:
                                <?php
                                if ($fetch_order['Trangthai'] == 'Đã giao hàng') {
                                    echo 'green';
                                } elseif ($fetch_order['Trangthai'] == 'hủy bỏ') {
                                    echo 'red';
                                } else {
                                    echo 'orange';
                                }
                                ?>">
                                <?= $fetch_order['Trangthai']; ?>
                            </p>
                        </div>
                    </a>
                </div>

            <?php
                    }
                }
            } else {
                echo '<p class="empty">Bạn chưa có đơn hàng nào!</p>';
            }
            ?>

            </div>
        </div>
    </section>

    <?php include 'components/footer.php'; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="script.js"></script>
<?php include 'components/alert.php'; ?>

</body>
</html>