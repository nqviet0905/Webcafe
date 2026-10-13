<?php
include 'components/connection.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    header('location: login.php');
    exit();
}

$admin_id = $_SESSION['user_id'];
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" type="text/css" href="admin_style.css?v=<?php echo time();?>">
<title></title>
</head>
<body>
<?php include 'components/admin_header.php'; ?>

<div class="main">
    <div class="banner">
        <h1>Tổng số đơn đặt hàng</h1>
    </div>
    <div class="title2">
        <a href="dashboard.php">Bảng điều khiển</a><span> / Tổng số đơn đặt hàng chưa xác nhận</span>
    </div>

    <section class="order-container">
        <h1 class="heading">Tổng số đơn đặt hàng chưa xác nhận</h1>
        <div class="box-container">
            <?php
            $select_orders = $conn->prepare("SELECT * FROM `dathang` WHERE payment_status = ?");
            $select_orders->execute(['Đang chờ xử lý']);
            
            if($select_orders->rowCount() > 0){
                while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
            ?>
            <div class="box">
                <div class="status" style="color: green;"><?= $fetch_orders['payment_status'] ?></div>
                <div class="detail">
                    <p>Tên người dùng: <span><?= $fetch_orders['Name'] ?></span></p>
                    <p>Mã: <span><?= $fetch_orders['id'] ?></span></p>
                    <p>Ngày đặt: <span><?= $fetch_orders['date'] ?></span></p>
                    <p>Số điện thoại: <span><?= $fetch_orders['DienThoai'] ?></span></p>
                    <p>Email: <span><?= $fetch_orders['Email'] ?></span></p>
                    <p>Tổng đơn: <span><?= $fetch_orders['Giaban'] ?></span></p>
                    <p>Địa chỉ: <span><?= $fetch_orders['address'] ?></span></p>
                </div>
            </div>
            <?php
                }
            }else{
                echo '<div class="empty"><p>Hiện tại không có đơn hàng nào đang chờ xử lý</p></div>';
            }
            ?>
        </div>
    </section>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script type="text/javascript" src="script.js"></script>
<?php include 'components/alert.php'; ?>
</body>
</html>
